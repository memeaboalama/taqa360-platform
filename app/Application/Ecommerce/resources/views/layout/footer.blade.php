    <footer class="footer-ecommerce">
        <div class="upper-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer_first">
                            <div class="footer-title mobile-title">
                                <h3>{{ __('ecommerce::ecommerce.about_us') }}</h3>
                            </div>
                            <div class="footer-contant">
                                @if(isset($footerData['logo']) && $footerData['logo'])
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($footerData['logo']) }}" class="foot_logo" alt="Footer Logo">
                                @else
                                    <img src="{{ asset('assets/ecommerce/images/logo.png') }}" class="foot_logo" alt="Default Logo">
                                @endif
                                <style>
                                    [dir="rtl"] .foot_logo { transform: scaleX(1) !important; }
                                </style>
                                
                                
                                <p>
                                    @if(app()->getLocale() == 'ar')
                                        {{ $footerData['description']['ar'] ?? '' }}
                                    @else
                                        {{ $footerData['description']['en'] ?? '' }}
                                    @endif
                                </p>
                                
                                @if(isset($footerData['social']) && !empty($footerData['social']))
                                    <div class="footer_social">
                                        <div>
                                            <ul>
                                                @foreach($footerData['social'] as $key => $link)
                                                    @if($link)
                                                        <li><a href="{{ $link }}" target="_blank"><i class="fa fa-{{ $key == 'twitter' ? 'twitter' : ($key == 'facebook' ? 'facebook' : ($key == 'instagram' ? 'instagram' : ($key == 'linkedin' ? 'linkedin' : ($key == 'youtube' ? 'youtube' : ($key == 'whatsapp' ? 'whatsapp' : 'link'))))) }}" aria-hidden="true"></i></a></li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer_second">
                            <div>
                                <div class="footer-title">
                                    <h3>{{ __('ecommerce::ecommerce.important_links') }}</h3>
                                </div>
                                <div class="footer-contant">
                                    <ul>
                                        @if(isset($footerData['links']['terms']) && $footerData['links']['terms'])
                                            <li><a href="{{ $footerData['links']['terms'] }}"><i class="fa fa-chevron-circle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('ecommerce::ecommerce.terms_conditions') }}</a></li>
                                        @endif
                                        @if(isset($footerData['links']['privacy']) && $footerData['links']['privacy'])
                                            <li><a href="{{ $footerData['links']['privacy'] }}"><i class="fa fa-chevron-circle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('ecommerce::ecommerce.privacy_policy') }}</a></li>
                                        @endif
                                        @if(isset($footerData['links']['contact']) && $footerData['links']['contact'])
                                            <li><a href="{{ $footerData['links']['contact'] }}"><i class="fa fa-chevron-circle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('ecommerce::ecommerce.contact_us') }}</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer_second">
                            <div>
                                <div class="footer-title">
                                    <h3>{{ __('ecommerce::ecommerce.information') }}</h3>
                                </div>
                                <div class="footer-contant">
                                    <ul>
                                        <!-- Keep hardcoded links or make dynamic later? For now hardcoded as per new design but cleaner -->
                                        <li><a href="#"><i class="fa fa-chevron-circle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('ecommerce::ecommerce.all_collection') }}</a></li>
                                        <li><a href="#"><i class="fa fa-chevron-circle-{{ app()->getLocale() == 'ar' ? 'left' : 'right' }}"></i> {{ __('ecommerce::ecommerce.new_products') }}</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="footer_forth">
                            <div class="footer-title">
                                <h3>{{ __('ecommerce::ecommerce.contact_info') }}</h3>
                            </div>
                            <div class="footer-contant">
                                <ul>
                                    @if(isset($footerData['contact']['address'][app()->getLocale()]) && $footerData['contact']['address'][app()->getLocale()])
                                        <li><i class="fa fa-map-marker" aria-hidden="true"></i> {{ $footerData['contact']['address'][app()->getLocale()] }}</li>
                                    @endif
                                    @if(isset($footerData['contact']['phone']) && $footerData['contact']['phone'])
                                        <li><i class="fa fa-phone" aria-hidden="true"></i> {{ $footerData['contact']['phone'] }}</li>
                                    @endif
                                    @if(isset($footerData['contact']['email']) && $footerData['contact']['email'])
                                        <li><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ $footerData['contact']['email'] }}</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Copyright section Start -->
        <section class="copyright_sec">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="cop_p text-center">
                            <p>
                                @if(app()->getLocale() == 'ar')
                                    © {{ date('Y') }}
                                @else
                                    {{ $footerData['copyright']['en'] ?? '© 2024. All rights reserved.' }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Copyright section End -->
    </footer>
