<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (Section::count() == 0) {
            DB::table('sections')->insert(['slug' => 'about', 'title' => 'Receive SMS Online for FREE', 'content' => 'We are a service that allows you to use our phone numbers for FREE to receive SMS online and anonymously. Recently, SMS spamming is increasing drastically, so to overcome that, we had created this portal which allows you use our phone numbers and keep your personal phone numbers private so that you don\'t be a victim of spamming. You are allowed to use as many phone numbers as you want and you can receive as many SMS as you want. Whenever you need a phone number for a website that requires a SMS/phone verification, our service is always available and can be used for such verification purposes.', 'created_at' => '2019-01-01 00:00:00', 'updated_at' => '2019-01-01 00:00:00']);
            DB::table('sections')->insert([
                'slug' => 'faq', 'title' => 'Frequently asked Questions', 'content' =>
                '<p><strong>I\'ve waited more than 15 min but no message arrived!</strong><br />Maybe the website where you entered our disposable phone number blocked the number you used for verification. Or sometimes there is delay with OTP if it has been used often. If you have problems receiving your verification code then try another number in our list.</p>
                <p><strong>How to use our service</strong><br />Select a phone number listed on the top of our website. Enter your chosen phone number in the app or website from where you want to get a SMS. Now wait until we receive your SMS.</p>
                <p><strong>How often do you add new phone numbers?</strong><br />We try to add new phone numbers weekly. But there is no regular period when we add new numbers.</p>
                <p><strong>How many temporary numbers do you have?</strong><br />The total amount of temporary phone numbers varies. Sometimes we have to delete old numbers and sometimes we add new to our database.</p>
                <p><strong>Do I need a phone number to use this website?</strong><br />No. The only thing you need is a web browser like Google Chrome, Mozilla Firefox, Opera or Microsoft Edge.</p>
                <p><strong>Is it neccessary to download a mobile app or a software to use your service?</strong><br />No you do not need any software or app. This is a web-based service that is accessible from everywhere.</p>
                <p><strong>Do you filter any received text messages?</strong><br />All messages that we receive were displayed without any filtering.</p>', 'created_at' => '2019-01-01 00:00:00', 'updated_at' => '2019-01-01 00:00:00'
            ]);
        }
    }
}
