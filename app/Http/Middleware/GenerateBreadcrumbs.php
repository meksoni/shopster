<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Brand;
use App\Models\SubCategory;
use App\Models\SubSubCategory;

class GenerateBreadcrumbs
{
    public function handle(Request $request, Closure $next)
    {
        $breadcrumbs = [];
        $segments = $request->segments();
        $url = '';
        $currentPage = '';

        if (isset($segments[0]) && $segments[0] === 'admin') {
            // Dodajte Kontrolnu tablu u breadcrumbs
            $breadcrumbs[] = ['name' => 'Kontrolna tabla', 'url' => route('admin.dashboard')];
            array_shift($segments); // Preskočimo prvi segment 'admin'
            $url = '/admin'; // Postavimo početni URL za admin panele
        }

        foreach ($segments as $segment) {
            if(in_array($segment,['edit', 'create'])){
                continue;
            };
            // Postavimo currentPage na osnovu trenutnog segmenta
            switch ($segment) {
                case 'products':
                    $currentPage = 'products';
                    break;
                case 'categories':
                    $currentPage = 'categories';
                    break;
                case 'sub-categories':
                    $currentPage = 'sub-categories';
                    break;
                case 'sub-sub-categories':
                    $currentPage = 'sub-sub-categories';
                    break;
                case 'brands':
                    $currentPage = 'brands';
                    break;
                default:
                    $currentPage = 'unknown'; // Postavimo na unknown ako ne prepoznamo segment
                    break;
            }

            $name = ucfirst(str_replace('-', ' ', $segment)); // Početni default name

            // Proverimo da li je segment ID i zamenimo ga odgovarajućim imenom
            if (is_numeric($segment)) {
                $category = Category::find($segment);
                if ($category) {
                    $name = $category->name;
                    $url .= '/categories'; // Dodamo kategoriju u URL ako je pronađena
                } else {
                    $product = Product::find($segment);
                    if ($product) {
                        $name = $product->title;
                    } else {
                        $brand = Brand::find($segment);
                        if ($brand) {
                            $name = $brand->name;
                        } else {
                            $subCategory = SubCategory::find($segment);
                            if ($subCategory) {
                                $name = $subCategory->name;
                            } else {
                                $subSubCategory = SubSubCategory::find($segment);
                                if ($subSubCategory) {
                                    $name = $subSubCategory->name;
                                }
                            }
                        }
                    }
                }
            }

            $url .= '/' . $segment;
            $breadcrumbs[] = ['name' => $name, 'url' => url($url)];
        }

        // Prosledite $breadcrumbs varijablu svim pogledima
        view()->share('breadcrumbs', $breadcrumbs);
        view()->share('currentPage', $currentPage);

        return $next($request);
    }
}
