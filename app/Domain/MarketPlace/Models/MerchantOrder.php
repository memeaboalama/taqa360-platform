<?php

namespace App\Domain\MarketPlace\Models;

use App\Application\Marketplace\Enums\MerchantOrderStatuses;
use App\Application\Marketplace\Enums\MerchantOrderMarks;
use App\Domain\AppBase\Models\CashBack;
use App\Domain\AppBase\Models\GeneralSetting;
use App\Domain\AppBase\Models\Promocode;
use App\Domain\User\Models\User;
use App\Domain\User\Models\UserAddress;
use App\Support\Traits\Models\BelongsToMerchant;
use App\Support\Traits\Models\HasNewFactory;
use App\Support\Traits\Models\HasStatus;
use App\Support\Traits\Models\MerchantRelation;
use App\Support\Traits\Models\Payment\HasPayment;
use App\Domain\AppBase\Models\RefundPayment;
use Domain\AppBase\Models\Payment;
use Domain\Driver\Models\Driver;
use Domain\MarketPlace\Models\ElmOrder;
use Domain\MarketPlace\Models\Merchant;
use Domain\MarketPlace\Models\MerchantBranch;
use Domain\MarketPlace\Models\MerchantEmployee;
use Domain\MarketPlace\Models\MerchantOrderService;
use Domain\MarketPlace\Models\TempOrder;
use App\Domain\AppBase\Models\TypeService;
use Faker\Generator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Model;
use tiagomichaelsousa\LaravelFilters\Traits\Filterable;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class MerchantOrder extends Model
{
    use HasNewFactory, BelongsToMerchant, MerchantRelation, HasStatus;
    use HasEvents, HasPayment;

    protected $fillable = [
        'subtotal',
        'vat',
        'total_after_vat',
        'total_addon',
        'total_service',
        'discount',
        'total_after_discount',
        'channel',
        'payment_type',
        "from_wallet",
        "from_merchant_balance",
        'payment_method',
        'client_name',
        'client_phone',
        'client_lat',
        'client_long',
        'merchant_name',
        'status',
        'dialcode',
        'schedulable',
        'date',
        'from',
        'to',
        'order_type',
        'merchant_id',
        'user_id',
        'address_id',
        'addressInOrder',
        'merchant_branch_id',
        'code',
        'employee_id',
        'driver_id',
        'has_assign_driver',
        'assign_driver_id',
        'elm_order_id',
        'gift',
        'gift_confidential',
        'ask_receipt',
        'promocode',
        'promocode_id',
        'cash_back',
        'cash_back_id',
        'service_type_id',
        'delivery_fee',
        'delivery_fee_discount',
        'delivery_distance',
        'commission_amount',
        'commission_rate',
        'package_id',
        'note',
        'rate',
        'driver_rate',
        'commission_from_driver',
        'sales_agent_commission',
        'admin_note',
        'mark',
        'expired_at',
    ];

    protected $casts = [
        'status' => MerchantOrderStatuses::class,
        'mark' => MerchantOrderMarks::class,
        // 'addressInOrder' => 'json',
    ];


    public function type_service(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }
    public function type_service_name(): string
    {
        $typeService = TypeService::where('id', $this->service_type_id)->first();
        return $typeService ? $typeService->name : '';
    }
    public function services(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrderService::class, 'order_id');
    }
    public function promocode(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Promocode::class, 'promocode_id', 'id');
    }

    public function orderItems(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrderItem::class, 'order_id');
    }

    public function getStatusLabelAttribute()
    {
        return $this->status instanceof MerchantOrderStatuses ? $this->status->trans() : $this->status;
    }

    public function getStatusColorAttribute()
    {
        return $this->status instanceof MerchantOrderStatuses ? $this->status->color() : 'secondary';
    }

    public function orderDiscounts(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantOrderItem::class, 'order_id', 'id');
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'id');
    }



    public function assign_driver(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Driver::class, 'assign_driver_id', 'id');
    }


    public function merchant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Merchant::class, 'merchant_id');
    }

    public function employee(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantEmployee::class, 'employee_id', 'id');
    }

    public function address(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function branch(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(MerchantBranch::class, 'merchant_branch_id', 'id');
    }

    public function orderChoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantChoice::class, 'order_id', 'id');
    }

    public function temp_orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TempOrder::class, 'order_id', 'id');
    }
    public function refundPayment(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RefundPayment::class, 'order_id', 'id');
    }

    public function orders_history(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(MerchantOrderHistory::class, 'order_id', 'id');
    }

    public function elmOrder(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(ElmOrder::class, 'elm_order_id', 'id');
    }

    protected function subtotal(): Attribute
    {
        return new Attribute(
            get: fn() => $this->orderItems->reduce(function ($value, $item) {
                return $value + $item->subtotal;
            }, 0)
        );
    }
    public static function resolvePaymentStatus(?Payment $payment, ?string $fallback = 'unpaid', ?string $paymentMethod = null): ?string
    {
        $normalizedMethod = strtolower(trim((string) ($paymentMethod ?? '')));
        if (in_array($normalizedMethod, ['cod', 'cash', 'cash_on_delivery', 'cashondelivery'], true)) {
            return 'paid';
        }

        if (!$payment) {
            return $fallback;
        }

        $status = is_string($payment->status ?? null)
            ? strtolower(trim($payment->status))
            : null;

        if ($status !== null && $status !== '') {
            return $status === 'success' ? 'paid' : $status;
        }

        return ((float) ($payment->amount ?? 0)) > 0 ? 'paid' : ($fallback ?? 'unpaid');
    }

    protected function paymentStatus(): Attribute
    {
        $pay = Payment::Where('payable_id', $this->id)
            ->Where('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder')
            ->latest()->first();

        return new Attribute(
            get: fn() => self::resolvePaymentStatus($pay, 'unpaid', $this->payment_method)
        );
    }

    protected function paymentLabel(): Attribute
    {
        return new Attribute(
            get: fn() => match($this->payment_status) {
                'paid' => __('ecommerce::ecommerce.paid'),
                'unpaid' => __('ecommerce::ecommerce.unpaid'),
                'pending' => __('ecommerce::ecommerce.payment_pending'),
                'failed' => __('ecommerce::ecommerce.payment_failed'),
                default => $this->payment_status ?: __('ecommerce::ecommerce.unpaid'),
            }
        );
    }

    protected function paymentColor(): Attribute
    {
        return new Attribute(
            get: fn() => match($this->payment_status) {
                'paid' => 'bg-success',
                'unpaid', 'failed' => 'bg-danger',
                'pending' => 'bg-warning',
                default => 'bg-secondary',
            }
        );
    }
    protected function     GetOrdersHistoryLastStatusChangeByAttribute() //: Attribute
    {
        $last_status_by = $this->orders_history()->latest()->first();
        $status = $last_status_by->action ?? '';
        return $status;
        // return new Attribute(
        // get: fn() =>$status
        // );
    }

    protected function tax(): Attribute
    {
        return new Attribute(
            get: fn() => $this->merchant?->country?->vat ?
                $this->subtotal * ($this->merchant?->country?->vat / 100) : 0
        );
    }

    protected function total(): Attribute
    {
        return new Attribute(
            get: fn() => $this->subtotal + $this->tax
        );
    }

    protected function commission(): Attribute
    {
        return new Attribute(
            get: fn() => round(($this->commission_rate * $this->total_after_vat) / 100, 2)
        );
    }

    protected function GetRefPromocodeAttribute(): ?Promocode
    {
        // $promo = null;
        if ($this->promocode_id) {
            return Promocode::with(['merchants'])->select(['*'])->where(['id' => $this->promocode_id])->first();
            //  $promo= Promocode::with(['merchants'])->select(['*'])->where(['id' => $this->promocode_id])->first();
            // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;

        }
        // return new Attribute(
        //     get: fn() => $promo
        // );
        return null;
    }
    protected function GetRefPaymentAttribute(): ?Payment
    {
        // $promo = null;
        // if($this->promocode_id)
        // {
        //  return Payment::Where('payable_id', $this->id)
        //  ->Where('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder')
        //  // ->orderByDesc('updatedAt')->first();
        // //  ->latest('updated_at')->first();
        //  ->latest('status')->first();
        return $this->payment()
            ->Where('payable_id', $this->id)
            ->Where('payable_type', 'App\\Domain\\MarketPlace\\Models\\MerchantOrder')
            ->latest()
            ->first();
        //  return $this->payment()->firstWhere('status', 'paid');
        //  $promo= Promocode::with(['merchants'])->select(['*'])->where(['id' => $this->promocode_id])->first();
        // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;

        // }
        // return new Attribute(
        //     get: fn() => $promo
        // );
        // return $this->payment()->firstWhere('status', 'paid');
    }

    protected function commissionFromSub(): Attribute
    {
        $discount = 0;
        if ($this->promocode_id) {
            $payment_commission = $this->paymentCommissionFromSub();
            $promo = Promocode::with([])->select(['*'])->where(['id' => $this->promocode_id])->first();
            // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;
            if ($promo && $promo->merchant_id && $promo->merchant_id == $this->merchant_id)
                $discount = $this->discount;
        }

        return new Attribute(
            get: fn() => round(($this->commission_rate * ($this->subtotal - $discount)) / 100, 2)
        );
    }

    protected function driverCommission(): Attribute
    {
        return new Attribute(
            get: fn() => round(($this->delivery_fee * $this->commission_from_driver) / 100, 2)
        );
    }
    // protected function salesAgentCommission(): Attribute
    // {
    //     return new Attribute(
    //         get: fn() => $this->sales_agent_commission
    //     );
    // }
    // public function setSalesAgentCommissionAttribute()
    // {
    //     if($this->promocode_id && $this->promocode->sales_agent_id){
    //         $salesAgent = $this->promocode->sales_agent;
    //         $this->sales_agent_commission = round(($salesAgent->taken_commission * $this->total_after_vat) / 100, 2);
    //         $this->save();
    //     }
    // }
    protected function dueToMerchant(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->total_after_vat - $this->commission, 2)
        );
    }

    protected function refundpaymentSum(): Attribute
    {

        $refundpaymentWithStore = 0;
        if ($this->refundpayment) {
            $refundpaymentWithStore = RefundPayment::where('order_id', $this->id)
                ->sum('amount');
        }
        return new Attribute(
            get: fn() => number_format($refundpaymentWithStore, 2)
        );
    }
    protected function dueToMerchantFromSub(): Attribute
    {
        $payment_commission = 0;
        // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;
        // $discount = 0;
        if ($this?->refPayment) {
            $payment_commission = $this->paymentCommissionFromSub();
        }
        // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;
        $discount = 0;
        if ($this->promocode_id) {
            $payment_commission = $this->paymentCommissionFromSub();
            $promo = Promocode::with([])->select(['*'])->where(['id' => $this->promocode_id])->first();
            // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;
            if ($promo && $promo->merchant_id && $promo->merchant_id == $this->merchant_id)
                $discount = $this->discount;
        }

        $refundpaymentWithStore = 0;
        if ($this->refundpayment) {
            $refundpaymentWithStore = RefundPayment::where('order_id', $this->id)
                ->where('calculated_on', 'store')
                ->sum('amount');
        }
        return new Attribute(
            get: fn() => round($this->subtotal - $discount - $this->commissionFromSub - $payment_commission - $refundpaymentWithStore, 2)
        );
    }
    protected function PaymentCommission(): Attribute
    {
        $payment_commission = 0;
        // $discount = $this->promocode?->hasMerchant() ? $this->discount : 0;
        // $discount = 0;
        if ($this?->refPayment) {
            $payment_commission = $this->paymentCommissionFromSub();
        }
        return new Attribute(
            get: fn() => number_format($payment_commission, 2)
        );
    }

    public function paymentCommissionFromSub()
    {

        $maymentMethod = $this?->refPayment?->source_company ?? null;
        $paidAmount = ((float)($this?->refPayment?->amount ?? 0)) / 100;
        $paymets_commission = GeneralSetting::select(
            'visa_commission AS visa',
            'master_commission AS master',
            'mada_commission AS mada',
            'apple_commission AS applepay',
            'stc_commission AS stcpay'
        )->first();

        $commission = $paymets_commission[$maymentMethod] ?? 0;
        $commission_noVat = 1 + (($commission * $paidAmount) / 100);
        return round((($commission_noVat * 15) / 100) + $commission_noVat, 2);
    }

    protected function dueToDriver(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->delivery_fee - $this->driverCommission, 2)
        );
    }

    protected function deliveryVat(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->delivery_fee - ($this->delivery_fee / 1.15), 2)
        );
    }

    protected function deliveryExcludeVat(): Attribute
    {
        return new Attribute(
            get: fn() => round($this->delivery_fee - $this->deliveryVat, 2)
        );
    }

    protected function customerBranchDistance(): Attribute
    {
        $lat = $this?->address?->latitude ?? $this->client_lat;
        $lng = $this?->address?->longitude ?? $this->client_long;
        $regex = '/^[-]?((([0-8]?[0-9])(\.(\d{1,20}))?)|(90(\.0+)?))$/';
        if (!preg_match($regex, $lat) || !preg_match($regex, $lng)) {
            return new Attribute(
                get: fn() => 0 . " km"
            );
        }
        $distance = $this->branch->getDistanceTo([$lat, $lng])->first()?->distance;
        return new Attribute(
            get: fn() => ($distance) ? round($distance / 1000000, 2) . " km" : 0 . " km"
        );
    }

    protected function driverBranchDistance(): Attribute
    {
        if (!$this->driver_id) {
            return new Attribute(
                get: fn() => 0 . " km"
            );
        }
        $lat = $this->driver?->location?->latitude;
        $lng = $this->driver?->location?->longitude;
        $regex = '/^[-]?((([0-8]?[0-9])(\.(\d{1,20}))?)|(90(\.0+)?))$/';
        if (!preg_match($regex, $lat) || !preg_match($regex, $lng)) {
            return new Attribute(
                get: fn() => 0 . " km"
            );
        }
        $distance = $this->branch->getDistanceTo([$lat, $lng])->first()?->distance;
        return new Attribute(
            get: fn() => ($distance) ? round($distance / 1000000, 2) . " km" : 0 . " km"
        );
    }
}
