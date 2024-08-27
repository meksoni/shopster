<?php

namespace App\Http\Middleware;

use App\Models\Brand;
use App\Models\Notifications;
use App\Models\SubCategory;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\View;
use App\Models\Order;



class GlobalVariablesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Dohvati sve proizvode sa statusom 1
        $products = Product::where('status', 1)->where('quantity', '>', 0)->get();

        // Dohvati kategorije sa podkategorijama koje su dostupne za prikaz na početnoj stranici
        $categories = Category::orderBy('id', 'ASC')
            ->where('status', 1)
            ->where('showHome', 'Yes')
            ->withCount(['products as available_products_count' => function ($query) {
                $query->where('quantity', '>', 0); // Broji samo proizvode koji imaju količinu veću od 0
            }])
            ->having('available_products_count', '>', 0) // Prikaži samo kategorije sa dostupnim proizvodima
            ->get();

        $footerCategories = Category::orderBy('id', 'ASC')
            ->where('status', 1)
            ->where('showHome', 'Yes')
            ->withCount(['products as available_products_count' => function ($query) {
                $query->where('quantity', '>', 0); // Broji samo proizvode koji imaju količinu veću od 0
            }])
            ->having('available_products_count', '>', 0) // Prikaži samo kategorije sa dostupnim proizvodima
            ->limit(5)
            ->get();
        
        $footerBrands = Brand::orderBy('id', 'ASC')
            ->where('status', 1)
            ->withCount(['products as available_products_count' => function ($query) {
                $query->where('quantity', '>', 0);
            }])
            ->having('available_products_count', '>', 0)
            ->limit(5)
            ->get();

        // Izracunavanje novih porudzbina u 24 sata
        $newOrdersCount = Order::where('is_opened', false)->count();

        // Deli ove podatke sa svim pogledima kroz Laravel-ov View kompozitor
        View::share('products', $products);
        View::share('categories', $categories);
        View::share('newOrdersCount', $newOrdersCount);
        View::share('footerCategories', $footerCategories);
        View::share('footerBrands', $footerBrands);
        
        return $next($request);
    }
}
