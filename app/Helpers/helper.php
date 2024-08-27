<?php

namespace App\Helpers;

use App\Mail\OrderNotificationEmail;
use App\Models\Category;
use App\Models\CompanyOrder;
use App\Models\Country;
use App\Models\Store;
use App\Models\SubCategory;
use App\Models\UserOrder;
use Illuminate\Support\Facades\Http;
Use Illuminate\Support\Facades\Mail;
Use App\Models\Order;
Use App\Mail\OrderEmail;
use Barryvdh\DomPDF\Facade\Pdf;


function generateQrCode($order) {
    $store = Store::first();
    // Formatiranje cene prema specifikaciji NBS IPS QR
    $orderPrice = number_format($order->grand_total, 2, ',', '');
    // Uklanjanje tačke za hiljade
    $orderPrice = str_replace('.', '', $orderPrice);
    $formattedOrderPrice = "RSD" . $orderPrice;
    $bankAccountNumber = str_replace([' ', '-', '_'], '', $store->cp_bank_account);

    $createdYear = $order->created_at->format('Y');


    // Formatiranje teksta prema specifikaciji NBS IPS QR
    $qrData = [
        "K" => "PR",
        "V" => "01",
        "C" => "1",
        "R" => $bankAccountNumber,
        "N" => $store->cp_name . "\r\n" . $store->cp_address . "\r\n" . $store->cp_city . " " . $store->cp_zip,
        "I" => $formattedOrderPrice,
        "P" => $order->orderable->full_name . "\r\n" . $order->orderable->address . "\r\n" . $order->orderable->city . " " . $order->orderable->zip_code,
        "SF" => "289",
        "S" => "Kupovina IT opreme Porudžbina " . $order->id,
        "RO" => "99" . $order->id . $createdYear,
    ];

    \Log::info('Generating QR code with data: ', $qrData);

    // Slanje podataka kao niz, Laravel će ga konvertovati u JSON
    $response = Http::withHeaders(['Content-Type' => 'application/json'])
        ->post('https://nbs.rs/QRcode/api/qr/v1/gen', $qrData);

    if ($response->successful()) {
        \Log::info('QR code generated successfully');
        return 'data:image/png;base64,' . base64_encode($response->body());
    } else {
        \Log::error('Failed to generate QR code. Response: ' . $response->body());
        return null;
    }
}

function orderEmail($orderId) {
    $order = Order::where('id', $orderId)->with('orderable')->first();

    if (!$order || !$order->orderable) {
        \Log::error('Order or orderable not found for order ID: ' . $orderId);
        return;
    }

    $email = '';
    if ($order->orderable_type === UserOrder::class) {
        $email = $order->orderable->email;
    } elseif ($order->orderable_type === CompanyOrder::class) {
        $email = $order->orderable->email;
    }

    if (!$email) {
        \Log::error('Email not found for order ID: ' . $orderId);
        return;
    }

    $adminEmail = config('mail.from.address');

    $qrCode = generateQrCode($order);
    \Log::info('Generated QR Code: ' . $qrCode);

    $attachFile = null;
    if ($order->payment_method === 'bank_transfer') {
        $pdf = Pdf::loadView('pdf.uplatnica', ['order' => $order, 'qrCode' => $qrCode])
                  ->setPaper('A4', 'landscape');
        $attachFile = public_path('uplatnice/ips_bank-nalog-' . $order->id . '.pdf');
        if (!file_exists(dirname($attachFile))) {
            mkdir(dirname($attachFile), 0755, true);
        }
        $pdf->save($attachFile);
    }

    try {
        Mail::to($email)
            ->send(new OrderEmail([
                'subject' => 'AlfaSoft Online Prodavnica | Porudžbina: #' . $orderId,
                'order' => $order,
                'qrCode' => $qrCode,
            ], $attachFile));

        Mail::to($adminEmail)
            ->send(new OrderNotificationEmail($orderId));

        \Log::info('Emails sent successfully for order ID: ' . $orderId);

    } catch (\Exception $e) {
        \Log::error('Failed to send emails for order ID: ' . $orderId . '. Error: ' . $e->getMessage());
    }
}





function getCountryInfo($id) {
    return Country::where('id', $id)->first();
}

function getCategories() {
    return Category::orderBy('name', 'ASC')->get();
}


if (!function_exists('getRandomSubCategories')) {
    function getRandomSubCategories($count = 6) {
        \Log::info('getRandomSubCategories function called'); // linija za debagovanje
        $subCategories = SubCategory::all();
        return $subCategories->random(min($count, $subCategories->count()));
    }
}


?>