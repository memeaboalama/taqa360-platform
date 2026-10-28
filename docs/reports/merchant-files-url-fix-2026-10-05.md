# Merchant Files URL Fix — تقرير إصلاح روابط مرفقات المتجر

**التاريخ:** 2026-10-05
**المشكلة:** مرفقات المتجر من نوع PDF/CSV/XLS/XLSX ترجع 404 في لوحة الإدارة
**النطاق:** لوحة الإدارة + لوحة المتجر — تبويب مرفقات المتجر فقط

---

## 1) سبب المشكلة

`ImageService::storeImage()` يخزّن `path` **متضمناً اسم الملف**:

`app/Support/Services/ImagesServices/ImageService.php:66-68`
```php
'file_name' => $name,
'path'      => "$path/$name",   // ← path يحتوي اسم الملف
'url'       => Storage::disk($disk)->url("$path/$name"),
```

لكن كود لوحة الإدارة كان **يضيف `file_name` مرة ثانية** فوق `path` داخل فرع
المستندات فقط:

```php
// ❌ قبل الإصلاح
Storage::disk($this->diskName)->url($obj->path . '/' . $obj->file_name)
```

### النتيجة المُثبتة

```
path       = upload/file/.../store-1-doc-3.pdf
file_name  = store-1-doc-3.pdf

قبل : /storage/.../store-1-doc-3.pdf/store-1-doc-3.pdf   → 404 ❌
بعد  : /storage/.../store-1-doc-3.pdf                     → 200 ✅
```

**لماذا الصور كانت تعمل؟** لأن فرع الصورة كان يستخدم `url($path)` فقط.
الفرع الصحيح كان موجوداً في نفس الدالة — ولم يُطبَّق على المستندات.

---

## 2) الملفات والأسطر المُعدَّلة

### أ) رابط الملف — 16 موضعاً (8 في كل ملف)

| الملف | قبل | بعد |
|---|---|---|
| `app/Application/Admin/Livewire/Merchant/Tabs/Info/Files.php` | أسطر 113-166 | `fillData()` collapsed |
| `app/Application/Marketplace/Livewire/Profile/Tabs/Files.php` | أسطر 150-203 | `fillData()` collapsed |

**ما تغيّر بالضبط:** حُذف الفرع المزدوج بالكامل واستُبدل بدالة واحدة:

```php
$fileUrl = function ($obj) {
    if (!$obj) {
        return null;
    }

    return $obj->url ?: ($obj->path ? Storage::disk($this->diskName)->url($obj->path) : null);
};

$this->company_logo_file          = $fileUrl($this->company_logo_obj);
$this->bank_account_details_file  = $fileUrl($this->bank_account_details_obj);
$this->vat_certificated_file      = $fileUrl($this->vat_certificated_obj);
$this->owner_id_file              = $fileUrl($this->owner_id_obj);
$this->menu_copy_file             = $fileUrl($this->menu_copy_obj);
$this->cr_copy_file               = $fileUrl($this->cr_copy_obj);
$this->national_address_copy_file = $fileUrl($this->national_address_copy_obj);
$this->signed_contract_copy_file  = $fileUrl($this->signed_contract_copy_obj);
```

- `url` من قاعدة البيانات = الخيار المفضّل (requirement 3)
- `Storage::disk($this->diskName)->url($obj->path)` = الـ fallback
- `null` آمن — لا خطأ

**صافي التغيير: −124 سطر / +64 سطر** (أي إزالة تكرار، لا إضافة).

### ب) فحص الامتداد — 16 موضعاً

| الملف | عدد المواضع |
|---|---|
| `app/Application/Admin/resources/views/livewire/merchant/tabs/info/files.blade.php` | 8 |
| `app/Application/Marketplace/resources/views/livewire/profile/tabs/files.blade.php` | 8 |

**قبل** (حسّاس لحالة الأحرف، يفشل مع `null`، يعتمد على `strpos + substr`):
```php
@if(in_array(substr($name, strpos($name, ".") + 1), ['pdf', 'csv', 'xls', 'xlsx']))
```

**بعد** (يُعرَّف مرة واحدة أعلى الملف):
```php
@php
    $isDocumentFile = function (?string $name): bool {
        return in_array(strtolower(pathinfo((string) $name, PATHINFO_EXTENSION)), ['pdf', 'csv', 'xls', 'xlsx'], true);
    };
@endphp
...
@if($isDocumentFile($menu_copy_original_name))
```

**المجموع: 32 موضعاً مُصلَح.**

---

## 3) عدد المواضع المُصلَحة

| البند | العدد |
|---|---|
| بناء رابط الملف | **16** (8 Admin + 8 Marketplace) |
| فحص الامتداد | **16** (8 Admin blade + 8 Marketplace blade) |
| **الإجمالي** | **32** |

