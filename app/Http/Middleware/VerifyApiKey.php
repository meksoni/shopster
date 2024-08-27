<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;


class VerifyApiKey
{
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->header('API_KEY');

        if (!$apiKey) {
            return response()->json(['message' => 'Missing API key.'], 401);
        }

        $validApiKey = ApiKey::where('key', $apiKey)
                             ->where('expires_at', '>', Carbon::now())
                             ->exists(); // Dodajemo exists() da bismo proverili stvarnu validnost ključa

        if (!$validApiKey) {
            return response()->json(['message' => 'Invalid API key or expired.'], 401);
        }

        return $next($request);
    }
}
