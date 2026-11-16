<div>
    <div class="modal fade" id="showOrderDetailsModal" wire:ignore.self
         wire:poll.1s.visible="refreshPaymentStatus"
         data-bs-keyboard="false" data-bs-backdrop="static" tabindex="-1" aria-labelledby="showOrderDetailsModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="showOrderDetailsModalLabel">
                        {{ __('driver::home.Order Details') }} -
                        <span class="text-primary">#{{ $order['code'] ?? '' }}</span>
                        @if(!empty($order['status_value']))
                            <span class="ms-2 badge {{ $order['status_color'] }}">{{ $order['status_trans'] }}</span>
                        @endif
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                @if(!empty($order))
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Product Details') }}</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table text-nowrap align-middle mb-0">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('driver::home.item') }}</th>
                                                        <th class="text-center">{{ __('driver::home.qty') }}</th>
                                                        <th class="text-center">{{ __('driver::home.Price') }}</th>
                                                        <th class="text-end">{{ __('driver::home.Total Price') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($items as $item)
                                                        <tr>
                                                            <td>
                                                                <div class="fs-14 fw-semibold">{{ $item['item_name'] }}</div>
                                                                @if(!empty($item['addons']))
                                                                    <span class="text-info fs-12">{{ $item['addons'] }}</span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center">{{ $item['qty'] }}</td>
                                                            <td class="text-center">{{ number_format($item['price'], 2) }} {{ __('driver::home.SAR') }}</td>
                                                            <td class="text-end">{{ number_format($item['total'], 2) }} {{ __('driver::home.SAR') }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="text-center text-muted py-3">-</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="p-3 border-top border-block-end-dashed">
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.Sub Total') }}:</span>
                                                <span>{{ number_format($order['subtotal'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.Delivery Fee') }}:</span>
                                                <span>{{ number_format($order['delivery_fee'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-1">
                                                <span class="fw-semibold">{{ __('driver::home.VAT') }}:</span>
                                                <span>{{ number_format($order['vat'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                            @if(!empty($order['discount']))
                                                <div class="d-flex justify-content-between mb-1">
                                                    <span class="fw-semibold">{{ __('driver::home.Discount') }}:</span>
                                                    <span>-{{ number_format($order['discount'], 2) }} {{ __('driver::home.SAR') }}</span>
                                                </div>
                                            @endif
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-semibold">{{ __('driver::home.Total Price') }}:</span>
                                                <span class="fw-semibold text-primary">{{ number_format($order['total_after_vat'] ?? 0, 2) }} {{ __('driver::home.SAR') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.User Details') }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if(!empty($order['user_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.userName') }} : </span>{{ $order['user_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['user_phone']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Phone Number') }} : </span>{{ $order['user_phone'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['client_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Client Name') }} : </span>{{ $order['client_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['client_phone']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Client Phone') }} : </span>{{ $order['client_phone'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['address']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.address') }} : </span>
                                                <a href="{{ $order['map_url'] }}" target="_blank" rel="noopener" style="color:#0b5ee7">{{ $order['address'] }}</a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Delivery Details') }}</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        @if(!empty($order['merchant_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Company Name') }} : </span>{{ $order['merchant_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['branch_name']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Branch Name') }} : </span>{{ $order['branch_name'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['delivery_distance']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Distance') }} : </span>{{ $order['delivery_distance'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['order_type']))
                                            <p class="mb-2">
                                                <span class="fw-semibold">{{ __('driver::home.Type') }} : </span>{{ \Illuminate\Support\Facades\Lang::has('admin::home.' . $order['order_type']) ? __('admin::home.' . $order['order_type']) : $order['order_type'] }}
                                            </p>
                                        @endif
                                        <p class="mb-2">
                                            <span class="fw-semibold">{{ __('driver::home.Payment Method') }} : </span>{{ $order['payment_method'] ?? '-' }}
                                        </p>
                                        <p class="mb-2">
                                            <span class="fw-semibold">{{ __('ecommerce::ecommerce.payment_status') }} : </span>{{ $order['payment_label'] ?? '-' }}
                                        </p>
                                        @if(!empty($order['note']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.Order Note') }} : </span>{{ $order['note'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            @if(\App\Domain\MarketPlace\Models\MerchantOrder::isCashOnDeliveryMethod($order['payment_method'] ?? null)
                                && ($order['status_value'] ?? null) === \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value)
                                <div class="col-12">
                                    <div class="card custom-card shadow-none mb-0">
                                        <div class="card-header bg-white">
                                            <h6 class="card-title fw-semibold mb-0">بيانات التحصيل والتوريد</h6>
                                        </div>
                                        <div class="card-body">
                                            @if(!empty($order['settlement_status']))
                                                <p class="mb-1">
                                                    <span class="fw-semibold">حالة الدفع:</span>
                                                    <span class="badge bg-success">{{ __('ecommerce::ecommerce.paid') }}</span>
                                                </p>
                                                <p class="mb-1">
                                                    <span class="fw-semibold">مكان المبلغ:</span> {{ $order['settlement_custody'] ?? 'لدى السائق' }}
                                                    ({{ number_format($order['total_after_vat'] ?? 0, 2) }} SAR)
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fw-semibold">حالة التوريد:</span>
                                                    @lang('driver::home.settlement_status_' . $order['settlement_status'])
                                                </p>
                                                @if(!empty($order['settlement_id']))
                                                    <p class="mb-0"><span class="fw-semibold">رقم التسوية:</span> STL-{{ $order['settlement_id'] }}</p>
                                                @endif
                                                @foreach($order['settlement_history'] ?? [] as $history)
                                                    @if($history['note'])
                                                        <div class="alert alert-warning py-2 mt-2 mb-0">
                                                            <strong>@lang('driver::home.settlement_action_' . $history['action']):</strong>
                                                            {{ $history['note'] }}
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(($order['payment_status'] ?? null) === 'paid' && empty($order['settlement_status']))
                                                <p class="mb-1">
                                                    <span class="fw-semibold">حالة الدفع:</span>
                                                    <span class="badge bg-success">{{ __('ecommerce::ecommerce.paid') }}</span>
                                                </p>
                                                <p class="mb-0">
                                                    <span class="fw-semibold">مكان المبلغ:</span>
                                                    {{ $order['payment_source_name'] ?? 'تم تسجيله مسبقًا' }}
                                                </p>
                                            @endif
                                            @if(empty($order['settlement_status']) || in_array($order['settlement_status'], ['returned', 'rejected'], true))
                                                @if(($order['payment_status'] ?? null) === 'paid' && empty($order['settlement_status']))
                                                    <p class="text-warning mb-0">تم تسجيل التحصيل مسبقًا ولا يمكن إنشاء توريد جديد من عهدة السائق.</p>
                                                @elseif(!empty($order['collection_config']['enabled_methods']))
                                                <p class="text-muted">
                                                    @if(($order['settlement_status'] ?? null) === 'returned')
                                                        استكمل ملاحظات الإدارة وأعد إرسال التسوية نفسها (STL-{{ $order['settlement_id'] }}).
                                                    @elseif(($order['settlement_status'] ?? null) === 'rejected')
                                                        أُتيحت التحصيلات لطلب توريد جديد؛ سيُحفظ كسجل تسوية جديد دون تكرار التحصيل.
                                                    @else
                                                        سجّل توريد كامل قيمة الطلب ({{ number_format($order['total_after_vat'] ?? 0, 2) }} SAR). تسجيل التحصيل لا يعني اعتماد التوريد.
                                                    @endif
                                                </p>
                                                <form wire:submit.prevent="submitSettlement">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="form-label">وسيلة التوريد</label>
                                                            <select class="form-select" wire:model="settlementMethod">
                                                                @foreach($order['collection_config']['enabled_methods'] as $method)
                                                                    <option value="{{ $method }}">{{ ['cash' => 'نقد', 'transfer' => 'تحويل', 'deposit' => 'إيداع'][$method] ?? $method }}</option>
                                                                @endforeach
                                                            </select>
                                                            @error('settlementMethod') <span class="text-danger">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="form-label">جهة الاستلام</label>
                                                            <div class="form-control bg-light">
                                                                {{ $order['collection_config']['recipient'] === 'merchant' ? 'التاجر' : 'المنصة' }}
                                                            </div>
                                                        </div>
                                                        @if(in_array(($order['settlement_status'] ?? null), ['returned', 'rejected'], true) && !in_array($settlementMethod, ['transfer', 'deposit'], true))
                                                            <div class="col-md-6">
                                                                <label class="form-label">رقم المرجع</label>
                                                                <input class="form-control" wire:model.defer="settlementReference" maxlength="255">
                                                            </div>
                                                        @endif
                                                        @if(in_array($settlementMethod, ['transfer', 'deposit'], true))
                                                            <div class="col-md-6">
                                                                <label class="form-label">رقم المرجع <span class="text-danger">*</span></label>
                                                                <input class="form-control" wire:model.defer="settlementReference" maxlength="255" required>
                                                                @error('settlementReference') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label class="form-label">إثبات التوريد (صورة أو PDF) <span class="text-danger">*</span></label>
                                                                <input class="form-control" type="file" wire:model="settlementProof" accept=".jpg,.jpeg,.png,.pdf" required>
                                                                @error('settlementProof') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                                                        @endif
                                                        <div class="col-12">
                                                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="submitSettlement,settlementProof">
                                                                {{ ($order['settlement_status'] ?? null) === 'returned' ? 'إعادة إرسال التسوية' : 'تسجيل التحصيل وإرسال التوريد' }}
                                                            </button>
                                                            <span wire:loading wire:target="settlementProof" class="text-muted ms-2">جارٍ رفع الإثبات...</span>
                                                        </div>
                                                    </div>
                                                    @php($settlementMethodData = $order['collection_config']['methods'][$settlementMethod] ?? [])
                                                    @if($settlementMethodData)
                                                        <div class="alert alert-info mt-3 mb-0">
                                                            <div><strong>جهة الاستلام:</strong> {{ $settlementMethodData['recipient_name'] ?? '' }}</div>
                                                            <div>{{ $settlementMethodData['instructions'] ?? '' }}</div>
                                                            @if(!empty($settlementMethodData['account_data']))
                                                                <div class="mt-1">{{ $settlementMethodData['account_data'] }}</div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </form>
                                                @else
                                                <p class="text-danger mb-0">لم تُحفظ وسائل توريد مفعّلة لهذا الطلب؛ يرجى التواصل مع الإدارة.</p>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-12">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="card-header bg-white">
                                        <h6 class="card-title fw-semibold mb-0">{{ __('driver::home.Order Summary') }}</h6>
                                    </div>
                                    <div class="card-body p-3 d-flex flex-wrap gap-3">
                                        <p class="mb-0">
                                            <span class="fw-semibold">{{ __('driver::home.Ordered Date') }} : </span>{{ $order['created_at'] ?? '-' }}
                                        </p>
                                        @if(!empty($order['from']) && !empty($order['to']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.From') }} : </span>{{ $order['from'] }}
                                                <span class="fw-semibold ms-2">{{ __('driver::home.To') }} : </span>{{ $order['to'] }}
                                            </p>
                                        @endif
                                        @if(!empty($order['schedulable']) && !empty($order['date']))
                                            <p class="mb-0">
                                                <span class="fw-semibold">{{ __('driver::home.Ordered Date') }} (جدولة) : </span>{{ $order['date'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="modal-body">
                        <p class="text-center text-muted mb-0">-</p>
                    </div>
                @endif

                <div class="modal-footer">
                    @if(in_array($order['status_value'] ?? null, ['pending', 'upcoming']))
                        <form action="{{ route('driver:orders.accept', $order['id']) }}" method="POST" class="d-inline" id="acceptOrderForm">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="ri-check-line me-1"></i> قبول الطلب
                            </button>
                        </form>
                    @endif
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                            style="border-color: var(--input-border); color: #6c757d; background-color: transparent;">
                        {{ __('driver::home.Close') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>