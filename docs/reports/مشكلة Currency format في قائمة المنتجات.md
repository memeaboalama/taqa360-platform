# المنتجات ← قائمة المنتجات — `Call to undefined method Currency::format()`

**التاريخ:** 05-10-2026 · **فحص فقط — بدون أي تعديل**

**المسار المتأثر:** `/ar/categories` (قائمة المنتجات) — ومعه **29 صفحة أخرى**

---

## النتيجة: ليست مشكلة كود — بل **نشر جزئي (Partial Deployment)**

`Currency::format()` **موجود في المستودع المحلي ويعمل**. المشكلة أن السيرفر لديه
**الواجهات التي تستدعيه**، بينما **موديل `Currency` عنده قديم بدون الدالة**.

---

## 1) الدليل

### قبل commit `2d78eaec` — `Currency` لا يحتوي أي دالة تنسيق
```php
class Currency extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = ['title','iso'];
    public function services(): HasMany { ... }
    // ❌ لا يوجد format() ولا symbol() ولا storeIso()
}
```

### بعده (الحالي)
```php
public static function storeIso(): string { ... }   // سطر 20
public static function symbol(): string   { ... }   // سطر 29
public static function format($amount): string {   // سطر 44
    return number_format((float) $amount, 2) . ' ' . static::symbol();
}
```

**⇒ خطأ `Call to undefined method` يظهر فقط إذا نُشرت الواجهات ونُسي الموديل.**

---

## 2) النطاق: 30 ملفاً يستدعي الدالة

| الفحص | النتيجة |
|---|---|
| ملفات تستدعي `Currency::format()` أو `::symbol()` | **30** |
| منها أُضيفت في commit `2d78eaec` | **28** |
| ومنها ملفات «العروض الخاصة» (غير مرفوعة بعد) | 2 |

**المهم:** الإصلاح في **ملف واحد** يفكّ الـ 30 استدعاءاً دفعة واحدة.

### أبرز الصفحات المتأثرة
```
pages/categories.blade.php          ← قائمة المنتجات (المبلَّغ عنها)
pages/home.blade.php               ← الرئيسية
pages/partials/product-grid.blade.php
pages/partials/tab-content.blade.php
pages/product/product-details.blade.php
pages/cart/* · pages/checkout/*     ← السلة والدفع
pages/merchant/merchant.blade.php
Marketplace/Livewire/Product/Table/All.php   ← لوحة المتجر
```

---

## 3) لماذا لم يُكتشف محلياً

اختبرت **10 مسارات منتجات** — كلها **200** بلا خطأ:

```
/ar/categories            200      /ar/products/2        200
/ar/categories/1          200      /ar/product-page/2    200
/ar/categories/1/products 200     /ar/product/2         200
/ar/products              200      /ar/special-offers    200
/ar                       200
```

وتحققت من الدالة مباشرة:
```
Currency::format(1234.5) = 1,234.50 ر.س   ✅
method_exists(format)   = YES ✅
currencies rows = 1 · stored iso = 'sar'
```

⇒ **الخلل على السيرفر فقط.**

---

## 4) لماذا لم ألتقطه في قائمة الرفع السابقة ⚠️

`app/Domain/AppBase/Models/Setting/Currency.php` **مُودَع في git ونظيف** (ليس `M` ولا `??`)
⇒ لم يظهر في `git status` ⇒ لم يدخل أي من مجموعات الرفع التي أرسلتها.

**هذه نقطة ضعف في طريقة «قائمة الرفع»: الطريقة تعتمد على الملفات المتغيّرة فقط،
ولا تلتقط ملفاً مُودَعاً كان ناقصاً في النشر.**

---

## 5) الحل

### خطوة واحدة — ارفع هذا الملف فقط
```
app/Domain/AppBase/Models/Setting/Currency.php
```
ثم على السيرفر:
```bash
php artisan optimize:clear
```

هذا وحده يُصلح **الصفحة المبلَّغ عنها + الـ 29 صفحة الأخرى**.

### تحقق بعد الرفع
```powershell
php -r "require 'vendor/autoload.php'; $a=require 'bootstrap/app.php';
$a->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo App\Domain\AppBase\Models\Setting\Currency::format(1234.5);"
```
**المتوقع:** `1,234.50 ر.س`

---

## 6) تنبيه: نفس المشكلة قد تُخفي غيرها

الـ 28 ملفاً من commit `2d78eaec` يفترض أنها رُفعت معاً. إن كان السيرفر نُشر بشكل جزئي،
فقد تكون هناك دوال أخرى مفقودة بنفس النمط.

**التوصية:** تأكد أن السيرفر على نفس الـ commit:
```bash
git log -1 --oneline
```
ويجب أن يكون `2d78eaec` أو أحدث.

---

## 7) متى يحدث هذا عملياً؟

| السيناريو | النتيجة |
|---|---|
| نشر الواجهات فقط دون الموديل | ❌ **هذا الخطأ** |
| نشر الملفين معاً | ✅ يعمل |
| السيرفر على commit أقدم من `2d78eaec` | ❌ نفس الخطأ |

---

## ما لم يُنفَّذ
- ❌ لا تعديل على أي كود
- ❌ لا تعديل على Production
- ❌ لا تعديل على قاعدة البيانات

---

*نهاية التقرير — فحص فقط.*