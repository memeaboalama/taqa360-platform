<script src="{{ asset('assets/ecommerce/js/jquery-3.3.1.min.js') }}"></script>
{{-- Unpoly moved to <head> to survive body swaps --}}
<script src="{{ asset('assets/ecommerce/js/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/popper.min.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/menu.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/slick.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/script.js') }}"></script>
<script src="{{ asset('assets/ecommerce/js/ecommerce.js') }}"></script>




<script>
document.addEventListener('DOMContentLoaded', initializeApp);

function initializeApp() {
    console.log('🚀 Starting app initialization...');

    if (typeof applyDirection === 'function') {
        applyDirection();
    }

    hideLoader();
    setupFilterHandlers();
    setupMerchantPage();
    initComponents();
    console.log('✅ App ready');
}

function hideLoader() {
    const loader = document.querySelector('.loader-wrapper');
    if (loader && loader.classList.contains('active')) {
        loader.classList.remove('active');
        setTimeout(() => loader.style.display = 'none', 500);
    }
}

        function isHomeUrl(url) {
            if (!url) return false;
            const urlObj = typeof url === 'string' ? new URL(url, window.location.origin) : url;
            const path = urlObj.pathname;
            return path === '/' ||
                   path === '/ar' ||
                   path === '/en' ||
                   path === '/ar/' ||
                   path === '/en/' ||
                   path.endsWith('/home') ||
                   path.endsWith('/index') ||
                   path === '/ar/home' ||
                   path === '/en/home';
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

            console.log(`🔍 Detected variants - Current: ${currentVariant}, New: ${newVariant}`);

            if (newVariant && newVariant !== currentVariant) {
                console.log(`🔄 Variant change detected! Swapping Bootstrap: ${currentVariant} → ${newVariant}`);
                swapBootstrapCSSSync(newVariant);
            }
        });

        function swapBootstrapCSSSync(variant) {
            const bootstrapLink = document.getElementById('bootstrap-css');

            if (!bootstrapLink) {
                console.warn('⚠️ Bootstrap link not found');
                return;
            }

            const rtlHref = '/assets/vendor/libs/bootstrap/css/bootstrap.rtl.min.css';
            const ltrHref = '/assets/ecommerce/css/bootstrap.css';
            const targetHref = variant === 'rtl' ? rtlHref : ltrHref;

            const currentHref = bootstrapLink.getAttribute('href');
            if ((variant === 'rtl' && currentHref.includes('rtl')) ||
                (variant === 'ltr' && !currentHref.includes('rtl'))) {
                console.log('✅ Bootstrap already correct');
                return;
            }

            bootstrapLink.setAttribute('href', targetHref);
            console.log(`✅ Bootstrap CSS swapped to: ${variant}`);
        }


        function isHomeUrl(url) {
            if (!url) return false;
            const urlObj = typeof url === 'string' ? new URL(url, window.location.origin) : url;
            const path = urlObj.pathname;
            return path === '/' ||
                   path === '/ar' ||
                   path === '/en' ||
                   path === '/ar/' ||
                   path === '/en/' ||
                   path.endsWith('/home') ||
                   path.endsWith('/index') ||
                   path === '/ar/home' ||
                   path === '/en/home';
        }

        up.on('up:link:follow', function(event) {
            const targetUrl = event.request?.url || event.link?.getAttribute('href') || window.location.href;

            if (isHomeUrl(targetUrl)) {
                console.log('🏠 Home navigation detected - using body target');
                if (event.renderOptions) {
                    event.renderOptions.target = 'body';
                    event.renderOptions.cache = false;
                }
            }
        });

        up.on('up:location:changed', function(event) {
            if (isHomeUrl(event.location)) {
                console.log('🔙 History navigation to home detected');
                setTimeout(() => {
                    if (isHomeUrl(window.location.href)) {
                        applyDirection();
                        reinitializeHomeComponents();
                    }
                }, 100);
            }
        });


        up.on('up:fragment:inserted', function(event) {
            if (event.target && event.target.matches('body')) {
                console.log('🔄 Body swapped! Re-initializing Unpoly...');

                setTimeout(() => {
                    if (typeof window.setupUnpolyAfterSwap === 'function') {
                        window.setupUnpolyAfterSwap();
                    }
                }, 50);
            }
        });

        up.fragment.config.mainTargets = ['html', '.ecommerce-layout', 'body'];
        up.fragment.config.failTarget = 'body';
        up.history.config.restoreTargets = ['html', 'body', '.ecommerce-layout'];

        up.fragment.config.cacheSize = 0;
        up.network.config.cacheEvictAge = 0;
        up.fragment.config.noCache = true;

        up.form.config.autoSubmit = false;


        function getCurrentLocale() {
            return document.querySelector('meta[name="app-locale"]')?.content || 'en';
        }

        function getCurrentDir() {
            return document.querySelector('meta[name="app-dir"]')?.content || 'ltr';
        }

        function extractMetaFromHTML(htmlString) {
            const parser = new DOMParser();
            const doc = parser.parseFromString(htmlString, 'text/html');

            return {
                locale: doc.querySelector('meta[name="app-locale"]')?.content,
                dir: doc.querySelector('meta[name="app-dir"]')?.content,
                lang: doc.documentElement.getAttribute('lang')
            };
        }

        function syncHtmlAttributes(newLocale, newDir, newLang) {
            const html = document.documentElement;

            if (newLang && html.getAttribute('lang') !== newLang) {
                console.log(`🌐 Updating lang: ${html.getAttribute('lang')} → ${newLang}`);
                html.setAttribute('lang', newLang);
            }

            if (newDir && html.getAttribute('dir') !== newDir) {
                console.log(`↔️ Updating dir: ${html.getAttribute('dir')} → ${newDir}`);
                html.setAttribute('dir', newDir);

                void html.offsetHeight;
            }
        }

        function syncMetaTags(newLocale, newDir) {
            let localeMeta = document.querySelector('meta[name="app-locale"]');
            let dirMeta = document.querySelector('meta[name="app-dir"]');

            if (localeMeta && newLocale) localeMeta.content = newLocale;
            if (dirMeta && newDir) dirMeta.content = newDir;
        }

        function reinitializeHomeComponents() {
            console.log('🔄 Reinitializing home page components...');

            if (window.jQuery && jQuery.fn.owlCarousel) {
                jQuery('.owl-carousel').each(function() {
                    const $carousel = jQuery(this);
                    if ($carousel.hasClass('owl-loaded')) {
                        $carousel.trigger('destroy.owl.carousel');
                        $carousel.removeClass('owl-loaded owl-drag');
                    }
                });

                setTimeout(() => {
                    if (typeof window.initializeCarousels === 'function') {
                        window.initializeCarousels();
                    }
                }, 100);
            }

            if (typeof Swiper !== 'undefined') {
                const swiperContainer = document.querySelector('.swiper-cat-container');
                if (swiperContainer && !swiperContainer.swiper) {
                    console.log('Reinitializing Swiper...');
                }
            }

            console.log('✅ Home components reinitialized');
        }


        let previousLocale = getCurrentLocale();
        let previousDir = getCurrentDir();

        up.on('up:fragment:loaded', function(event) {
            console.log('📦 Fragment loaded, analyzing...');

            const response = event.response;
            if (!response || !response.text) return;

            const newMeta = extractMetaFromHTML(response.text);

            if (newMeta.locale && newMeta.dir) {
                const localeChanged = newMeta.locale !== previousLocale;
                const dirChanged = newMeta.dir !== previousDir;

                if (localeChanged || dirChanged) {
                    console.log(`🔄 Locale/Dir change detected!`);
                    console.log(`   Previous: ${previousLocale} (${previousDir})`);
                    console.log(`   New: ${newMeta.locale} (${newMeta.dir})`);

                    syncHtmlAttributes(newMeta.locale, newMeta.dir, newMeta.lang);
                    syncMetaTags(newMeta.locale, newMeta.dir);

                    applyDirection();

                    previousLocale = newMeta.locale;
                    previousDir = newMeta.dir;
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

            if (isHomeUrl(window.location.href)) {
                console.log('🏠 Home page inserted, reinitializing...');
                reinitializeHomeComponents();
            }

            applyDirection();

            console.log(`✅ Fragment inserted. Current state: ${currentLocale} (${currentDir})`);
        });


        up.on('up:location:changed', function(event) {
            console.log('🔙 History navigation detected');

            setTimeout(() => {
                const locale = getCurrentLocale();
                const dir = getCurrentDir();
                const html = document.documentElement;

                if (html.getAttribute('lang') !== locale) {
                    html.setAttribute('lang', locale);
                }

                if (html.getAttribute('dir') !== dir) {
                    html.setAttribute('dir', dir);
                    void html.offsetHeight;
                }

                previousLocale = locale;
                previousDir = dir;

                applyDirection();
            }, 50);
        });


        up.compiler('body, .ecommerce-layout, .main-body', function(element) {
            console.log('🔧 Unpoly compiler triggered, applying direction...');
            applyDirection();
        });

        up.on('up:layer:opened', function() {
            console.log('🪟 Layer opened, applying direction...');
            applyDirection();
        });

        console.log('✅ Unpoly configured successfully with advanced RTL/LTR support');
        console.log('   - Target: .ecommerce-layout (includes header)');
        console.log('   - Home page: html (complete <head> + <body> replacement)');
        console.log('   - Auto-syncs: <html lang/dir> attributes');
        console.log('   - Tracks: CSS files via full html replacement');
        console.log('   - Compiler: Applies direction on ALL fragments');

    } catch (error) {
        console.error('❌ Unpoly setup failed:', error);
    }
}