الحقول: `company_logo` · `owner_id` · `bank_account_details` · `vat_certificated` ·
`menu_copy` · `cr_copy` · `national_address_copy` · `signed_contract_copy`

---

## 4) نتائج الاختبارات

### أ) قبل / بعد على بيانات حقيقية

```
file_name                    rendered   BEFORE   AFTER
store-1-image-1.webp         <img>      200      200
store-1-image-2.webp         <img>      200      200
store-1-doc-3.pdf            link       404      200   ✅
store-2-image-1.webp         <img>      200      200
store-2-image-2.webp         <img>      200      200
store-3-doc-1.pdf            link       404      200   ✅
store-3-doc-2.pdf            link       404      200   ✅
store-4-image-1.webp         <img>      200      200
store-4-image-2.webp         <img>      200      200
store-4-doc-3.pdf            link       404      200   ✅

BROKEN BEFORE: 4 / 10        BROKEN AFTER: 0 / 10
```

### ب) كل الحالات المطلوبة

| الحالة | النتيجة | HTTP |
|---|---|---|
| PDF | link ✅ | 200 `application/pdf` |
| **PDF بحروف كبيرة** `commercial-register.PDF` | link ✅ *(previously broken)* | 200 `application/pdf` |
| صورة `company-logo.jpg` | `<img>` ✅ | 200 `image/jpeg` |
| **CSV** `national-address.csv` | link ✅ | 200 `text/csv` |
| **XLSX** `signed-contract.xlsx` | link ✅ | 200 (spreadsheet MIME) |
| **حقل فارغ** `owner_id` | EMPTY — بلا خطأ ✅ | n/a |
| **حقل فارغ** `vat_certificated` | EMPTY — بلا خطأ ✅ | n/a |
| `original_name = null` | لا خطأ ✅ | n/a |
| `contract.Pdf` / `Contract.Pdf` | يذهب لفرع المستند ✅ | — |
| `a.b.c.pdf` (نقطتان) | يذهب لفرع المستند ✅ *(previously broken)* | — |
| `scanned_document` (بلا امتداد) | صورة — بلا خطأ ✅ | — |
| `url` = NULL | fallback يعمل ✅ | 200 |
| `url` + `path` = NULL | `null` — بلا خطأ ✅ | n/a |

### ج) لا انحراف في السلوك
```
rows where the render decision changed: 0 (expected 0 for existing data)
```
الصور بقيت صور، وPDF بقيت روابط — **لا تغيير غير مقصود**.

### د) فحوص أخرى
- `php -l` نظيف على الملفين + الـ seeder
- `blade.compiler` يجمع الملفين بلا أخطاء
- **لا PHP notices/warnings** في أي حالة
- Regression: `/ar/merchants` · `/ar/ecommerce/merchant/{1,5}` · `/ar/special-offers` · `/ar/categories` = **200**

---

## 5) خطوات الاختبار اليدوي

### prepares
```powershell
cd D:\Newtouch\Projects\Dev-NewTouch\edu-solutions-platform
php artisan db:seed --class=MerchantDocumentsSeeder --force
php artisan view:clear
php artisan serve --port=8000
```

### الاختبار 1 — PDF (كان 404)
1. افتح `/admin/login` وسجّل الدخول
2. اذهب إلى `/admin/merchants/1`
3. تبويب معلومات المتجر → بطاقة **First Copy** (`menu_copy`)
4. سيظهر **اسم الملف كرابط** (وليس صورة)
5. اضغط الرابط ← يجب أن يُفتح الـ PDF

### الاختبار 2 — PDF بحروف كبيرة
- بطاقة **CR Copy** (`cr_copy`) — الملف `commercial-register.PDF`
- يجب أن يظهر كرابط ويفتح 200

### الاختبار 3 — صورة
- بطاقة **Company Logo** (`company_logo`) — `company-logo.jpg`
- يجب أن تظهر **كصورة معاينة** (وليس رابط)

### الاختبار 4 — CSV / XLSX
- **National Address Copy** → `.csv` كرابط
- **Signed Contract Copy** → `.xlsx` كرابط

### الاختبار 5 — الحقول الفارغة
- **Owner ID** و **VAT Certificate** يجب أن يعرضا
  «No file attached yet» — **بدون أي خطأ**

