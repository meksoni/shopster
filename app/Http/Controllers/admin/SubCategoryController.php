<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Image;




class SubCategoryController extends Controller
{

    public function index(Request $request) {

        $subCategories = SubCategory::select('sub_categories.*', 'categories.name as categoryName')
                                ->latest('sub_categories.id')
                                ->leftJoin('categories','categories.id','sub_categories.category_id');

        if (!empty($request->get('keyword'))) {
            $subCategories = $subCategories->where('sub_categories.name', 'like', '%'. $request->get('keyword') .'%');
            $subCategories = $subCategories->where('categories.name', 'like', '%'. $request->get('keyword') .'%');
        }

        $subCategories = $subCategories->paginate(15);

        foreach ($subCategories as $subCategory) {
            $subCategory->categoryLink = route('categories.edit', ['category' => $subCategory->category_id]);
            // Ako koristite drugačiji naziv rute ili ako vam je potrebna drugačija logika za generisanje linka, prilagodite ovaj deo
        }

        $data['subCategories'] = $subCategories;

        return view('admin.sub_categories.index', $data);
    }
    public function create() {
        $categories = Category::orderBy('name', 'DESC')->get();

        $data['categories']= $categories;

        return view('admin.sub_categories.create', $data);
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->passes()) {

            $subCategory = new SubCategory();
            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->category_id = $request->category;
            $subCategory->status = $request->status;
            $subCategory->save();

            // Save image here

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $subCategory->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/sub-categories/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/sub-categories/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                //$img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $subCategory->image = $newImageName;
                $subCategory->save();
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je dodao novu pod-kategoriju ' . '<span class="fw-medium">' .  $subCategory->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Pod-kategorija uspešno napravljena.');

            return response([
                
                'status' => true,
                'message' => 'Pod-kategorija uspešno napravljena.'
            ]);

        } else {
            return response([
                
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request) {

        $subCategory = SubCategory::find($id);

        if(empty($subCategory)) {
            $request->session()->flash('error', 'Zapisi nisu pronađeni');
            return redirect()->route('sub-categories.index');
        }

        $categories = Category::orderBy('name', 'DESC')->get();

        $data['categories']= $categories;
        $data['subCategory'] = $subCategory;

        return view('admin.sub_categories.edit', $data);
    }

    public function update($id, Request $request) {

        $subCategory = SubCategory::find($id);

        if(empty($subCategory)) {
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
            'slug' => 'required|unique:sub_categories,slug,'.$subCategory->id.',id',
            'category' => 'required',
            'status' => 'required'
        ]);

        if ($validator->passes()) {

            $subCategory->name = $request->name;
            $subCategory->slug = $request->slug;
            $subCategory->status = $request->status;
            $subCategory->category_id = $request->category;
            $subCategory->save();

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je ažurirao pod-kategoriju ' . '<span class="fw-medium">' .  $subCategory->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Pod-kategorija uspešno ažurirana');

            return response([
                
                'status' => true,
                'message' => 'Podkategorija uspešno ažurirana'
            ]);

        } else {
            return response([
                
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id, Request $request) {
        $subCategory = SubCategory::find($id);
        
        if(empty($subCategory))  {
            $request->session()->flash('error', 'Nije pronadjena podkategorija');
            return response()->json([
                'status' => true,
                'message' => 'Nije pronadjena podkategorija',
            ]);
        }

        File::delete(public_path().'/uploads/sub-categories/thumb/'.$subCategory->image);
        File::delete(public_path().'/uploads/sub-categories/'.$subCategory->image);

        $subCategory->delete();
        $subCatName = $subCategory->name;

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je obrisao pod-kategoriju ' . '<span class="fw-medium">' .  $subCategory->name . '</span>',
            'is_read' => false,
        ]);

        $request->session()->flash('success', 'Podkategorija <b>' . $subCatName . '</b> uspešno obrisana');
        return response()->json([
            'status' => true,
            'message' => 'PodKategorija uspešno obrisana!',
        ]);
            
    }
}
