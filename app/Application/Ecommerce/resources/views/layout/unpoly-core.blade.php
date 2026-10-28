<script>
function applyDirection() {
    const metaLocale = document.querySelector('meta[name="app-locale"]');
    const currentLang = metaLocale ? metaLocale.content : 'en';

    const html = document.documentElement;
    const body = document.body;

    console.log('🎨 Applying direction for locale:', currentLang);

    if (currentLang === 'ar') {
        html.setAttribute('dir', 'rtl');
        html.setAttribute('lang', 'ar');
        body.classList.add('rtl');
        body.classList.remove('ltr');
    } else {
        html.setAttribute('dir', 'ltr');
        html.setAttribute('lang', 'en');
        body.classList.remove('rtl');
        body.classList.add('ltr');
    }

    void html.offsetHeight;
}

window.applyDirection = applyDirection;

function setupUnpoly() {
    if (typeof up === 'undefined') {
        console.warn('⚠️ Unpoly not available');
        return;
    }

    try {

        console.log('🔧 Configuring Unpoly for RTL/LTR support...');

        // Global Scroll Reset Configuration
        up.fragment.config.navigateOptions.scroll = 'reset';
        // up.viewport.config.scroll = 'reset';


        function getCurrentLocale() {
            return document.querySelector('meta[name="app-locale"]')?.content || 'en';
        }

        function getCurrentDir() {
            return document.querySelector('meta[name="app-dir"]')?.content || 'ltr';
        }

        function isHomeUrl(url) {
            if (!url) return false;
            const urlObj = typeof url === 'string' ? new URL(url, window.location.origin) : url;
            const path = urlObj.pathname;
            // Match: /, /ar, /en, /ar/, /en/
            return path === '/' ||
                   path === '/ar' ||
                   path === '/en' ||
                   path === '/ar/' ||
                   path === '/en/';
        }

        up.on('up:fragment:loaded', function(event) {
            console.log('📦 Fragment loaded, checking for Bootstrap variant...');

            const response = event.response;
            if (!response || !response.text) return;

            const parser = new DOMParser();
            const doc = parser.parseFromString(response.text, 'text/html');
            const newBody = doc.querySelector('body');

            if (!newBody) return;

            const newVariant = newBody.getAttribute('data-bootstrap-variant');
            const currentVariant = document.body.getAttribute('data-bootstrap-variant');

            console.log(`🔍 Variants - Current: ${currentVariant}, New: ${newVariant}`);

            if (newVariant && newVariant !== currentVariant) {
                console.log(`🔄 Swapping Bootstrap: ${currentVariant} → ${newVariant}`);

                const bootstrapLink = document.getElementById('bootstrap-css');
                if (bootstrapLink) {
                    const rtlHref = '/assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css';
                    const ltrHref = '/assets/ecommerce/css/bootstrap.css';
                    bootstrapLink.setAttribute('href', newVariant === 'rtl' ? rtlHref : ltrHref);
                    console.log(`✅ Bootstrap CSS swapped to: ${newVariant}`);
                }
            }
        });

        up.on('up:fragment:inserted', function(event) {
            const currentLocale = getCurrentLocale();
            const currentDir = getCurrentDir();
            const html = document.documentElement;

            if (html.getAttribute('lang') !== currentLocale) {
                html.setAttribute('lang', currentLocale);
            }

            if (html.getAttribute('dir') !== currentDir) {
                html.setAttribute('dir', currentDir);
                void html.offsetHeight;
            }

            applyDirection();

            // CRITICAL: Reinit Swiper for home page after fragment insertion
            if (isHomeUrl(window.location.href)) {
                console.log('🏠 Home page fragment inserted - reinitializing Swiper...');
                setTimeout(() => {
                    initCategoriesSlider();
                }, 300);
            }

            console.log(`✅ Fragment inserted. Current: ${currentLocale} (${currentDir})`);
        });

        up.on('up:location:changed', function(event) {
            console.log(`📍 Location changed to: ${event.location}`);
            applyDirection();

            if (isHomeUrl(event.location)) {
                console.log('🏠 Home page - reinitializing categories slider...');
                setTimeout(() => {
                    initCategoriesSlider();
                }, 200);
            }
        });

        // Function to initialize categories slider
        function initCategoriesSlider() {
            console.log('🎠 Initializing all Swiper sliders...');

            // For Swiper Categories
            if (typeof Swiper !== 'undefined') {
                // Categories Swiper
                const categoriesContainer = document.querySelector('.swiper-cat-container');
                if (categoriesContainer) {
                    // Destroy if exists
                    if (categoriesContainer.swiper) {
                        categoriesContainer.swiper.destroy(true, true);
                    }

                    // Initialize with exact config from home.blade.php
                    setTimeout(() => {
                        new Swiper(".swiper-cat-container", {
                            slidesPerView: 2,
                            spaceBetween: 10,
                            autoplay: false,
                            pagination: {
                                el: ".swiper-pagination",
                                clickable: true,
                            },
                            breakpoints: {
                                480: {
                                    slidesPerView: 3,
                                    spaceBetween: 15,
                                    grid: { rows: 2, fill: 'row' }
                                },
                                768: {
                                    slidesPerView: 4,
                                    spaceBetween: 20,
                                    grid: { rows: 2, fill: 'row' }
                                },
                                992: {
                                    slidesPerView: 5,
                                    spaceBetween: 20,
                                    grid: { rows: 2, fill: 'row' }
                                },
                                1200: {
                                    slidesPerView: 6,
                                    spaceBetween: 25,
                                    grid: { rows: 2, fill: 'row' }
                                },
                            },
                        });
                        console.log('✅ Categories Swiper initialized');
                    }, 150);
                }

                // Merchants Swiper
                const merchantsContainer = document.querySelector('.merchant-swiper-container');
                if (merchantsContainer) {
                    if (merchantsContainer.swiper) {
                        merchantsContainer.swiper.destroy(true, true);
                    }

                    setTimeout(() => {
                        new Swiper(".merchant-swiper-container", {
                            slidesPerView: 2,
                            spaceBetween: 15,
                            autoplay: { delay: 3000 },
                            breakpoints: {
                                480: { slidesPerView: 3 },
                                768: { slidesPerView: 4 },
                                992: { slidesPerView: 5 },
                                1200: { slidesPerView: 6 },
                            },
                        });
                        console.log('✅ Merchants Swiper initialized');
                    }, 200);
                }

                // Clients Swiper (if exists)
                const clientsContainer = document.querySelector('.clients-swiper-container');
                if (clientsContainer) {
                    if (clientsContainer.swiper) {
                        clientsContainer.swiper.destroy(true, true);
                    }

                    setTimeout(() => {
                        new Swiper(".clients-swiper-container", {
                            slidesPerView: 2,
                            spaceBetween: 15,
                            autoplay: { delay: 3500 },
                            breakpoints: {
                                480: { slidesPerView: 3 },
                                768: { slidesPerView: 4 },
                                992: { slidesPerView: 5 },
                                1200: { slidesPerView: 6 },
                            },
                        });
                        console.log('✅ Clients Swiper initialized');
                    }, 250);
                }
            }

            console.log('✅ All Swiper sliders initialized');
        }

        up.compiler('body, .ecommerce-layout, .main-body', function(element) {
            console.log('🔧 Unpoly compiler triggered');
            applyDirection();
        });

        up.on('up:layer:opened', function() {
            console.log('🪟 Layer opened');
            applyDirection();
        });

        up.fragment.config.mainTargets = ['body', '.ecommerce-layout'];
        up.fragment.config.failTarget = 'body';

        up.history.config.enabled = true;
        up.history.config.restoreTargets = ['body'];

        up.link.config.followSelectors.push('a[up-target]');
        up.link.config.followSelectors.push('a[href]');

        up.on('up:link:follow', function(event) {
            if (event.renderOptions) {
                event.renderOptions.history = event.renderOptions.history !== false;
            }
            const url = event.request?.url || event.link?.getAttribute('href') || '';
            console.log(`🔗 Following link to: ${url} (history: ${event.renderOptions?.history})`);
        });

        // Auth pages are standalone full-page designs (own <head>/styles). Unpoly swaps only a
        // fragment and would render them unstyled, so force a real page load for them.
        const upStandaloneAuthPaths = ['login', 'register', 'verify', 'forgot-password', 'reset-password'];
        up.on('up:link:follow', function(event) {
            const href = event.link && event.link.getAttribute('href');
            if (!href) return;
            try {
                const path = new URL(href, window.location.origin).pathname.replace(/^\/+/, '');
                const firstSegment = path.replace(/^(ar|en)\//, '').split('/')[0];
                if (upStandaloneAuthPaths.indexOf(firstSegment) !== -1) {
                    event.preventDefault();
                    window.location.href = href;
                }
            } catch (err) {
                console.error('up standalone-nav error:', err);
            }
        });

        up.on('up:history:pushed', function(event) {
            console.log(`📜 History pushed: ${event.location}`);
        });

        up.on('up:history:replaced', function(event) {
            console.log(`📜 History replaced: ${event.location}`);
        });

        up.fragment.config.cacheSize = 0;
        up.network.config.cacheEvictAge = 0;
        up.fragment.config.noCache = true;

        up.form.config.autoSubmit = false;

        console.log('✅ Unpoly configured successfully');
        console.log('   - SPA navigation enabled');
        console.log('   - History updates: ENABLED for all links');
        console.log('   - Intelligent CSS swapping active');
        console.log('   - RTL/LTR auto-sync enabled');

    } catch (error) {
        console.error('❌ Unpoly setup failed:', error);
    }
}
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initializing Unpoly core...');
    applyDirection();
    setupUnpoly();
});
</script>
