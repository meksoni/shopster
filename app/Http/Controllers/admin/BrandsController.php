<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\TempImage;
use Illuminate\Http\Request;
use App\Models\Brand;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Image;



class BrandsController extends Controller
{

    public function index(Request $request) {
        $brands = Brand::orderBy('created_at' ,'DESC');

        if(!empty($request->get('keyword'))) {
            $brands = $brands->where('name','LIKE', '%' .$request->get('keyword').'%');
        }

        $brands = $brands->paginate(15);

        $data['brands'] = $brands;

        return view('admin.brands.index', $data);
    }
    public function create() {
        return view('admin.brands.create');
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'name'=> 'required',
            'slug'=> 'required|unique:brands',
        ]);

        if ($validator->passes()) {
            $brands = new Brand();
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $brands->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/brands/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/brands/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                //$img->resize(450, 600);
                $img->fit(450, 600, function ($constraint) {
                    $constraint->upsize();
                });
                $img->save($dPath);

                $brands->image = $newImageName;
                $brands->save();
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je dodao novi brend ' . '<span class="fw-medium">' . $brands->name .'</span>',
                'is_read' => 0,
            ]);


            $request->session()->flash('success', 'Uspešno ste dodali brend');

            return response()->json([
                'status' => true,
                'message' => 'Uspešno ste dodali brend'
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        
    }

    public function edit($id, Request $request) {
        $brands = Brand::find($id);

        if(empty($brands)) {
            return redirect()->route('brands.index');
        }

        return view('admin.brands.edit', compact('brands'));
    }

    public function update($id, Request $request) {
        $brands = Brand::find($id);

        if(empty($brands)) {
            $request->session()->flash('error', 'Brend nije pronađen');
            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Brend nije pronađen',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$brands->id.',id',
        ]);

        if ($validator->passes()) {
            $brands->name = $request->name;
            $brands->slug = $request->slug;
            $brands->status = $request->status;
            $brands->save();

            $oldImage = $brands->image;

            if(!empty($request->image_id)) {
                $tempImage = TempImage::find($request->image_id);
                $extArray = explode('.',$tempImage->name);
                $ext = last($extArray);

                $newImageName = $brands->id.'-'.time().'.'.$ext;
                $sPath = public_path().'/temp/'.$tempImage->name;
                $dPath = public_path().'/uploads/brands/'.$newImageName;
                File::copy($sPath,$dPath);

                //Generate Image Thumbnail
                $dPath = public_path().'/uploads/brands/thumb/'.$newImageName;
                $img =  Image::make($sPath);
                // $img->resize(450, 600);


                $img->save($dPath);

                $brands->image = $newImageName;
                $brands->save();

                // Delete old image
                File::delete(public_path().'/uploads/brands/thumb/'.$oldImage);
                File::delete(public_path().'/uploads/brands/'.$oldImage);
                
            }

            $userId = Auth::id();

            Notifications::create([
                'user_id' => $userId,
                'message' => 'je ažurirao brend ' . '<span class="fw-medium">' .  $brands->name . '</span>',
                'is_read' => 0,
            ]);


            $request->session()->flash('success', 'Uspešno ste ažurirali brend');

            return response()->json([
                'status' => true,
                'message' => 'Uspešno ste ažurirali brend'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function destroy($id, Request $request) {
        $brands = Brand::find($id);
        if(empty($brands)) {
            $request->session()->flash('error', 'Brend nije pronađen');
            return response()->json([
                'status'=> true,
                'message'=> 'Brend nije pronađen'
            ]);
        }

        File::delete(public_path().'/uploads/brands/thumb/'.$brands->image);
        File::delete(public_path().'/uploads/brands/'.$brands->image);

        $brands->delete();
        $brandName = $brands->name;

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je obrisao brend ' . '<span class="fw-medium">' .  $brands->name . '</span>',
            'is_read' => 0,
        ]);
        
        
        $request->session()->flash('success', 'Brend <b>' . $brandName . '</b> uspešno obrisan!');
        return response()->json([
            'status'=> true,
            'message'=> 'Uspešno ste obrisali brend!'
        ]);
    }
}
