<?php

use App\Models\Offer_product_list;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

function getUploadPath()
{
    return public_path('storage');
}

function userCanAccess($access_id)
{
    $access_list = Auth::guard('admin')->user()->role->access_role_list;
    $accessListArray = explode(",", $access_list);

    return $result = in_array($access_id, $accessListArray);
}


function calculateDiscount($item)
{
    $product = Product::find($item['id']);
    $currentDate = strtotime(Carbon::now()->format('Y-m-d'));
    $discount = 0;

    if ($product->discount > 0) {
        if ($product->discount_type == 0) {
            $discount = $product->discount;
        }
        if ($product->discount_type == 1) {
            $discount = ($product->discount * $product->current_sale_price) / 100;
        }
    }

    if ($item['offerId'] > 0) {
        $offerInfo = Offer_product_list::with('offerInfo')->where('product_id', $item['id'])->where('offer_id', $item['offerId'])->first();
        $startDate = strtotime($offerInfo['offerInfo']->start_date);
        $endDate = strtotime($offerInfo['offerInfo']->end_date);

        if ($startDate <= $currentDate && $endDate >= $currentDate) {
            if ($offerInfo->offer_type == 0) {
                $discount = $offerInfo->offer_amount;
            }
            if ($offerInfo->offer_type == 1) {
                $discount = ($offerInfo->offer_amount * $product->current_sale_price) / 100;
            }
        }
    }
    return $discount;
}

function calculateOrder($order_items)
{
    $total_payable = 0;
    $discountTotal = 0;
    foreach ($order_items as $item) {
        $product = Product::find($item['id']);
        $discount = calculateDiscount($item);
        $unitPrice = $product->current_sale_price - $discount;
        $quantity = $item['quantity'];
        $total_price = $quantity * $unitPrice;
        $total_payable += $total_price;
        $discountTotal += $discount * $quantity;
    }
    return [$total_payable, $discountTotal];
}

