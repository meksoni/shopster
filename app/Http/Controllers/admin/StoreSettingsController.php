<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use Illuminate\Http\Request;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;


class StoreSettingsController extends Controller
{
    public function edit() {
        $store = Store::firstOrCreate([]);
        return view('admin.store.edit', compact('store'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'cp_name' => 'nullable|string|max:255',
            'cp_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cp_email' => 'nullable|email|max:255',
            'cp_phone' => 'nullable|string|max:20',
            'global_currency' => 'nullable|string|max:10',
            'cp_pib' => 'nullable|string|max:30',
            'cp_mb' => 'nullable|string|max:30',
            'cp_address' => 'nullable|string|max:255',
            'cp_city' => 'nullable|string|max:100',
            'cp_country' => 'nullable|string|max:100',
            'cp_zip' => 'nullable|string|max:20',
            'cp_bank_account' => 'nullable|string|max:20',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
        ]);

        // Uklonite sve "_" iz telefonskog broja
        $request['cp_phone'] = preg_replace('/_/', '', $request['cp_phone']);
        $request['cp_bank_account'] = preg_replace('/_/', '', $request['cp_bank_account']);

        $store = Store::firstOrCreate([]);
        $store->update($request->all());

        if ($request->hasFile('cp_logo')) {
            $imagePath = $request->file('cp_logo')->store('logo', 'public');
            $store->update(['cp_logo' => $imagePath]);
        }

        $userId = Auth::id();

        Notifications::create([
            'user_id' => $userId,
            'message' => 'je ažurirao podatke prodavnice',
            'is_read' => false,
        ]);

       

        return redirect()->route('store.edit')->with('success', 'Uspešno ste sačuvali podešavanja vaše prodavnice.');
    }
}
