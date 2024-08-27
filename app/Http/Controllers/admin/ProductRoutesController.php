<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\SubSubCategory;


class ProductRoutesController extends Controller
{
    public function index(Request $request) {
        if (!empty($request->category_id)) {
            $subCategories = SubCategory::where('category_id', $request->category_id)->orderBy('name', 'ASC')->get();
    
            return response()->json([
                'status'=> true,    
                'subCategories'=> $subCategories
            ]);
        } else if (!empty($request->sub_category_id)) {
            $subSubCategories = SubSubCategory::where('sub_category_id', $request->sub_category_id)->orderBy('name', 'ASC')->get();
    
            return response()->json([
                'status'=> true,
                'subSubCategories'=> $subSubCategories
            ]);
        } else {
            return response()->json([
                'status'=> true,
                'subCategories'=> [],
                'subSubCategories'=> []
            ]);
        }
    }
    
}
