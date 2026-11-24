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
                                && in_array(($order['status_value'] ?? null), [
                                    \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value,
                                    \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value,
                                ], true))
                                <div class="col-12">
                                    <div class="card custom-card shadow-none mb-0">
                                        <div class="card-header bg-white">
                                            <h6 class="card-title fw-semibold mb-0">بيانات التحصيل والتوريد</h6>
                                        </div>
                                        <div class="card-body">
                                            @php($failureReport = $order['collection_failure_report'] ?? null)
                                            @php($collectionDue = ($order['total_after_discount'] ?? 0) > 0 ? $order['total_after_discount'] : ($order['total_after_vat'] ?? 0))
                                            @if(($order['status_value'] ?? null) === \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value
                                                && (empty($failureReport) || $failureReport['status'] === 'collection_succeeded'))
                                                <form class="alert alert-warning mb-3"
                                                      wire:submit.prevent="confirmDeliveryAndCollection">
                                                    <h6 class="fw-semibold">تأكيد التسليم والتحصيل</h6>
                                                    <dl class="row mb-3">
                                                        <dt class="col-sm-4">رقم الطلب</dt>
                                                        <dd class="col-sm-8">#{{ $order['code'] }}</dd>
                                                        <dt class="col-sm-4">طريقة الدفع</dt>
                                                        <dd class="col-sm-8">{{ $order['payment_method'] }}</dd>
                                                        <dt class="col-sm-4">المبلغ المتبقي</dt>
                                                        <dd class="col-sm-8">{{ number_format($order['collection_remaining'] ?? 0, 2) }} SAR</dd>
                                                    </dl>
                                                    <label class="form-check mb-2">
                                                        <input class="form-check-input" type="checkbox"
                                                               wire:model="deliveryAcknowledged">
                                                        <span class="form-check-label">سلّمت الطلب</span>
                                                    </label>
                                                    @if($requiresCollectionConfirmation)
                                                        <label class="form-check mb-3">
                                                            <input class="form-check-input" type="checkbox"
                                                                   wire:model="collectionAcknowledged">
                                                            <span class="form-check-label">حصّلت المبلغ كاملًا</span>
                                                        </label>
                                                    @else
                                                        <div class="text-success mb-3">التحصيل مسجل مسبقًا.</div>
                                                    @endif
                                                    @error('deliveryAcknowledged') <div class="text-danger mb-2">{{ $message }}</div> @enderror
                                                    @error('collectionAcknowledged') <div class="text-danger mb-2">{{ $message }}</div> @enderror
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-success"
                                                                wire:loading.attr="disabled"
                                                                wire:target="confirmDeliveryAndCollection"
                                                                @if(!$deliveryAcknowledged || ($requiresCollectionConfirmation && !$collectionAcknowledged)) disabled @endif>
                                                            <span wire:loading.remove wire:target="confirmDeliveryAndCollection">تأكيد التسليم والتحصيل</span>
                                                            <span wire:loading wire:target="confirmDeliveryAndCollection">جارٍ الحفظ...</span>
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                                data-bs-dismiss="modal">
                                                            إلغاء
                                                        </button>
                                                        @if($requiresCollectionConfirmation && empty($failureReport))
                                                            <button type="button" class="btn btn-outline-warning"
                                                                    wire:click="$set('showCollectionFailureForm', true)">
                                                                تعذر التحصيل
                                                            </button>
                                                        @endif
                                                    </div>
                                                </form>
                                            @endif
                                            @if(empty($order['has_driver_cod_collection'])
                                                && ($order['payment_status'] ?? null) !== 'paid'
                                                && ($order['status_value'] ?? null) === \App\Application\Marketplace\Enums\MerchantOrderStatuses::PREVIOUS->value
                                                && empty($failureReport))
                                                <div class="alert alert-warning">
                                                    <div class="fw-semibold">لم يُسجل تحصيل المبلغ بعد.</div>
                                                    <div class="mb-2">أكد استلام {{ number_format($collectionDue, 2) }} SAR من العميل أولًا. تأكيد التحصيل مستقل عن إرسال التوريد.</div>
                                                    <button type="button" class="btn btn-success btn-sm"
                                                            wire:click="confirmCashCollection" wire:loading.attr="disabled"
                                                            wire:target="confirmCashCollection">
                                                        تأكيد استلام المبلغ
                                                    </button>
                                                </div>
                                            @endif
                                            @if($failureReport)
                                                <div class="alert alert-info">
                                                    <strong>@lang('driver::home.collection_failure_report')</strong>
                                                    <div>@lang('driver::home.collection_failure_status'): @lang('driver::home.collection_failure_status_' . $failureReport['status'])</div>
                                                    @php($failureReasonKey = 'driver::home.collection_failure_reason_' . $failureReport['reason'])
                                                    <div>@lang('driver::home.collection_failure_reason'): {{ \Illuminate\Support\Facades\Lang::has($failureReasonKey) ? __($failureReasonKey) : $failureReport['reason'] }}</div>
                                                    @if(!empty($failureReport['admin_instructions']))
                                                        <div class="mt-1"><strong>@lang('driver::home.collection_failure_instructions'):</strong> {{ $failureReport['admin_instructions'] }}</div>
                                                    @endif
                                                    @foreach($failureReport['histories'] ?? [] as $history)
                                                        <small class="d-block text-muted">
                                                            @lang('driver::home.collection_failure_action_' . $history['action'])
                                                            · {{ $history['actor']['name'] ?? '—' }} · {{ $history['created_at'] ?? '' }}
                                                        </small>
                                                        @if($history['note'])
                                                            <small class="d-block">{{ $history['note'] }}</small>
                                                        @endif
                                                    @endforeach
                                                    @if($failureReport['status'] === 'retry_requested')
                                                        <button type="button" class="btn btn-success btn-sm mt-2"
                                                                wire:click="confirmRetryCollection" wire:loading.attr="disabled">
                                                            @lang('driver::home.confirm_collection_retry')
                                                        </button>
                                                        <div class="mt-2">
                                                            <textarea class="form-control mb-2" wire:model.defer="retryFailureNote"
                                                                      maxlength="2000" placeholder="@lang('driver::home.retry_failure_note')"></textarea>
                                                            @error('retryFailureNote') <span class="text-danger">{{ $message }}</span> @enderror
                                                            <button type="button" class="btn btn-outline-warning btn-sm"
                                                                    wire:click="reportRetryFailure" wire:loading.attr="disabled">
                                                                @lang('driver::home.report_retry_failed')
                                                            </button>
                                                        </div>
                                                    @elseif($failureReport['status'] === 'return_requested')
                                                        <button type="button" class="btn btn-warning btn-sm mt-2"
                                                                wire:click="confirmReturnCompleted" wire:loading.attr="disabled">
                                                            @lang('driver::home.confirm_return_completed')
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif

                                            @if(($order['status_value'] ?? null) === \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value
                                                && ($order['payment_status'] ?? null) !== 'paid'
                                                && empty($order['has_driver_cod_collection'])
                                                && empty($failureReport)
                                                && $showCollectionFailureForm)
                                                <form class="border rounded p-3 mb-3" wire:submit.prevent="reportCollectionFailure">
                                                    <h6>@lang('driver::home.report_collection_failure')</h6>
                                                    <dl class="row mb-3">
                                                        <dt class="col-sm-4">@lang('driver::home.order_number')</dt>
                                                        <dd class="col-sm-8">#{{ $order['code'] }}</dd>
                                                        <dt class="col-sm-4">@lang('driver::home.amount_due')</dt>
                                                        <dd class="col-sm-8">{{ number_format($order['collection_remaining'] ?? 0, 2) }} SAR</dd>
                                                    </dl>
                                                    <div class="alert alert-info py-2">
                                                        @lang('driver::home.collection_failure_partial_payment_notice')
                                                    </div>
                                                    <label class="form-label">@lang('driver::home.collection_failure_reason')</label>
                                                    <select class="form-select mb-2" wire:model="collectionFailureReason" required>
                                                        <option value="">@lang('driver::home.select_collection_failure_reason')</option>
                                                        <option value="payment_refused">@lang('driver::home.collection_failure_reason_payment_refused')</option>
                                                        <option value="insufficient_amount">@lang('driver::home.collection_failure_reason_insufficient_amount')</option>
                                                        <option value="unable_to_contact">@lang('driver::home.collection_failure_reason_unable_to_contact')</option>
                                                        <option value="other">@lang('driver::home.collection_failure_reason_other')</option>
                                                    </select>
                                                    @error('collectionFailureReason') <span class="text-danger">{{ $message }}</span> @enderror
                                                    <label class="form-label">@lang('driver::home.collection_failure_note')</label>
                                                    <textarea class="form-control mb-2" wire:model.defer="collectionFailureNote"
                                                              maxlength="2000"
                                                              @if($collectionFailureReason === 'other') required @endif></textarea>
                                                    @error('collectionFailureNote') <span class="text-danger">{{ $message }}</span> @enderror
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-warning"
                                                                wire:loading.attr="disabled" wire:target="reportCollectionFailure">
                                                            @lang('driver::home.submit_collection_failure_report')
                                                        </button>
                                                        <button type="button" class="btn btn-outline-secondary"
                                                                wire:click="cancelCollectionFailure">
                                                            @lang('driver::home.cancel')
                                                        </button>
                                                    </div>
                                                </form>
                                            @endif
                                            @if(!empty($order['settlement_status']))
                                                <p class="mb-1">
                                                    <span class="fw-semibold">حالة الدفع:</span>
                                                    <span class="badge {{ ($order['payment_status'] ?? null) === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                                        {{ $order['payment_label'] ?? __('ecommerce::ecommerce.pending') }}
                                                    </span>
                                                </p>
                                                <p class="mb-1">
                                                    <span class="fw-semibold">مكان المبلغ:</span> {{ $order['settlement_custody'] ?? 'لدى السائق' }}
                                                    ({{ number_format($collectionDue, 2) }} SAR)
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
                                            @if(!empty($order['has_driver_cod_collection'])
                                                && ($order['payment_status'] ?? null) !== 'paid')
                                                <p class="text-warning mb-0">تم تعديل قيمة التحصيل؛ أوقف التوريد حتى مراجعة التصحيح ومطابقة الرصيد.</p>
                                            @endif
                                            @if((empty($order['settlement_status']) || in_array($order['settlement_status'], ['returned', 'rejected'], true))
                                                && (empty($order['has_driver_cod_collection']) || ($order['payment_status'] ?? null) === 'paid')
                                                && (empty($failureReport) || in_array($failureReport['status'], ['collection_succeeded'], true) || in_array($order['settlement_status'] ?? null, ['returned', 'rejected'], true)))
                                                @if(($order['payment_status'] ?? null) === 'paid' && empty($order['settlement_status']) && empty($order['driver_cod_collection']))
                                                    <p class="text-warning mb-0">تم تسجيل التحصيل مسبقًا ولا يمكن إنشاء توريد جديد من عهدة السائق.</p>
                                                @elseif(!empty($order['collection_config']['enabled_methods']))
                                                <p class="text-muted">
                                                    @if(($order['settlement_status'] ?? null) === 'returned')
                                                        استكمل ملاحظات الإدارة وأعد إرسال التسوية نفسها (STL-{{ $order['settlement_id'] }}).
                                                    @elseif(($order['settlement_status'] ?? null) === 'rejected')
                                                        أُتيحت التحصيلات لطلب توريد جديد؛ سيُحفظ كسجل تسوية جديد دون تكرار التحصيل.
                                                    @else
                                                        سجّل توريد كامل قيمة الطلب ({{ number_format($collectionDue, 2) }} SAR). تسجيل التحصيل لا يعني اعتماد التوريد.
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
                                                                {{ ($order['settlement_status'] ?? null) === 'returned' ? 'إعادة إرسال التسوية' : 'إرسال التوريد للمراجعة' }}
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

                            @if(!\App\Domain\MarketPlace\Models\MerchantOrder::isCashOnDeliveryMethod($order['payment_method'] ?? null)
                                && ($order['payment_status'] ?? null) === 'paid'
                                && ($order['status_value'] ?? null) === \App\Application\Marketplace\Enums\MerchantOrderStatuses::ON_WAY->value)
                                <div class="col-12">
                                    <div class="card custom-card shadow-none mb-0">
                                        <div class="card-header bg-white">
                                            <h6 class="card-title fw-semibold mb-0">تسليم الطلب المدفوع مسبقًا</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="alert alert-info">
                                                <div class="fw-semibold">المطلوب تحصيله من العميل: 0.00 ريال</div>
                                            </div>
                                            <form class="alert alert-light border mb-0" wire:submit.prevent="confirmPrepaidDelivery">
                                                <dl class="row mb-3">
                                                    <dt class="col-sm-4">رقم الطلب</dt>
                                                    <dd class="col-sm-8">#{{ $order['code'] }}</dd>
                                                    <dt class="col-sm-4">المبلغ المدفوع</dt>
                                                    <dd class="col-sm-8">{{ number_format($order['prepaid_amount'] ?? 0, 2) }} SAR</dd>
                                                    <dt class="col-sm-4">طريقة الدفع</dt>
                                                    <dd class="col-sm-8">{{ $order['prepaid_method'] ?: $order['payment_method'] }}</dd>
                                                    @if(!empty($order['prepaid_reference']))
                                                        <dt class="col-sm-4">مرجع الدفع</dt>
                                                        <dd class="col-sm-8">{{ $order['prepaid_reference'] }}</dd>
                                                    @endif
                                                </dl>
                                                <label class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" wire:model="deliveryAcknowledged">
                                                    <span class="form-check-label">سلّمت الطلب</span>
                                                </label>
                                                @error('deliveryAcknowledged') <div class="text-danger mb-2">{{ $message }}</div> @enderror
                                                <div class="d-flex gap-2">
                                                    <button type="submit" class="btn btn-success"
                                                            wire:loading.attr="disabled"
                                                            wire:target="confirmPrepaidDelivery"
                                                            @if(!$deliveryAcknowledged) disabled @endif>
                                                        <span wire:loading.remove wire:target="confirmPrepaidDelivery">تأكيد التسليم</span>
                                                        <span wire:loading wire:target="confirmPrepaidDelivery">جارٍ الحفظ...</span>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                        إلغاء
                                                    </button>
                                                </div>
                                            </form>
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
                    @if(in_array($order['status_value'] ?? null, [
                        \App\Application\Marketplace\Enums\MerchantOrderStatuses::PENDING->value,
                        \App\Application\Marketplace\Enums\MerchantOrderStatuses::UPCOMING->value,
                        \App\Application\Marketplace\Enums\MerchantOrderStatuses::READY->value,
                    ], true) && empty($order['is_accepted_by_driver']))
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