window.setupUnpolyAfterSwap = function() {
    console.log('🔄 Re-attaching Unpoly event listeners after body swap...');

    try {
        if (typeof applyDirection === 'function') {
            applyDirection();
        }


        function isHomeUrl(url) {
            if (!url) return false;
            const urlObj = typeof url === 'string' ? new URL(url, window.location.origin) : url;
            const path = urlObj.pathname;
            return path === '/' ||
                   path === '/ar' ||
                   path === '/en' ||
                   path === '/ar/' ||
                   path === '/en/' ||
                   path.endsWith('/home') ||
                   path.endsWith('/index') ||
                   path === '/ar/home' ||
                   path === '/en/home';
        }

        if (isHomeUrl(window.location.href)) {
            console.log('🏠 On home page after swap, reinitializing components...');
            setTimeout(() => {
                if (typeof reinitializeHomeComponents === 'function') {
                    if (window.initializeCarousels) {
                        window.initializeCarousels();
                    }
                }
            }, 100);
        }

        console.log('✅ Unpoly event listeners re-attached');

    } catch (error) {
        console.error('❌ setupUnpolyAfterSwap failed:', error);
    }
};


function setupFilterHandlers(container = document) {
    const filterSelectors = [
        'input[name="main_category"]',
        'input[name="sub_category"]',
        'input[name="child_category"]',
        'input[name="brands[]"]',
        'input[name="price_ranges[]"]',
        'select[name="per_page"]',
        'select[name="sort_by"]'
    ];

    filterSelectors.forEach(selector => {
        container.querySelectorAll(selector).forEach(input => {
            if (input.dataset.handlerAttached) return;
            input.dataset.handlerAttached = 'true';
            input.addEventListener('change', handleFilterChange);
        });
    });

    container.querySelectorAll('.category-list a').forEach(link => {
        if (link.dataset.handlerAttached) return;
        link.dataset.handlerAttached = 'true';
        link.addEventListener('click', (e) => {
            e.preventDefault();

            const list = link.closest('.category-list');
            if (list) {
                list.querySelectorAll('a').forEach(a => a.classList.remove('active'));
            }
            link.classList.add('active');

            const targetRadioId = link.dataset.targetRadio;
            const targetRadio = document.getElementById(targetRadioId);
            if (targetRadio && !targetRadio.checked) {
                targetRadio.checked = true;
                targetRadio.dispatchEvent(new Event('change', { bubbles: true }));
            }
        });
    });
}

