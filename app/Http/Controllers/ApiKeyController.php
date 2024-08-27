<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use Carbon\Carbon;
use Illuminate\Http\Request;


class ApiKeyController extends Controller
{
    public function getCurrentKey() {
        $currentKey = ApiKey::where('expires_at', '>', Carbon::now())->first();
    
        if (!$currentKey) {
            return response()->json(['message' => 'Nema podataka o validnom API kljucu'], 404);
        }
    
        return response()->json(['api_key' => $currentKey->key]); 
    }
}
