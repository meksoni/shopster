<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ShopController extends Controller
{
    public function index(Request $request, $categorySlug = null, $subCategorySlug = null, $subSubCategorySlug = null) {
        $categorySelected = '';
        $subCategorySelected = '';
        $subSubCategorySelected = '';
        $brandsArray = [];
        $showSubCategories = true;
        $showSubSubCategories = true;
    
        // Dohvatanje svih kategorija i brendova
        $categories = Category::orderBy('id', 'ASC')
            ->where('status', 1)
            ->where('showHome', 'Yes')
            ->withCount(['products as available_products_count' => function ($query) {
                $query->where('quantity', '>', 0); // Broji samo proizvode koji imaju količinu veću od 0
            }])
            ->having('available_products_count', '>', 0) // Prikaži samo kategorije sa dostupnim proizvodima
            ->get();
        
        $brands = Brand::orderBy('name', 'ASC')->where('status', 1)->get();
    
        // Inicijalizacija varijabli za filtriranje
        $products = Product::where('status', 1);
        $subCategories = collect();
        $subSubCategories = collect();
        $brandIds = collect(); // Koristi se za filtriranje relevantnih brendova
    
        $categoryName = null;
        $subCategoryName = null;
        $subSubCategoryName = null;
    
        // Filtriranje proizvoda na osnovu kategorije
        if (!empty($categorySlug)) {
            $category = Category::where('slug', $categorySlug)->first();
            if ($category) {
                $products = $products->where('category_id', $category->id);
                $categorySelected = $category->id;
                $categoryName = $category->name;
        
                // Dobijamo podkategorije koje imaju proizvode
                $subCategories = $category->sub_category()
                    ->where('status', 1)
                    ->whereHas('products', function ($query) {
                        $query->where('quantity', '>', 0); // Uslov za dostupne proizvode
                    })
                    ->get();
                
                // Dobijamo brendove vezane za proizvode u trenutnoj kategoriji
                $brandIds = Product::where('category_id', $category->id)->pluck('brand_id')->unique();
            }
        }
    
        // Filtriranje proizvoda na osnovu podkategorije
        if (!empty($subCategorySlug)) {
            $subCategory = SubCategory::where('slug', $subCategorySlug)->first();
            if ($subCategory) {
                $products = $products->where('sub_category_id', $subCategory->id);
                $subCategorySelected = $subCategory->id;
                $subCategoryName = $subCategory->name;
                $showSubCategories = false;
                
                // Dobijamo podpodkategorije koje imaju proizvode sa količinom većom od nula
                $subSubCategories = $subCategory->sub_sub_category()
                    ->where('status', 1)
                    ->whereHas('products', function($query) {
                        $query->where('quantity', '>', 0);
                    })
                    ->get();
                
                // Dobijamo brendove vezane za proizvode u trenutnoj podkategoriji
                $brandIds = Product::where('sub_category_id', $subCategory->id)->pluck('brand_id')->unique();
            }
        }
    
        // Filtriranje proizvoda na osnovu podpodkategorije
        if (!empty($subSubCategorySlug)) {
            $subSubCategory = SubSubCategory::where('slug', $subSubCategorySlug)->first();
            if ($subSubCategory) {
                $products = $products->where('sub_sub_category_id', $subSubCategory->id);
                $subSubCategorySelected = $subSubCategory->id;
                $subSubCategoryName = $subSubCategory->name;
                $showSubSubCategories = false;
                
                // Dobijamo brendove vezane za proizvode u trenutnoj podpodkategoriji
                $brandIds = Product::where('sub_sub_category_id', $subSubCategory->id)->pluck('brand_id')->unique();
            } else {
                abort(404);
            }
        }
    
        // Filtriranje proizvoda na osnovu izabranih brendova
        if (!empty($request->get('brand'))) {
            $brandsArray = explode(',', $request->get('brand'));
            $products = $products->whereIn('brand_id', $brandsArray);
        }
    
        // Dobijamo broj proizvoda po brendu unutar trenutne kategorije, podkategorije ili podpodkategorije
        $brandCounts = Product::select('brand_id', DB::raw('count(*) as total'))
            ->where('quantity', '>', 0)
            ->whereIn('brand_id', $brandIds)
            ->when($categorySelected, function ($query) use ($categorySelected) {
                return $query->where('category_id', $categorySelected);
            })
            ->when($subCategorySelected, function ($query) use ($subCategorySelected) {
                return $query->where('sub_category_id', $subCategorySelected);
            })
            ->when($subSubCategorySelected, function ($query) use ($subSubCategorySelected) {
                return $query->where('sub_sub_category_id', $subSubCategorySelected);
            })
            ->groupBy('brand_id')
            ->having('total', '>', 0) // Filtriramo samo brendove sa brojem proizvoda većim od 0
            ->pluck('total', 'brand_id');
    
        // Filtriramo brendove koji imaju proizvode veće od 0
        $filteredBrands = Brand::whereIn('id', $brandCounts->keys())->get();
    
        // Filtriranje proizvoda po količini
        $products = $products->where('quantity', '>', 0);
    
        // Sortiranje proizvoda
        $sortBy = $request->get('sortBy', 'onStock');
        switch ($sortBy) {
            case 'priceSortMax':
                $products = $products->orderBy('price', 'DESC');
                break;
            case 'priceSortMin':
                $products = $products->orderBy('price', 'ASC');
                break;
            case 'sortNewest':
                $products = $products->orderBy('created_at', 'DESC');
                break;
            case 'onStock':
            default:
                break;
        }
    
        // Dobijamo proizvode
        $products = $products->get();
    
        return view('shop.shop', [
            'categories' => $categories,
            'subCategories' => $subCategories,
            'subSubCategories' => $subSubCategories,
            'brands' => $brands,
            'filteredBrands' => $filteredBrands,
            'products' => $products,
            'categorySelected' => $categorySelected,
            'subCategorySelected' => $subCategorySelected,
            'subSubCategorySelected' => $subSubCategorySelected,
            'brandsArray' => $brandsArray,
            'brandCounts' => $brandCounts,
            'priceMax' => $request->get('price_max'),
            'priceMin' => $request->get('price_min'),
            'categorySlug' => $categorySlug,
            'subCategorySlug' => $subCategorySlug,
            'subSubCategorySlug' => $subSubCategorySlug,
            'showSubCategories' => $showSubCategories,
            'showSubSubCategories' => $showSubSubCategories,
            'categoryName' => $categoryName,
            'subCategoryName' => $subCategoryName,
            'subSubCategoryName' => $subSubCategoryName,
            'sortBy' => $sortBy
        ]);
    }
    
    public function product($slug) {
        $product = Product::where('slug',$slug)->with(['product_images', 'specifications'])->first();
        if ($product == null) {
            abort(404);
        }

        $breadcrumbs = [];
        $breadcrumbs[] = ['name' => 'Početna', 'url' => route('shop.homepage')];
    
        if ($product->category) {
            $breadcrumbs[] = ['name' => $product->category->name, 'url' => route('shop.shop', $product->category->slug)];
        }
    
        if ($product->sub_category) {
            $breadcrumbs[] = ['name' => $product->sub_category->name, 'url' => route('shop.shop', $product->category->slug . '/' . $product->sub_category->slug)];
        }
    
        if ($product->sub_sub_category) {
            $breadcrumbs[] = ['name' => $product->sub_sub_category->name, 'url' => route('shop.shop', $product->category->slug . '/' . $product->sub_category->slug . '/' . $product->sub_sub_category->slug)];
        }
    
        $breadcrumbs[] = ['name' => $product->title, 'url' => ''];


        $data['product'] = $product;
        $data['breadcrumbs'] = $breadcrumbs;

        return view('shop.product', $data);
    }
}
