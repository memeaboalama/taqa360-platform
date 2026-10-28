# الملف الشخصي — عرض المرفق من نوع PDF (لوحة المتجر)

**التاريخ:** 05-10-2026 · **فحص فقط — بدون أي تعديل**

**المسار:** `لوحة المتجر ← الملف الشخصي ← تبويب Files (المرفقات)`

---

## النتيجة المختصرة: الخطأ **مُصلَح محلياً لكنه غير مرفوع بعد** ← سبب ما زال العميل يراه

| الفحص | النتيجة |
|---|---|
| `Files.php` فيه تكرار `path . '/' . file_name` | ✅ **لا** — مُصلَح |
| `files.blade.php` فيه `in_array(substr(...))` | ✅ **لا** — مُصلَح |
| كل روابط الملفات (22 ملف) عبر HTTP | ✅ **200** |
| **`git status` للملفات الأربعة** | 🔴 **`M` = معدّل وغير مُرفع** |

> آخر commit على هذه الملفات: `44fa6b5c update merchant screen` — وهو **ما زال يحمل النسخة المعطوبة**.
> **⇒ العميل يختبر على سيرفر لم يصله الإصلاح.**

---

## سبب المشكلة الجذري (مُثبت)

`ImageService` يخزّن `path` **متضمناً اسم الملف**، والكود كان يضيف `file_name` مرة ثانية:

```
قبل : /storage/upload/file/menu_copy.pdf/menu_copy.pdf   → 404 ❌
بعد  : /storage/upload/file/menu_copy.pdf                 → 200 ✅
```

**كان يصيب PDF و CSV و XLS و XLSX فقط** — أما الصور فتمرّ، لأن لها فرعاً منفصلاً في نفس الدالة.

---

## كيف تفتح لوحة المتجر وتصل للمرفقات

```powershell
cd D:\Newtouch\Projects\Dev-NewTouch\edu-solutions-platform
php artisan serve --port=8000
```

1. افتح **`http://127.0.0.1:8000/merchant/login`**
2. سجّل الدخول بـ المتجر رقم 1:
   ```
   merchant@gmail.com  /  password
   ```
   > ✅ تحققت أن كلمة المرور `password` مطابقة للمتجر #1
3. بعد الدخول ستُحوَّل إلى `merchant/home`
4. اذهب إلى **`http://127.0.0.1:8000/merchant/profile`**
5. اضغط تبويب **Files** (أيقونة الملف) — التبويب **ظاهر دائماً** لمالك المتجر
   (تحققت: `MerchantHasRole` يُرجع `true` لأي متجر مسجّل الدخول)
6. اضغط على رابط أي ملف PDF — **سيعمل الآن محلياً ✅**

**الحقول الثمانية:** Company Logo · Owner ID · Menu Copy · CR Copy · National Address · Signed Contract · Bank Account · VAT Certificate

---

## كيف نصلحها (الخلاصة)

**لا يوجد أي تعديل كود مطلوب.** الإصلاح جاهز محلياً — المطلوب فقط رفع 4 ملفات:

```
app/Application/Marketplace/Livewire/Profile/Tabs/Files.php
app/Application/Marketplace/resources/views/livewire/profile/tabs/files.blade.php
app/Application/Admin/Livewire/Merchant/Tabs/Info/Files.php
app/Application/Admin/resources/views/livewire/merchant/tabs/info/files.blade.php
```

ثم على السيرفر:
```bash
php artisan optimize:clear
```

> هذه الملفات هي **مجموعة B** في قائمة الرفع التي أرسلتها سابقاً.

---

## ملاحظات ثانوية (غير مانعة، خارج المطلوب)

| # | الملاحظة |
|---|---|
| 1 | الروابط بـ `target="_blank"` **بدون `rel="noopener"`** — ثغرة أمنية بسيطة |
| 2 | إدخال `company_logo` يستخدم `wire:model` بينما `owner_id` يستخدم `wire:model.defer` — **غير متسق** |
| 3 | `updateFiles()` يُتحقَّق من **الخانات الثمانية كلها** في كل حفظ |

---

*نهاية التقرير — فحص فقط.*