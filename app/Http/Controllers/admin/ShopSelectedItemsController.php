<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ShopSelectedItems;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;


class ShopSelectedItemsController extends Controller
{

    public function update(Request $request) {
        $selectedItems = ShopSelectedItems::first();

        if (!$selectedItems) {
            $selectedItems = ShopSelectedItems::create([
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'sub_sub_category_id' => $request->sub_sub_category_id,
            ]);
        } else {
            $selectedItems->update([
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'sub_category_id' => $request->sub_category_id,
                'sub_sub_category_id' => $request->sub_sub_category_id,
            ]);
        }

        return redirect()->back()->with('success', 'Uspešno ste sačuvali podatke');
    }
}

