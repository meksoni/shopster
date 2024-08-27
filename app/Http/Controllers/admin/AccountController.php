<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreated;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;


class AccountController extends Controller
{
    //

    public function edit()
    {
        $user = Auth::user();
        return view('admin.account.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'current_password' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed', // Lozinka je sada obavezna
        ]);

        // Provera trenutne lozinke ako je nova lozinka unešena
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->save();

        return redirect()->route('account.settings')->with('success', 'Account settings updated successfully.');
    }

    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function adminUpdate(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|integer',
            'position' => 'nullable|string|max:255',
        ]);

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->role = $request->role;
        $user->position = $request->position;
        $user->save();

        return redirect()->route('admin.users')->with('success', 'User updated successfully.');
    }

    public function store(Request $request) {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|integer',
            'position' => 'nullable|string|max:255',
        ]);
    
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->role = $request->role;
        $user->position = $request->position;
        $user->save();        
        
        // Generiši SVG sliku
        $this->createUserImage($user);

        // Izbacili smo slanje na mejl zbog sigurnosnih razloga
        // Mail::to($user->email)->send(new UserCreated($user->username, $user->email, $request->password));
    
        return redirect()->route('admin.users')->with('success', 'User created successfully.');
    }

    public function createUserImage($user) {
        // Koristi mb_substr da uzmeš inicijale sa podrškom za UTF-8
        $initials = strtoupper(mb_substr($user->first_name, 0, 1) . mb_substr($user->last_name, 0, 1));
        $backgroundColors = ['#50B1A3', '#FF5733', '#ffc107', '#2fb964']; // Primer boja
        $backgroundColor = $backgroundColors[array_rand($backgroundColors)];
    
        $img = Image::canvas(100, 100, $backgroundColor);
        $img->text($initials, 50, 50, function($font) {
            $font->file(public_path('assets/fonts/Kanit.ttf')); // Putanja do fonta
            $font->size(40);
            $font->color('#FFFFFF');
            $font->align('center');
            $font->valign('middle');
        });
        $img->save(storage_path('app/public/user_images/user_' . $user->id . '.png'));
    
        return $img; // Vrati putanju do sačuvane slike
    }

    public function destroy($id, Request $request) {
        //Delete user funkcija
        $user = User::find($id);
    
        if (empty($user)) {
            $request->session()->flash('error', 'Korisnik nije pronađen');
            return response()->json([
                'status' => false,
                'message' => 'Proizvod nije pronađen'
            ]);
        }
 
        // Brisanje slike korisnika
        $imagePath = storage_path('app/public/user_images/user_' . $user->id . '.png');
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
        
        // Brisanje korisnika
        $user->delete();

    
        $request->session()->flash('success', 'Korisnik je uspešno obrisan');
        return response()->json([
            'status' => true,
            'message' => 'Korisnik je uspešno obrisan'
        ]);
    }

}
