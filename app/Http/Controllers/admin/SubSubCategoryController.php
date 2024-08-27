<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;


class SubSubCategoryController extends Controller
{

    public function index(Request $request) {

        $subSubCategoriesQuery = SubSubCategory::select('sub_sub_categories.*', 'categories.name as categoryName', 'sub_categories.name as subCategoryName')
        ->leftJoin('sub_categories', 'sub_categories.id', '=', 'sub_sub_categories.sub_category_id')
        ->leftJoin('categories', 'categories.id', '=', 'sub_sub_categories.category_id')
        ->latest('sub_sub_categories.id');

        if ($request->has('keyword')) {
            $keyword = $request->get('keyword');
            $subSubCategoriesQuery->where(function($query) use ($keyword) {
                $query->where('sub_sub_categories.name', 'like', '%' . $keyword . '%')
                    ->orWhere('categories.name', 'like', '%' . $keyword . '%')
                    ->orWhere('sub_categories.name', 'like', '%' . $keyword . '%');
            });
        }

        $subSubCategories = $subSubCategoriesQuery->paginate(15);

        foreach ($subSubCategories as $subSubCategory) {
            $subSubCategory->categoryLink = route('categories.edit', ['category' => $subSubCategory->category_id]);
            $subSubCategory->subCategoryLink = route('sub-categories.edit', ['subCategory' => $subSubCategory->sub_category_id]);
            // Ako koristite drugačiji naziv rute ili ako vam je potrebna drugačija logika za generisanje linka, prilagodite ovaj deo
        }


        $data['subSubCategories'] = $subSubCategories;

        
        
        return view('admin.sub_sub_categories.index', $data);
    }
    public function create() {
        $data= [];
        $categories = Category::orderBy('name', 'DESC')->get();
        $subCategories = SubCategory::orderBy('name', 'DESC')->get();

        $data['categories']= $categories;
        $data['subCategories']= $subCategories;

        return view('admin.sub_sub_categories.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_sub_categories',
            'category' => 'required',
            'subCategory' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $SubSubCategory = new SubSubCategory();
            $SubSubCategory->name = $request->name;
            $SubSubCategory->slug = $request->slug;
            $SubSubCategory->category_id = $request->category;
            $SubSubCategory->sub_category_id = $request->subCategory;
            $SubSubCategory->status = $request->status;
            $SubSubCategory->save();

            // Save image here

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $SubSubCategory->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/sub-sub-categories/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/sub-sub-categories/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                //$img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $SubSubCategory->image = $newImageName;
                $SubSubCategory->save();
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je dodao novu pod-pod-kategoriju ' . '<span class="fw-medium">' .  $SubSubCategory->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Pod-pod-kategorija uspešno napravljena');

            return response([
                
                'status' => true,
                'message' => 'Pod-pod-kategorija uspešno napravljena'
            ]);

        } else {
            return response([
                
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {
        $subSubCategory = SubSubCategory::find($id);

        if(empty($subSubCategory)) {
            $request->session()->flash('error', 'Zapisi nisu pronađeni');
            return redirect()->route('sub-sub-categories.index');
        }

        $categories = Category::orderBy('name', 'DESC')->get();
        $subCategories = SubCategory::orderBy('name', 'DESC')->get();

        $data['subSubCategory']= $subSubCategory;
        $data['categories']= $categories;
        $data['subCategories'] = $subCategories;

        return view('admin.sub_sub_categories.edit', $data);
    }

    public function update($id, Request $request) {
        $subSubCategory = SubSubCategory::find($id);

        if(empty($subSubCategory)) {
            $request->session()->flash('error', 'Zapisi nisu pronađeni');
            return response([
                'status' => false,
                'notFound' => true
            ]);
            // return redirect()->route('sub-categories.index');
        }
       
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            //'slug' => 'required|unique:sub_categories',
            'slug' => 'required|unique:sub_sub_categories,slug,'.$subSubCategory->id.',id',
            'subCategory' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $subSubCategory->name = $request->name;
            $subSubCategory->slug = $request->slug;
            $subSubCategory->status = $request->status;
            $subSubCategory->category_id = $request->category;
            $subSubCategory->sub_category_id = $request->subCategory;
            $subSubCategory->save();


            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je ažurirao pod-pod-kategoriju ' . '<span class="fw-medium">' .  $subSubCategory->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Pod-pod-kategorija uspešno ažurirana');

            return response([
                
                'status' => true,
                'message' => 'Pod-pod-kategorija uspešno ažurirana'
            ]);

        } else {
            return response([
                
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $subSubCategory = SubSubCategory::find($id);
        
        if(empty($subSubCategory))  {
            $request->session()->flash('error', 'Nije pronadjena subSubCategory');
            return response()->json([
                'status' => true,
                'message' => 'Nije pronadjena subSubCategory',
            ]);
        }

        File::delete(public_path().'/uploads/sub-sub-categories/thumb/'.$subSubCategory->image);
        File::delete(public_path().'/uploads/sub-sub-categories/'.$subSubCategory->image);

        $subSubCategory->delete();
        $subSubCatName = $subSubCategory->name;

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je obrisao pod-pod-kategoriju ' . '<span class="fw-medium">' .  $subSubCategory->name . '</span>',
            'is_read' => false,
        ]);

        $request->session()->flash('success', 'SubSubCategory <b>' . $subSubCatName . '</b> uspešno obrisana');
        return response()->json([
            'status' => true,
            'message' => 'SubSubCategory uspešno obrisana!',
        ]);
            
    }
}

