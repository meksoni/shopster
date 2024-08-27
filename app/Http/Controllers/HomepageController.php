<?php

namespace App\Http\Controllers;

use App\Models\ShopSelectedItems;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Category;

class HomepageController extends Controller
{
    public function index() {
        

        $selectedBrands = $this->selectedBrands();
        $selectedCategories = $this->selectedCategories();
        $selectedSubcategories = $this->selectedSubcategories();
        $selectedSubSubCategories = $this->selectedSubSubCategories();

        // Random 5 brands, categories and subcategories
        $data['selectedBrands'] = $selectedBrands;
        $data['selectedCategories'] = $selectedCategories;
        $data['selectedSubcategories'] = $selectedSubcategories;
        $data['selectedSubSubCategories'] = $selectedSubSubCategories;


        return view('shop.homepage',$data);
    }

    public function selectedBrands()
    {
        $selectedItems = ShopSelectedItems::first();
        if (!$selectedItems || !$selectedItems->brand_id) {
            return [];
        }
        return Product::where('brand_id', $selectedItems->brand_id)->where('quantity', '>', 0)->get();
    }
    public function selectedCategories()
    {
        $selectedItems = ShopSelectedItems::first();
        if (!$selectedItems || !$selectedItems->category_id) {
            return [];
        }
        return Product::where('category_id', $selectedItems->category_id)->where('quantity', '>', 0)->get();
    }
    public function selectedSubcategories()
    {
        $selectedItems = ShopSelectedItems::first();
        if (!$selectedItems || !$selectedItems->sub_category_id) {
            return [];
        }
        return Product::where('sub_category_id', $selectedItems->sub_category_id)->where('quantity', '>', 0)->get();
    }
    public function selectedSubSubCategories()
    {
        $selectedItems = ShopSelectedItems::first();
        if (!$selectedItems || !$selectedItems->sub_sub_category_id) {
            return [];
        }
        return Product::where('sub_sub_category_id', $selectedItems->sub_sub_category_id)->where('quantity', '>', 0)->get();
    }

    public function search(Request $request) {
        $searchTerm = $request->input('query');
        
        $products = Product::with('product_images')      
        ->where('title', 'like', "%{$searchTerm}%")
        ->get();
    
        return response()->json($products);
    }
}
