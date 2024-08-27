<?php

namespace App\Http\Controllers;

use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Log;
use function App\Helpers\orderEmail;
use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use App\Models\CompanyOrder;
use App\Models\UserOrder;
use Illuminate\Support\Facades\Mail;




class CartController extends Controller
{

    public function addToCart(Request $request) {
        $product = Product::with('product_images')->find($request->id);
    
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Proizvod nije pronađen',
            ]);
        }
    
        // Proveri da li proizvod ima discount_price
        $price = $product->discount_price ?? $product->price;
    
        if (Cart::count() > 0) {
            $cartContent = Cart::content();
            $productAlreadyExist = false;
        
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $newQuantity = $item->qty + 1;
        
                    // Proverite da li je nova količina veća od dostupne količine
                    if ($newQuantity <= $product->quantity) { // Pretpostavljamo da $product->quantity sadrži dostupnu količinu
                        Cart::update($item->rowId, $newQuantity);
                        $status = true;
                        $message = 'Količina artikla u korpi je povećana!';
                    } else {
                        $status = false;
                        $message = 'Nema dovoljno artikala na stanju!';
                    }
                    $productAlreadyExist = true;
                    break; // Prekinite petlju, jer je artikal već pronađen
                }
            }
        
            if (!$productAlreadyExist) {
                // Proverite da li je dostupna količina za novi artikal
                if ($product->quantity > 0) {
                    Cart::add($product->id, $product->title, 1, $price, [
                        'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''
                    ]);
                    $status = true;
                    $message = 'Artikal je dodat u korpu!';
                } else {
                    $status = false;
                    $message = 'Nema dovoljno artikala na stanju!';
                }
            }
        } else {
            // Proverite da li je dostupna količina za novi artikal
            if ($product->quantity > 0) {
                Cart::add($product->id, $product->title, 1, $price, [
                    'productImage' => (!empty($product->product_images)) ? $product->product_images->first() : ''
                ]);
                $status = true;
                $message = 'Artikal je dodat u korpu!';
            } else {
                $status = false;
                $message = 'Nema dovoljno artikala na stanju!';
            }
        }
    
        return response()->json([
            'status' => $status,
            'message' => $message,
        ]);
    }
    public function cart(Request $request) {
        $cartContent = Cart::content();

        $data['cartContent'] = $cartContent;
        return view('shop.cart', $data);
    }

    public function updateCart(Request $request) {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        if ($product->track_quantity == 'Yes') {

            if ( $qty <= $product->quantity) {
                Cart::update($rowId, $qty);
                $message = 'Uspešno ste ažurirali korpu';
                $status = true;
                session()->flash('success', $message);
            } else {
                $message = "Izabrana količina ($qty) nije dostupna u našem skladištu.";
                $status = false;
                session()->flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Uspešno ste ažurirali korpu';
            $status = true;
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        $rowId = $request->rowId;
        $itemInfo = Cart::get($rowId);

        if ($itemInfo == null) {
            $errorMessage = 'Nemate ništa u korpi, nastavite kupovinu.';
            session()->flash('error',  $errorMessage);
            
            return response()->json([
                'status' => false,
                'message' =>  $errorMessage
            ]);
        }

        Cart::remove($request->rowId);
        
        
        return response()->json([
            'status' => false,
        ]);

    }

    public function checkout() {

        //-- if cart is empty redirect to cart page
        if (Cart::count() == 0) {
            return redirect()->route('shop.cart');
        }

        $countries = Country::orderBy('name', 'ASC')->get();

        $data['countries'] = $countries;

        //-- if user is not logged in then redirect to login page
        // if (Auth::check() ==  false ) {
                // session(['url.intended' => url()->current()]);
        //      return redirect()->route('account.login');
        // }

        return view('shop.checkout', $data);
    }

    private function getIpAddressAndCountry() {
        $ipAddress = request()->ip(); // Dobijanje IP adrese posetioca
    
        // API URL za IP adresu
        $apiUrl = "https://freeipapi.com/api/json/{$ipAddress}";
    
        $response = file_get_contents($apiUrl);
        $data = json_decode($response, true);
    
        Log::info('IP Geolocation API Response', $data);
    
        if (isset($data['ipAddress']) && isset($data['countryName'])) {
            return [
                'ip_address' => $data['ipAddress'],
                'country' => $data['countryName'],
            ];
        }
    
        return [
            'ip_address' => 'Nepoznato',
            'country' => 'Nepoznato',
        ];
    }

    public function processCheckout(Request $request)
    {
        // Korak 1: Validacija podataka za fizicka lica (UserOrder)
        $validatorUser = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'zip_code' => 'required',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
            'delivery_method' => 'required|in:address,store',
        ]);

        $errorsUser = $validatorUser->errors();

        // Ako ima grešaka za UserOrder
        if ($validatorUser->fails()) {
            return response()->json([
                'message' => 'Greške u unosu za fizicka lica',
                'status' => false,
                'errors' => $errorsUser
            ]);
        }

        // Snimanje podataka u odgovarajucu tabelu (UserOrder ili CompanyOrder)
        if ($request->input('order_type') === 'user') {
            $userOrder = new UserOrder();
            $userOrder->subtotal = Cart::subtotal(2, '.', '');

            // Postavljanje troška dostave na osnovu delivery_method
            if ($request->input('delivery_method') === 'address') {
                $userOrder->shipping = '500'; // Cena dostave za adresu
            } else {
                $userOrder->shipping = '0'; // Cena dostave za preuzimanje u radnji
            }

            $userOrder->grand_total = $userOrder->subtotal + $userOrder->shipping;
            $userOrder->full_name = $request->input('full_name');
            $userOrder->email = $request->input('email');
            $userOrder->phone_number = $request->input('phone_number');
            $userOrder->address = $request->input('address');
            $userOrder->city = $request->input('city');
            $userOrder->zip_code = $request->input('zip_code');
            $userOrder->note_to_seller = $request->input('note_to_seller');
            $userOrder->payment_method = $request->input('payment_method');
            $userOrder->delivery_method = $request->input('delivery_method');
            $userOrder->save();

            // Kreiramo glavni Order zapis
            $order = new Order();
            $order->orderable_id = $userOrder->id;
            $order->orderable_type = UserOrder::class;
            $order->subtotal = $userOrder->subtotal;
            $order->shipping = $userOrder->shipping;
            $order->grand_total = $userOrder->grand_total;
            $order->payment_method = $userOrder->payment_method;
            $order->delivery_method = $userOrder->delivery_method;
            $orderDetails = $this->getIpAddressAndCountry();
            $order->ip_address = $orderDetails['ip_address'];
            $order->country = $orderDetails['country'];
            $order->save();

            $this->saveOrderItems($order);

            // Pozivanje funkcije orderEmail
            orderEmail($order->id);

            //Unistavanje sesije korpe
            Cart::destroy();

            return response()->json([
                'message' => 'Uspešno ste poručili proizvod kao (fizicko lice)',
                'orderId' => $order->id,
                'status' => true
            ]);


        } else {
            return response()->json([
                'message' => 'Nepoznat tip porudžbine',
                'status' => false
            ]);
        }
    }

    public function processCompanyOrder(Request $request)
    {
        // Korak 1: Validacija podataka za pravna lica (CompanyOrder)
        $validator = Validator::make($request->all(), [
            'full_name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'city' => 'required',
            'phone_number' => 'required',
            'zip_code' => 'required',
            'company_name' => 'required',
            'company_owner' => 'required',
            'company_pib' => 'required',
            'payment_method' => 'required|in:cash_on_delivery,bank_transfer',
        ]);

        // Ako validacija ne uspe, vraćamo greške
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Greške u unosu za pravna lica',
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        // Korak 2: Snimanje podataka u tabelu CompanyOrder
        $companyOrder = new CompanyOrder();
        $companyOrder->subtotal = Cart::subtotal(2, '.', '');

        // Postavljanje troška dostave na osnovu delivery_method
        if ($request->input('delivery_method') === 'address') {
            $companyOrder->shipping = '500'; // Cena dostave za adresu
        } else {
            $companyOrder->shipping = '0'; // Cena dostave za preuzimanje u radnji
        }
        
        $companyOrder->grand_total = $companyOrder->subtotal + $companyOrder->shipping;
        $companyOrder->full_name = $request->input('full_name');
        $companyOrder->email = $request->input('email');
        $companyOrder->address = $request->input('address');
        $companyOrder->city = $request->input('city');
        $companyOrder->phone_number = $request->input('phone_number');
        $companyOrder->zip_code = $request->input('zip_code');
        $companyOrder->company_name = $request->input('company_name');
        $companyOrder->company_owner = $request->input('company_owner');
        $companyOrder->company_pib = $request->input('company_pib');
        $companyOrder->bank_account_number = $request->input('bank_account_number');
        $companyOrder->payment_method = $request->input('payment_method');
        $companyOrder->delivery_method = $request->input('delivery_method');
        $companyOrder->note_to_seller = $request->input('note_to_seller');
        $companyOrder->save();

        // Kreiramo glavni Order zapis
        $order = new Order();
        $order->orderable_id = $companyOrder->id;
        $order->orderable_type = CompanyOrder::class;
        $order->subtotal = $companyOrder->subtotal;
        $order->shipping = $companyOrder->shipping;
        $order->grand_total = $companyOrder->grand_total;
        $order->payment_method = $companyOrder->payment_method;
        $order->delivery_method = $companyOrder->delivery_method;
        $orderDetails = $this->getIpAddressAndCountry();
        $order->ip_address = $orderDetails['ip_address'];
        $order->country = $orderDetails['country'];
        $order->save();

        // Snimanje stavki narudžbine
        $this->saveOrderItems($order);

        // Pozivanje funkcije orderEmail
        orderEmail($order->id);

        //Unistavanje sesije korpe
        Cart::destroy();

        return response()->json([
            'message' => 'Uspešno ste poručili proizvod kao (pravno lice)',
            'orderId' => $order->id,
            'status' => true
        ]);
    }
    // Metod za snimanje stavki narudžbine
    private function saveOrderItems($order)
    {
        foreach (Cart::content() as $item) {
            $orderItem = new OrderItem();
            $orderItem->product_id = $item->id;
            $orderItem->order_id = $order->id; // Koristimo order ID iz Order modela
            $orderItem->name = $item->name;
            $orderItem->qty = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;
            $orderItem->save();

        }
    }

    public function getCartCount() {
        $cartCount = Cart::count();
        return response()->json([
            'count' => $cartCount
        ]);
    }

    public function successPage($orderId) {
        $order = Order::findOrFail($orderId);

        $fullName = $order->orderable->full_name;
        $createdAt = $order->orderable->created_at;

        $data['order'] = $order;
        $data['fullName'] = $fullName;
        $data['createdAt'] = $createdAt;

        return view('shop.successPage', $data);
    }
}
