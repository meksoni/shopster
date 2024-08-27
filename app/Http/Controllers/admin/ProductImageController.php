<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Image;

class ProductImageController extends Controller
{
    public function update(Request $request) {
        // Proveri da li je product_id prazan
        if (empty($request->product_id)) {
            return response()->json(['status' => false, 'message' => 'Product ID cannot be null.'], 400);
        }
    
        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $sourcePath = $image->getPathName();
    
        $productImage = new ProductImage();
        $productImage->product_id = $request->product_id; // Postavi product_id
        $productImage->image = 'NULL'; // Postavi na 'NULL' samo ako želiš da zadržiš tu vrednost
        $productImage->save();
    
        $imageName = $request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
        $productImage->image = $imageName;
        $productImage->save();
    
        // Spasi slike
        // ...
    
        return response()->json([
            'status' => true,
            'image_id' => $productImage->id,
            'ImagePath' => asset('uploads/product/small/'.$productImage->image),
            'message' => 'Fotografija uspesno sacuvana'
        ]);
    }

    public function destroy( Request $request){
        $productImage = ProductImage::find($request->id);

        //deleteimage
        File::delete(public_path('uploads/product/large/'.$productImage->image));
        File::delete(public_path('uploads/product/small/'.$productImage->image));

        $productImage->delete();

        return response()->json([
            'status' => true,
            'message' => 'Fotografija uspesno izbrisana'
        ]);
    }
}
