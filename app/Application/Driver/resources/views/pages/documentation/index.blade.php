@extends('driver::layout.layout')
@section('title',"Driver |" . __('driver::home.docs'))

@section('content')
    @push('css')
        <!-- Prism CSS -->
        <link rel="stylesheet" href="{{ asset('assets/vendor/libs/prismjs/themes/prism-coy.min.css') }}">
    @endpush
    <div class="col-xxl-12 col-xl-12">
        <div class="row">
            <div class="col-xl-12">
                <div class="card custom-card">
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom border-block-end-dashed d-flex align-items-center justify-content-between">
                            <div>
                                <ul class="nav nav-tabs mb-0 tab-style-6 justify-content-start" id="myTab" role="tablist" wire:ignore>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="keys-tab" data-bs-toggle="tab"
                                                data-bs-target="#keys-tab-pane" type="button" role="tab"
                                                aria-controls="keys-tab-pane" aria-selected="true"><i
                                                class="ri-lock-password-fill me-1 align-middle d-inline-block"></i>API Keys</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="docs-tab" data-bs-toggle="tab"
                                                data-bs-target="#docs-tab-pane" type="button" role="tab"
                                                aria-controls="docs-tab-pane" aria-selected="false"><i
                                                class="ri-file-3-fill me-1 align-middle d-inline-block"></i>Docs</button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="p-3">
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane show active fade p-0 border-0" id="keys-tab-pane"
                                     role="tabpanel" aria-labelledby="keys-tab" tabindex="0" wire:ignore.self>

                                    <div class="mt-2">
                                        <div class="col-xl-12 gy-3">
                                            <div class="col-xl-6">
                                                <label for="key" class="form-label text-default">API Key</label>
                                                <div class="input-group">
                                                    <input type="text"
                                                           class="form-control form-control-lg"
                                                           id="api_key"
                                                           readonly
                                                           value="key_z93uq8c8ppFR5ojCgXpD7rpJ1LvLsH7ZWgyK2iz93uq8c8"
                                                           placeholder="">
                                                    <button class="btn btn-light btn-wave"
                                                            data-bs-toggle="popover"
                                                            data-bs-placement="top"
                                                            data-clipboard-action="copy"
                                                            data-bs-custom-class="popover-success"
                                                            data-bs-content="Copied Key to clipboard!"
                                                            data-bs-original-title="Copied"
                                                            aria-describedby="popover803685"
                                                            data-clipboard-target="#api_key"><i class="ri-file-copy-line"></i></button>
                                                </div>
                                            </div>

                                            <div class="col-xl-6 mt-3">
                                                <label for="secret" class="form-label text-default">Secret</label>
                                                <div class="input-group">
                                                    <input type="password"
                                                           class="form-control form-control-lg"
                                                           readonly
                                                           id="secret"
                                                           value="secret_zojCgXpD7yK2iz93uq8c8pprpJ1LvLsH7ZWgFR53uq8c89"
                                                           placeholder="">
                                                    <button class="btn btn-light btn-wave"
                                                            data-bs-toggle="popover"
                                                            data-bs-placement="top"
                                                            data-clipboard-action="copy"
                                                            data-bs-custom-class="popover-success"
                                                            data-bs-content="Copied Key to clipboard!"
                                                            data-bs-original-title="Copied"
                                                            aria-describedby="popover803685"
                                                            data-clipboard-target="#secret"><i class="ri-file-copy-line"></i></button>
                                                    <button class="btn btn-light" onclick="createpassword('secret',this)" type="button" id="button-addon2"><i class="ri-eye-off-line align-middle"></i></button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane  fade p-0 border-0" id="docs-tab-pane"
                                     role="tabpanel" aria-labelledby="docs-tab" tabindex="0" wire:ignore.self>

                                    <div class="container-fluid">
                                        <div class="file-manager-container p-2 gap-2 d-sm-flex">
                                            <div class="file-manager-folders">
                                                <div class="d-flex p-3 flex-wrap gap-2 align-items-center justify-content-between border-bottom">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">API Collection</h6>
                                                    </div>
                                                    <div class="d-flex gap-2">
                                                        <button id="folders-close-btn" class="d-sm-none d-block btn btn-icon btn-sm btn-danger-light">
                                                            <i class="ri-close-fill"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="p-3 file-folders-container" id="file-folders-container">
                                                    <div class="row mb-3">
                                                        <div class="col-md-12">
                                                            <div class="col-xxl-12 col-xl-12 col-lg-12 col-md-12 col-sm-12" id="#getAllOrders">
                                                                <div class="card custom-card bg-light">
                                                                    <div class="card-body">
                                                                        <div class="d-flex align-items-center mb-3">
                                                                            <h3>
                                                                                <span class="mb-0 fw-semibold fs-18 text-success">GET</span>
                                                                                <span class="mb-0 fw-semibold fs-18 text-black">Get All Orders.</span>
                                                                            </h3>
                                                                        </div>
                                                                        <div class="col-md-10 mb-3">
                                                                            <div class="input-group">
                                                                                <input class="form-control" id="orders-get" readonly type="text" value="{{ url('/') }}/api/v1/order/all">
                                                                                <button class="btn btn-info-light btn-wave"
                                                                                        data-bs-toggle="popover"
                                                                                        data-bs-placement="top"
                                                                                        data-clipboard-action="copy"
                                                                                        data-bs-custom-class="popover-success"
                                                                                        data-bs-content="Copied Key to clipboard!"
                                                                                        data-bs-original-title="Copied"
                                                                                        aria-describedby="popover803685"
                                                                                        data-clipboard-target="#orders-get"><i class="ri-file-copy-line"></i></button>
                                                                            </div>
                                                                        </div>
                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <h3>
                                                                                <span class="mb-0 fw-semibold fs-18 text-black">Authorization</span>
                                                                                <span class="mb-0 fw-semibold fs-18 text-muted">Bearer Token</span>
                                                                            </h3>
                                                                        </div>

                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <h3>
                                                                                <span class="mb-0 fw-semibold fs-18 text-black">Example</span>
                                                                            </h3>
                                                                        </div>

                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-muted">Request</span>
                                                                            </h5>
                                                                        </div>

                                                                        <div class="align-items-center justify-content-between bg-white gy-3 p-4">
                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black-50">curl --location --globoff</span>
                                                                                <span class="mb-0 fw-semibold fs-16 text-success">'{{ url('/') }}/api/v1/order/all'</span>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black-50">\</span>
                                                                            </h5>

                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black-50">--header</span>
                                                                                <span class="mb-0 fw-semibold fs-16 text-success">'accept: "application/json"'</span>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black-50">\</span>
                                                                            </h5>

                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black-50">--header</span>
                                                                                <span class="mb-0 fw-semibold fs-16 text-success">'Accept-Language: "en"'</span>
                                                                            </h5>
                                                                        </div>

                                                                        <div class="d-flex align-items-center justify-content-between">
                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-muted">Response</span>
                                                                            </h5>
                                                                        </div>


                                                                        <div class="align-items-center">
                                                                            <h5>
                                                                                <span class="mb-0 fw-semibold fs-16 text-black">
                                                                                    <pre class="language-json">
                                                                                        <code class="language-json">
{
    "data": [
        {
            "id": 2,
            "status": "on_way",
            "driver": {
                "id": 1,
                "name": "muhammad salah",
                "logo": "/storage/image.png",
                "address": "LA , 22 BAKER STREET",
            },
            "code": "7219HK1",
            "payment_method": "Cash",
            "payment_status": "paid",
            "address": "LA , 22 BAKER STREET",
            "date": "27 jul, 2023",
            "timing": "27 jul, 2023",
            "time": {
                "form": "22:10 pm",
                "to": "23:10 pm",
            },
            "services": [

            ],
            "payment_summary": {
                "amount": "1101.21",
                "vat": "28.12",
                "wallet_balance": "19213.123",
                "total": "1130.123",
            },
        },

    ]
}
                                                                                        </code>
                                                                                    </pre>
                                                                                </span>
                                                                            </h5>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="file-manager-navigation">
                                                <div class="d-flex align-items-center justify-content-between w-100 p-3 border-bottom">
                                                    <div>
                                                        <h6 class="fw-semibold mb-0">JUMP TO</h6>
                                                    </div>
                                                </div>
                                                <div class="p-3 border-bottom">
                                                    <div class="input-group">
                                                        <input type="text" class="form-control bg-light border-0" placeholder="Search Files" aria-describedby="button-addon2">
                                                        <button class="btn btn-light" type="button" id="button-addon2"><i class="ri-search-line text-muted"></i></button>
                                                    </div>
                                                </div>
                                                <div>
                                                    <ul class="list-unstyled files-main-nav" id="files-main-nav">
                                                        <li class="active files-type">
                                                            <a href="#getAllOrders">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-2">
                                                                        <i class="ri-folder-2-line fs-16"></i>
                                                                    </div>
                                                                    <span class="flex-fill text-nowrap">My Orders</span>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <!-- Show Password JS -->
        <script src="{{ asset('assets/vendor/js/show-password.js') }}"></script>
        <!-- Prism JS -->
        <script src="{{ asset('assets/vendor/libs/prismjs/prism.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/prism-custom.js') }}"></script>

        <!-- 3. Instantiate clipboard -->
        <script>
            var clipboard = new ClipboardJS('.btn');

            clipboard.on('success', function (e) {
                console.info('Action:', e.action);
                console.info('Text:', e.text);
                console.info('Trigger:', e.trigger);
            });

            clipboard.on('error', function (e) {
                console.log(e);
            });
        </script>
    @endpush
@endsection
