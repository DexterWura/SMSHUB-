<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $settings = new \stdClass;
        $settings->name = 'tSMS';
        $settings->logo = 'images/logo.png';
        $settings->favicon = 'images/favicon.png';
        $settings->version = '2.5';
        $settings->license_key = '';
        $settings->theme = 'default';
        $settings->show_country_list = true;
        $settings->numbers_per_page = -1;
        $settings->messages_per_page = env('MSG_PER_PAGE', 15);
        $settings->widget_allowed_domains = '';
        $settings->blocked_keywords = env('BLOCKED_KEYWORDS', '');
        $settings->cron_password = str_shuffle('6789abcdefghijklmnopqrstuvwxy');
        $settings->auth_key = str_shuffle('6789abcdefghijklmnopqrstuvwxy');
        $settings->keywords = [
            'to' => 'to',
            'from' => 'from',
            'msg' => 'msg',
            'uuid' => 'uuid',
        ];
        $settings->ads = [
            'one' => '',
            'two' => '',
            'three' => '',
            'four' => '',
            'five' => '',
            'six' => '',
        ];
        $settings->socials = [];
        $settings->colors = [
            'primary' => env('PRIMARY_COLOR', '#233d4d'),
            'secondary' => env('SECONDARY_COLOR', '#c1dc78'),
            'tertiary' => '#d2ab3e'
        ];
        $settings->homepage = [
            'header' => '',
            'footer' => '',
        ];
        $settings->global = [
            'css' => '',
            'js' => '',
            'header' => '',
            'footer' => ''
        ];
        $settings->cookie = [
            'enable' => true,
            'text' => '<p>By using this website you agree to our <a href="#" target="_blank">Cookie Policy</a></p>'
        ];
        $settings->font_family = [
            'head' => 'Kadwa',
            'body' => 'Poppins',
        ];
        $settings->currency = [
            'symbol' => '₹',
            'code' => 'inr'
        ];
        $settings->language = 'en';
        $settings->captcha = 'off'; //Options - off|recaptcha2|recaptcha3|hcaptcha
        $settings->recaptcha2 = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->recaptcha3 = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->hcaptcha = [
            'site_key' => '',
            'secret_key' => ''
        ];
        $settings->min_recharge = 5;

        foreach ($settings as $key => $value) {
            if (!Setting::where('key', $key)->exists()) {
                Setting::create([
                    'key' => $key,
                    'value' => serialize($value)
                ]);
            }
        }

        //START To be removed in v3.0
        $font_family = Setting::pick('font_family');
        if (!is_array($font_family)) {
            Setting::put('font_family', [
                'head' => $font_family,
                'body' => $font_family,
            ]);
        }
        //END To be removed in v3.0
    }
}
