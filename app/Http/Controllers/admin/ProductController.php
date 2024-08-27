<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notifications;
use App\Models\Specification;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\ProductImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Image;
use Carbon\Carbon;




class ProductController extends Controller
{
    public function index(Request $request) {
        $products = Product::latest('id')->with('product_images');

        if ($request->get('keyword')) {
            $products = $products->where(function($query) use ($request) {
                $keyword = $request->get('keyword');
                $query->where('title', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('sku', 'LIKE', '%' . $keyword . '%')
                      ->orWhere('id', 'LIKE', '%' . $keyword . '%');
            });
        }

        $products = $products->paginate(15);
        $data['products'] = $products;

        return view('admin.products.index', $data);
    }

    public function create() {
        $data= [];
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();

        $data['categories'] = $categories;
        $data['brands'] = $brands;

        return view('admin.products.create', $data);
    }

    public function store(Request $request) {
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'vat_rate' => 'required',
            'sku' => 'required|unique:products',
            'track_quantity' => 'in:Yes,No',
            'category' => 'numeric',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_start_date' => 'nullable|date',
            'discount_end_date' => 'nullable|date|after_or_equal:discount_start_date',
        ];
    
        if ($request->track_quantity == 'Yes') {
            $rules['quantity'] = 'required|numeric';
        }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validacija nije uspela.',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $productData = $request->all();

        // Postavite podrazumevani datum završetka ako nije unet
        // Postavite podrazumevani datum završetka ako nije unet
    if (empty($request->discount_end_date) && !empty($request->discount_start_date)) {
        $startDate = Carbon::createFromFormat('Y-m-d', $request->discount_start_date);
        $request->merge(['discount_end_date' => $startDate->addDays(10)->format('Y-m-d')]);
    }

    // Kreiranje novog proizvoda
    $product = new Product;
    
    $product->title = $request->title;
    $product->slug = $request->slug;
    $product->short_description = $request->short_description ?? null;
    $product->price = $request->price;
    $product->vat_rate = $request->vat_rate;
    $product->unit_measure = $request->unit_measure ?? null;
    $product->sku = $request->sku;
    $product->barcode = $request->barcode ?? null;
    $product->track_quantity = $request->track_quantity;
    $product->quantity = $request->quantity ?? null;
    $product->status = $request->status ?? null;
    $product->category_id = $request->category ?? null;
    $product->sub_category_id = $request->sub_category ?? null;
    $product->sub_sub_category_id = $request->sub_sub_category ?? null;
    $product->brand_id = $request->brand ?? null;
    $product->is_featured = $request->is_featured ?? null;
    $product->discount_percentage = $request->discount_percentage;
    $product->discount_start_date = $request->discount_start_date;
    $product->discount_end_date = $request->discount_end_date;

    // Izračunavanje i postavljanje cene sa popustom
    if (!empty($request->discount_percentage)) {
        $discountedPrice = $request->price - ($request->price * $request->discount_percentage / 100);
        $product->discount_price = $discountedPrice;
    }

    // Čuvanje proizvoda u bazi
    $product->save();

        // Čuvanje specifikacija
        if (!empty($productData['specifications'])) {
            foreach ($productData['specifications'] as $specification) {
                if (!empty($specification['name']) && !empty($specification['value'])) {
                    $product->specifications()->create([
                        'name' => $specification['name'],
                        'value' => $specification['value'],
                    ]);
                }
            }
        }

        //Save Gallery Pics
        if(!empty($request->image_array)) {
            foreach ($request->image_array as $temp_image_id) {

                $tempImageInfo = TempImage::find($temp_image_id);
                $extArray = explode('.',$tempImageInfo->name);
                $ext = last($extArray); // like jpg,gif,png..

                $productImage = new ProductImage();
                $productImage->product_id = $product->id;
                $productImage->image = 'NULL';
                $productImage->save();

                $imageName = $product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                // product_id => 4 ; $product_image_id => 1
                // 4-1-123456.jpg
                $productImage->image = $imageName;
                $productImage->save();

                //Generate Product Thumbnail

                //Large image
                $sourcePath = public_path().'/temp/'.$tempImageInfo->name;
                $destPath = public_path().'/uploads/product/large/'.$imageName;
                $image = Image::make($sourcePath);
                $image->save($destPath);

                $destPath = public_path().'/uploads/product/small/'.$imageName;
                $image = Image::make($sourcePath);
                $image->resize(80, null, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $image->save($destPath);

            }
        }

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je dodao novi proizvod ' . '<span class="fw-medium">' .  $product->title . '</span>',
            'is_read' => false,
        ]);

        $request->session()->flash('success', 'Uspešno ste napravili novi proizvod!');

