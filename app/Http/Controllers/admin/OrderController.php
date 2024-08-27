<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\Notifications;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;




class OrderController extends Controller
{
    public function index(Request $request) {
        

        $orders = Order::latest();

        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $orders = $orders->whereHas('orderable', function ($query) use ($keyword) {
                $query->where('full_name', 'like', '%' . $keyword . '%')
                ->orWhere('email', 'like', '%' . $keyword . '%')
                ->orWhere('phone_number', 'like', '%' . $keyword . '%');
            })->orWhere('orders.id', 'like', '%' . $keyword . '%');
        }

        if ($request->filled('from_date')) {
            $orders->whereDate('created_at', '>=', $request->from_date);
        }
    
        if ($request->filled('to_date')) {
            $orders->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $orders->paginate(15);

        $data['orders'] = $orders;
        

        return view('admin.orders.index', $data);
    }

    public function detail($orderId) {
        $order = Order::with('orderable')
                    ->where('id', $orderId)
                    ->first();

        // Ako narudžbina nije otvorena, označi je kao otvorenu
        if (!$order->is_opened) {
            $order->is_opened = true;
            $order->save();
        }

        // Pripremi sve moguće statuse
        $orderStatus = [
            'na_cekanju' => 'Na čekanju',
            'poslato' => 'Poslato',
            'isporuceno' => 'Isporučeno',
            'otkazano' => 'Otkazano',
            'vraceno' => 'Vraćeno',
            'neuspesno' => 'Neuspešno',
        ];
        

        $orderItems = OrderItem::where('order_id', $orderId)->get();

        return view('admin.orders.detail',[
            'order' => $order,
            'orderItems' => $orderItems,
            'orderStatus' => $orderStatus
        ]);
    }

    public function updateStatus(Request $request, $orderId) {
        $request->validate([
            'status' => 'required|in:na_cekanju,poslato,isporuceno,otkazano,vraceno,neuspesno',
        ]);
    
        $order = Order::findOrFail($orderId);
        $previousStatus = $order->status; // Sačuvaj prethodni status
        $order->status = $request->input('status');
        $order->save();
    
        // Ako je novi status "isporučeno" i prethodni status nije "isporučeno"
        if ($order->status == 'isporuceno' && $previousStatus != 'isporuceno') {
            $orderItems = OrderItem::where('order_id', $orderId)->get();
            foreach ($orderItems as $item) {
                $product = Product::find($item->product_id); // Pretpostavljam da OrderItem ima kolonu product_id
                if ($product) {
                    // Proveri da li smanjenje količine dovodi do negativne vrednosti
                    if ($product->quantity >= $item->qty) {
                        $product->quantity -= $item->qty;
                    } else {
                        // Ako smanjenje dovodi do negativne vrednosti, postavi količinu na nulu
                        $product->quantity = 0;
                    }
                    $product->save();
                }
            }
        }

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je ažurirao status porudžbine ' . '<span class="fw-medium">' .  $order->id . '</span>',
            'is_read' => false,
        ]);
    
        return redirect()->back()->with('success', 'Status porudžbine uspešno ažuriran.');
    }
}
