<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class FetchSettings {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next) {
        if (file_exists(storage_path('installed'))) {
            $settings = Schema::hasTable('settings') ? Setting::get() : [];
            foreach ($settings as $setting) {
                config([
                    'app.settings.' . $setting->key => unserialize($setting->value)
                ]);
            }
        }
        return $next($request);
    }
}
