<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\Number;
use App\Models\Order;
use App\Models\Page;
use App\Models\PaymentMethod;
use App\Services\Util;
use App\Models\WalletTransaction;
use App\Services\CoinPayments;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class AppController extends Controller {

    /**
     * Function for MAIN App View
     * 
     * @since 2.0.0
     */

    public function home() {
        if (file_exists(public_path('themes')) !== TRUE) {
            symlink(base_path('resources/views/themes'), public_path('themes'));
        }
        return view('themes.' . config('app.settings.theme') . '.index');
    }

    /**
     * Function for Number View
     * 
     * @since 2.0.0
     */
    public function number($n) {
        $number = Number::where('number', $n)->where('status', 1)->first();
        if ($number) {
            if ($number->type == 1 && !Auth::check()) {
                Session::flash('error', 'You must be registered user to view this number');
                return redirect()->route('home');
            }
            if ($number->type == 3 || $number->type == 4) {
                if ($number->hasUserOrdered(Auth::id()) == false) {
                    Session::flash('error', 'You must purchase this number to view messages');
                    return redirect()->route('home');
                }
            }
            return view('themes.' . config('app.settings.theme') . '.index')->with(compact('number'));
        }
        Session::flash('error', 'Number you\'re trying to access, does not exist. Please try using a different number.');
        return redirect()->route('home');
    }

    /**
     * Function for Pages View
     * 
     * @since 2.0.0
     */

    public function page($slug = '', $inner = '') {
        if ($inner) {
            $parent = Page::select('id')->where('slug', $slug)->first();
            if ($parent) {
                $parent_id = $parent->id;
            } else {
                return abort(404);
            }
        }
        $page = Page::where('slug', ($inner) ? $inner : $slug)->where('parent_id', isset($parent_id) ? $parent_id : null)->first();
        if ($page) {
            $page->header = Util::setupHeader($page->header, $page->meta);
            return view('themes.' . config('app.settings.theme') . '.index')->with(compact('page'));
        }
        return abort(404);
    }

    /**
     * Function for Locale
     * 
     * @since 2.0.0
     */
    public function locale($locale) {
        if (in_array($locale, config('app.locales'))) {
            session(['locale' => $locale]);
            return redirect()->back();
        }
        abort(400);
    }

    /**
     * Cron
     * 
     * @since 2.0.0
     */
    public function cron($password) {
        if ($password == config('app.settings.cron_password')) {
            $transactions = WalletTransaction::where('status', 0)->where('created_at', '<', Carbon::now()->subDay())->get();
            foreach ($transactions as $transaction) {
                $transaction->status = 2;
                $transaction->save();
            }
            //Renew Orders if Auto Renewal
            $orders = Order::where('auto_renew', true)->where('expiry', '<', Carbon::now())->get();
            foreach ($orders as $order) {
                $order->renew();
                $order->auto_renew = false;
                $order->save();
            }
            //Update CoinPayments Metadata
            if (PaymentMethod::where('name', 'coinpayments')->where('is_active', true)->count() > 0) {
                CoinPayments::updateCoins();
            }
            Storage::put('cron_log', Carbon::now());
        } else {
            abort(401);
        }
    }

    /**
     * Receive and Store Messages
     * 
     * @since 2.0.0
     */
    public function receive($auth_key = null, Request $request) {
        if ($auth_key == config('app.settings.auth_key')) {
            $to = $request->input(config('app.settings.keywords.to'));
            $from = $request->input(config('app.settings.keywords.from'));
            $msg = $request->input(config('app.settings.keywords.msg'));
            $uuid = $request->input(config('app.settings.keywords.uuid'));
            $message = new Message;
            $message->to = $to;
            $message->from = $from;
            $message->msg = $msg;
            $message->uuid = $uuid;
            $message->save();
            return "OK";
        } else {
            return abort(403);
        }
    }

    /**
     * Sitemap Generator
     * 
     * @since 2.4.0
     */
    public function sitemap() {
        $numbers = Number::select('number')->get();
        $pages = Page::select('id', 'parent_id', 'slug', 'updated_at')->where('lang', null)->get();
        $contents = view('common.sitemap')->with('numbers', $numbers)->with('pages', $pages);
        return response($contents)->header('Content-Type', 'application/xml');
    }

    /**
     * Recieve and Store Messages Depreciated
     * 
     * @since 1.0.0
     */
    public function receiveDepreciated(Request $request) {
        if ($request->input(env('API_KEYWORD_KEY', 'key')) == env('API_KEY')) {
            $number = $request->input(env('API_KEYWORD_NUMBER', 'number'));
            $sender = $request->input(env('API_KEYWORD_SENDER', 'sender'));
            $msg = $request->input(env('API_KEYWORD_MSG', 'msg'));
            $msg_id = $request->input(env('API_KEYWORD_MSG_ID', 'msg_id'));
            $message = new Message;
            $message->to = $number;
            $message->from = $sender;
            $message->msg = $msg;
            $message->uuid = $msg_id;
            $message->save();
            return "OK";
        } else {
            return abort(403);
        }
    }
}
