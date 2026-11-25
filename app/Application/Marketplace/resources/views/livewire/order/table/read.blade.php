@php
    $statusEnum = $order->status instanceof \App\Application\Marketplace\Enums\MerchantOrderStatuses
        ? $order->status
        : \App\Application\Marketplace\Enums\MerchantOrderStatuses::tryFrom((string) $order->status);
    $statusVal = $statusEnum?->value ?? (string) $order->status;
    $statusLabel = $statusEnum?->trans() ?? str_replace('-', ' ', $statusVal);
    $statusBadge = match ($statusVal) {
        'completed', 'confirmed' => 'od-badge-green',
        'ready', 'on-way' => 'od-badge-blue',
        'cancelled', 'expired' => 'od-badge-red',
        'pending', 'started-work', 'will-be-late', 'draft' => 'od-badge-amber',
        default => 'od-badge-gray',
    };
    $paymentStatus = $paymentLedger['payment_status'] ?? 'unpaid';
    $paymentBadge = match ($paymentStatus) {
        'paid' => 'od-badge-green',
        'pending', 'initiated', 'partially_paid' => 'od-badge-amber',
        'failed', 'refunded', 'partially_refunded' => 'od-badge-red',
        default => 'od-badge-gray',
    };
    $paymentLabel = $paymentLedger['payment_label'] ?? $paymentStatus;
    $serviceName = $order->type_service?->name;
    $requiresDriver = $serviceName !== 'without_delivery' && (string) $order->service_type_id !== '2';
    $operationalStatusLabel = $order->operational_status_label;
    if ($order->driver) {
        $driverAssignmentLabel = $statusVal === 'confirmed'
            ? $operationalStatusLabel
            : 'تم تعيين السائق';
        $driverUnassignedMessage = 'تم ربط الطلب بالسائق ' . $order->driver->name . '.';
        $driverAssignmentBadge = 'od-badge-green';
    } elseif (!$requiresDriver) {
        $driverAssignmentLabel = 'لا يلزم تعيين سائق';
        $driverUnassignedMessage = 'هذا الطلب مخصص للاستلام من المتجر.';
        $driverAssignmentBadge = 'od-badge-gray';
    } else {
        [$driverAssignmentLabel, $driverUnassignedMessage, $driverAssignmentBadge] = match ($statusVal) {
            'draft', 'pending' => [
                'بانتظار قبول الطلب',
                'يبدأ البحث عن سائق بعد قبول الطلب.',
                'od-badge-amber',
            ],
            'confirmed' => [
                $operationalStatusLabel,
                'تم قبول الطلب، ويجري إرساله للسائقين بانتظار قبول أحدهم.',
                'od-badge-amber',
            ],
            'started-work' => [
                'بانتظار سائق',
                'الطلب قيد التجهيز، ولم يقبل أي سائق الطلب بعد.',
                'od-badge-amber',
            ],
            'ready' => [
                'بانتظار السائق',
                'الطلب جاهز، ولم يُسجل سائق مسند إليه بعد.',
                'od-badge-amber',
            ],
            'on-way', 'will-be-late', 'completed' => [
                'تحقق من إسناد السائق',
                'حالة التوصيل متقدمة، لكن لا يوجد سائق مرتبط بالطلب.',
                'od-badge-red',
            ],
            'cancelled', 'expired' => [
                'لا يلزم تعيين سائق',
                'الطلب ملغي أو منتهي.',
                'od-badge-gray',
            ],
            default => [
                'لا يوجد سائق مسند',
                'لم يتم تسجيل سائق مرتبط بهذا الطلب.',
                'od-badge-gray',
            ],
        };
    }

    $dueAmount = (float) ($order->total_after_discount > 0
        ? $order->total_after_discount
        : $order->total_after_vat);
    $customerName = $orderUser?->name ?: ($order->client_name ?: '—');
    $customerInitial = $customerName !== '—'
        ? \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($customerName, 0, 1))
        : '؟';
    $customerPhone = $orderUser?->phone ?: $order->client_phone;
    $orderAddressSnapshot = $order->addressInOrder;
    if (is_string($orderAddressSnapshot)) {
        $decodedAddress = json_decode($orderAddressSnapshot, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decodedAddress)) {
            $orderAddressSnapshot = $decodedAddress;
        }
    }
    $snapshotAddressText = is_array($orderAddressSnapshot)
        ? (data_get($orderAddressSnapshot, 'address') ?: data_get($orderAddressSnapshot, 'full_address'))
        : (is_object($orderAddressSnapshot)
            ? (data_get($orderAddressSnapshot, 'address') ?: data_get($orderAddressSnapshot, 'full_address'))
            : $orderAddressSnapshot);
    $addressParts = collect([
        $address?->address,
        $address?->district_name,
        $address?->distract?->title,
        $address?->city?->title,
        $address?->country?->title,
        data_get($orderAddressSnapshot, 'district_name'),
        data_get($orderAddressSnapshot, 'city'),
        data_get($orderAddressSnapshot, 'country'),
        $snapshotAddressText,
    ])->filter(fn ($part) => is_string($part) && trim($part) !== '')
        ->unique()
        ->values();
    $addressText = $addressParts->isNotEmpty() ? $addressParts->implode('، ') : null;
    $latitude = $address?->latitude ?? data_get($orderAddressSnapshot, 'latitude') ?? $order->client_lat;
    $longitude = $address?->longitude ?? data_get($orderAddressSnapshot, 'longitude') ?? $order->client_long;
    $mapQuery = is_numeric($latitude) && is_numeric($longitude)
        ? $latitude . ',' . $longitude
        : $addressText;
    $mapUrl = $mapQuery
        ? 'https://www.google.com/maps?q=' . rawurlencode($mapQuery)
        : null;

    $historyLabels = [
        'draft' => 'تم إنشاء المسودة',
        'pending' => 'بانتظار قبول الطلب',
        'create-order' => 'تم استلام الطلب',
        'confirmed' => 'تم تأكيد الطلب',
        'employee-accept' => 'تم قبول الطلب',
        'employee-reject' => 'تم رفض الطلب',
        'start-cooking' => 'بدأ تجهيز الطلب',
        'marked-ready' => 'أصبح الطلب جاهزًا',
        'driver-accept' => 'قبل السائق التوصيل',
        'driver-on-way' => 'بدأ السائق التوصيل',
        'will-be-late' => 'تم الإبلاغ عن تأخير',
        'completed-order' => 'تم تسليم الطلب',
        'employee-completed-order' => 'تم إكمال الطلب',
        'user-cancel' => 'تم إلغاء الطلب',
        'expired' => 'انتهت صلاحية الطلب',
        'driver-assign-manually' => 'تم إسناد سائق للطلب',
    ];
    $historyLabel = fn ($action) => $historyLabels[$action] ?? ucfirst(str_replace('-', ' ', $action));
    $findHistoryEvent = fn (array $actions) => $orderHistory->first(
        fn ($event) => in_array($event->action, $actions, true)
    );
    $timelineIndex = match ($statusVal) {
        'confirmed', 'started-work', 'ready' => 1,
        'on-way', 'will-be-late' => 2,
        'completed' => 3,
        default => 0,
    };
    $timelineSteps = [
        ['label' => 'استلام الطلب', 'actions' => ['create-order', 'pending']],
        ['label' => 'تجهيز الطلب', 'actions' => ['employee-accept', 'start-cooking', 'marked-ready']],
        [
            'label' => $requiresDriver ? 'جاري التوصيل' : 'جاهز للاستلام',
            'actions' => ['driver-accept', 'driver-on-way'],
        ],
        [
            'label' => $requiresDriver ? 'تم التسليم' : 'تم الاستلام',
            'actions' => ['completed-order', 'employee-completed-order'],
        ],
    ];
    foreach ($timelineSteps as $index => $step) {
        $event = $findHistoryEvent($step['actions']);
        $timelineSteps[$index]['time'] = $event?->created_at
            ? $event->created_at->format('H:i')
            : ($index === 0 ? $order->created_at?->format('H:i') : null);
    }
    $serviceLabel = match (true) {
        $serviceName === 'default',
        $serviceName === 'with_delivery',
        in_array((string) $order->service_type_id, ['1', 'with_delivery'], true) => 'توصيل إلى عنوان العميل',
        $serviceName === 'without_delivery',
        in_array((string) $order->service_type_id, ['2', 'without_delivery'], true) => 'استلام من المتجر',
        default => $serviceName ?: 'غير محدد',
    };
    $customerNote = trim((string) ($order->note ?? ''));
    $settlement = $paymentLedger['settlement'] ?? null;
    $isCashOnDelivery = \App\Domain\MarketPlace\Models\MerchantOrder::isCashOnDeliveryMethod($order->payment_method);
    $settlementStatus = $settlement?->status;
    $pendingSettlementAmount = $isCashOnDelivery && in_array($settlementStatus, ['pending_review', 'reviewed'], true)
        ? min((float) $settlement->amount, (float) ($paymentLedger['collected'] ?? 0))
        : 0.0;
    $approvedSettlementAmount = $isCashOnDelivery && $settlementStatus === 'approved'
        ? min((float) ($settlement->received_amount ?? $settlement->amount), (float) ($paymentLedger['collected'] ?? 0))
        : 0.0;
    $unremittedAmount = $isCashOnDelivery
        ? max((float) ($paymentLedger['collected'] ?? 0) - $pendingSettlementAmount - $approvedSettlementAmount, 0)
        : 0.0;
    $settlementStatusLabel = match ($settlementStatus) {
        'pending_review' => 'مقدم للمراجعة',
        'reviewed' => 'قيد المراجعة',
        'approved' => 'توريد معتمد',
        'returned' => 'معاد للتصحيح',
        'rejected' => 'مرفوض',
        default => 'لم يقدم السائق توريدًا بعد',
    };
    $settlementStatusBadge = match ($settlementStatus) {
        'pending_review', 'reviewed' => 'od-badge-amber',
        'approved' => 'od-badge-green',
        'returned', 'rejected' => 'od-badge-red',
        default => 'od-badge-gray',
    };
    $collectionStatusLabel = $isCashOnDelivery
        ? ((float) ($paymentLedger['collected'] ?? 0) > 0
            ? ((float) ($paymentLedger['remaining'] ?? 0) > 0 ? 'تم تحصيل جزء من المبلغ' : 'تم التحصيل')
            : 'بانتظار التحصيل')
        : $paymentLabel;
    $collectionStatusBadge = $isCashOnDelivery
        ? ((float) ($paymentLedger['collected'] ?? 0) > 0 && (float) ($paymentLedger['remaining'] ?? 0) <= 0
            ? 'od-badge-green'
            : 'od-badge-amber')
        : $paymentBadge;
    $remittanceLabel = !$isCashOnDelivery
        ? 'لا ينطبق على الدفع الإلكتروني'
        : match ($settlementStatus) {
            'approved' => 'تم اعتماد التوريد',
            'pending_review' => 'مقدم للمراجعة',
            'reviewed' => 'تمت مراجعة التوريد',
            'returned' => 'أعيد التوريد للسائق للتصحيح',
            'rejected' => 'تم رفض التوريد',
            default => (float) ($paymentLedger['collected'] ?? 0) > 0
                ? 'لم يقدم السائق التوريد بعد'
                : 'لم يسجل تحصيل نقدي بعد',
        };
    $remittanceBadge = match ($settlementStatus) {
        'approved' => 'od-badge-green',
        'pending_review', 'reviewed' => 'od-badge-amber',
        'returned', 'rejected' => 'od-badge-red',
        default => 'od-badge-gray',
    };
    $paymentMovements = $paymentLedger['movements'] ?? [];
    $paymentMovementCount = count($paymentMovements);
