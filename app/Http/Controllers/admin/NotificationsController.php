<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Notifications;
use App\Models\Store;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;





class NotificationsController extends Controller
{


    public function getNotifications()
    {
       // Uzimanje poslednjih 5 notifikacija za sve korisnike
        // $notifications = Notifications::with('user') // Pretpostavljam da imaš vezu sa modelom User
        // ->latest() // Uzimanje najnovijih
        // ->take(10) // Ograničavanje na 10
        // ->get();

        // Uzimanje poslednjih 10 notifikacija za sve korisnike, ne starijih od 10 dana
        $notifications = Notifications::with('user') // Pretpostavljam da imaš vezu sa modelom User
        ->where('created_at', '>=', Carbon::now()->subDays(2)) // Filtriranje notifikacija starijih od 10 dana
        ->latest() // Uzimanje najnovijih
        ->take(5) // Ograničavanje na 5
        ->get();

        $store = Store::first();

        return response()->json([
            'notifications' => $notifications,
            'store' => $store
        ]);
    }

    public function markAllAsRead() {
        $userId = Auth::id();
    
        // Ažurirajte is_read za notifikacije korisnika
        $updatedCount = Notifications::where('user_id', $userId)->update(['is_read' => 1]);
    
        // Ažurirajte sve notifikacije sa niskim stanjem proizvoda kao pročitane
        Notifications::where('message', 'like', '%ima manje od 3 komada na stanju.%')
            ->update(['is_read' => 1]);
    
        return response()->json(['success' => true, 'updated_count' => $updatedCount]);
    }
    

    //Funkcija za pojedinacno mark as read
    // public function markAsRead($id)
    // {
    //     $notification = Notifications::find($id);
    //     if ($notification) {
    //         $notification->is_read = true;
    //         $notification->save();
    //         return response()->json(['success' => true]);
    //     }
        
    //     return response()->json(['success' => false], 404);
    // }

}
