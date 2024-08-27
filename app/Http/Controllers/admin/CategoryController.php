<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Notifications;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Image;


class CategoryController extends Controller
{
    public function index(Request $request) {
        $categories = Category::orderBy('created_at', 'DESC');

        if(!empty($request->get('keyword'))) {
            $categories = $categories->where('name', 'like', '%'.$request->get('keyword').'%');
        }

        $categories = $categories->paginate(15);
        // dd($categories); Dump Items 
        return view('admin.categories.index', compact('categories'));
    }

    public function create() {
        return view('admin.categories.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories'
           
        ]);

        if ($validator->passes()) {

            $category = new Category();
            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            // Save image here

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/categories/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/categories/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                //$img->resize(450, 600);
                $img->fit(25, 25, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je dodao novu kategoriju ' . '<span class="fw-medium">' .  $category->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Uspešno ste napravili novu kategoriju');

            return response()->json([
                'status' => true,
                'message' => 'Uspešno ste napravili novu kategoriju'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($categoryId, Request $request) {
        $category = Category::find($categoryId);

        if(empty($category)) {
            return redirect()->route('categories.index');
        }

        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update($categoryId, Request $request) {

        $category = Category::find($categoryId);

        if(empty($category)) {
            $request->session()->flash('error', 'Kategorija nije pronađena');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Kategorija nije pronađena',
            ]);
        }


        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$category->id.',id',
        ]);

        if ($validator->passes()) {

            $category->name = $request->name;
            $category->slug = $request->slug;
            $category->status = $request->status;
            $category->showHome = $request->showHome;
            $category->save();

            $oldImage = $category->image;

            // Save image here

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $category->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/categories/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/categories/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                // $img->resize(450, 600);


                $img->save($dPath);

                $category->image = $newImageName;
                $category->save();

                // Delete old image
                File::delete(public_path().'/uploads/categories/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/categories/'.$oldImage);
                
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je ažurirao kategoriju ' . '<span class="fw-medium">' .  $category->name . '</span>',
                'is_read' => false,
            ]);

            $request->session()->flash('success', 'Kategorija uspešno ažurirana');

            return response()->json([
                'status' => true,
                'message' => 'Kategorija uspešno ažurirana'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($categoryId, Request $request) {
        
        $category = Category::find($categoryId);

        if(empty($category)) {
            $request->session()->flash('error', 'Kategorija nije pronađena');
            return response()->json([
                'status' => true,
                'message' => 'Kategorija nije pronađena',
            ]);
        }

        File::delete(public_path().'/uploads/categories/thumb/'.$category->image);
        File::delete(public_path().'/uploads/categories/'.$category->image);

        $category->delete();
        $catName = $category->name;

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je obrisao kategoriju ' . '<span class="fw-medium">' .  $category->name . '</span>',
            'is_read' => false,
        ]);

        $request->session()->flash('success', 'Kategorija <b>' . $catName . '</b> uspešno obrisana');
        return response()->json([
            'status' => true,
            'message' => 'Kategorija uspešno obrisana!',
        ]);

    }
}
