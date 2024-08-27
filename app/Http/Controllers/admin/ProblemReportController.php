<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Store;
Use Illuminate\Support\Facades\Mail;

class ProblemReportController extends Controller
{
    public function showForm() {
        return view('admin.problems.report');
    }

    public function submitForm(Request $request) {
        //Validacija podataka iz forme
        $request->validate([
            'description' => 'required|string|max:1000',
        ]);

        $store = Store::first();

        Mail::send([], [], function ($message) use ($request, $store) {
            $message->to('support@neodigital.pro')
                    ->subject('Prijava problema' . $store->cp_name)
                    ->from($store->cp_email)
                    ->html('Korisnik: ' . $store->cp_name . '<br>Email: ' . $store->cp_email . '<br>Kontakt: ' . $store->cp_phone .'<br><br>Opis problema: ' . $request->description);  
        });

        return redirect()->back()->with('success', 'Problem je uspešno prijavljen, kontaktiraćemo Vas ubrzo.');
    }
}