### كيف تتأكد أن HTTP أصبح 200
```powershell
# 1) من الـ Blade: افتح رابط الملف بـ Ctrl+click
# 2) أو من الكونسول:
curl.exe -s -o NUL -w "%{http_code}`n" "http://127.0.0.1:8000/storage/upload/file/merchant-documents/merchant-1-menu_copy.pdf"
# المتوقع: 200
```
**القاعدة:** الرابط يجب أن ينتهي بـ `...pdf` **مرة واحدة فقط**.
إذا رأيت `...pdf/xxx.pdf` فالخطأ لم يُصلَح.

### الاختبار 6 — لوحة المتجر (نفس الكود)
`/merchant/profile` ← تبويب الملفات ← نفس الحقول

---

## 6) البيانات التجريبية المُضافة

`database/seeders/MerchantDocumentsSeeder.php` (جديد، idempotent):
يملأ الـ 8 خانات الحقيقية في `merchant_files` لمتجرين (#1, #2).

| الخانة | `original_name` | يغطي |
|---|---|---|
| company_logo | `company-logo.jpg` | فرع الصورة |
| menu_copy | `menu-copy.pdf` | PDF |
| cr_copy | `commercial-register.PDF` | PDF بأحرف كبيرة |
| national_address_copy | `national-address.csv` | CSV |
| signed_contract_copy | `signed-contract.xlsx` | XLSX |
| bank_account_details | `bank-account-details.pdf` | PDF |
| owner_id | *(فارغ)* | Empty state |
| vat_certificated | *(فارغ)* | Empty state |

Idempotent: تحقق بالتشغيل مرتين → `12 created` ثم `0 created, 12 already existed`.

> الملفات_BYTES مكتوبة على قرص `fileDisk` نفسه وبنفس شكل `ImageService` —
> **لم يتغيّر نظام التخزين ولا `ImageService` ولا قاعدة البيانات.**

---

## 7) مشاكل متبقية

### 🔴 CRITICAL — تسريب مستندات خاصة في صفحة المتجر العامة

القسم الذي أضفته في مهمة سابقة على صفحة المتجر يفلتر **بالقائمة السوداء**:

`app/Application/Ecommerce/Controllers/MerchantController.php:121`
```php
$merchant->loadMissing(['files' => fn ($q) => $q->where('collection_name', '!=', 'app_logo')]);
```

⇒ أي ملف **ليس** `app_logo` يظهر للعموم. وبعد تعبئة خانات الإدارة، صار pageable على
`/ar/ecommerce/merchant/1`:

```
merchant-1-company_logo.jpg
merchant-1-menu_copy.pdf            ← عقد/قائمة
merchant-1-cr_copy.pdf              ← سجل تجاري
merchant-1-national_address_copy.csv← عنوان وطني
merchant-1-signed_contract_copy.xlsx← عقد موقّع
merchant-1-bank_account_details.pdf ← بيانات حساب بنكي
```

**الإصلاح المقترح (سطر واحد — قائمة بيضاء):**
```php
$merchant->loadMissing(['files' => fn ($q) => $q->where('collection_name', 'merchant_attachments')]);
```

> ⚠️ **لم أنفّذه** — يقع خارج نطاق هذه المهمة. **Production غير متأثر** لأن القسم
> لم يُرفع بعد. لكن يجب حسمه **قبل** رفع ذلك القسم.

### 🟡 نفس الخطأ موجود في ملفات أخرى (خارج النطاق)

| الملف | المواضع |
|---|---|
| `app/Application/Admin/Livewire/Driver/Tabs/Files.php` | 5 |
| `app/Application/Admin/Livewire/Driver/Tabs/FilesRegister.php` | 5 |
| `app/Application/Driver/Livewire\Auth/Tabs/Files.php` | 5 |
| `app/Application/Company/Livewire/Driver/Tabs/Files.php` | 5 |
| `app/Application/Admin/Livewire/SalesAgent/Tabs/Files.php` | 1 |
| `app/Domain/MarketPlace/Models/MerchantIban.php` | 1 |

نفس نمط `url($path . '/' . $file_name)`. **لم تُمس** — خارج النطاق.

### 🟡 `.xlsx` تجريبي وليس ملف Excel حقيقي
`ZipArchive` غير متاح في PHP المحلي ⇒ الملف payload نصي بسيط.
`ZipArchive` غير متاح في PHP المحلي، لذلك محتوى ملف `.xlsx` هو نص بسيط.
الرابط يعمل (200) لكن فتحه في Excel لن ينجح. **الاختبار يغطّي الرابط فقط.**

---

## 8) تأكيد صريح

| البند | الحالة |
|---|---|
| **Production** | ✅ **لم يتأثر** — كل الاختبارات على `127.0.0.1` |
| **حذف أي بيانات** | ✅ **لا يوجد** — `DELETE`/`TRUNCATE`/`migrate:fresh` لم تُستخدم |
| نظام التخزين / `fileDisk` / `ImageService` | ✅ **لم تتغير** |
| إعادة هيكلة المشروع | ✅ **لا يوجد** |
| Service / Helper / Model / Route جديد | ✅ **لا يوجد** (seeder واحد فقط) |
| وظائف خارج النطاق | ✅ **لم تتغير** — عدا تسريب مُبلَّغ عنه أعلاه |
| أسطر مضافة/محذوفة | `+64 / −124` (إزالة تكرار) |

---

*نهاية التقرير*
