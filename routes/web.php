<?php

use App\Http\Controllers\admin\AdminLoginController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\CategoryRoutesController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\NotificationsController;
use App\Http\Controllers\admin\OrderController;
use App\Http\Controllers\admin\ProblemReportController;
use App\Http\Controllers\admin\ProductImageController;
use App\Http\Controllers\admin\ProductRoutesController;
use App\Http\Controllers\admin\ShopSelectedItemsController;
use App\Http\Controllers\admin\StoreSettingsController;
use App\Http\Controllers\admin\TempImagesController;
use App\Http\Controllers\admin\BrandsController;
use App\Http\Controllers\admin\ProductController;
use App\Http\Controllers\admin\SubCategoryController;
use App\Http\Controllers\admin\SubSubCategoryController;
use App\Http\Controllers\admin\AccountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\VisitorController;
use App\Mail\OrderEmail;
use Illuminate\Support\Facades\Route;











/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('global.variables')->group(function () {
    // Rute koje koriste globalne promenljive
    //Homepage
    Route::get('/', [HomepageController::class, 'index'])->name('shop.homepage');
    //Korpa
    Route::get('/cart', [CartController::class, 'cart'])->name('shop.cart');
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->name('shop.addToCart');
    Route::post('/update-cart', [CartController::class, 'updateCart'])->name('shop.updateCart');
    Route::post('/delete-item', [CartController::class, 'deleteItem'])->name('shop.deleteItem.cart');
    Route::get('cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    //Admin
    Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');

    //checkout route
    Route::get('/checkout', [CartController::class, 'checkout'])->name('shop.checkout');
    //Checkout Process for Fizicko lice
    Route::post('/process-checkout', [CartController::class, 'processCheckout'])->name('shop.processCheckout');
    //Checkout Proccess for Pravno Lice
    Route::post('/process-company-order', [CartController::class, 'processCompanyOrder'])->name('shop.processCompanyOrder');
    //Success Page route
    Route::get('/success/{orderId}', [CartController::class, 'successPage'])->name('shop.successPage');

    //Shop Products
    Route::get('/shop/{categorySlug?}/{subCategorySlug?}/{subSubCategorySlug?}', [ShopController::class, 'index'])->name('shop.shop');
    Route::get('/product/{slug}', [ShopController::class, 'product'])->name('shop.product');

    //Session & Cookies Route
    Route::post('/track-visitor', [VisitorController::class, 'trackVisitor'])->name('track.visitor');

    // Search kontroler
    Route::get('/search', [HomepageController::class, 'search'])->name('search');
});


Route::group(['prefix' => 'admin'], function() {
    Route::group(['middleware' => 'admin.guest'], function(){
        Route::get('/',[AdminLoginController::class,'index'])->name('admin.login');
        Route::post('/',[AdminLoginController::class,'authenticate'])->name('admin.authenticate');
    });
    //Admin Authenticate ; ROLE = [1,2] is allowed to enter
    Route::group(['middleware' => 'admin.auth'], function(){
        Route::group(['middleware' => ['check.profile.complete', 'role:1,2']], function() {
            Route::get('/dashboard',[DashboardController::class, 'index'])->name('admin.dashboard');
            Route::get('/dashboard/data', [DashboardController::class, 'getChartData'])->name('admin.dashboard.data');

            Route::get('/components/shopSelectedItems', [ShopSelectedItemsController::class, 'edit'])->name('selectedItems.edit');
            Route::put('/components/shopSelectedItems', [ShopSelectedItemsController::class, 'update'])->name('selectedItems.update');
            

            // Products Routes
            Route::get('/products',[ProductController::class, 'index'])->name('products.index');
            Route::get('/products/create',[ProductController::class, 'create'])->name('products.create');
            Route::post('/products',[ProductController::class, 'store'])->name('products.store');
            Route::get('/products/edit/{product}',[ProductController::class, 'edit'])->name('products.edit');
            Route::put('/products/{product}',[ProductController::class, 'update'])->name('products.update');
            Route::delete('/products/{product}',[ProductController::class, 'destroy'])->name('products.delete');
            
            //Categories Routes
            Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index'); 
            Route::get('/categories/create',[CategoryController::class, 'create'])->name('categories.create');
            Route::post('/categories',[CategoryController::class, 'store'])->name('categories.store');  
            Route::get('/categories/edit/{category}',[CategoryController::class, 'edit'])->name('categories.edit');
            Route::put('/categories/{category}',[CategoryController::class, 'update'])->name('categories.update');
            Route::delete('/categories/{category}',[CategoryController::class, 'destroy'])->name('categories.delete');

            //Brands Routes
            Route::get('/brands',[BrandsController::class, 'index'])->name('brands.index');
            Route::get('/brands/create',[BrandsController::class, 'create'])->name('brands.create');
            Route::post('/brands',[BrandsController::class, 'store'])->name('brands.store');
            Route::get('/brands/edit/{brand}',[BrandsController::class, 'edit'])->name('brands.edit');
            Route::put('/brands/{brand}',[BrandsController::class, 'update'])->name('brands.update');
            Route::delete('/brands/{brand}',[BrandsController::class, 'destroy'])->name('brands.delete');
            
            // Sub Categories
            Route::get('/sub-categories',[SubCategoryController::class, 'index'])->name('sub-categories.index');
            Route::get('/sub-categories/create',[SubCategoryController::class, 'create'])->name('sub-categories.create');
            Route::post('/sub-categories',[SubCategoryController::class, 'store'])->name('sub-categories.store');
            Route::get('/sub-categories/edit/{subCategory}',[SubCategoryController::class, 'edit'])->name('sub-categories.edit');
            Route::put('/sub-categories/{subCategory}',[SubCategoryController::class, 'update'])->name('sub-categories.update');
            Route::delete('/sub-categories/{subCategory}',[SubCategoryController::class, 'destroy'])->name('sub-categories.delete');

            // Sub Sub Categories
            Route::get('/sub-sub-categories',[SubSubCategoryController::class, 'index'])->name('sub-sub-categories.index');
            Route::get('/sub-sub-categories/create',[SubSubCategoryController::class, 'create'])->name('sub-sub-categories.create');
            Route::post('/sub-sub-categories',[SubSubCategoryController::class, 'store'])->name('sub-sub-categories.store');
            Route::get('/sub-sub-categories/edit/{subCategory}',[SubSubCategoryController::class, 'edit'])->name('sub-sub-categories.edit');
            Route::put('/sub-sub-categories/{subCategory}',[SubSubCategoryController::class, 'update'])->name('sub-sub-categories.update');
            Route::delete('/sub-sub-categories/{subCategory}',[SubSubCategoryController::class, 'destroy'])->name('sub-sub-categories.delete');

            //Routes For Fetch Sub and SubSub Categories
            Route::get('/product-routes',[ProductRoutesController::class, 'index'])->name('product-routes.index');
            //Routes For Fetch SubCategory in SubSubCategory
            Route::get('/category-routes',[CategoryRoutesController::class, 'index'])->name('category-routes.index');

            //Product Images
            Route::post('/product-images/update',[ProductImageController::class, 'update'])->name('product-images.update');
            Route::delete('/product-images/delete',[ProductImageController::class, 'destroy'])->name('product-images.destroy');

            // temp-images.create
            Route::post('/upload-temp-image',[TempImagesController::class, 'create'])->name('temp-images.create');

            //Account Settings
            Route::get('/account/settings', [AccountController::class, 'edit'])->name('account.settings');
            Route::post('/account/settings', [AccountController::class, 'update'])->name('account.settings.update');

            //Order Routes
            Route::get('/orders',[OrderController::class, 'index'])->name('orders.index');
            Route::get('/orders/{id}',[OrderController::class, 'detail'])->name('orders.detail');
            Route::put('/admin/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

            // Problem Report
            Route::get('/report', [ProblemReportController::class, 'showForm'])->name('problems.show');
            Route::post('/report', [ProblemReportController::class, 'submitForm'])->name('problems.submit');

            
        });
        // Rute za notifikacije
        Route::get('/notifications', [NotificationsController::class, 'getNotifications'])->name('notifications.getNotifications');
        Route::post('/notifications/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');


        Route::get('/logout',[DashboardController::class, 'logout'])->name('admin.logout');

        //RUTE ZA SUPER ADMINISTRATORA
        Route::group(['middleware' => 'role:1'], function() {
            Route::get('/store/edit', [StoreSettingsController::class, 'edit'])->name('store.edit');
            Route::post('/store/update', [StoreSettingsController::class, 'update'])->name('store.update');

            //Admin Users Settings
            Route::get('/users', [AccountController::class, 'index'])->name('admin.users');
            Route::post('/users/{user}', [AccountController::class, 'adminUpdate'])->name('admin.users.update');
            Route::post('/users', [AccountController::class, 'store'])->name('admin.users.store');
            Route::delete('/users/{user}',[AccountController::class, 'destroy'])->name('admin.users.delete');

            

            //Clear cache
            Route::post('/optimize',[DashboardController::class, 'optimize'])->name('optimize.clear');
        });
    });

});
