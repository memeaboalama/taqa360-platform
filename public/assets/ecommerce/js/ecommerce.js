/*-----------------------------------------------------------------------------------

 Template Name: reno
 Template URI: http://themes.pixelstrap.com/reno/
 Description: This Is multipurpose service bases template
 Author: Pixelstrap
 Author URI : https://themeforest.net/author_dashboard

 ----------------------------------------------------------------------------------- */
/* 01.Main slider *\
/* 02.Main slider js *\
/* 03.Tab js *\
/* 04. Menu */
/* 05. header fix */
/* 06. drop-list js */

(function($) {

    /*=====================
     01.Main slider js
     ==========================*/
    $('.slide-1').slick({
        infinite: true,
        margin:5
    });
    /*=====================
     02.Main slider js
     ==========================*/
    $(".toggle-btn").click(function(){
        $(".cat-block").slideToggle("slow");
        $(".hanger").toggleClass("hide");
    });
    
    /*=====================
     03.Tab js
     ==========================*/
    /*=====================
     03.Tab js
     ==========================*/
    /* Code removed to fix conflict with Unpoly/Server-side tabs */
    function initProduct5Slider() {
        $('.product-5').not('.slick-initialized').slick({
            dots: false,
            infinite: true,
            margin:30,
            speed: 300,
            arrow: false,
            slidesToShow: 5,
            slidesToScroll: 5,
            responsive: [
                {
                    breakpoint: 1367,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4
                    }
                },
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        infinite: true
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3
                    }
                },
                {
                    breakpoint: 575,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });
    }

    // Initialize on load
    initProduct5Slider();

    // Re-initialize after Unpoly content updates
    $(document).on('up:fragment:inserted', function() {
        initProduct5Slider();
    });
    $('.product-five').slick({
        dots: false,
        infinite: true,
        margin:30,
        speed: 300,
        arrow: false,
        slidesToShow: 5,
        slidesToScroll: 5,
        responsive: [
            {
                breakpoint: 1367,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4
                }
            },
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true
                }
            },
            {
                breakpoint: 900,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 767,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3
                }
            },
            {
                breakpoint: 575,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
    $(".vert-midd").slick({
        arrows: true,
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 2,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    /*=====================
    04. Menu
    ==========================*/
    $(window).on('load', function() {
        $('#main-menu').smartmenus({
            subMenusSubOffsetX: 1,
            subMenusSubOffsetY: -8
        });
        $('#sub-menu').smartmenus({
            subMenusSubOffsetX: 1,
            subMenusSubOffsetY: -8
        });
    });

    /*=====================
    05. header fix
    ==========================*/
    $(window).scroll(function(){
        if ($(window).scrollTop() >= 600) {
            $('.main-header').addClass('header-fix');
        }
        else {
            $('.main-header').removeClass('header-fix');
        }
    });

    /*=====================
    06. drop-list js
    ==========================*/
    $(window).scroll(function(){
        if ($(window).scrollTop() >= 600) {
            $('.hanger').addClass('set-top');
        }
        else {
            $('.hanger').removeClass('set-top');
        }
    });

})(jQuery);







// ==========================================
// Global Notification System (Unpoly Integration)
// ==========================================

window.showNotification = function(message, type = 'success') {
    // Remove existing notification if any
    const existingMessage = document.querySelector('.global-notification');
    if (existingMessage) {
        existingMessage.remove();
    }

    // Determine class and icon
    const messageClass = type === 'warning' ? 'alert-warning' : (type === 'danger' ? 'alert-danger' : 'alert-success');
    const icon = type === 'checked' || type === 'success' ? '<i class="fa fa-check-circle"></i>' : '<i class="fa fa-info-circle"></i>';

    // Position based on language direction: Arabic (RTL) => left, English (LTR) => right
    const isRtl = document.documentElement.getAttribute('dir') === 'rtl' || document.body.classList.contains('rtl');
    const hPos = isRtl ? 'left: 20px; right: auto;' : 'right: 20px; left: auto;';

    // Create the alert HTML
    const messageHTML = `
        <div class="alert ${messageClass} alert-dismissible fade show global-notification"
             style="position: fixed; top: 100px; ${hPos} z-index: 99999; min-width: 300px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            ${icon} ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;

    // Append to body
    document.body.insertAdjacentHTML('beforeend', messageHTML);

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        const messageElement = document.querySelector('.global-notification');
        if (messageElement) {
            // Optional: CSS transition for smoother removal could go here
            messageElement.remove();
        }
    }, 5000);
};

// Initialize Unpoly Listener
if (typeof up !== 'undefined') {
    up.on('up:request:loaded', function(event) {
        const xhr = event.request.xhr;

        // Check for Custom Notification Header
        if (xhr && xhr.getResponseHeader('X-App-Notification')) {
            const raw = xhr.getResponseHeader('X-App-Notification');
            try {
                // Decode: Base64 -> UTF-8 String
                // Note: Server sends base64_encode(json_encode(['type' => '...', 'message' => '...']))
                const decoded = decodeURIComponent(escape(atob(raw))); 
                let data;
                
                // Parse JSON
                if (decoded.trim().startsWith('{')) {
                    data = JSON.parse(decoded);
                } else {
                    // Fallback for legacy "type|message" format
                    const parts = decoded.split('|');
                    data = { type: parts[0] || 'success', message: parts[1] };
                }
                
                // Show Notification
                if (data && data.message) {
                   window.showNotification(data.message, data.type || 'success');
                }
            } catch (e) {
                console.error('Notification decode failed:', e);
            }
        }
    });
} else {
    // console.warn('Unpoly not found. Notification listener not attached.');
}

// Show the quick-view modal after its content is swapped in by Unpoly
if (typeof up !== 'undefined') {
    up.on('up:fragment:inserted', function(event) {
        const result = event.renderResult;
        const fragment = result && result.fragment;
        if (fragment && fragment.id === 'quick-view' && window.jQuery && jQuery.fn.modal) {
            jQuery(fragment).modal('show');
        }
    });
}

// ==========================================
// Quick View modal (Bootstrap 3 + jQuery AJAX)
// ==========================================
// The modal markup (#quick-view) is included AFTER the scripts in the layout,
// so we resolve it at click time instead of at load time.
(function($) {
    // Open the modal and load product content.
    // Capture phase + stopPropagation so Unpoly does not follow the link.
    document.addEventListener('click', function(e) {
        const $trigger = $(e.target).closest('[data-quick-view]');
        if (!$trigger.length) return;

        e.preventDefault();
        e.stopImmediatePropagation();
        e.stopPropagation();

        const $modal = $('#quick-view');
        if (!$modal.length) return;

        const url = $trigger.attr('data-quick-view') || $trigger.attr('href');
        if (!url) return;

        const $icon = $trigger.find('i');
        const originalIconClass = $icon.length ? $icon.attr('class') : '';
        if ($icon.length) {
            $icon.attr('class', 'fa fa-spinner fa-spin');
        }

        const restoreIcon = function() {
            if ($icon.length) {
                $icon.attr('class', originalIconClass);
            }
        };

        $('#quick-view-body').html(
            '<div class="text-center py-5"><i class="fa fa-spinner fa-spin fa-2x"></i></div>'
        );

        $.get(url, function(html) {
            $('#quick-view-body').html(html);
            try {
                if (window.up && up.compiler.compile) {
                    up.compiler.compile($modal[0]);
                }
            } catch (err) {}
            restoreIcon();
            $modal.modal('show');
        }).fail(function() {
            $('#quick-view-body').html(
                '<div class="alert alert-danger text-center my-3">Failed to load product details</div>'
            );
            restoreIcon();
            $modal.modal('show');
        });
    }, true);

    // Delegated quick-view listeners, guarded so re-execution of this file
    // (e.g. Unpoly full-layout swaps) does not stack duplicate listeners.
    if (!window.__ecommerceQuickViewBound) {
        window.__ecommerceQuickViewBound = true;

        // Quantity controls inside the dynamically loaded modal content
        document.addEventListener('click', function(e) {
            const $plus = $(e.target).closest('.quantity-right-plus');
            if ($plus.length && $plus.closest('#quick-view').length) {
                const input = $plus.closest('.input-group').find('input.input-number');
                const val = parseInt(input.val(), 10) || 1;
                input.val(val + 1);
                return;
            }

            const $minus = $(e.target).closest('.quantity-left-minus');
            if ($minus.length && $minus.closest('#quick-view').length) {
                const input = $minus.closest('.input-group').find('input.input-number');
                const val = parseInt(input.val(), 10) || 1;
                if (val > 1) {
                    input.val(val - 1);
                }
            }
        }, true);

        // Guarantee the modal close button works
        document.addEventListener('click', function(e) {
            const $close = $(e.target).closest('#quick-view [data-dismiss="modal"], #quick-view .quick-view-close');
            if ($close.length && $('#quick-view').length) {
                e.preventDefault();
                $('#quick-view').modal('hide');
            }
        }, true);
    }

    // Product page quantity controls.
    // Delegated so they keep working after Unpoly fragment swaps and back/forward cache restores,
    // where the buttons are replaced but a delegated listener on document keeps firing.
    // Guarded so re-execution of this file (e.g. Unpoly full-layout swaps) does not stack duplicate listeners.
    if (!window.__ecommerceQtyBound) {
        window.__ecommerceQtyBound = true;

        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.quantity-right-plus, .quantity-left-minus');
            if (!btn) return;
            if (btn.closest('#quick-view')) return;

            const quantityInput = document.getElementById('product-quantity');
            const cartQuantityInput = document.getElementById('cart-quantity');
            const buyNowQuantityInput = document.getElementById('buy-now-quantity');
            if (!quantityInput || !cartQuantityInput || !buyNowQuantityInput) return;

            e.preventDefault();

            let currentVal = parseInt(quantityInput.value, 10);
            if (isNaN(currentVal) || currentVal < 1) currentVal = 1;

            if (btn.classList.contains('quantity-right-plus')) {
                currentVal += 1;
            } else {
                if (currentVal <= 1) return;
                currentVal -= 1;
            }

            quantityInput.value = currentVal;
            cartQuantityInput.value = currentVal;
            buyNowQuantityInput.value = currentVal;
        });

        // Keep the hidden cart/buy-now quantities in sync when the user types directly into the input
        document.addEventListener('input', function(e) {
            if (e.target.id !== 'product-quantity') return;
            let value = parseInt(e.target.value, 10);
            if (isNaN(value) || value < 1) value = 1;
            const cartQuantityInput = document.getElementById('cart-quantity');
            const buyNowQuantityInput = document.getElementById('buy-now-quantity');
            if (cartQuantityInput) cartQuantityInput.value = value;
            if (buyNowQuantityInput) buyNowQuantityInput.value = value;
        });
    }
})(jQuery);