let filterTimeout;
let currentRequestController = null;

function handleFilterChange(event) {
    console.log('Filter changed:', event.target.name, event.target.value);
    clearTimeout(filterTimeout);
    if (currentRequestController) {
        currentRequestController.abort();
    }
    showLoading();
    filterTimeout = setTimeout(() => {
        submitFilterForm();
    }, 400);
}

function submitFilterForm() {
    const form = document.getElementById('filter-form');
    if (!form) {
        console.error('Filter form not found');
        return;
    }

    const formData = new FormData(form);
    const params = new URLSearchParams(formData);
    const url = new URL(form.action);
    url.search = params.toString();

    console.log('Submitting filter request to:', url.toString());
    currentRequestController = new AbortController();

    fetch(url.toString(), {
        method: 'GET',
        signal: currentRequestController.signal,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        processFilterResponse(data);
    })
    .catch(error => {
        if (error.name !== 'AbortError') {
            console.error('Filter request failed:', error);
            showMessage(@json(__('ecommerce::ecommerce.update_products_error')), 'error');
        }
    })
    .finally(() => {
        hideLoading();
        currentRequestController = null;
    });
}

function processFilterResponse(data) {
    const productGrid = document.querySelector('.product-wrapper-grid');
    const subSection = document.querySelector('.subcategories-section');
    const childSection = document.querySelector('.childcategories-section');

    if (productGrid && data.product_grid_html) {
        productGrid.innerHTML = data.product_grid_html;
    }
    if (subSection && data.subcategories_html) {
        subSection.innerHTML = data.subcategories_html;
        setupFilterHandlers(subSection);
    }
    if (childSection && data.childcategories_html) {
        childSection.innerHTML = data.childcategories_html;
        setupFilterHandlers(childSection);
    }

    if (data.new_url) {
        window.history.pushState({}, '', data.new_url);
    }

    initComponents(document.querySelector('.collection-content'));
    console.log('Filter response processed successfully');
}