function countryListData()
{

    $countryList = array(
        "AF" => array("name" => "Afghanistan", "symbol" => "؋"),
        "AX" => array("name" => "Aland Islands", "symbol" => "€"),
        "AL" => array("name" => "Albania", "symbol" => "Lek"),
        "DZ" => array("name" => "Algeria", "symbol" => "دج"),
        "AS" => array("name" => "American Samoa", "symbol" => "$"),
        "AD" => array("name" => "Andorra", "symbol" => "€"),
        "AO" => array("name" => "Angola", "symbol" => "Kz"),
        "AI" => array("name" => "Anguilla", "symbol" => "$"),
        "AQ" => array("name" => "Antarctica", "symbol" => "$"),
        "AG" => array("name" => "Antigua and Barbuda", "symbol" => "$"),
        "AR" => array("name" => "Argentina", "symbol" => "$"),
        "AM" => array("name" => "Armenia", "symbol" => "֏"),
        "AW" => array("name" => "Aruba", "symbol" => "ƒ"),
        "AU" => array("name" => "Australia", "symbol" => "$"),
        "AT" => array("name" => "Austria", "symbol" => "€"),
        "AZ" => array("name" => "Azerbaijan", "symbol" => "m"),
        "BS" => array("name" => "Bahamas", "symbol" => "B$"),
        "BH" => array("name" => "Bahrain", "symbol" => ".د.ب"),
        "BD" => array("name" => "Bangladesh", "symbol" => "৳"),
        "BB" => array("name" => "Barbados", "symbol" => "Bds$"),
        "BY" => array("name" => "Belarus", "symbol" => "Br"),
        "BE" => array("name" => "Belgium", "symbol" => "€"),
        "BZ" => array("name" => "Belize", "symbol" => "$"),
        "BJ" => array("name" => "Benin", "symbol" => "CFA"),
        "BM" => array("name" => "Bermuda", "symbol" => "$"),
        "BT" => array("name" => "Bhutan", "symbol" => "Nu."),
        "BO" => array("name" => "Bolivia", "symbol" => "Bs."),
        "BQ" => array("name" => "Bonaire, Sint Eustatius and Saba", "symbol" => "$"),
        "BA" => array("name" => "Bosnia and Herzegovina", "symbol" => "KM"),
        "BW" => array("name" => "Botswana", "symbol" => "P"),
        "BV" => array("name" => "Bouvet Island", "symbol" => "kr"),
        "BR" => array("name" => "Brazil", "symbol" => "R$"),
        "IO" => array("name" => "British Indian Ocean Territory", "symbol" => "$"),
        "BN" => array("name" => "Brunei Darussalam", "symbol" => "B$"),
        "BG" => array("name" => "Bulgaria", "symbol" => "Лв."),
        "BF" => array("name" => "Burkina Faso", "symbol" => "CFA"),
        "BI" => array("name" => "Burundi", "symbol" => "FBu"),
        "KH" => array("name" => "Cambodia", "symbol" => "KHR"),
        "CM" => array("name" => "Cameroon", "symbol" => "FCFA"),
        "CA" => array("name" => "Canada", "symbol" => "$"),
        "CV" => array("name" => "Cape Verde", "symbol" => "$"),
        "KY" => array("name" => "Cayman Islands", "symbol" => "$"),
        "CF" => array("name" => "Central African Republic", "symbol" => "FCFA"),
        "TD" => array("name" => "Chad", "symbol" => "FCFA"),
        "CL" => array("name" => "Chile", "symbol" => "$"),
        "CN" => array("name" => "China", "symbol" => "¥"),
        "CX" => array("name" => "Christmas Island", "symbol" => "$"),
        "CC" => array("name" => "Cocos (Keeling) Islands", "symbol" => "$"),
        "CO" => array("name" => "Colombia", "symbol" => "$"),
        "KM" => array("name" => "Comoros", "symbol" => "CF"),
        "CG" => array("name" => "Congo", "symbol" => "FC"),
        "CD" => array("name" => "Congo, Democratic Republic of the Congo", "symbol" => "FC"),
        "CK" => array("name" => "Cook Islands", "symbol" => "$"),
        "CR" => array("name" => "Costa Rica", "symbol" => "₡"),
        "CI" => array("name" => "Cote D'Ivoire", "symbol" => "CFA"),
        "HR" => array("name" => "Croatia", "symbol" => "kn"),
        "CU" => array("name" => "Cuba", "symbol" => "$"),
        "CW" => array("name" => "Curacao", "symbol" => "ƒ"),
        "CY" => array("name" => "Cyprus", "symbol" => "€"),
        "CZ" => array("name" => "Czech Republic", "symbol" => "Kč"),
        "DK" => array("name" => "Denmark", "symbol" => "Kr."),
        "DJ" => array("name" => "Djibouti", "symbol" => "Fdj"),
        "DM" => array("name" => "Dominica", "symbol" => "$"),
        "DO" => array("name" => "Dominican Republic", "symbol" => "$"),
        "EC" => array("name" => "Ecuador", "symbol" => "$"),
        "EG" => array("name" => "Egypt", "symbol" => "ج.م"),
        "SV" => array("name" => "El Salvador", "symbol" => "$"),
        "GQ" => array("name" => "Equatorial Guinea", "symbol" => "FCFA"),
        "ER" => array("name" => "Eritrea", "symbol" => "Nfk"),
        "EE" => array("name" => "Estonia", "symbol" => "€"),
        "ET" => array("name" => "Ethiopia", "symbol" => "Nkf"),
        "FK" => array("name" => "Falkland Islands (Malvinas)", "symbol" => "£"),
        "FO" => array("name" => "Faroe Islands", "symbol" => "Kr."),
        "FJ" => array("name" => "Fiji", "symbol" => "FJ$"),
        "FI" => array("name" => "Finland", "symbol" => "€"),
        "FR" => array("name" => "France", "symbol" => "€"),
        "GF" => array("name" => "French Guiana", "symbol" => "€"),
        "PF" => array("name" => "French Polynesia", "symbol" => "₣"),
        "TF" => array("name" => "French Southern Territories", "symbol" => "€"),
        "GA" => array("name" => "Gabon", "symbol" => "FCFA"),
        "GM" => array("name" => "Gambia", "symbol" => "D"),
        "GE" => array("name" => "Georgia", "symbol" => "ლ"),
        "DE" => array("name" => "Germany", "symbol" => "€"),
        "GH" => array("name" => "Ghana", "symbol" => "GH₵"),
        "GI" => array("name" => "Gibraltar", "symbol" => "£"),
        "GR" => array("name" => "Greece", "symbol" => "€"),
        "GL" => array("name" => "Greenland", "symbol" => "Kr."),
        "GD" => array("name" => "Grenada", "symbol" => "$"),
        "GP" => array("name" => "Guadeloupe", "symbol" => "€"),
        "GU" => array("name" => "Guam", "symbol" => "$"),
        "GT" => array("name" => "Guatemala", "symbol" => "Q"),
        "GG" => array("name" => "Guernsey", "symbol" => "£"),
        "GN" => array("name" => "Guinea", "symbol" => "FG"),
        "GW" => array("name" => "Guinea-Bissau", "symbol" => "CFA"),
        "GY" => array("name" => "Guyana", "symbol" => "$"),
        "HT" => array("name" => "Haiti", "symbol" => "G"),
        "HM" => array("name" => "Heard Island and McDonald Islands", "symbol" => "$"),
        "VA" => array("name" => "Holy See (Vatican City State)", "symbol" => "€"),
        "HN" => array("name" => "Honduras", "symbol" => "L"),
        "HK" => array("name" => "Hong Kong", "symbol" => "$"),
        "HU" => array("name" => "Hungary", "symbol" => "Ft"),
        "IS" => array("name" => "Iceland", "symbol" => "kr"),
        "IN" => array("name" => "India", "symbol" => "₹"),
        "ID" => array("name" => "Indonesia", "symbol" => "Rp"),
        "IR" => array("name" => "Iran, Islamic Republic of", "symbol" => "﷼"),
        "IQ" => array("name" => "Iraq", "symbol" => "د.ع"),
        "IE" => array("name" => "Ireland", "symbol" => "€"),
        "IM" => array("name" => "Isle of Man", "symbol" => "£"),
        "IL" => array("name" => "Israel", "symbol" => "₪"),
        "IT" => array("name" => "Italy", "symbol" => "€"),
        "JM" => array("name" => "Jamaica", "symbol" => "J$"),
        "JP" => array("name" => "Japan", "symbol" => "¥"),
        "JE" => array("name" => "Jersey", "symbol" => "£"),
        "JO" => array("name" => "Jordan", "symbol" => "ا.د"),
        "KZ" => array("name" => "Kazakhstan", "symbol" => "лв"),
        "KE" => array("name" => "Kenya", "symbol" => "KSh"),
        "KI" => array("name" => "Kiribati", "symbol" => "$"),
        "KP" => array("name" => "Korea, Democratic People's Republic of", "symbol" => "₩"),
        "KR" => array("name" => "Korea, Republic of", "symbol" => "₩"),
        "XK" => array("name" => "Kosovo", "symbol" => "€"),
        "KW" => array("name" => "Kuwait", "symbol" => "ك.د"),
        "KG" => array("name" => "Kyrgyzstan", "symbol" => "лв"),
        "LA" => array("name" => "Lao People's Democratic Republic", "symbol" => "₭"),
        "LV" => array("name" => "Latvia", "symbol" => "€"),
        "LB" => array("name" => "Lebanon", "symbol" => "£"),
        "LS" => array("name" => "Lesotho", "symbol" => "L"),
        "LR" => array("name" => "Liberia", "symbol" => "$"),
        "LY" => array("name" => "Libyan Arab Jamahiriya", "symbol" => "د.ل"),
        "LI" => array("name" => "Liechtenstein", "symbol" => "CHf"),
        "LT" => array("name" => "Lithuania", "symbol" => "€"),
        "LU" => array("name" => "Luxembourg", "symbol" => "€"),
        "MO" => array("name" => "Macao", "symbol" => "$"),
        "MK" => array("name" => "Macedonia, the Former Yugoslav Republic of", "symbol" => "ден"),
        "MG" => array("name" => "Madagascar", "symbol" => "Ar"),
        "MW" => array("name" => "Malawi", "symbol" => "MK"),
        "MY" => array("name" => "Malaysia", "symbol" => "RM"),
        "MV" => array("name" => "Maldives", "symbol" => "Rf"),
        "ML" => array("name" => "Mali", "symbol" => "CFA"),
        "MT" => array("name" => "Malta", "symbol" => "€"),
        "MH" => array("name" => "Marshall Islands", "symbol" => "$"),
        "MQ" => array("name" => "Martinique", "symbol" => "€"),
        "MR" => array("name" => "Mauritania", "symbol" => "MRU"),
        "MU" => array("name" => "Mauritius", "symbol" => "₨"),
        "YT" => array("name" => "Mayotte", "symbol" => "€"),
        "MX" => array("name" => "Mexico", "symbol" => "$"),
        "FM" => array("name" => "Micronesia, Federated States of", "symbol" => "$"),
        "MD" => array("name" => "Moldova, Republic of", "symbol" => "L"),
        "MC" => array("name" => "Monaco", "symbol" => "€"),
        "MN" => array("name" => "Mongolia", "symbol" => "₮"),
        "ME" => array("name" => "Montenegro", "symbol" => "€"),
        "MS" => array("name" => "Montserrat", "symbol" => "$"),
        "MA" => array("name" => "Morocco", "symbol" => "DH"),
        "MZ" => array("name" => "Mozambique", "symbol" => "MT"),
        "MM" => array("name" => "Myanmar", "symbol" => "K"),
        "NA" => array("name" => "Namibia", "symbol" => "$"),
        "NR" => array("name" => "Nauru", "symbol" => "$"),
        "NP" => array("name" => "Nepal", "symbol" => "₨"),
        "NL" => array("name" => "Netherlands", "symbol" => "€"),
        "AN" => array("name" => "Netherlands Antilles", "symbol" => "NAf"),
        "NC" => array("name" => "New Caledonia", "symbol" => "₣"),
        "NZ" => array("name" => "New Zealand", "symbol" => "$"),
        "NI" => array("name" => "Nicaragua", "symbol" => "C$"),
        "NE" => array("name" => "Niger", "symbol" => "CFA"),
        "NG" => array("name" => "Nigeria", "symbol" => "₦"),
        "NU" => array("name" => "Niue", "symbol" => "$"),
        "NF" => array("name" => "Norfolk Island", "symbol" => "$"),
        "MP" => array("name" => "Northern Mariana Islands", "symbol" => "$"),
        "NO" => array("name" => "Norway", "symbol" => "kr"),
        "OM" => array("name" => "Oman", "symbol" => ".ع.ر"),
        "PK" => array("name" => "Pakistan", "symbol" => "₨"),
        "PW" => array("name" => "Palau", "symbol" => "$"),
        "PS" => array("name" => "Palestinian Territory, Occupied", "symbol" => "₪"),
        "PA" => array("name" => "Panama", "symbol" => "B/."),
        "PG" => array("name" => "Papua New Guinea", "symbol" => "K"),
        "PY" => array("name" => "Paraguay", "symbol" => "₲"),
        "PE" => array("name" => "Peru", "symbol" => "S/."),
        "PH" => array("name" => "Philippines", "symbol" => "₱"),
        "PN" => array("name" => "Pitcairn", "symbol" => "$"),
        "PL" => array("name" => "Poland", "symbol" => "zł"),
        "PT" => array("name" => "Portugal", "symbol" => "€"),
        "PR" => array("name" => "Puerto Rico", "symbol" => "$"),
        "QA" => array("name" => "Qatar", "symbol" => "ق.ر"),
        "RE" => array("name" => "Reunion", "symbol" => "€"),
        "RO" => array("name" => "Romania", "symbol" => "lei"),
        "RU" => array("name" => "Russian Federation", "symbol" => "₽"),
        "RW" => array("name" => "Rwanda", "symbol" => "FRw"),
        "BL" => array("name" => "Saint Barthelemy", "symbol" => "€"),
        "SH" => array("name" => "Saint Helena", "symbol" => "£"),
        "KN" => array("name" => "Saint Kitts and Nevis", "symbol" => "$"),
        "LC" => array("name" => "Saint Lucia", "symbol" => "$"),
        "MF" => array("name" => "Saint Martin", "symbol" => "€"),
        "PM" => array("name" => "Saint Pierre and Miquelon", "symbol" => "€"),
        "VC" => array("name" => "Saint Vincent and the Grenadines", "symbol" => "$"),
        "WS" => array("name" => "Samoa", "symbol" => "SAT"),
        "SM" => array("name" => "San Marino", "symbol" => "€"),
        "ST" => array("name" => "Sao Tome and Principe", "symbol" => "Db"),
        "SA" => array("name" => "Saudi Arabia", "symbol" => "﷼"),
        "SN" => array("name" => "Senegal", "symbol" => "CFA"),
        "RS" => array("name" => "Serbia", "symbol" => "din"),
        "CS" => array("name" => "Serbia and Montenegro", "symbol" => "din"),
        "SC" => array("name" => "Seychelles", "symbol" => "SRe"),
        "SL" => array("name" => "Sierra Leone", "symbol" => "Le"),
        "SG" => array("name" => "Singapore", "symbol" => "$"),
        "SX" => array("name" => "St Martin", "symbol" => "ƒ"),
        "SK" => array("name" => "Slovakia", "symbol" => "€"),
        "SI" => array("name" => "Slovenia", "symbol" => "€"),
        "SB" => array("name" => "Solomon Islands", "symbol" => "Si$"),
        "SO" => array("name" => "Somalia", "symbol" => "Sh.so."),
        "ZA" => array("name" => "South Africa", "symbol" => "R"),
        "GS" => array("name" => "South Georgia and the South Sandwich Islands", "symbol" => "£"),
        "SS" => array("name" => "South Sudan", "symbol" => "£"),
        "ES" => array("name" => "Spain", "symbol" => "€"),
        "LK" => array("name" => "Sri Lanka", "symbol" => "Rs"),
        "SD" => array("name" => "Sudan", "symbol" => ".س.ج"),
        "SR" => array("name" => "Suriname", "symbol" => "$"),
        "SJ" => array("name" => "Svalbard and Jan Mayen", "symbol" => "kr"),
        "SZ" => array("name" => "Swaziland", "symbol" => "E"),
        "SE" => array("name" => "Sweden", "symbol" => "kr"),
        "CH" => array("name" => "Switzerland", "symbol" => "CHf"),
        "SY" => array("name" => "Syrian Arab Republic", "symbol" => "LS"),
        "TW" => array("name" => "Taiwan, Province of China", "symbol" => "$"),
        "TJ" => array("name" => "Tajikistan", "symbol" => "SM"),
        "TZ" => array("name" => "Tanzania, United Republic of", "symbol" => "TSh"),
        "TH" => array("name" => "Thailand", "symbol" => "฿"),
        "TL" => array("name" => "Timor-Leste", "symbol" => "$"),
        "TG" => array("name" => "Togo", "symbol" => "CFA"),
        "TK" => array("name" => "Tokelau", "symbol" => "$"),
        "TO" => array("name" => "Tonga", "symbol" => "$"),
        "TT" => array("name" => "Trinidad and Tobago", "symbol" => "$"),
        "TN" => array("name" => "Tunisia", "symbol" => "ت.د"),
        "TR" => array("name" => "Turkey", "symbol" => "₺"),
        "TM" => array("name" => "Turkmenistan", "symbol" => "T"),
        "TC" => array("name" => "Turks and Caicos Islands", "symbol" => "$"),
        "TV" => array("name" => "Tuvalu", "symbol" => "$"),
        "UG" => array("name" => "Uganda", "symbol" => "USh"),
        "UA" => array("name" => "Ukraine", "symbol" => "₴"),
        "AE" => array("name" => "United Arab Emirates", "symbol" => "إ.د"),
        "GB" => array("name" => "United Kingdom", "symbol" => "£"),
        "US" => array("name" => "United States", "symbol" => "$"),
        "UM" => array("name" => "United States Minor Outlying Islands", "symbol" => "$"),
        "UY" => array("name" => "Uruguay", "symbol" => "$"),
        "UZ" => array("name" => "Uzbekistan", "symbol" => "лв"),
        "VU" => array("name" => "Vanuatu", "symbol" => "VT"),
        "VE" => array("name" => "Venezuela", "symbol" => "Bs"),
        "VN" => array("name" => "Viet Nam", "symbol" => "₫"),
        "VG" => array("name" => "Virgin Islands, British", "symbol" => "$"),
        "VI" => array("name" => "Virgin Islands, U.s.", "symbol" => "$"),
        "WF" => array("name" => "Wallis and Futuna", "symbol" => "₣"),
        "EH" => array("name" => "Western Sahara", "symbol" => "MAD"),
        "YE" => array("name" => "Yemen", "symbol" => "﷼"),
        "ZM" => array("name" => "Zambia", "symbol" => "ZK"),
        "ZW" => array("name" => "Zimbabwe", "symbol" => "$")
    );

    return $countryList;
}

if (!function_exists('countryList')) {
    function countryList()
    {
        return countryListData();
    }
}
