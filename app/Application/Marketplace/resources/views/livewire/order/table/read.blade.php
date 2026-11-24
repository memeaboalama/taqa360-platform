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
    $serviceName = $order->type_service?->name;
    $requiresDriver = $serviceName !== 'without_delivery' && (string) $order->service_type_id !== '2';
    if ($order->driver) {
        $driverAssignmentLabel = 'تم تعيين السائق';
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
                'بانتظار قبول سائق',
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

    $paymentStatus = $paymentLedger['payment_status'] ?? 'unpaid';
    $paymentBadge = match ($paymentStatus) {
        'paid' => 'od-badge-green',
        'pending', 'initiated', 'partially_paid' => 'od-badge-amber',
        'failed', 'refunded', 'partially_refunded' => 'od-badge-red',
        default => 'od-badge-gray',
    };
    $paymentLabel = $paymentLedger['payment_label'] ?? $paymentStatus;
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
        : $orderAddressSnapshot;
    $addressParts = collect([
        $address?->address,
        $address?->distract?->title,
        $address?->city?->title,
        $address?->country?->title,
        $snapshotAddressText,
    ])->filter(fn ($part) => is_string($part) && trim($part) !== '')
        ->unique()
        ->values();
    $addressText = $addressParts->isNotEmpty() ? $addressParts->implode('، ') : null;
    $latitude = $address?->latitude ?: $order->client_lat;
    $longitude = $address?->longitude ?: $order->client_long;
    $mapUrl = $latitude !== null && $latitude !== '' && $longitude !== null && $longitude !== ''
        ? 'https://www.google.com/maps?q=' . rawurlencode($latitude . ',' . $longitude)
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
        ['label' => 'جاري التوصيل', 'actions' => ['driver-accept', 'driver-on-way']],
        ['label' => 'تم التسليم', 'actions' => ['completed-order', 'employee-completed-order']],
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
    $paymentMethod = ($paymentLedger['payment_method'] ?? $order->payment_method) ?: '—';
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
            <a href="{{ route('marketplace:orders') }}" class="od-btn">
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
                    {{ $order->driver ? 'تم إسناد السائق، بانتظار قبوله' : 'بانتظار سائق' }}
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
                    <h2><i class="ri-shopping-bag-3-line"></i> المنتجات</h2>
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
                @if($order->note)
                    <div class="od-client-note">
                        <strong><i class="ri-sticky-note-line"></i> ملاحظة العميل</strong>
                        <span>{{ $order->note }}</span>
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

                    <div class="od-detail-label">عنوان التوصيل</div>
                    <div class="od-map-link">
                        <span>
                            <i class="ri-map-pin-2-line"></i>
                            {{ $addressText ?: 'لم يتم حفظ عنوان توصيل لهذا الطلب.' }}
                        </span>
                        @if($mapUrl)
                            <a href="{{ $mapUrl }}" target="_blank" rel="noopener noreferrer">عرض الخريطة</a>
                        @endif
                    </div>
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

            @include('ecommerce::components.order-payment-ledger', [
                'paymentLedger' => $paymentLedger,
                'allowCorrection' => false,
                'financialReportUrl' => route('marketplace:finance.reports.orders.index', ['code' => $order->code]),
            ])

            <section class="od-card">
                <header class="od-card-head">
                    <h2><i class="ri-time-line"></i> سجل إجراءات الطلب</h2>
                    <span class="od-head-count">{{ $orderHistory->count() }}</span>
                </header>
                <div class="od-card-body">
                    @if($order->admin_note)
                        <div class="od-client-note mb-3">
                            <strong><i class="ri-shield-user-line"></i> ملاحظة الإدارة</strong>
                            <span>{{ $order->admin_note }}</span>
                        </div>
                    @endif
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
                    <h2><i class="ri-user-3-line"></i> بيانات العميل</h2>
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
                    <h2><i class="ri-e-bike-2-line"></i> السائق</h2>
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
                    <h2><i class="ri-bank-card-line"></i> طريقة الدفع</h2>
                    <span class="od-badge {{ $paymentBadge }}">{{ $paymentLabel }}</span>
                </header>
                <div class="od-card-body">
                    <div class="od-detail-row">
                        <i class="ri-wallet-3-line"></i>
                        <strong>{{ $paymentMethod }}</strong>
                    </div>
                    <div class="od-detail-row">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>المبلغ المتبقي: {{ number_format((float) ($paymentLedger['remaining'] ?? $dueAmount), 2) }} ر.س</span>
                    </div>
                </div>
            </section>
        </aside>
    </div>

    <livewire:marketplace::order.modal.cancel />
</div>