function initComponents(container = document) {
    initCart(container);
    initImages(container);
    initCarousels(container);
}

function initCart(container = document) {
    container.querySelectorAll('.add-to-cart-form').forEach(form => {
        form.onsubmit = (e) => {
            e.preventDefault();
            addToCart(form);
        };
    });
}

function initImages(container = document) {
    container.querySelectorAll('img:not([data-processed])').forEach(img => {
        img.setAttribute('data-processed', 'true');
        const wrapper = img.closest('.img-wrapper');
        if (wrapper) wrapper.classList.add('loading');

        img.onload = function() {
            this.classList.add('loaded');
            if (wrapper) wrapper.classList.remove('loading');
        };
        img.onerror = function() {
            console.error('Image failed to load:', this.src);
            if (wrapper) {
                wrapper.classList.remove('loading');
                wrapper.classList.add('error');
            }
        };
        if (img.complete) {
            img.onload();
        }
    });
}


function initCarousels(container = document) {
    if (window.jQuery && jQuery.fn.owlCarousel) {
        jQuery(container).find('.owl-carousel:not(.owl-loaded)').each(function() {
            jQuery(this).owlCarousel({ });
        });
    }
}

function addToCart(form) {
    const btn = form.querySelector('button[type="submit"]');
    const formData = new FormData(form);

    if (btn) {
        const original = btn.innerHTML;
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>...';
        btn.disabled = true;
    }

    fetch(form.action, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': getToken(), 'Accept': 'application/json' },
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Network error');
        return response.json();
    })
    .then(data => {
        if (btn) {
            btn.innerHTML = @json(__('ecommerce::ecommerce.added'));
            setTimeout(() => {
                if (btn) {
                    btn.innerHTML = original;
                    btn.disabled = false;
                }
            }, 1000);
        }
        if (data.login_required) {
            showLoginMessage(data.message);
            return;
        }
        if (data.success) {
            if (data.cart_header) {
                updateHeader(data.cart_header);
            }
            showMessage(data.message, 'success');
        } else {
            showMessage(data.message, 'error');
        }
    })
    .catch(error => {
        if (btn) {
            btn.innerHTML = original;
            btn.disabled = false;
        }
        showMessage(@json(__('ecommerce::ecommerce.add_error_short')), 'error');
    });
}

function updateHeader(html) {
    const header = document.querySelector('.cart-header-container');
    if (header && html) {
        header.innerHTML = html;
        setTimeout(() => initCart(header), 50);
    }
}

function getToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

function showLoginMessage(message) {
    if (confirm(message + '\n\n' + @json(__('ecommerce::ecommerce.go_to_login')))) {
        window.location.href = '/login';
    }
}

