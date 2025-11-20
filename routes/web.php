<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\PaymentController;
use App\Models\Message;
use App\Models\Number;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Installer Routes
Route::get('/installer', function () {
    if (file_exists(storage_path('installed'))) {
        return redirect()->route('home');
    } else {
        return view('installer.index');
    }
})->name('installer');

Route::middleware(['verify.install'])->group(function () {
    Route::get('/', [AppController::class, 'home'])->name('home');
    Route::get('/number/{number}', [AppController::class, 'number'])->name('number');

    /**
     * Locale Route
     */
    Route::post('locale/{locale}', [AppController::class, 'locale'])->name('locale');

    /**
     * User Routes
     */
    Route::middleware(['auth:sanctum', 'verified'])->group(function () {
        Route::get('order', function () {
            return view('themes.' . config('app.settings.theme') . '.order');
        })->name('order');
        Route::get('wallet', function () {
            return view('themes.' . config('app.settings.theme') . '.wallet');
        })->name('wallet');
        Route::get('wallet/process/{id}/{status}', [PaymentController::class, 'process'])->name('wallet.process');
        Route::post('wallet/process/{id}/{status}', [PaymentController::class, 'process'])->name('wallet.process');
        /**
         * PayPal Routes
         */
        Route::get('wallet/paypal', [PaymentController::class, 'initiatePayPal'])->name('wallet.initiate.paypal');
    });

    /**
     * Admin Routes
     */
    Route::middleware(['auth:sanctum', 'verified', 'role:admin'])->prefix('admin')->group(function () {
        Route::get('/', function () {
            return redirect()->route('dashboard');
        })->name('admin');
        Route::get('dashboard', function () {
            $stats = new stdClass;
            $stats->total_numbers = Number::count();
            $stats->total_messages = Message::count();
            $stats->total_users = User::count();
            $stats->total_orders = Order::count();
            return view('backend.dashboard')->with(compact('stats'));
        })->name('dashboard');
        Route::get('numbers', function () {
            return view('backend.numbers.index');
        })->name('numbers');
        Route::get('numbers/number/{id}/map', function ($id) {
            $number = \App\Models\Number::findOrFail($id);
            return view('backend.numbers.number.index')->with(compact('number'));
        })->name('numbers.number.map');
        Route::get('settings', function () {
            return view('backend.settings.index');
        })->name('settings');
        Route::get('orders', function () {
            $orders = Order::with(['user', 'number', 'plan'])->orderBy('created_at', 'desc')->paginate(15);
            return view('backend.orders.index')->with(compact('orders'));
        })->name('orders');
        Route::get('plans', function () {
            return view('backend.plans.index');
        })->name('plans');
        Route::get('plans.plan/{id}/map', function ($id) {
            $plan = \App\Models\Plan::findOrFail($id);
            return view('backend.plans.plan.index')->with(compact('plan'));
        })->name('plans.plan.map');
        Route::get('menu', function () {
            return view('backend.menu.index');
        })->name('menu');
        Route::get('pages', function () {
            return view('backend.pages.index');
        })->name('pages');
        Route::get('sections', function () {
            return view('backend.sections.index');
        })->name('sections');
        Route::get('features', function () {
            return view('backend.features.index');
        })->name('features');
        Route::get('users', function () {
            return view('backend.users.index');
        })->name('users');
        Route::get('update', function () {
            return view('backend.update.index');
        })->name('update');
        Route::get('setup', function () {
            return view('backend.setup');
        })->name('setup');
    });

    Route::get('sitemap.xml', [AppController::class, 'sitemap']);

    // Paynow callback (no auth required - server-to-server)
    Route::post('wallet/paynow/callback', [PaymentController::class, 'paynowCallback'])->name('wallet.paynow.callback');

    //Page Routes
    Route::get('{slug}/{inner?}', [AppController::class, 'page'])->name('page');
});
