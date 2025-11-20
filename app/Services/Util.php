<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class Util {

    public static function setupHeader($header, $metas) {
        foreach ($metas ? unserialize($metas) : [] as $meta) {
            if ($meta['name'] == 'canonical') {
                $header .= '<link rel="canonical" href="' . $meta['content'] . '" />';
            } else if (str_contains($meta['name'], 'og:')) {
                $header .= '<meta property="' . $meta['name'] . '" content="' . $meta['content'] . '" />';
            } else {
                $header .= '<meta name="' . $meta['name'] . '" content="' . $meta['content'] . '" />';
            }
        }
        return $header;
    }

    public static function showPaymentMethod($method, $currency = null) {
        $coinpayments_currency = ["usd", "cad", "eur", "kyd", "aed", "aoa", "ars", "aud", "azn", "bgn", "brl", "byn", "chf", "clp", "cny", "cop", "cuc", "czk", "dkk", "gbp", "gel", "gip", "hkd", "hrk", "huf", "idr", "ils", "inr", "irr", "irt", "isk", "jpy", "kes", "krw", "lak", "mkd", "mur", "mxn", "myr", "ngn", "nok", "nzd", "pen", "php", "pkr", "pln", "ron", "rub", "rwf", "sek", "sgd", "thb", "tnd", "try", "twd", "uah", "vnd", "xag", "xau", "xof", "zar"];
        $paystack_currency = ['usd', 'ngn', 'ghs', 'zar'];
        if ($currency == null) {
            $currency = strtolower(config('app.settings.currency.code'));
        }
        if ($method == 'paypal' && $currency == 'inr') {
            return false;
        }
        if ($method == 'razorpay' && $currency != 'inr') {
            return false;
        }
        if ($method == 'coinpayments' && !in_array($currency, $coinpayments_currency)) {
            return false;
        }
        if ($method == 'paystack' && !in_array($currency, $paystack_currency)) {
            return false;
        }
        // Paynow is primarily for Zimbabwe (ZWL currency)
        if ($method == 'paynow' && $currency != 'zwl') {
            // Allow paynow for other currencies too, but you can restrict it here if needed
            // return false;
        }
        return true;
    }

    public static $country = [
        "AD" => "Andorra",
        "AE" => "United Arab Emirates",
        "AF" => "Afghanistan",
        "AG" => "Antigua and Barbuda",
        "AI" => "Anguilla",
        "AL" => "Albania",
        "AM" => "Armenia",
        "AN" => "Netherlands Antilles",
        "AO" => "Angola",
        "AQ" => "Antarctica",
        "AR" => "Argentina",
        "AS" => "American Samoa",
        "AT" => "Austria",
        "AU" => "Australia",
        "AW" => "Aruba",
        "AX" => "Åland Islands",
        "AZ" => "Azerbaijan",
        "BA" => "Bosnia and Herzegovina",
        "BB" => "Barbados",
        "BD" => "Bangladesh",
        "BE" => "Belgium",
        "BF" => "Burkina Faso",
        "BG" => "Bulgaria",
        "BH" => "Bahrain",
        "BI" => "Burundi",
        "BJ" => "Benin",
        "BL" => "Saint Barthélemy",
        "BM" => "Bermuda",
        "BN" => "Brunei Darussalam",
        "BO" => "Bolivia",
        "BQ" => "Bonaire, Sint Eustatius and Saba",
        "BR" => "Brazil",
        "BS" => "Bahamas",
        "BT" => "Bhutan",
        "BV" => "Bouvet Island",
        "BW" => "Botswana",
        "BY" => "Belarus",
        "BZ" => "Belize",
        "CA" => "Canada",
        "CC" => "Cocos (Keeling) Islands",
        "CD" => "Congo, The Democratic Republic Of The",
        "CF" => "Central African Republic",
        "CG" => "Congo",
        "CH" => "Switzerland",
        "CI" => "Côte D'Ivoire",
        "CK" => "Cook Islands",
        "CL" => "Chile",
        "CM" => "Cameroon",
        "CN" => "China",
        "CO" => "Colombia",
        "CR" => "Costa Rica",
        "CU" => "Cuba",
        "CV" => "Cape Verde",
        "CW" => "Curaçao",
        "CX" => "Christmas Island",
        "CY" => "Cyprus",
        "CZ" => "Czech Republic",
        "DE" => "Germany",
        "DJ" => "Djibouti",
        "DK" => "Denmark",
        "DM" => "Dominica",
        "DO" => "Dominican Republic",
        "DZ" => "Algeria",
        "EC" => "Ecuador",
        "EE" => "Estonia",
        "EG" => "Egypt",
        "EH" => "Western Sahara",
        "ER" => "Eritrea",
        "ES" => "Spain",
        "ET" => "Ethiopia",
        "FI" => "Finland",
        "FJ" => "Fiji",
        "FK" => "Falkland Islands (Malvinas)",
        "FM" => "Micronesia, Federated States Of",
        "FO" => "Faroe Islands",
        "FR" => "France",
        "GA" => "Gabon",
        "GB" => "United Kingdom",
        "GD" => "Grenada",
        "GE" => "Georgia",
        "GF" => "French Guiana",
        "GG" => "Guernsey",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GL" => "Greenland",
        "GM" => "Gambia",
        "GN" => "Guinea",
        "GP" => "Guadeloupe",
        "GQ" => "Equatorial Guinea",
        "GR" => "Greece",
        "GS" => "South Georgia and the South Sandwich Islands",
        "GT" => "Guatemala",
        "GU" => "Guam",
        "GW" => "Guinea-Bissau",
        "GY" => "Guyana",
        "HK" => "Hong Kong",
        "HM" => "Heard and McDonald Islands",
        "HN" => "Honduras",
        "HR" => "Croatia",
        "HT" => "Haiti",
        "HU" => "Hungary",
        "ID" => "Indonesia",
        "IE" => "Ireland",
        "IL" => "Israel",
        "IM" => "Isle of Man",
        "IN" => "India",
        "IO" => "British Indian Ocean Territory",
        "IQ" => "Iraq",
        "IR" => "Iran, Islamic Republic Of",
        "IS" => "Iceland",
        "IT" => "Italy",
        "JE" => "Jersey",
        "JM" => "Jamaica",
        "JO" => "Jordan",
        "JP" => "Japan",
        "KE" => "Kenya",
        "KG" => "Kyrgyzstan",
        "KH" => "Cambodia",
        "KI" => "Kiribati",
        "KM" => "Comoros",
        "KN" => "Saint Kitts And Nevis",
        "KP" => "Korea, Democratic People's Republic Of",
        "KR" => "Korea, Republic of",
        "KW" => "Kuwait",
        "KY" => "Cayman Islands",
        "KZ" => "Kazakhstan",
        "LA" => "Lao People's Democratic Republic",
        "LB" => "Lebanon",
        "LC" => "Saint Lucia",
        "LI" => "Liechtenstein",
        "LK" => "Sri Lanka",
        "LR" => "Liberia",
        "LS" => "Lesotho",
        "LT" => "Lithuania",
        "LU" => "Luxembourg",
        "LV" => "Latvia",
        "LY" => "Libya",
        "MA" => "Morocco",
        "MC" => "Monaco",
        "MD" => "Moldova, Republic of",
        "ME" => "Montenegro",
        "MF" => "Saint Martin",
        "MG" => "Madagascar",
        "MH" => "Marshall Islands",
        "MK" => "Macedonia, the Former Yugoslav Republic Of",
        "ML" => "Mali",
        "MM" => "Myanmar",
        "MN" => "Mongolia",
        "MO" => "Macao",
        "MP" => "Northern Mariana Islands",
        "MQ" => "Martinique",
        "MR" => "Mauritania",
        "MS" => "Montserrat",
        "MT" => "Malta",
        "MU" => "Mauritius",
        "MV" => "Maldives",
        "MW" => "Malawi",
        "MX" => "Mexico",
        "MY" => "Malaysia",
        "MZ" => "Mozambique",
        "NA" => "Namibia",
        "NC" => "New Caledonia",
        "NE" => "Niger",
        "NF" => "Norfolk Island",
        "NG" => "Nigeria",
        "NI" => "Nicaragua",
        "NL" => "Netherlands",
        "NO" => "Norway",
        "NP" => "Nepal",
        "NR" => "Nauru",
        "NU" => "Niue",
        "NZ" => "New Zealand",
        "OM" => "Oman",
        "PA" => "Panama",
        "PE" => "Peru",
        "PF" => "French Polynesia",
        "PG" => "Papua New Guinea",
        "PH" => "Philippines",
        "PK" => "Pakistan",
        "PL" => "Poland",
        "PM" => "Saint Pierre And Miquelon",
        "PN" => "Pitcairn",
        "PR" => "Puerto Rico",
        "PS" => "Palestine, State of",
        "PT" => "Portugal",
        "PW" => "Palau",
        "PY" => "Paraguay",
        "QA" => "Qatar",
        "RE" => "Réunion",
        "RO" => "Romania",
        "RS" => "Serbia",
        "RU" => "Russian Federation",
        "RW" => "Rwanda",
        "SA" => "Saudi Arabia",
        "SB" => "Solomon Islands",
        "SC" => "Seychelles",
        "SD" => "Sudan",
        "SE" => "Sweden",
        "SG" => "Singapore",
        "SH" => "Saint Helena",
        "SI" => "Slovenia",
        "SJ" => "Svalbard And Jan Mayen",
        "SK" => "Slovakia",
        "SL" => "Sierra Leone",
        "SM" => "San Marino",
        "SN" => "Senegal",
        "SO" => "Somalia",
        "SR" => "Suriname",
        "SS" => "South Sudan",
        "ST" => "Sao Tome and Principe",
        "SV" => "El Salvador",
        "SX" => "Sint Maarten",
        "SY" => "Syrian Arab Republic",
        "SZ" => "Swaziland",
        "TC" => "Turks and Caicos Islands",
        "TD" => "Chad",
        "TF" => "French Southern Territories",
        "TG" => "Togo",
        "TH" => "Thailand",
        "TJ" => "Tajikistan",
        "TK" => "Tokelau",
        "TL" => "Timor-Leste",
        "TM" => "Turkmenistan",
        "TN" => "Tunisia",
        "TO" => "Tonga",
        "TR" => "Turkey",
        "TT" => "Trinidad and Tobago",
        "TV" => "Tuvalu",
        "TW" => "Taiwan, Republic Of China",
        "TZ" => "Tanzania, United Republic of",
        "UA" => "Ukraine",
        "UG" => "Uganda",
        "UM" => "United States Minor Outlying Islands",
        "US" => "United States",
        "UY" => "Uruguay",
        "UZ" => "Uzbekistan",
        "VA" => "Holy See (Vatican City State)",
        "VC" => "Saint Vincent And The Grenadines",
        "VE" => "Venezuela, Bolivarian Republic of",
        "VG" => "Virgin Islands, British",
        "VI" => "Virgin Islands, U.S.",
        "VN" => "Vietnam",
        "VU" => "Vanuatu",
        "WF" => "Wallis and Futuna",
        "WS" => "Samoa",
        "YE" => "Yemen",
        "YT" => "Mayotte",
        "ZA" => "South Africa",
        "ZM" => "Zambia",
        "ZW" => "Zimbabwe",
    ];

    public static function getCountryNameFromCode($code) {
        return isset(Util::$country[$code]) ? Util::$country[$code] : 'NA';
    }

    public static function translateModelAttribute($fields, $table, $name, $value) {
        $item = null;
        if (in_array($table, ['menus'])) {
            if ($fields->translations) {
                $translations = unserialize($fields->translations);
                if (isset($translations[session('locale')]) && $translations[session('locale')]) {
                    return $translations[session('locale')];
                }
            }
        } else if (in_array($table, ['pages', 'sections'])) {
            $item = DB::table($table)->where('slug', $fields->slug)->where('lang', session('locale'))->first();
        } else if (in_array($table, ['features'])) {
            $item = DB::table($table)->where('translate_parent_id', $fields->id)->where('lang', session('locale'))->first();
        }
        if ($item) {
            return $item->{$name};
        }
        return $value;
    }
}