@endphp

<div id="order-details-page" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    <div class="od-page-title">
        <div class="od-title-content">
            <div class="od-eyebrow">تفاصيل الطلب</div>
            <div class="od-title-row">
                <h1>#{{ $order->code ?: $order->id }}</h1>
                <span class="od-badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                <span class="od-badge {{ $paymentBadge }}">{{ $paymentLabel }}</span>
            </div>
            <p class="od-subtitle">
                <span>رقم الطلب: {{ $order->id }}</span>
                <span class="mx-1">·</span>
                <span>تاريخ الإنشاء: {{ $order->created_at?->format('Y-m-d H:i') ?: '—' }}</span>
                @if($order->merchant_name)
                    <span class="mx-1">·</span>
                    <span>{{ $order->merchant_name }}</span>
                @endif
            </p>
        </div>
        <div class="od-page-actions no-print">
            <a href="{{ $ordersUrl ?? route('marketplace:orders') }}" class="od-btn">
                <i class="ri-arrow-go-back-line"></i>
                <span>العودة للطلبات</span>
            </a>
        </div>
    </div>

    <section class="od-overview">
        <div class="od-overview-content">
            <span class="od-overview-icon"><i class="ri-file-list-3-line"></i></span>
            <div>
                <h2>ملخص حالة الطلب</h2>
                <p>
                    @switch($statusVal)
                        @case('pending')
                            تم استلام الطلب وبانتظار قبول المتجر؛ يبدأ البحث عن سائق بعد القبول.
                            @break
                        @case('started-work')
                            يجري تجهيز الطلب حاليًا.
                            @break
                        @case('confirmed')
                            تم قبول الطلب، وهو الآن بانتظار قبول سائق.
                            @break
                        @case('ready')
                            الطلب جاهز وبانتظار استلام السائق.
                            @break
                        @case('on-way')
                            الطلب في طريقه إلى العميل.
                            @break
                        @case('completed')
                            تم تسليم الطلب وإكماله.
                            @break
                        @case('cancelled')
                            تم إلغاء هذا الطلب.
                            @break
                        @default
                            حالة الطلب الحالية: {{ $statusLabel }}.
                    @endswitch
                </p>
            </div>
        </div>
        <div class="od-page-actions no-print">
            @if(in_array($statusVal, ['pending', 'started-work', 'confirmed']))
                <button wire:click="$emit('cancelOrder', {{ $order->id }})" class="od-btn od-btn-danger">
                    <i class="ri-close-circle-line"></i>
                    <span>إلغاء الطلب</span>
                </button>
            @endif

            @if($statusVal === 'pending')
                <button wire:click="acceptOrder" class="od-btn od-btn-primary"
                        wire:loading.attr="disabled" wire:target="acceptOrder">
                    <span wire:loading.class="d-none" wire:target="acceptOrder">
                        <i class="ri-check-line"></i>
                        <span>قبول الطلب</span>
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="acceptOrder">
                        جاري قبول الطلب
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            @elseif($statusVal === 'confirmed' && $requiresDriver)
                <span class="od-badge od-badge-amber">
                    <i class="ri-time-line me-1"></i>
                    {{ $operationalStatusLabel }}
                </span>
            @elseif($statusVal === 'confirmed' && !$requiresDriver)
                <button wire:click="startOrder" class="od-btn od-btn-primary"
                        wire:loading.attr="disabled" wire:target="startOrder">
                    <span wire:loading.class="d-none" wire:target="startOrder">
                        <i class="ri-play-line"></i>
                        <span>بدء تجهيز الطلب</span>
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="startOrder">
                        جاري بدء التجهيز
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            @elseif($statusVal === 'started-work' && ($order->driver || !$requiresDriver))
                <button wire:click="markAsReady" class="od-btn od-btn-primary"
                        wire:loading.attr="disabled" wire:target="markAsReady">
                    <span wire:loading.class="d-none" wire:target="markAsReady">
                        <i class="ri-check-line"></i>
                        <span>تغيير الحالة إلى: جاهز للتوصيل</span>
                    </span>
                    <span class="d-none" wire:loading.class.remove="d-none" wire:target="markAsReady">
                        @lang('marketplace::home.Wait')
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            @endif
        </div>
    </section>

    <div class="od-content-grid">
        <main class="od-main-col">
            <section class="od-card">
                <header class="od-card-head">
                    <h2>
                        <i class="ri-shopping-bag-3-line"></i> منتجات الطلب
                        <span class="od-head-count">{{ $order->orderItems->count() }}</span>
                    </h2>
                    <span class="od-store-label">
                        <i class="ri-store-2-line"></i>
                        {{ $order->merchant_name ?: 'المتجر' }}
                    </span>
                </header>
                <div class="od-products-wrap">
                    <table class="od-products">
                        <thead>
                            <tr>
                                <th>المنتج</th>
                                <th>سعر الوحدة</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($order->orderItems as $item)
                                @php
                                    $itemImage = $item->item?->image_path;
                                    $itemUnitPrice = (float) ($item->item_price ?? 0);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="od-product-main">
                                            <span class="od-product-image">
                                                @if($itemImage)
                                                    <img src="{{ $itemImage }}" alt="{{ $item->item_name }}">
                                                @else
                                                    <i class="ri-image-line"></i>
                                                @endif
                                            </span>
                                            <span>
                                                <span class="od-product-name">{{ $item->item_name ?: 'منتج' }}</span>
                                                @if($item->item_id)
                                                    <span class="od-product-id">رقم المنتج: {{ $item->item_id }}</span>
                                                @endif
                                                @foreach($item->orderChoices as $choice)
                                                    <span class="od-addon">
                                                        {{ $choice->group?->title ? $choice->group->title . ': ' : '' }}
                                                        {{ $choice->choice?->title ?: 'إضافة' }}
                                                        @if((float) $choice->price > 0)
                                                            · +{{ number_format((float) $choice->price, 2) }} ر.س
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ number_format($itemUnitPrice, 2) }} ر.س</td>
                                    <td>{{ $item->qty }}</td>
                                    <td><strong>{{ number_format((float) $item->subtotal, 2) }} ر.س</strong></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">لا توجد منتجات مرتبطة بهذا الطلب.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="od-products-foot">
                    <i class="ri-information-line"></i>
                    <span>عدد المنتجات: {{ $order->orderItems->sum('qty') }}</span>
                    <span>·</span>
                    <span>القيم المعروضة محفوظة ضمن تفاصيل الطلب.</span>
                </div>
                @if($customerNote !== '')
                    <div class="od-client-note">
                        <strong><i class="ri-sticky-note-line"></i> ملاحظة العميل</strong>
                        <span>{{ $customerNote }}</span>
                    </div>
                @endif
            </section>

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-route-line"></i> التنفيذ والتوصيل</h2>
                    <span class="od-badge {{ $driverAssignmentBadge }}">{{ $driverAssignmentLabel }}</span>
                </header>
                <div class="od-card-body">
                    @if(in_array($statusVal, ['cancelled', 'expired'], true))
                        <div class="alert alert-danger mb-0">
                            {{ $statusVal === 'cancelled' ? 'تم إلغاء الطلب ولا يوجد مسار تنفيذ نشط.' : 'انتهت صلاحية الطلب.' }}
                        </div>
                    @else
                        <div class="od-timeline">
                            @foreach($timelineSteps as $index => $step)
                                <div class="od-step {{ $index < $timelineIndex ? 'is-done' : '' }} {{ $index === $timelineIndex ? 'is-current' : '' }}">
                                    <span class="od-step-dot">
                                        @if($index < $timelineIndex)
                                            <i class="ri-check-line"></i>
                                        @else
                                            {{ $index + 1 }}
                                        @endif
                                    </span>
                                    <div class="od-step-label">{{ $step['label'] }}</div>
                                    @if($step['time'])
                                        <div class="od-step-time">{{ $step['time'] }}</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($requiresDriver)
                        <div class="od-detail-label">عنوان التوصيل</div>
                        @if($mapQuery)
                            <div class="od-map-frame">
                                <div data-taqa-delivery-map data-query="{{ $mapQuery }}" style="width:100%;height:300px" wire:ignore></div>
                            </div>
                        @else
                            <div class="od-map-placeholder">لم يتم حفظ إحداثيات أو عنوان يمكن عرضه على الخريطة.</div>
                        @endif
                        <div class="od-map-link">
                            <span>
                                <i class="ri-map-pin-2-line"></i>
                                {{ $addressText ?: 'لم يتم حفظ عنوان توصيل لهذا الطلب.' }}
                            </span>
                            @if($mapUrl)
                                <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer">فتح الخريطة</a>
                            @endif
                        </div>
                    @else
                        <div class="od-map-link">
                            <span><i class="ri-store-2-line"></i> {{ $serviceLabel }}</span>
                        </div>
                    @endif
                    <div class="od-row mt-3">
                        <span class="od-muted">نوع الخدمة</span>
                        <strong>{{ $serviceLabel }}</strong>
                    </div>
                    @if($order->delivery_distance)
                        <div class="od-row mt-2">
                            <span class="od-muted">مسافة التوصيل</span>
                            <strong>{{ number_format((float) $order->delivery_distance, 2) }} كم</strong>
                        </div>
                    @endif
                </div>
            </section>

            <section class="od-card od-payment-card">
                <header class="od-card-head">
                    <h2><i class="ri-wallet-3-line"></i> الدفع والتحصيل</h2>
                    <span class="od-badge {{ $collectionStatusBadge }}">{{ $collectionStatusLabel }}</span>
                </header>
                <div class="od-card-body">
                    <div class="od-payment-top">
                        <div class="od-payment-method">
                            <span class="od-payment-method-icon">
                                <i class="{{ $isCashOnDelivery ? 'ri-wallet-3-line' : 'ri-bank-card-line' }}"></i>
                            </span>
                            <span>
                                <span class="od-detail-label">طريقة الدفع</span>
                                <strong>{{ $paymentLedger['payment_method'] ?: '—' }}</strong>
                            </span>
                        </div>
                        <div class="od-payment-links">
                            <a class="od-link" href="#payment-movements">تفاصيل الدفع</a>
                            @if(!empty($financialReportUrl))
                                <a class="od-link" href="{{ $financialReportUrl }}">التقرير المالي</a>
                            @endif
                        </div>
                    </div>

                    <div class="od-payment-numbers">
                        <div>
                            <span>إجمالي الطلب</span>
                            <strong>{{ number_format((float) ($paymentLedger['due'] ?? $dueAmount), 2) }} ر.س</strong>
                        </div>
                        <div class="is-collected">
                            <span>المحصّل</span>
                            <strong>{{ number_format((float) ($paymentLedger['collected'] ?? 0), 2) }} ر.س</strong>
                        </div>
                        <div class="is-balance">
                            <span>المتبقي للتحصيل</span>
                            <strong>{{ number_format((float) ($paymentLedger['remaining'] ?? $dueAmount), 2) }} ر.س</strong>
                        </div>
                        <div>
                            <span>المسترد</span>
                            <strong>{{ number_format((float) ($paymentLedger['refunded'] ?? 0), 2) }} ر.س</strong>
                        </div>
                    </div>

                    <div class="od-payment-meta">
                        <span><i class="ri-time-line"></i> آخر تحصيل</span>
                        <strong>{{ $paymentLedger['collection_date'] ?: 'لم يسجل تحصيل بعد' }}</strong>
                    </div>
                    <div class="od-payment-meta">
                        <span><i class="ri-shield-check-line"></i> التوريد للإدارة</span>
                        <span class="od-badge {{ $remittanceBadge }}">{{ $remittanceLabel }}</span>
                    </div>

                    <div class="od-payment-actions">
                        @if((float) ($paymentLedger['collected'] ?? 0) > 0)
                            <a class="od-btn" href="#payment-movements">
                                <i class="ri-file-list-3-line"></i> إثبات الدفع
                            </a>
                        @else
                            <button type="button" class="od-btn" disabled>
                                <i class="ri-file-list-3-line"></i> إثبات الدفع
                            </button>
                        @endif
                    </div>
                    <p class="od-helper">
                        {{ $isCashOnDelivery
                            ? 'يسجل السائق استلام النقد من لوحته؛ وتظهر هنا حركة التحصيل والمبلغ ووقت التسجيل.'
                            : 'تُعرض هنا حركات الدفع الإلكتروني والتحصيل المسجلة لهذا الطلب.' }}
                    </p>
                </div>

                <div class="od-payment-movements-head" id="payment-movements">
                    <strong>حركات الدفع والتحصيل</strong>
                    <span class="od-head-count">{{ $paymentMovementCount }}</span>
                </div>
                @if($paymentMovementCount > 0)
                    <div class="od-payment-table-wrap">
                        <table class="od-payment-table">
                            <thead>
                                <tr>
                                    <th>رقم الحركة</th>
                                    <th>المبلغ</th>
                                    <th>طريقة الدفع</th>
                                    <th>المحصّل</th>
                                    <th>التاريخ</th>
                                    <th>التوريد</th>
                                    <th>التفاصيل</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($paymentMovements as $movement)
                                    @php
                                        $movementSettlementLabel = match($movement['settlement_status'] ?? null) {
                                            'pending_review' => 'مقدم للمراجعة',
                                            'reviewed' => 'تمت المراجعة',
                                            'approved' => 'معتمد',
                                            'returned' => 'معاد للتصحيح',
                                            'rejected' => 'مرفوض',
                                            default => null,
                                        };
                                    @endphp
                                    <tr>
                                        <td>{{ $movement['number'] ?: '—' }}</td>
                                        <td class="{{ $movement['type'] === 'refund' || (float) $movement['amount'] < 0 ? 'is-refund' : 'is-collection' }}">
                                            @if($movement['type'] === 'refund' && ($movement['refund_status'] ?? null) === 'pending')
                                                {{ number_format((float) ($movement['requested_amount'] ?? 0), 2) }} ر.س
                                                <small>استرداد قيد التنفيذ</small>
                                            @else
                                                {{ number_format((float) $movement['amount'], 2) }} ر.س
                                            @endif
                                        </td>
                                        <td>{{ $movement['method'] ?: '—' }}</td>
                                        <td>{{ $movement['collector'] ?: '—' }}</td>
                                        <td>{{ $movement['occurred_at'] ?: '—' }}</td>
                                        <td>
                                            {{ $movement['settlement_number'] ?: '—' }}
                                            @if($movementSettlementLabel)
                                                <small>{{ $movementSettlementLabel }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $movement['details'] ?: '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="od-payment-empty">
                        <i class="ri-wallet-3-line"></i>
                        <span>لا توجد حركات دفع أو تحصيل مسجلة.</span>
                        @if($isCashOnDelivery)
                            <span>تظهر الحركات بعد تسجيل التحصيل من السائق.</span>
                        @endif
                    </div>
                @endif
            </section>

            @if($adminContext ?? false)
                @if($requiresDriver)
                    <section class="od-card">
                        <header class="od-card-head">
                            <h2><i class="ri-shield-check-line"></i> توريد متحصلات الطلب</h2>
                            <span class="od-badge {{ $settlementStatusBadge }}">{{ $settlementStatusLabel }}</span>
                        </header>
                        <div class="od-card-body">
                            <p class="od-helper od-section-intro">
                                تعرض هذه البطاقة حالة توريد متحصلات الطلب المسجلة من السائق واعتماد الإدارة.
                            </p>
                            <div class="od-settlement-summary">
                                <div>
                                    <span>غير مورد من هذا الطلب</span>
                                    <strong>{{ number_format($unremittedAmount, 2) }} ر.س</strong>
                                </div>
                                <div>
                                    <span>مقدم للمراجعة</span>
                                    <strong>{{ number_format($pendingSettlementAmount, 2) }} ر.س</strong>
                                </div>
                                <div>
                                    <span>توريد معتمد</span>
                                    <strong>{{ number_format($approvedSettlementAmount, 2) }} ر.س</strong>
                                </div>
                            </div>
                            @if(!$isCashOnDelivery)
                                <div class="od-notice">لا تنطبق تسوية عهدة السائق على طريقة الدفع لهذا الطلب.</div>
                            @elseif($settlement)
                                <div class="od-settlement-details">
                                    <div><span>رقم التسوية</span><strong>{{ $paymentLedger['settlement_number'] ?: '—' }}</strong></div>
                                    <div><span>السائق</span><strong>{{ $settlement->driver?->name ?: '—' }}</strong></div>
                                    <div><span>طريقة التوريد</span><strong>{{ ['cash' => 'نقدًا', 'transfer' => 'تحويل', 'deposit' => 'إيداع'][$settlement->method] ?? $settlement->method }}</strong></div>
                                    @if($settlement->reference)
                                        <div><span>المرجع</span><strong>{{ $settlement->reference }}</strong></div>
                                    @endif
                                    @if($settlement->approved_at)
                                        <div><span>تاريخ الاعتماد</span><strong>{{ \Carbon\Carbon::parse($settlement->approved_at)->format('Y-m-d H:i') }}</strong></div>
                                    @endif
                                </div>
                                @if(\App\Domain\MarketPlace\Models\CollectionSetting::adminCan('view'))
                                    <a class="od-link od-settlement-link"
                                       href="{{ route('admin:driver-settlements.show', $settlement->id) }}">
                                        عرض تفاصيل التسوية
                                    </a>
                                @endif
                            @else
                                <div class="od-empty">
                                    {{ $paymentLedger['collected'] > 0
                                        ? 'لم يقدم السائق توريدًا لهذا الطلب بعد.'
                                        : 'لا توجد متحصلات مسجلة لهذا الطلب حتى الآن.' }}
                                </div>
                            @endif
                        </div>
                    </section>
                @endif
            @endif

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-time-line"></i> سجل الإجراءات</h2>
                    <span class="od-head-count">{{ $orderHistory->count() }}</span>
                </header>
                <div class="od-card-body">
                    @forelse($orderHistory->sortByDesc('created_at')->take(8) as $event)
                        <div class="od-history-item {{ !$loop->last ? 'mb-3' : '' }}">
                            <span class="od-history-dot"><i class="ri-check-line"></i></span>
                            <span>
                                <span class="od-history-title">{{ $historyLabel($event->action) }}</span>
                                <span class="od-history-meta">{{ $event->created_at?->format('Y-m-d H:i') ?: '—' }}</span>
                                @if($event->reason)
                                    <span class="od-history-reason">{{ $event->reason }}</span>
                                @endif
                            </span>
                        </div>
                    @empty
                        <div class="od-empty">لا يوجد سجل إجراءات متاح لهذا الطلب.</div>
                    @endforelse
                </div>
            </section>

            @if($adminContext ?? false)
                <section class="od-card">
                    <header class="od-card-head">
                        <h2><i class="ri-lock-line"></i> ملاحظات الإدارة</h2>
                        <span class="od-badge od-badge-gray">داخلية</span>
                    </header>
                    <div class="od-card-body">
                        @if($order->admin_note)
                            <div class="od-internal-note">{{ $order->admin_note }}</div>
                        @else
                            <div class="od-empty">لا توجد ملاحظات إدارية محفوظة لهذا الطلب.</div>
                        @endif
                        <form class="od-note-editor" wire:submit.prevent="saveAdminNote">
                            <textarea class="form-control" wire:model.defer="adminNoteDraft"
                                      maxlength="1500" rows="3"
                                      placeholder="أضف ملاحظة لفريق الإدارة…"
                                      aria-label="ملاحظة إدارية"></textarea>
                            @error('adminNoteDraft')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            <button type="submit" class="od-btn od-btn-primary"
                                    wire:loading.attr="disabled" wire:target="saveAdminNote">
                                <span wire:loading.class="d-none" wire:target="saveAdminNote">
                                    <i class="ri-save-line"></i> حفظ الملاحظة
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="saveAdminNote">
                                    جاري الحفظ
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </form>
                        <p class="od-helper">تظهر هذه الملاحظة لفريق الإدارة فقط.</p>
                    </div>
                </section>
            @endif

        </main>

        <aside class="od-side-col">
            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-calculator-line"></i> ملخص المبلغ</h2>
                </header>
                <div class="od-card-body">
                    <div class="od-amount-row">
                        <span class="od-muted">المجموع الفرعي</span>
                        <span>{{ number_format((float) $order->subtotal, 2) }} ر.س</span>
                    </div>
                    <div class="od-amount-row">
                        <span class="od-muted">ضريبة القيمة المضافة</span>
                        <span>{{ number_format((float) $order->vat, 2) }} ر.س</span>
                    </div>
                    @if((float) $order->discount > 0)
                        <div class="od-amount-row">
                            <span class="od-muted">الخصم</span>
                            <span class="text-success">-{{ number_format((float) $order->discount, 2) }} ر.س</span>
                        </div>
                    @endif
                    <div class="od-amount-row">
                        <span class="od-muted">رسوم التوصيل</span>
                        <span>
                            @if((float) $order->delivery_fee > 0)
                                {{ number_format((float) $order->delivery_fee, 2) }} ر.س
                            @else
                                مجاني
                            @endif
                        </span>
                    </div>
                    @if((float) $order->delivery_fee_discount > 0)
                        <div class="od-amount-row">
                            <span class="od-muted">خصم التوصيل</span>
                            <span class="text-success">-{{ number_format((float) $order->delivery_fee_discount, 2) }} ر.س</span>
                        </div>
                    @endif
                    <div class="od-amount-row od-amount-total">
                        <span>الإجمالي المستحق</span>
                        <span class="od-total-value">{{ number_format($dueAmount, 2) }} ر.س</span>
                    </div>
                    <p class="od-tax-caption">الإجمالي حسب قيمة الطلب المحفوظة.</p>
                    <div class="od-divider"></div>
                    <div class="od-amount-row">
                        <span class="od-muted">المبلغ المحصل</span>
                        <strong>{{ number_format((float) ($paymentLedger['collected'] ?? 0), 2) }} ر.س</strong>
                    </div>
                    <div class="od-amount-row">
                        <span class="od-muted">المبلغ المسترد</span>
                        <strong>{{ number_format((float) ($paymentLedger['refunded'] ?? 0), 2) }} ر.س</strong>
                    </div>
                </div>
            </section>

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-user-3-line"></i> العميل وعنوان التسليم</h2>
                </header>
                <div class="od-card-body">
                    <div class="od-client-box">
                        <span class="od-avatar">{{ $customerInitial }}</span>
                        <span>
                            <span class="od-client-name">{{ $customerName }}</span>
                            <span class="od-product-id">رقم العميل: {{ $order->user_id ?: '—' }}</span>
                        </span>
                    </div>
                    @if($customerPhone)
                        <div class="od-detail-row">
                            <i class="ri-phone-line"></i>
                            <a class="od-link" href="tel:{{ $customerPhone }}" dir="ltr">{{ $customerPhone }}</a>
                        </div>
                    @endif
                    @if($orderUser?->email)
                        <div class="od-detail-row">
                            <i class="ri-mail-line"></i>
                            <span>{{ $orderUser->email }}</span>
                        </div>
                    @endif
                    <div class="od-divider"></div>
                    <div class="od-detail-label">عنوان الشحن المحفوظ في الطلب</div>
                    <p class="od-address">{{ $addressText ?: 'لم يتم حفظ عنوان للطلب.' }}</p>
                    @if($mapUrl)
                        <a class="od-link od-address-link" href="{{ $mapUrl }}" target="_blank"
                           rel="noopener noreferrer">عرض العنوان على الخريطة</a>
                    @endif
                    <div class="od-divider"></div>
                    <div class="od-detail-label">موعد الطلب</div>
                    <div class="od-detail-row">
                        <i class="ri-calendar-line"></i>
                        <span>{{ $order->date ? \Carbon\Carbon::parse($order->date)->format('Y-m-d') : ($order->created_at?->format('Y-m-d') ?: '—') }}</span>
                    </div>
                    @if($order->from || $order->to)
                        <div class="od-detail-row">
                            <i class="ri-time-line"></i>
                            <span>
                                @if($order->from) {{ \Carbon\Carbon::parse($order->from)->format('H:i') }} @endif
                                @if($order->from && $order->to) – @endif
                                @if($order->to) {{ \Carbon\Carbon::parse($order->to)->format('H:i') }} @endif
                            </span>
                        </div>
                    @endif
                </div>
            </section>

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-e-bike-2-line"></i> السائق والإسناد</h2>
                    <span class="od-badge {{ $driverAssignmentBadge }}">{{ $driverAssignmentLabel }}</span>
                </header>
                <div class="od-card-body">
                    @if($order->driver)
                        <div class="od-driver-line">
                            <span class="od-icon-wrap"><i class="ri-user-star-line"></i></span>
                            <span>
                                <span class="od-client-name">{{ $order->driver->name }}</span>
                                <span class="od-product-id">تم إسناده إلى الطلب</span>
                            </span>
                        </div>
                        @if($order->driver->phone)
                            <div class="od-detail-row mt-3">
                                <i class="ri-phone-line"></i>
                                <a class="od-link" href="tel:{{ $order->driver->phone }}" dir="ltr">{{ $order->driver->phone }}</a>
                            </div>
                        @endif
                    @else
                        <div class="od-empty">{{ $driverUnassignedMessage }}</div>
                        @if($requiresDriver && in_array($statusVal, ['confirmed', 'ready'], true))
                            <div class="od-driver-assign">
                                <label class="od-driver-assign-label" for="order-driver-select">
                                    إسناد سائق يدويًا
                                </label>
                                @if($availableDrivers->isNotEmpty())
                                    <div class="od-driver-assign-row">
                                        <select id="order-driver-select" class="form-select form-select-sm od-driver-select"
                                                wire:model.defer="selected_driver_id">
                                            <option value="">اختر السائق</option>
                                            @foreach($availableDrivers as $availableDriver)
                                                <option value="{{ $availableDriver->id }}">
                                                    {{ $availableDriver->name ?: 'سائق' }}
                                                    @if($availableDriver->phone)
                                                        ({{ $availableDriver->phone }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" class="od-btn od-btn-primary"
                                                wire:click="assignDriver"
                                                wire:loading.attr="disabled"
                                                wire:target="assignDriver">
                                            <span wire:loading.class="d-none" wire:target="assignDriver">
                                                <i class="ri-user-add-line"></i>
                                                <span>إسناد السائق</span>
                                            </span>
                                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="assignDriver">
                                                @lang('marketplace::home.Wait')
                                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                @else
                                    <div class="od-empty">لا يوجد سائقون نشطون لإسنادهم حاليًا.</div>
                                @endif
                            </div>
                        @endif
                    @endif
                </div>
            </section>

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-truck-line"></i> طريقة الاستلام</h2>
                </header>
                <div class="od-card-body">
                    <div class="od-detail-row">
                        <i class="{{ $requiresDriver ? 'ri-truck-line' : 'ri-store-2-line' }}"></i>
                        <strong>{{ $serviceLabel }}</strong>
                    </div>
                    <p class="od-helper">طريقة الاستلام المحفوظة مع الطلب.</p>
                </div>
            </section>
        </aside>
    </div>

    @if($adminContext ?? false)
        <livewire:admin::order.modal.cancel-details />
    @else
        <livewire:marketplace::order.modal.cancel />
    @endif
</div>

@push('js')
    @include('shared.maps')
    <script>TaqaMaps.mountDeliveryMaps();</script>
@endpush
