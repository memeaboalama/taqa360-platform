<?php

namespace Database\Seeders\Location;

use App\Domain\AppBase\Models\Location\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('cities')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // country_id = 1: Saudi Arabia
        $cities = [
            [1, 'الرياض', 'Riyadh'],
            [1, 'جدة', 'Jeddah'],
            [1, 'مكة المكرمة', 'Mecca'],
            [1, 'المدينة المنورة', 'Medina'],
            [1, 'الدمام', 'Dammam'],
            [1, 'الخبر', 'Khobar'],
            [1, 'الظهران', 'Dhahran'],
            [1, 'تبوك', 'Tabuk'],
            [1, 'أبها', 'Abha'],
            [1, 'القصيم', 'Qassim'],
            [1, 'حائل', 'Hail'],
            [1, 'نجران', 'Najran'],
            [1, 'جازان', 'Jazan'],
            [1, 'الباحة', 'Al Baha'],
            [1, 'الحدود الشمالية', 'Northern Borders'],

            // country_id = 2: UAE
            [2, 'أبو ظبي', 'Abu Dhabi'],
            [2, 'دبي', 'Dubai'],
            [2, 'الشارقة', 'Sharjah'],
            [2, 'عجمان', 'Ajman'],
            [2, 'رأس الخيمة', 'Ras Al Khaimah'],
            [2, 'الفجيرة', 'Fujairah'],
            [2, 'أم القيوين', 'Umm Al Quwain'],

            // country_id = 3: Qatar
            [3, 'الدوحة', 'Doha'],
            [3, 'الريان', 'Al Rayyan'],
            [3, 'الوكرة', 'Al Wakrah'],
            [3, 'الخور', 'Al Khor'],

            // country_id = 4: Kuwait
            [4, 'مدينة الكويت', 'Kuwait City'],
            [4, 'حولي', 'Hawalli'],
            [4, 'الفروانية', 'Farwaniya'],
            [4, 'الجهراء', 'Jahra'],
            [4, 'الأحمدي', 'Ahmadi'],

            // country_id = 5: Oman
            [5, 'مسقط', 'Muscat'],
            [5, 'صلالة', 'Salalah'],
            [5, 'صحار', 'Sohar'],
            [5, 'نزوى', 'Nizwa'],
            [5, 'صور', 'Sur'],

            // country_id = 6: Bahrain
            [6, 'المنامة', 'Manama'],
            [6, 'المحرق', 'Muharraq'],
            [6, 'الرفاع', 'Riffa'],
            [6, 'عيسى', 'Isa Town'],
            [6, 'سترة', 'Sitra'],

            // country_id = 7: Yemen
            [7, 'صنعاء', 'Sana\'a'],
            [7, 'عدن', 'Aden'],
            [7, 'تعز', 'Taiz'],
            [7, 'الحديدة', 'Hodeidah'],
            [7, 'المكلا', 'Mukalla'],
            [7, 'إب', 'Ibb'],
            [7, 'ذمار', 'Dhamar'],
            [7, 'عمران', 'Amran'],
            [7, 'صعدة', 'Saada'],
            [7, 'حضرموت', 'Hadramaut'],
            [7, 'مأرب', 'Marib'],
            [7, 'الضالع', 'Ad Dhale'],
            [7, 'البيضاء', 'Al Bayda'],
            [7, 'شبوة', 'Shabwah'],
            [7, 'حجة', 'Hajjah'],
            [7, 'المحويت', 'Al Mahwit'],
            [7, 'لودر', 'Lawdar'],
            [7, 'زنجبار', 'Zinjibar'],

            // country_id = 8: Egypt
            [8, 'القاهرة', 'Cairo'],
            [8, 'الإسكندرية', 'Alexandria'],
            [8, 'الجيزة', 'Giza'],
            [8, 'الغردقة', 'Hurghada'],
            [8, 'الأقصر', 'Luxor'],
            [8, 'أسوان', 'Aswan'],
            [8, 'شرم الشيخ', 'Sharm El Sheikh'],
            [8, 'بورسعيد', 'Port Said'],
            [8, 'السويس', 'Suez'],
            [8, 'طنطا', 'Tanta'],
            [8, 'المنصورة', 'Mansoura'],
            [8, 'الزقازيق', 'Zagazig'],
            [8, 'دمنهور', 'Damanhur'],
            [8, 'كفر الشيخ', 'Kafr El Sheikh'],
            [8, 'بنها', 'Benha'],

            // country_id = 9: Jordan
            [9, 'عمان', 'Amman'],
            [9, 'إربد', 'Irbid'],
            [9, 'الزرقاء', 'Zarqa'],
            [9, 'البلقاء', 'Balqa'],
            [9, 'العقبة', 'Aqaba'],
            [9, 'مادبا', 'Madaba'],
            [9, 'الكرك', 'Karak'],
            [9, 'جرش', 'Jerash'],
            [9, 'عجلون', 'Ajloun'],
            [9, 'معان', 'Maan'],
            [9, 'الطفيلة', 'Tafilah'],

            // country_id = 10: Lebanon
            [10, 'بيروت', 'Beirut'],
            [10, 'طرابلس', 'Tripoli'],
            [10, 'صيدا', 'Sidon'],
            [10, 'صور', 'Tyre'],
            [10, 'زحلة', 'Zahle'],
            [10, 'جبيل', 'Byblos'],
            [10, 'البترون', 'Batroun'],
            [10, 'النبطية', 'Nabatieh'],

            // country_id = 11: Palestine
            [11, 'القدس', 'Jerusalem'],
            [11, 'غزة', 'Gaza'],
            [11, 'رام الله', 'Ramallah'],
            [11, 'نابلس', 'Nablus'],
            [11, 'الخليل', 'Hebron'],
            [11, 'بيت لحم', 'Bethlehem'],
            [11, 'جنين', 'Jenin'],
            [11, 'طولكرم', 'Tulkarm'],

            // country_id = 12: Syria
            [12, 'دمشق', 'Damascus'],
            [12, 'حلب', 'Aleppo'],
            [12, 'حمص', 'Homs'],
            [12, 'اللاذقية', 'Latakia'],
            [12, 'حماة', 'Hama'],
            [12, 'دير الزور', 'Deir ez-Zor'],
            [12, 'الحسكة', 'Hasaka'],
            [12, 'درعا', 'Daraa'],
            [12, 'إدلب', 'Idlib'],
            [12, 'طرطوس', 'Tartus'],
            [12, 'السويداء', 'Suwayda'],

            // country_id = 13: Iraq
            [13, 'بغداد', 'Baghdad'],
            [13, 'البصرة', 'Basra'],
            [13, 'الموصل', 'Mosul'],
            [13, 'أربيل', 'Erbil'],
            [13, 'السليمانية', 'Sulaymaniyah'],
            [13, 'كركوك', 'Kirkuk'],
            [13, 'النجف', 'Najaf'],
            [13, 'كربلاء', 'Karbala'],
            [13, 'الناصرية', 'Nasiriyah'],
            [13, 'الحلة', 'Hillah'],
            [13, 'الرمادي', 'Ramadi'],
            [13, 'دهوك', 'Dohuk'],
            [13, 'العمارة', 'Amarah'],

            // country_id = 14: Sudan
            [14, 'الخرطوم', 'Khartoum'],
            [14, 'أم درمان', 'Omdurman'],
            [14, 'بورسودان', 'Port Sudan'],
            [14, 'كسلا', 'Kassala'],
            [14, 'الأبيض', 'El Obeid'],
            [14, 'ود مدني', 'Wad Madani'],
            [14, 'عطبرة', 'Atbara'],
            [14, 'نيالا', 'Nyala'],
            [14, 'جنينة', 'El Geneina'],

            // country_id = 15: Libya
            [15, 'طرابلس', 'Tripoli'],
            [15, 'بنغازي', 'Benghazi'],
            [15, 'مصراتة', 'Misrata'],
            [15, 'الزاوية', 'Zawiya'],
            [15, 'سبها', 'Sabha'],
            [15, 'طبرق', 'Tobruk'],
            [15, 'البيضاء', 'Al Bayda'],

            // country_id = 16: Tunisia
            [16, 'تونس', 'Tunis'],
            [16, 'صفاقس', 'Sfax'],
            [16, 'سوسة', 'Sousse'],
            [16, 'القيروان', 'Kairouan'],
            [16, 'بنزرت', 'Bizerte'],
            [16, 'قابس', 'Gabès'],
            [16, 'نابل', 'Nabeul'],

            // country_id = 17: Algeria
            [17, 'الجزائر', 'Algiers'],
            [17, 'وهران', 'Oran'],
            [17, 'قسنطينة', 'Constantine'],
            [17, 'عنابة', 'Annaba'],
            [17, 'بجاية', 'Bejaia'],
            [17, 'سطيف', 'Setif'],
            [17, 'باتنة', 'Batna'],
            [17, 'ورقلة', 'Ouargla'],
            [17, 'بشار', 'Bechar'],
            [17, 'تلمسان', 'Tlemcen'],

            // country_id = 18: Morocco
            [18, 'الرباط', 'Rabat'],
            [18, 'الدار البيضاء', 'Casablanca'],
            [18, 'مراكش', 'Marrakech'],
            [18, 'فاس', 'Fes'],
            [18, 'طنجة', 'Tangier'],
            [18, 'أكادير', 'Agadir'],
            [18, 'مكناس', 'Meknes'],
            [18, 'وجدة', 'Oujda'],
            [18, 'تطوان', 'Tetouan'],
            [18, 'الصويرة', 'Essaouira'],

            // country_id = 19: Mauritania
            [19, 'نواكشوط', 'Nouakchott'],
            [19, 'نواذيبو', 'Nouadhibou'],
            [19, 'كيفة', 'Kiffa'],
            [19, 'الزويرات', 'Zouérat'],
            [19, 'روصو', 'Rosso'],

            // country_id = 20: Somalia
            [20, 'مقديشو', 'Mogadishu'],
            [20, 'هرجيسا', 'Hargeisa'],
            [20, 'بربرة', 'Berbera'],
            [20, 'كيسمايو', 'Kismayo'],

            // country_id = 21: Djibouti
            [21, 'جيبوتي', 'Djibouti City'],
            [21, 'علي صبيح', 'Ali Sabieh'],
            [21, 'تاجورة', 'Tadjoura'],

            // country_id = 22: Comoros
            [22, 'موروني', 'Moroni'],
            [22, 'موتسامودو', 'Mutsamudu'],
            [22, 'فومبوني', 'Fomboni'],
        ];

        foreach ($cities as $c) {
            City::create([
                'title' => ['ar' => $c[1], 'en' => $c[2]],
                'key' => $c[1],
                'country_id' => $c[0],
            ]);
        }
    }
}