        return response()->json([
            'status' => true,
            'message' => 'Uspešno ste napravili novi proizvod!',
            'product' => $product
        ], 201);

    }

    public function edit($id, Request $request) {
        $product = Product::find($id);

        if(empty($product)) {
            return redirect()->route('products.index')->with('error', 'Proizvod nije pronadjen');
        }

        //Fetch images
        $productImages = ProductImage::where('product_id',$product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();
        $subSubCategories = SubSubCategory::where('sub_category_id', $product->sub_category_id)->get();

        $unitMeasures = Product::getEnumValues('unit_measure');

        $vatRate = Product::getEnumValues('vat_rate');

        $specifications = $product->specifications()->get();


        $data=[];
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $data['subSubCategories'] = $subSubCategories;
        $categories = Category::orderBy('name','ASC')->get();
        $brands = Brand::orderBy('name','ASC')->get();
        $data['categories'] = $categories;
        $data['unitMeasures'] = $unitMeasures;
        $data['vatRate'] = $vatRate;
        $data['brands'] = $brands;
        $data['specifications'] = $specifications;
        $data['productImages'] = $productImages;


        return view('admin.products.edit', $data);
    }

    protected $productImageController;

    public function __construct(ProductImageController $productImageController)
    {
        $this->productImageController = $productImageController;
    }

    public function update($id, Request $request) {
        $product = Product::find($id);
    
        // Ensure the product exists
        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ], 404);
        }
    
        // Validation rules
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id.',id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,'.$product->id.',id',
            'track_quantity' => 'required|in:Yes,No',
            'category' => 'numeric',
            'is_featured' => 'required|in:Yes,No',
            'unit_measure' => 'required',
            'vat_rate' => 'required',
        ];
    
        if (!empty($request->track_quantity) && $request->track_quantity == 'Yes') {
            $rules['quantity'] = 'required|numeric';
        }
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->passes()) {
            // Update product details
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->short_description = $request->short_description ?? null;
            $product->price = $request->price;
            $product->vat_rate = $request->vat_rate;
            $product->unit_measure = $request->unit_measure ?? null;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode ?? null;
            $product->track_quantity = $request->track_quantity;
            $product->quantity = $request->quantity ?? null;
            $product->status = $request->status ?? null;
            $product->category_id = $request->category ?? null;
            $product->sub_category_id = $request->sub_category;
            $product->sub_sub_category_id = $request->sub_sub_category ?? null;
            $product->brand_id = $request->brand ?? null;
            $product->is_featured = $request->is_featured ?? null;
            $product->discount_percentage = $request->discount_percentage;
            $product->discount_start_date = $request->discount_start_date;
            $product->discount_end_date = $request->discount_end_date;

            // Izračunavanje i postavljanje cene sa popustom
            if (!empty($request->discount_percentage)) {
                $discountedPrice = $request->price - ($request->price * $request->discount_percentage / 100);
                $product->discount_price = $discountedPrice;
            } else {
                $product->discount_price = null;
                $product->discount_start_date = null;
                $product->discount_end_date = null;
            }

            $product->save();
    
            // Manage specifications
            $existingSpecIds = $product->specifications->pluck('id')->toArray(); // Get existing specification IDs
    
            if ($request->has('specifications')) {
                $newSpecIds = [];
    
                foreach ($request->specifications as $specification) {
                    if (isset($specification['id'])) {
                        // Update existing specification
                        Specification::updateOrCreate(
                            ['id' => $specification['id']],
                            [
                                'product_id' => $product->id,
                                'name' => $specification['name'],
                                'value' => $specification['value']
                            ]
                        );
                        $newSpecIds[] = $specification['id']; // Track new spec IDs
                    } else {
                        // Create new specification
                        $spec = Specification::create([
                            'product_id' => $product->id,
                            'name' => $specification['name'],
                            'value' => $specification['value']
                        ]);
                        $newSpecIds[] = $spec->id; // Track new spec IDs
                    }
                }
    
                // Delete specifications that are no longer in the list
                $specificationsToDelete = array_diff($existingSpecIds, $newSpecIds);
                Specification::whereIn('id', $specificationsToDelete)->delete();
            } else {
                // If no specifications are provided, delete all existing ones
                Specification::where('product_id', $product->id)->delete();
            }

            // if (!empty($request->image_array)) {
            //     foreach ($request->image_array as $temp_image_id) {
            //         $tempImageInfo = TempImage::find($temp_image_id);
                    
            //         $requestForImage = new Request();
            //         $requestForImage->replace([
            //             'product_id' => $product->id, 
            //             'image' => $tempImageInfo->image 
            //         ]);

            //         Log::info('Request for adding image:', $request->all());
                    
            //         $this->productImageController->update($requestForImage);
            //     }
            // }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je ažurirao proizvod ' . '<span class="fw-medium">' .  $product->title . '</span>',
                'is_read' => false,
            ]);
    
            $request->session()->flash('success', 'Uspešno ste izmenili proizvod!');
    
            return response()->json([
                'status' => true,
                'message' => 'Uspešno ste izmenili proizvod!',
            ]);
    
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    
    public function destroy($id, Request $request) {
        //Delete product funkcije
        $product = Product::find($id);
    
        if (empty($product)) {
            $request->session()->flash('error', 'Proizvod nije pronađen');
            return response()->json([
                'status' => false,
                'message' => 'Proizvod nije pronađen'
            ]);
        }

        File::delete(public_path().'/uploads/product/large/'.$product->image);
        File::delete(public_path().'/uploads/product/small/'.$product->image);
    
        // Brisanje proizvoda
        $product->delete();

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je obrisao proizvod' . '<span class="fw-medium">' .  $product->title . '</span>',
            'is_read' => false,
        ]);
    
        $request->session()->flash('success', 'Proizvod je uspešno obrisan');
        return response()->json([
            'status' => true,
            'message' => 'Proizvod je uspešno obrisan'
        ]);
    }

    public function apiStoreFunction(Request $request)
    {
        
        $rules = [
            '*.title' => 'required',
            '*.slug' => 'required|unique:products,slug',
            '*.price' => 'required|numeric',
            '*.sku' => 'required|unique:products,sku',
            '*.track_quantity' => 'in:Yes,No',
            '*.category_id' => 'required|exists:categories,id',
            '*.sub_category_id' => 'nullable|exists:sub_categories,id',
            '*.sub_sub_category_id' => 'nullable|exists:sub_sub_categories,id',
            '*.brand_id' => 'nullable|exists:brands,id',
            '*.unit_measure' => 'in:kom,m',
            '*.images.*.image' => 'nullable', // validacija za base64 sliku
            '*.images.*.extension' => 'nullable|in:jpeg,png,jpg,svg,webp|max:2048' // validacija za ekstenziju slike
        ];

        if (!empty($request->track_quantity) && $request->track_quantity == 'Yes') {
            $rules['*.quantity'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Ubacivanje proizvoda nije uspelo.',
                'errors' => $validator->errors()
            ], 422);
        }

        $products = [];

        if (empty($request->discount_end_date) && !empty($request->discount_start_date)) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->discount_start_date);
            $request->merge(['discount_end_date' => $startDate->addDays(10)->format('Y-m-d')]);
        }

        foreach ($request->all() as $productData) {
            $product = new Product;
    
            $product->title = $productData['title'];
            $product->slug = $productData['slug'];
            $product->short_description = $productData['short_description'] ?? null;
            $product->vat_rate = $productData['vat_rate'];
            $product->price = $productData['price'];
            $product->unit_measure = $productData['unit_measure'] ?? null;
            $product->sku = $productData['sku'];
            $product->barcode = $productData['barcode'] ?? null;
            $product->track_quantity = $productData['track_quantity'];
            $product->quantity = $productData['quantity'] ?? null;
            $product->status = $productData['status'] ?? null;
            $product->category_id = $productData['category_id'];
            $product->sub_category_id = $productData['sub_category_id'] ?? null;
            $product->sub_sub_category_id = $productData['sub_sub_category_id'] ?? null;
            $product->brand_id = $productData['brand_id'] ?? null;
            $product->is_featured = $productData['is_featured'] ?? null;
            $product->is_low_stock_notified = $productData['is_low_stock_notified'];

            $product->discount_percentage = $productData['discount_percentage'] ?? null;
            $product->discount_start_date = $productData['discount_start_date'] ?? null;
            $product->discount_end_date = $productData['discount_end_date'] ?? null;

            if (!empty($request->discount_percentage)) {
                $discountedPrice = $request->price - ($request->price * $request->discount_percentage / 100);
                $product->discount_price = $discountedPrice;
            }

            $product->save();

            // Provera da li postoje slike
            if (isset($productData['images']) && is_array($productData['images'])) {
                foreach ($productData['images'] as $imageData) {
                    $imageContent = base64_decode($imageData['image']);
                    $imageName = Str::random(20) . '.' . $imageData['extension'];
                    $imagePath = 'public/uploads/product/large/' . $imageName;
                    Storage::put($imagePath, $imageContent);
    
                    // Kreiranje unosa za sliku u bazi podataka
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image' => $imagePath,
                    ]);
                }
            }

            $products[] = $product;
        }

        return response()->json([
            'status' => true,
            'message' => 'Uspešno ste napravili nove proizvode!',
            'products' => $products
        ], 201);

        
    }
}
