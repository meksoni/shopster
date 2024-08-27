<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShopSelectedItems;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Visitor;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use App\Models\TempImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;





class DashboardController extends Controller
{


    public function index(Request $request) {
    // Dohvati mesec i godinu iz zahteva ili koristi trenutni mesec i godinu
    $selectedMonth = $request->input('month', Carbon::now()->month);
    $selectedYear = $request->input('year', Carbon::now()->year);

    // Izvlačenje datuma za dinamičko vođenje statistike iz meseca u mesec
    $startDate = Carbon::createFromDate($selectedYear, $selectedMonth)->startOfMonth();
    $endDate = Carbon::createFromDate($selectedYear, $selectedMonth)->endOfMonth();

    // Formatiranje datuma za statistiku
    $formattedStartDate = $startDate->format('d.m.Y');
    $formattedEndDate = $endDate->format('d.m.Y');

    // Ukupna vrednost prodaje za izabrani mesec
    $totalRevenueForMonth = $this->getTotalRevenueForMonth($startDate, $endDate);
    
    // Broj prodatih proizvoda za izabrani mesec
    $totalProductsSoldForMonth = $this->getTotalProductsSoldForMonth($startDate, $endDate);
    
    // Ukupna vrednost prodaje
    $revenueData = $this->getTotalRevenueData($startDate, $endDate);

    // Broj prodatih proizvoda
    $salesData = $this->getTotalProductsSoldData($startDate, $endDate);

    // Generisanje datuma i vrednosti za grafikon
    [$dates, $sales, $revenue] = $this->getChartData($startDate, $endDate, $revenueData, $salesData);

    // Brisanje privremenih slika svaki novi dan
    $this->deleteTemporaryImages();


    $brands = Brand::withCount(['products' => function ($query) {
        $query->where('quantity', '>', 0); // Filtriraj proizvode sa količinom većom od 0
    }])->having('products_count', '>', 0)->get(); // Uzimanje samo brendova koji imaju proizvode
    
    $categories = Category::withCount(['products' => function ($query) {
        $query->where('quantity', '>', 0); // Filtriraj proizvode sa količinom većom od 0
    }])->having('products_count', '>', 0)->get(); // Uzimanje samo kategorija koje imaju proizvode
    
    $sub_categories = SubCategory::withCount(['products' => function ($query) {
        $query->where('quantity', '>', 0); // Filtriraj proizvode sa količinom većom od 0
    }])->having('products_count', '>', 0)->get(); // Uzimanje samo pod-kategorija koje imaju proizvode
    
    $sub_sub_categories = SubSubCategory::withCount(['products' => function ($query) {
        $query->where('quantity', '>', 0); // Filtriraj proizvode sa količinom većom od 0
    }])->having('products_count', '>', 0)->get(); // Uzimanje samo pod-pod-kategorija koje imaju proizvode

    $selectedItems = ShopSelectedItems::first() ?? new ShopSelectedItems();
    
    // Ukupna vrednost prodaje za trenutni mesec
    $currentMonthSales = array_sum($sales);

    // Ukupna vrednost prodaje za prethodni mesec
    $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
    $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
    $previousMonthSales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('status', 'isporuceno')
        ->whereBetween('orders.created_at', [$previousMonthStart, $previousMonthEnd])
        ->sum('order_items.qty');

    // Izračunaj procenat promene
    $salesPercentageChange = 0;
    if ($previousMonthSales > 0) {
        $salesPercentageChange = round((($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100);
    }

    // Proveri da li je promena pozitivna ili negativna
    $salesTrend = $salesPercentageChange >= 0 ? 'positive' : 'negative';

    // Podaci za poslednjih 7 dana
    $last7DaysData = $this->getLast7DaysData();

    // Ukupna vrednost prodaje i broj prodatih proizvoda za poslednjih 7 dana
    $totalRevenueLast7Days = $this->getTotalRevenueLast7Days();
    $totalSalesLast7Days = $this->getTotalSalesLast7Days();

    // Priprema podataka za JavaScript
    $salesData = [
        'currentMonthSales' => $currentMonthSales,
        'salesPercentageChange' => abs($salesPercentageChange),
        'salesTrend' => $salesTrend,
        'revenueCounts' => $last7DaysData['revenueCounts'],
        'salesCounts' => $last7DaysData['salesCounts'],
        'last7DaysDates' => $last7DaysData['last7DaysDates'],
        'totalRevenueForMonth' => $totalRevenueForMonth,
        'totalProductsSoldForMonth' => $totalProductsSoldForMonth,
    ];

    // Ukupno porudzbina
    $totalOrdersAll = Order::where('status', 'isporuceno')->count();

    //Ukupna zarada
    $totalRevenueAll = Order::where('status', 'isporuceno')->sum('grand_total');

    //Ukupno otkazanih porudzbina
    $totalOtkazano = Order::where('status', 'otkazano')->count();

    // Ukupno vracenih porudzbina
    $totalVraceno = Order::where('status', 'vraceno')->count();

    // TOP 5 Prodatih proizvoda
    $topProducts = $this->getTopProducts();

    // Ukupno posetioca po zemlji
    $totalVisitorsByCountry = $this->getVisitorDataByCountry();

    return view('admin.dashboard', [
        'brands' => $brands,
        'categories' => $categories,
        'sub_categories' => $sub_categories,
        'sub_sub_categories' => $sub_sub_categories,
        'dates' => $dates,
        'revenue' => $revenue,
        'sales' => $sales,
        'salesData' => $salesData,
        'totalRevenueLast7Days' => $totalRevenueLast7Days,
        'totalSalesLast7Days' => $totalSalesLast7Days,
        'totalOrdersAll' => $totalOrdersAll,
        'totalRevenueAll' => $totalRevenueAll,
        'formattedStartDate' => $formattedStartDate,
        'formattedEndDate' => $formattedEndDate,
        'totalOtkazano' => $totalOtkazano,
        'totalVisitorsByCountry' => $totalVisitorsByCountry,
        'topProducts' => $topProducts,
        'selectedItems' => $selectedItems,
        'totalVraceno' => $totalVraceno,
        'selectedMonth' => $selectedMonth,
        'selectedYear' => $selectedYear,
    ]);
    }

    private function getLast7DaysData() {
    $last7DaysStart = Carbon::now()->subDays(6)->startOfDay();
    $last7DaysEnd = Carbon::now()->endOfDay();
    
    $last7DaysRevenue = Order::whereBetween('created_at', [$last7DaysStart, $last7DaysEnd])
        ->where('status', 'isporuceno')
        ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total_revenue')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    $last7DaysSales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('status', 'isporuceno')
        ->whereBetween('orders.created_at', [$last7DaysStart, $last7DaysEnd])
        ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.qty) as total_products_sold')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    $last7DaysRevenueData = [];
    foreach ($last7DaysRevenue as $revenue) {
        $last7DaysRevenueData[$revenue->date] = $revenue->total_revenue;
    }
    
    $last7DaysSalesData = [];
    foreach ($last7DaysSales as $sales) {
        $last7DaysSalesData[$sales->date] = $sales->total_products_sold;
    }
    
    $last7DaysDates = [];
    $revenueCounts = [];
    $salesCounts = [];
    foreach ($last7DaysStart->daysUntil($last7DaysEnd) as $date) {
        $last7DaysDates[] = $date->format('D');
        $revenueCounts[] = (float) ($last7DaysRevenueData[$date->format('Y-m-d')] ?? 0.0);
        $salesCounts[] = (int) ($last7DaysSalesData[$date->format('Y-m-d')] ?? 0);
    }
    
    return [
        'last7DaysDates' => $last7DaysDates,
        'revenueCounts' => $revenueCounts,
        'salesCounts' => $salesCounts
    ];
    }

    private function getVisitorDataByCountry() {
        // Grupisanje po zemljama i brojanje poseta
        $visitors = Visitor::select('country', \DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderBy('total', 'DESC')
            ->take(5) // Uzmi prvih 5 zemalja
            ->get();

        return $visitors;
    }

    private function deleteTemporaryImages() {
    $dayBeforeToday = Carbon::now()->subDays(1)->format('Y-m-d');
    $tempImages = TempImage::where('created_at', '<=', $dayBeforeToday)->get();

    foreach ($tempImages as $tempImage) {
        $path = public_path('/temp/' . $tempImage->name);
        $thumbPath = public_path('/temp/thumb/' . $tempImage->name);

        // Delete main image
        if (File::exists($path)) {
            File::delete($path);
        }

        // Delete thumb image
        if (File::exists($thumbPath)) {
            File::delete($thumbPath);
        }

        $tempImage->delete();
    }
    }

    private function getChartData($startDate, $endDate, $revenueData, $salesData) {
    $dates = [];
    $sales = [];
    $revenue = [];
    foreach ($startDate->daysUntil($endDate) as $date) {
        $formattedDate = $date->format('M j');
        $dates[] = $formattedDate;
        $sales[] = (int) ($salesData[$date->format('Y-m-d')] ?? 0); // Ensure integer
        $revenue[] = (float) ($revenueData[$date->format('Y-m-d')] ?? 0.0); // Ensure float
    }
    return [$dates, $sales, $revenue];
    }

    private function getTotalProductsSoldData($startDate, $endDate) {
    return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('status', 'isporuceno')
        ->whereBetween('orders.created_at', [$startDate, $endDate])
        ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.qty) as total_products_sold')
        ->groupBy('date')
        ->get()
        ->pluck('total_products_sold', 'date')
        ->toArray();
    }

    private function getTotalRevenueData($startDate, $endDate) {
    return Order::whereBetween('created_at', [$startDate, $endDate])
        ->where('status', 'isporuceno')
        ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total_revenue')
        ->groupBy('date')
        ->get()
        ->pluck('total_revenue', 'date')
        ->toArray();
    }

    private function getTotalRevenueLast7Days() {
    $last7DaysStart = Carbon::now()->subDays(6)->startOfDay();
    $last7DaysEnd = Carbon::now()->endOfDay();
    
    $last7DaysRevenue = Order::whereBetween('created_at', [$last7DaysStart, $last7DaysEnd])
        ->where('status', 'isporuceno')
        ->selectRaw('DATE(created_at) as date, SUM(grand_total) as total_revenue')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    return $last7DaysRevenue->sum('total_revenue');
    }

    private function getTotalSalesLast7Days() {
    $last7DaysStart = Carbon::now()->subDays(6)->startOfDay();
    $last7DaysEnd = Carbon::now()->endOfDay();
    
    $last7DaysSales = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('status', 'isporuceno')
        ->whereBetween('orders.created_at', [$last7DaysStart, $last7DaysEnd])
        ->selectRaw('DATE(orders.created_at) as date, SUM(order_items.qty) as total_products_sold')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
    
    return $last7DaysSales->sum('total_products_sold');
    }

    private function getTotalRevenueForMonth($startDate, $endDate)
    {
        return Order::where('status', 'isporuceno')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('grand_total');
    }
    
    private function getTotalProductsSoldForMonth($startDate, $endDate)
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'isporuceno')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->sum('order_items.qty');
    }

    private function getTopProducts()
    {
        return OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
        ->where('orders.status', 'isporuceno') // Prvo ispravite ovde da se referišete na orders.status
        ->select('order_items.product_id', \DB::raw('count(*) as total_sold'))
        ->groupBy('order_items.product_id') // Koristite order_items.product_id
        ->orderBy('total_sold', 'desc')
        ->take(5)
        ->get()
        ->load('product.product_images'); // Učitajte slike nakon što dobijete rezultate
    }

    public function logout() {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }

    public function optimize() {
        Artisan::call('optimize:clear');
        return redirect()->route('admin.dashboard')->with('success', 'Keš memorija je uspešno očišćena!');
    }

    
}
