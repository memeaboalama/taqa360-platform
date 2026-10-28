<div>
    <div class="modal fade" id="createRefundPayment" wire:ignore.self
         data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="staticBackdropLabel"> @lang('admin::home.Create new refundpayment')</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                </div>

                <form wire:submit.prevent="store" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row gx-5">
                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-6">
                                <div class="card custom-card shadow-none mb-0">
                                    <div class="row p-3">
                                        
                                    <div class="col-xl-12">
                                    <button class="btn btn-outline-info rounded-pill btn-wave waves-effect waves-light dropdown-toggle" type="button" id="dropdownMenu2"
                data-bs-toggle="dropdown" aria-expanded="false">
            {{ __('admin::home.SelectOrder') }}
        </button>

        <div class="dropdown-menu" wire:ignore.self>
            <div class="row gy-3">
                <livewire:admin::refund-payment.search.order />
            </div>
            <div class="dropdown-divider"></div>
        </div>
                                    </div>
                                            <div class="col-xl-6">
                                                <label for="order_code" class="form-label text-black">{{__('admin::home.Order No')}}</label>
                                                <input type="text" class="form-control" id="order_code"
                                                       placeholder="OrderId" wire:model.defer="order_code" disabled>
                                                @error('order_code') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            <div class="col-xl-6">
                                                <label for="payment_id"
                                                       class="form-label text-black">{{__('admin::home.PaymentId')}}</label>
                                                <input type="text" class="form-control" id="payment_id"
                                                       wire:model.defer="payment_id"
                                                       placeholder="PaymentId" disabled>
                                                @error('payment_id') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6" hidden>
                                                <label for="user_id"
                                                       class="form-label text-black">{{__('admin::home.Customer No')}}</label>
                                                <input type="text" class="form-control" id="user_id"
                                                       wire:model.defer="user_id"
                                                       placeholder="Customer Id" >
                                                @error('user_id') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="user_name"
                                                       class="form-label text-black">{{__('admin::home.Customer Name')}}</label>
                                                <input type="text" class="form-control" id="user_name"
                                                       wire:model="user_name"
                                                       placeholder="Customer Name" disabled>
                                                @error('user_name') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="merchant_name"
                                                       class="form-label text-black">{{__('admin::home.Merchant')}}</label>
                                                <input type="text" class="form-control" id="merchant_name"
                                                       wire:model="merchant_name"
                                                       placeholder="Merchant Name" disabled>
                                                @error('merchant_name') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6">
                                                <label for="status"
                                                       class="form-label text-black">{{__('admin::home.status')}}</label>
                                                <input type="text" class="form-control" id="status"
                                                       wire:model="status"
                                                       placeholder="Status" disabled>
                                                @error('status') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>

                                            
                                            <div class="col-xl-6" >
                                                <label for="payment_amount"
                                                       class="form-label text-black">{{__('admin::home.PaymentAmount')}}</label>
                                                <input type="number" class="form-control" id="payment_amount"
                                                       wire:model="payment_amount"
                                                       placeholder="0.00" disabled>
                                                @error('payment_amount') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>
                                            <div class="col-xl-6" >
                                                <label for="refund_payment_amount_last"
                                                       class="form-label text-black">{{__('admin::home.RefundpaymentAmountLast')}}</label>
                                                <input type="number" min="0"  class="form-control" id="refund_payment_amount_last"
                                                       wire:model.defer="refund_payment_amount_last"
                                                       placeholder="0.00" disabled>
                                                @error('refund_payment_amount_last') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div> 
                                            <div class="row"> 
                                            <div class="col-xl-6">
                                                <label for="calculated_on" class="form-label text-black">{{__('admin::home.calculated on')}}</label>
                                                <select class="form-control @error('calculated_on') is-invalid @enderror"  wire:model="calculated_on">
                                                    <option value="">{{__('admin::home.Select calculated on')}}</option>
                                                    <option value="company">{{__('admin::home.Company')}}</option>
                                                    <option value="delivery">{{__('admin::home.Delivery')}}</option>
                                                    <option value="store">{{__('admin::home.Store')}}</option>
                                                </select>

                                                @error("calculated_on") <span
                                                    class="text-danger d-block mt-2">{{ $message }}</span> @enderror

                                            </div> 
                                            <div class="col-xl-6" >
                                                <label for="note"
                                                       class="form-label text-black">{{__('admin::home.RefundpaymentNote')}} <span class="text-danger">*</span></label>
                                                <input type="text"   class="form-control" id="note"
                                                       wire:model="note">
                                                @error('note') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                            </div>                           
                                            <div class="col-xl-6" >
                                                <label for="amount"
                                                       class="form-label text-black">{{__('admin::home.RefundpaymentAmount')}} <span class="text-danger">*</span></label>
                                                <input type="number" step="0.01" min="0.01"  class="form-control" id="amount"
                                                       wire:model="amount"
                                                       placeholder="0.00">
                                                @error('amount') <span
                                                    class="text-danger  d-block">{{ $message }}</span> @enderror
                                                    @if($messageFlag)
                                                    <span 
                                                    class="text-danger  d-block">{{__('admin::home.RefundpaymentAmountMin')}}</span>
                                                    @endif
                                            </div>    
                                            </div>                          
                                    </div>
                                    <div class="card-body p-0">
                                    @if($refundpayment)
                        <div class="table-responsive">
                        <label class="form-label text-black">{{__('admin::home.RefundpaymentLast')}}</label>
                            <table class="table text-nowrap">
                                <thead>
                                <tr>
                                    <th scope="col">@lang('admin::home.RefundpaymentAmount')</th>
                                    <th scope="col">@lang('admin::home.RefundpaymentNote')</th>
                                    <th scope="col">@lang('admin::home.Date')</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($refundpayment as $item)

                                    <tr>


                                        <td> {{ number_format($item?->amount, 2) }} @lang('admin::home.SAR')</td>
                                        <td>{{ $item?->note }} </td>
                                        <td>{{ $item?->created_at }} </td>

                                    </tr>

                                @endforeach


                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger"data-bs-dismiss="modal">
                            @lang('admin::home.Close')
                        </button>
                        <button type="submit" class="btn btn-success">
                                <span class="indicator-label" wire:loading.class="d-none">
                                    @lang('admin::home.RefundpaymentType toWallet')
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none">
                                        @lang('admin::home.Wait') 
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('js')
    <script>
        $(document).on('change', '.selectCountry', function () {
            $('.selectCity').empty();
            window.livewire.emit('updateCountry', $(this).val());
        });

        $(document).on('change', '.selectDistrict', function () {
            window.livewire.emit('updateDistrict', $(this).val());
        });


        Livewire.on('renderCities', cities => {
            let element = $('.selectCity');
            element.append(`<option value="">select</option>`);
            for (let i = 0; i < cities.length; i++) {
                element.append(`<option value="${cities[i].id}">${cities[i]?.title?.en}</option>`);
            }
            // Livewire.emit('renderSelect');
        });

        Livewire.on('renderDistricts', districts => {
            console.log(districts);

            for (let i = 0; i < districts.length; i++) {
                $('.selectDistrict').append(`<option value="${districts[i].id}">${districts[i]?.title?.en}</option>`);
            }
            Livewire.emit('renderSelect');
        });
    </script>
@endpush