function showMessage(message, type) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    document.querySelectorAll('.global-message').forEach(msg => msg.remove());
    const html = `
        <div class="alert ${alertClass} alert-dismissible fade show global-message" style="position: fixed; top: 20px; z-index: 9999;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', html);
    setTimeout(() => {
        const msg = document.querySelector('.global-message');
        if (msg) msg.remove();
    }, 5000);
}

function showLoading() {
    document.querySelector('.product-wrapper-grid')?.classList.add('loading');
    document.querySelector('.subcategories-section')?.classList.add('loading');
    document.querySelector('.childcategories-section')?.classList.add('loading');
}

function hideLoading() {
    document.querySelectorAll('.loading').forEach(el => el.classList.remove('loading'));
}



function initializeSearch() {
    const searchForm = document.getElementById('search-form');
    const searchInput = document.getElementById('search-input');
    const searchBtn = document.getElementById('search-btn');
    const categorySelect = document.getElementById('category-select');

    let searchTimeout;
    let lastSearchValue = '';

    searchBtn.addEventListener('click', function(e) {
        e.preventDefault();
        performSearch();
    });

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    searchInput.addEventListener('input', function(e) {
        const value = e.target.value.trim();

        clearTimeout(searchTimeout);

        if (value.length >= 3 && value !== lastSearchValue) {
            searchTimeout = setTimeout(() => {
                performSearch();
            }, 1000);
        }
    });

    categorySelect.addEventListener('change', function(e) {
        if (searchInput.value.trim().length >= 2) {
            performSearch();
        }
    });

    function performSearch() {
        const searchValue = searchInput.value.trim();

        if (searchValue.length < 2) {
            showSearchMessage('@lang("ecommerce::ecommerce.min_search_chars")', 'warning');
            return;
        }

        if (searchValue === lastSearchValue) {
            return;
        }

        lastSearchValue = searchValue;

        searchBtn.disabled = true;
        searchBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> @lang("ecommerce::ecommerce.searching")';

        up.submit(searchForm, {
            feedback: false
        });

        setTimeout(() => {
            searchBtn.disabled = false;
            searchBtn.innerHTML = '@lang("ecommerce::ecommerce.search") <i class="fa fa-search" aria-hidden="true"></i>';
        }, 1000);
    }

    function showSearchMessage(message, type) {
        const existingMessage = document.querySelector('.search-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        const messageClass = type === 'warning' ? 'alert-warning' : 'alert-info';

        const isRtl = document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
        const hPos = isRtl ? 'left: 20px; right: auto;' : 'right: 20px; left: auto;';

        const messageHTML = `
            <div class="alert ${messageClass} alert-dismissible fade show search-message"
                 style="position: fixed; top: 100px; ${hPos} z-index: 9999; min-width: 300px;">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', messageHTML);

        setTimeout(() => {
            const messageElement = document.querySelector('.search-message');
            if (messageElement) {
                messageElement.remove();
            }
        }, 3000);
    }

    up.on('up:fragment:inserted', function(event) {
        if (event.target.matches('.main-content') || event.target.closest('.main-content')) {
            setTimeout(initializeSearch, 100);
        }
    });





console.log('🛠️ App framework loaded');


function setupMerchantPage() {
    const isMerchantPage = document.querySelector('.merchant-page-header') !== null;
    if (!isMerchantPage) return;

    const categoryLinks = document.querySelectorAll('.category-list a[up-target]');
    categoryLinks.forEach(link => {
        link.dataset.merchantHandled = 'true';
    });

    if (typeof up !== 'undefined') {
        up.on('up:fragment:inserted', function(event) {
            if (event.target.matches('.products-main') || event.target.closest('.products-main')) {
                updateMerchantActiveCategory();
            }
        });

        up.on('up:location:changed', function(event) {
            updateMerchantActiveCategory();
        });
    }
}

function updateMerchantActiveCategory() {
    const urlParams = new URLSearchParams(window.location.search);
    const currentCategory = urlParams.get('category') || 'all';

    document.querySelectorAll('.category-list a').forEach(link => {
        const url = new URL(link.href);
        const linkCategory = url.searchParams.get('category') || 'all';

        if (linkCategory === currentCategory) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

setTimeout(setupMerchantPage, 100);


    // up.fragment.config.mainTargets = ['.category-page-container'];
    // up.network.config.progressBar = true;

    // up.compiler('.pagination a', function(link) {
    //     link.setAttribute('up-target', '.category-page-container');
    //     link.setAttribute('up-scroll', 'false');
    //     link.setAttribute('up-history', 'true');
    // });


    // up.on('up:fragment:updated', function(event) {
    //     // initImages();
    //     console.log('Page updated via Unpoly!');
    // });


</script>
