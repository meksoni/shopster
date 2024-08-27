<?php

namespace App\Http\Controllers;

use App\Models\Visitor;
use Illuminate\Http\Request;


class VisitorController extends Controller
{
    public function trackVisitor(Request $request)
    {
        $ip = $request->input('ip');
        $country = $request->input('country');

        // Proveri da li posetilac već postoji
        if (!Visitor::where('ip_address', $ip)->exists()) {
            // Kreiraj novog posetioca
            Visitor::create([
                'ip_address' => $ip,
                'country' => $country,
            ]);
        }

        return response()->json(['message' => 'Poseta zabeležena']);
    }
}
