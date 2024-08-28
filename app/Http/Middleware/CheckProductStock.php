<?php

namespace App\Http\Middleware;

use App\Models\Notifications;
use App\Models\Product;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;





class CheckProductStock
{
   
    public function handle(Request $request, Closure $next)
    {
        $products = Product::all();

        foreach ($products as $product) {
            // Proverite da li je proizvod ispod 3
            if ($product->quantity < 3) {
                // Proverite da li je obaveštenje već poslato
                if (!$product->is_low_stock_notified) {
                    $url = route('products.index', ['keyword' => $product->title]);

                    
    
                    $userId = Auth::id();
                    // Kreirajte novu notifikaciju
                    Notifications::create([
                        'user_id' => $userId,
                        'message' => 'Proizvod <span class="fw-medium">' . $product->title . '</span> ima manje od 3 komada na stanju.',
                        'url' => $url,
                        'is_read' => false,
                    ]);
    
                    // Ažurirajte status proizvoda
                    $product->is_low_stock_notified = true;
                    $product->save();
                }
            } else {
                // Ako je stanje veće od 3, resetujte obaveštenje
                $product->is_low_stock_notified = false;
                $product->save();
            }
        }

        return $next($request);
    }
}
