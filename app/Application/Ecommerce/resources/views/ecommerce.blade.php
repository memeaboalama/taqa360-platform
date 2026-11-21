@extends('ecommerce::layout.store-layout')

{{-- @section bodies are evaluated BEFORE the parent layout runs, so anything the
     sections need must be resolved here, not in store-layout. --}}
@php
    $currentLocale = $currentLocale ?? app()->getLocale();
    $isRTL = $isRTL ?? in_array($currentLocale, ['ar', 'he', 'fa', 'ur'], true);
@endphp

@section('title', __('ecommerce::ecommerce.home'))

@section('active-nav', 'home')

@section('page-styles')
    <style>
        /* ======================================================
               HERO SLIDER — Database banners
               ====================================================== */
        .hero {
            margin-top: 18px;
            border-radius: 18px;
            overflow: hidden;
            position: relative;
            background: #f1f4f8;
            background-image: radial-gradient(circle at 100% 50%, rgba(220, 230, 242, 0.45) 0%, transparent 60%);
            min-height: 310px;
            user-select: none;
        }

        /* Each slide combines the featured banner with its neighboring banners. */
        .hero-slide {
            display: none;
            direction: rtl;
            align-items: center;
            justify-content: space-between;
            padding: 24px 30px;
            gap: 24px;
            min-height: 310px;
        }

        .hero-slide.active {
            display: flex;
        }

        html[dir="ltr"] .hero-slide {
            direction: ltr;
        }

        /* ---- Right side: text content ---- */
        .hero-content {
            flex: 0 0 auto;
            width: 32%;
            text-align: right;
            z-index: 2;
        }

        html[dir="ltr"] .hero-content {
            text-align: left;
        }

        .hero h1 {
            margin: 0;
            font-size: 28px;
            line-height: 1.38;
            font-weight: 800;
            color: #17212f;
        }

        .hero h1 span {
            color: #17212f;
            display: block;
        }

        .hero p {
            color: #555f6d;
            line-height: 1.75;
            margin: 12px 0 22px;
            font-size: 13.5px;
            max-width: 360px;
        }

        /* Orange CTA button */
        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--yellow);
            color: #fff;
            border: none;
            border-radius: 999px;
            padding: 11px 26px;
            font-size: 13.5px;
            font-weight: 700;
            font-family: inherit;
            cursor: pointer;
            text-decoration: none !important;
            box-shadow: 0 4px 14px rgba(48, 111, 170, .28);
            transition: background .22s ease, transform .18s ease, box-shadow .22s ease;
        }

        .hero-cta:hover {
            background: var(--yellow-deep);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(48, 111, 170, .30);
            color: #fff;
            text-decoration: none !important;
        }

        .hero-cta svg {
            flex: 0 0 auto;
        }

        /* ---- Three-image banner composition ---- */
        .hero-images {
            flex: 1 1 68%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 9px;
            min-width: 0;
            height: 260px;
        }

        .hero-gallery {
            display: flex;
            direction: ltr;
            align-items: flex-end;
            justify-content: center;
            gap: 12px;
            width: 100%;
            height: 240px;
        }

        .hero-gallery-card {
            display: block;
            flex: 0 0 210px;
            min-width: 0;
            width: 210px;
            height: 210px;
            padding: 0;
            overflow: hidden;
            border: 0;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 5px 14px rgba(29, 37, 48, .14);
            cursor: pointer;
            transform-origin: center bottom;
            transition: transform .28s ease, opacity .28s ease;
        }

        .hero-gallery-card:not(.is-disabled) {
            text-decoration: none;
        }

        .hero-gallery-card.is-disabled {
            cursor: default;
        }

        .hero-gallery-card:nth-child(2) {
            transform: scale(.82);
        }

        .hero-gallery-card:nth-child(3) {
            transform: scale(.68);
        }

        .hero-gallery-card picture,
        .hero-gallery-card img {
            display: block;
            width: 100%;
            height: 100%;
        }

        .hero-gallery-card img {
            object-fit: cover;
            object-position: center;
            border-radius: 14px;
        }

        .hero-gallery-card:focus-visible {
            outline: 3px solid #306faa;
            outline-offset: 3px;
        }

        .hero-gallery--single .hero-gallery-card {
            flex-basis: 210px;
            height: 210px;
            cursor: default;
        }

        /* ---- Pagination dots underneath the banner image ---- */
        .hero-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            direction: ltr;
            margin: 0;
            padding: 0;
        }

        .hero-dot {
            border: none;
            padding: 0;
            cursor: pointer;
            border-radius: 999px;
            background: #cbd5e1;
            transition: width .28s ease, background .28s ease;
            height: 7px;
            width: 7px;
        }

        .hero-dot.active {
            width: 22px;
            background: var(--yellow);
        }

        /* legacy .dot kept for backward compat */
        .dot {
            display: none;
        }

        .primary-btn {
            border-radius: 22px;
            padding: 11px 23px;
            font-size: 12px;
            box-shadow: 0 4px 9px rgba(246, 181, 0, .16);
            text-decoration: none !important
        }

        .hero-cta:link,
        .hero-cta:visited,
        .hero-cta:hover,
        .hero-cta:focus,
        .small-btn:link,
        .small-btn:visited,
        .small-btn:hover,
        .small-btn:focus,
        .primary-btn:link,
        .primary-btn:visited,
        .primary-btn:hover,
        .primary-btn:focus {
            text-decoration: none !important
        }

        .categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(96px, 1fr));
            gap: 6px 3px;
            padding: 12px 10px;
            margin-top: 15px;
            border: 1px solid #edf0f2;
            border-radius: 9px;
            box-shadow: 0 3px 12px rgba(29, 37, 48, .045)
        }

        .category {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 6px 2px;
            border-radius: 10px;
            text-align: center;
            text-decoration: none;
            color: #343a42;
            transition: background .2s ease, box-shadow .2s ease
        }

        .category:hover {
            background: #f7fafd;
            box-shadow: 0 2px 8px rgba(29, 37, 48, .06)
        }

        .category-icon {
            width: 52px;
            height: 52px;
            flex: 0 0 auto;
            border-radius: 50%;
            display: grid;
            place-items: center;
            background: #eef4fa;
            color: #EA993E
        }

        .category:nth-child(5n + 1) .category-icon {
            background: #f1efff;
            color: #7665d8
        }

        .category:nth-child(5n + 2) .category-icon {
            background: #eaf7f1;
            color: #45a986
        }

        .category:nth-child(5n + 3) .category-icon {
            background: #fff0ef;
            color: #e97872
        }

        .category:nth-child(5n + 4) .category-icon {
            background: #eaf7f1;
            color: #45a986
        }

        .category:nth-child(5n) .category-icon {
            background: #f3efff;
            color: #7665d8
        }

        .category .category-icon.has-image {
            border-radius: 0;
            background: transparent;
        }

        .category-icon svg {
            width: 22px;
            height: 22px;
            fill: none;
            stroke: currentColor;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round
        }

        .category-icon img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .category span {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 12px;
            font-weight: 600;
            line-height: 1.45;
            word-break: break-word
        }

        .promo-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 17px;
            margin-top: 21px
        }

        .promo {
            height: 160px;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            padding: 22px 19px;
        }

        a.promo {
            color: inherit;
            text-decoration: none;;
            background-size: cover;
            background-position: center
        }

        .promo-content {
            position: relative;
            z-index: 1;
            width: 55%;
            color: #fff;
            text-shadow: 0 1px 4px rgba(0, 0, 0, .65)
        }

        .promo h3 {
            margin: 0 0 7px;
            font-size: 13px;
            line-height: 1.6
        }

        .promo p {
            margin: 0 0 10px;
            font-size: 12px
        }

        .small-btn {
            background: #fff;
            color: #306faa;
            padding: 7px 15px;
            border-radius: 15px;
            font-size: 10px;
            text-decoration: none !important;
            text-shadow: none
        }

        .section-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 25px 0 13px
        }

        .section-head h2 {
            margin: 0;
            font-size: 15px;
            padding-right: 12px;
            border-right: 3px solid var(--yellow)
        }

        .view-all {
            color: #EA993E;
            text-decoration: none;
            font-size: 10px
        }

        .products {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px
        }

        .products[hidden] {
            display: none
        }

        .products-carousel-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 12px
        }

        .products-carousel-controls button {
            display: inline-flex;
            width: 30px;
            height: 30px;
            align-items: center;
            justify-content: center;
            border: 1px solid #dfe5eb;
            border-radius: 50%;
            background: #fff;
            color: #306FAA;
            font-size: 16px
        }

        .products-carousel-status {
            color: #747b84;
            font-size: 11px
        }

        .product {
            min-height: 235px;
            border: 1px solid #e5e9ed;
            border-radius: 8px;
            padding: 12px 13px 10px;
            background: #fff;
            box-shadow: 0 3px 12px rgba(29, 37, 48, .07);
            position: relative;
            text-align: start
        }

        .favorite {
            background: transparent;
            border: 0;
            color: #9da5ad;
            position: absolute;
            z-index: 2;
            top: 9px;
            left: 9px;
            width: 22px;
            height: 22px;
            padding: 0;
            cursor: pointer;
            font-size: 16px
        }

        .product-image {
            height: 132px;
            width: 100%;
            object-fit: contain;
            display: block
        }

        .product h3 {
            font-size: 14px;
            margin: 2px 0 4px
        }

        .rating {
            font-size: 16px;
            color: #EA993E;
            direction: ltr;
            unicode-bidi: isolate
        }

        .rating span {
            color: #777;
            margin-inline-start: 4px
        }

        .price {
            font-weight: 700;
            font-size: 14px;
            direction: ltr;
            unicode-bidi: isolate;
            text-align: start;
            margin-top: 1px
        }

        [dir="rtl"] .product .rating,
        [dir="rtl"] .product .price {
            text-align: right
        }

        .add-cart {
            width: 100%;
            height: 33px;
            margin-top: 8px;
            border-radius: 18px;
            color: #ffffff;
            background: var(--yellow);
            font-size: 12px;
            font-weight: 700
        }

        .wide-banner {
            height: 150px;
            border-radius: 8px;
            margin-top: 18px;
            background-position: center;
            background-size: cover;
            position: relative;
            padding: 13px 70px
        }

        .wide-banner-content {
            position: absolute;
            right: 9%;
            top: 50%;
            width: 310px;
            z-index: 1;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
            transform: translateY(-50%)
        }

        .wide-banner h2 {
            margin: 0;
            font-size: 19px
        }

        .wide-banner p {
            margin: 0;
            color: #7b8086;
            font-size: 13px
        }

        .wide-banner .primary-btn {
            padding: 5px 18px;
            font-size: 10px
        }

        .benefits {
            margin-top: 15px;
            padding: 12px 30px;
            background: #fafbfc;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            text-align: center;
            gap: 15px
        }

        .benefit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px
        }

        .benefit-icon {
            font-size: 22px
        }

        .benefit strong {
            display: block;
            font-size: 16px
        }

        .benefit span {
            display: block;
            font-size: 14px;
            color: #858a90;
            margin-top: 3px
        }

        .extra-section {
            padding: 25px 0
        }

        .product-section {
            position: relative;
            isolation: isolate;
            margin: 12px 0;
            padding: 34px 0;
            background: transparent
        }

        .product-section::before {
            position: absolute;
            z-index: -1;
            top: 0;
            bottom: 0;
            left: 50%;
            width: 100vw;
            transform: translateX(-50%);
            background: #fff;
            content: ""
        }

        .product-section--soft::before {
            background: #f3f6f8
        }

        .product-section--watermark::after {
            position: absolute;
            z-index: 0;
            bottom: 0;
            width: 300px;
            height: 220px;
            background: url('{{ asset($platformBrand['logo']) }}') no-repeat center / contain;
            content: "";
            opacity: .14;
            pointer-events: none
        }

        .product-section--watermark-left::after {
            left: 0
        }

        .product-section--watermark-right::after {
            right: 0
        }

        .product-section .section-head,
        .product-section .products {
            position: relative;
            z-index: 1
            pointer-events: none;
        }

        .wide-banner-image-link {
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .wide-banner-content .primary-btn {
            pointer-events: auto;
        }

        .product-section .section-head {
            margin: 0 0 20px
        }

        @media(max-width:800px) {
            .product-section--watermark::after {
                width: 190px;
                height: 140px;
                opacity: .1
            }
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px
        }

        @media(max-width:1100px) {
            .news-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:800px) {
            .news-grid {
                grid-template-columns: 1fr;
                gap: 14px
            }
        }

        .news-card {
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e8eb;
            border-radius: 12px;
            overflow: hidden;
            background: #fff;
            box-shadow: 0 6px 18px rgba(29, 37, 48, .06);
            text-decoration: none;
            color: inherit;
            transition: transform .2s ease, box-shadow .2s ease
        }

        .news-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 24px rgba(29, 37, 48, .1)
        }

        .news-card-img {
            width: 100%;
            height: 160px;
            object-fit: cover;
            display: block;
            background: #f2f5f8
        }

        .news-card-body {
            padding: 12px 16px 16px
        }

        .news-card-body h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.55
        }

        .news-card-body p {
            margin: 6px 0 0;
            color: #858a90;
            font-size: 12px;
            line-height: 1.7
        }

        .brands-grid {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 20px
        }

        @media(max-width:1100px) {
            .brands-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr))
            }
        }

        @media(max-width:800px) {
            .brands-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px
            }
        }

        .brand-card {
            display: grid;
            place-items: center;
            min-height: 110px;
            padding: 18px;
            border: 1px solid #e5e8eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(29, 37, 48, .05);
            transition: box-shadow .2s ease
        }

        .brand-card:hover {
            box-shadow: 0 8px 20px rgba(29, 37, 48, .1)
        }

        .brand-card img {
            max-width: 100%;
            max-height: 56px;
            object-fit: contain;
            display: block
        }

        .testimonials-slider {
            overflow: hidden
        }

        .testimonials-track {
            display: flex;
            gap: 20px;
            overflow-x: auto;
            padding: 2px 2px 12px;
            scroll-snap-type: x mandatory;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth
        }

        .testimonials-track::-webkit-scrollbar {
            display: none
        }

        .testimonial-card {
            flex: 0 0 calc((100% - 40px) / 3);
            border: 1px solid #e5e8eb;
            border-radius: 12px;
            background: #fff;
            padding: 18px;
            box-shadow: 0 4px 12px rgba(29, 37, 48, .05);
            scroll-snap-align: start
        }

        .testimonial-card h3 {
            margin: 0;
            font-size: 14px;
            font-weight: 700
        }

        .testimonial-card .testimonial-position {
            color: #EA993E;
            font-size: 11px;
            margin-top: 2px
        }

        .testimonial-card p {
            margin: 10px 0 0;
            color: #626870;
            font-size: 12px;
            line-height: 1.8
        }

        .testimonials-slider-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 12px
        }

        .testimonials-slider-arrow {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border: 1px solid #e5e8eb;
            border-radius: 50%;
            color: #343a42;
            background: #fff;
            cursor: pointer
        }

        .testimonials-slider-dots {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px
        }

        .testimonials-slider-dot {
            width: 8px;
            height: 8px;
            padding: 0;
            border-radius: 50%;
            background: #cbd2d9;
            cursor: pointer
        }

        .testimonials-slider-dot[aria-current="true"] {
            width: 20px;
            border-radius: 999px;
            background: var(--yellow-deep)
        }

        @media(max-width:800px) {
            .testimonial-card {
                flex-basis: calc((100% - 20px) / 2)
            }
        }

        @media(max-width:520px) {
            .testimonials-track {
                gap: 14px
            }

            .testimonial-card {
                flex-basis: 100%
            }
        }

        .testimonial-float {
            position: fixed;
            z-index: 1040;
            inset-inline-end: 22px;
            bottom: 24px;
            display: inline-flex;
            align-items: center;
            gap: 9px;
            padding: 12px 18px;
            border-radius: 999px;
            color: #fff;
            background: var(--yellow-deep);
            box-shadow: 0 6px 20px rgba(32, 37, 45, .2);
            font-weight: 700;
            cursor: pointer
        }

        .testimonial-float:hover,
        .testimonial-float:focus-visible {
            color: #fff;
            background: var(--yellow);
            transform: translateY(-2px)
        }

        .testimonial-float i {
            font-size: 17px
        }

        .testimonial-dialog {
            width: min(520px, calc(100% - 28px));
            max-height: min(90vh, 720px);
            padding: 0;
            border: 0;
            border-radius: 16px;
            color: var(--ink);
            box-shadow: 0 20px 70px rgba(0, 0, 0, .28)
        }

        .testimonial-dialog::backdrop {
            background: rgba(15, 23, 42, .58)
        }

        .testimonial-dialog-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 18px 22px;
            color: #fff;
            background: var(--yellow-deep)
        }

        .testimonial-dialog-header h2 {
            margin: 0;
            font-size: 18px
        }

        .testimonial-dialog-close {
            padding: 4px 8px;
            color: #fff;
            background: transparent;
            font-size: 22px
        }

        .testimonial-dialog-body {
            padding: 22px
        }

        .testimonial-dialog-body label {
            display: block;
            margin: 0 0 6px;
            font-weight: 700
        }

        .testimonial-dialog-body input,
        .testimonial-dialog-body textarea {
            display: block;
            width: 100%;
            margin-bottom: 14px;
            padding: 10px 12px;
            border: 1px solid #dfe3e8;
            border-radius: 8px;
            direction: {{ $isRTL ? 'rtl' : 'ltr' }};
            text-align: {{ $isRTL ? 'right' : 'left' }}
        }

        .testimonial-dialog-body textarea {
            min-height: 130px;
            resize: vertical
        }

        .testimonial-dialog-note {
            margin: 0 0 16px;
            color: #6b7280;
            line-height: 1.7
        }

        .testimonial-form-alert {
            margin-bottom: 16px;
            padding: 11px 13px;
            border-radius: 8px;
            color: #165b34;
            background: #e8f6ed;
            line-height: 1.7
        }

        .testimonial-field-error {
            display: block;
            margin: -10px 0 12px;
            color: #b42318;
            font-size: 12px
        }

        .testimonial-honeypot {
            position: absolute;
            inset-inline-start: -10000px;
            width: 1px;
            height: 1px;
            overflow: hidden
        }

        @media(max-width:520px) {
            .testimonial-float {
                inset-inline-end: 14px;
                bottom: 16px;
                padding: 12px 14px
            }

            .testimonial-dialog-body {
                padding: 18px
            }
        }

        .stores-section {
            padding: 28px 0 34px
        }

        .stores-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px
        }

        .stores-heading h2 {
            margin: 0;
            padding-right: 12px;
            border-right: 3px solid var(--yellow);
            font-size: 19px;
            font-weight: 400
        }

        .stores-heading a {
            color: #EA993E;
            font-size: 11px;
            text-decoration: none
        }

        .stores-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 24px;
            max-width: 1160px;
            margin: auto
        }

        @media(max-width:1100px) {
            .stores-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        .store-card {
            overflow: hidden;
            border: 1px solid #e5e8eb;
            border-radius: 12px;
            background: #fff;
            box-shadow: 0 6px 18px rgba(29, 37, 48, .06)
        }

        .store-cover {
            height: 150px;
            position: relative;
            background-position: center;
            background-size: cover
        }

        .trusted-badge {
            position: absolute;
            top: 12px;
            right: 14px;
            padding: 4px 9px;
            border-radius: 14px;
            background: #fff;
            color: #3bbb4a;
            font-size: 10px
        }

        .store-info {
            padding: 14px 18px 18px
        }

        .store-title-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px
        }

        .store-title-row h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 400
        }

        .store-rating {
            color: var(--yellow-deep);
            font-size: 12px
        }

        .store-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
            margin-top: 12px
        }

        .store-tags span {
            padding: 5px 9px;
            border: 1px solid #e7e8ea;
            border-radius: 16px;
            color: #777;
            background: #fafafa;
            font-size: 10px
        }

        .store-stats {
            display: flex;
            justify-content: space-between;
            border-top: 1px dashed #e5e5e5;
            margin-top: 15px;
            padding-top: 12px;
            color: #777;
            font-size: 10px
        }

        .store-link {
            display: block;
            margin-top: 15px;
            padding: 8px;
            border: 1px solid var(--yellow);
            border-radius: 7px;
            color: #000;
            text-align: center;
            text-decoration: none;
            font-size: 11px
        }

        @media(max-width:992px) {
            .hero {
                min-height: 280px;
            }

            .hero-slide {
                padding: 20px 22px;
                gap: 18px;
                min-height: 280px;
            }

            .hero-content {
                width: 34%;
            }

            .hero h1 {
                font-size: 21px;
            }

            .hero p {
                font-size: 12px;
                margin: 8px 0 16px;
            }

            .hero-images {
                height: 225px;
                gap: 8px;
            }

            .hero-gallery {
                height: 205px;
                gap: 8px;
            }

            .hero-gallery-card {
                flex-basis: 185px;
                width: 185px;
                height: 185px;
            }

            .hero-gallery-card img {
                border-radius: 14px;
            }

            .hero-gallery--single .hero-gallery-card {
                height: 185px;
            }
        }

        @media(max-width:768px) {
            .hero-slide {
                flex-direction: column-reverse;
                align-items: center;
                padding: 18px 14px 22px;
                gap: 12px;
            }

            .hero-content {
                width: 100%;
                text-align: center;
            }

            .hero h1 {
                font-size: 19px;
            }

            .hero p {
                margin-right: auto;
                margin-left: auto;
                font-size: 12px;
            }

            .hero-images {
                width: 100%;
                height: 170px;
                gap: 6px;
            }

            .hero-gallery {
                height: 145px;
                gap: 0;
            }

            .hero-gallery-card {
                display: none;
            }

            .hero-gallery-card.is-current {
                display: block;
                flex: 0 1 48%;
                width: auto;
                height: 145px;
                transform: none;
            }

            .hero-gallery--single .hero-gallery-card {
                height: 145px;
            }
        }

        @media(max-width:800px) {
            .categories {
                grid-template-columns: repeat(4, minmax(0, 1fr));
                padding: 10px 4px;
                gap: 8px 2px
            }
        }

        @media(max-width:800px) {
            .category {
                padding: 5px 1px;
                gap: 5px
            }
        }

        @media(max-width:800px) {
            .category-icon {
                width: 42px;
                height: 42px
            }
        }

        @media(max-width:800px) {
            .category span {
                font-size: 10px
            }
        }

        @media(max-width:800px) {
            .promo-grid {
                grid-template-columns: 1fr;
                gap: 10px
            }
        }

        @media(max-width:800px) {
            .promo {
                height: 128px
            }
        }

        @media(max-width:800px) {
            .products {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px
            }
        }

        @media(max-width:800px) {
            .product {
                min-height: 220px;
                padding: 9px
            }
        }

        @media(max-width:800px) {
            .product-image {
                height: 105px
            }
        }

        @media(max-width:800px) {
            .product h3 {
                font-size: 10px
            }
        }

        @media(max-width:800px) {
            .rating {
                font-size: 10px
            }
        }

        @media(max-width:800px) {
            .price {
                font-size: 11px
            }
        }

        @media(max-width:800px) {
            .add-cart {
                height: 28px;
                font-size: 10px
            }
        }

        @media(max-width:800px) {
            .wide-banner {
                height: 125px;
                padding: 10px
            }
        }

        @media(max-width:800px) {
            .wide-banner-content {
                right: 6%;
                width: 48%
            }
        }

        @media(max-width:800px) {
            .wide-banner h2 {
                font-size: 14px
            }
        }

        @media(max-width:800px) {
            .wide-banner p {
                font-size: 9px
            }
        }

        @media(max-width:800px) {
            .benefits {
                grid-template-columns: repeat(2, 1fr);
                padding: 12px 4px
            }
        }

        @media(max-width:800px) {
            .benefit strong {
                font-size: 10px
            }
        }

        @media(max-width:800px) {
            .benefit span {
                font-size: 8px
            }
        }

        @media(max-width:800px) {
            .stores-grid {
                grid-template-columns: 1fr;
                gap: 16px
            }
        }

        @media(max-width:800px) {
            .store-title-row h3 {
                font-size: 15px
            }
        }

        .favorite.is-favorite {
            color: #EA993E
        }

        .ad-responsive-bg {
            background-image: var(--ad-image-desktop);
        }

        @media (max-width: 1024px) {
            .ad-responsive-bg {
                background-image: var(--ad-image-tablet, var(--ad-image-desktop));
            }
        }

        @media (max-width: 767px) {
            .ad-responsive-bg {
                background-image: var(--ad-image-mobile, var(--ad-image-desktop));
            }
        }
    </style>
@endsection

@section('content')
    @php
        $localized = static function ($value, $fallback = '') use ($currentLocale) {
            if (is_array($value)) {
                return $value[$currentLocale] ?? ($value['en'] ?? (reset($value) ?? $fallback));
            }
            return $value ?: $fallback;
        };
        $image = static function ($value, $fallback = 'assets/ecommerce/images/slider/4.webp') {
            return $value ?: asset($fallback);
        };
        $bannerBackgroundStyle = static function ($banner, $fallback = 'assets/ecommerce/images/slider/4.webp') use ($image) {
            $desktop = $image($banner->desktop_image_url ?? ($banner->image_desktop ?? null), $fallback);
            $tablet = $image($banner->tablet_image_url ?? null, $desktop);
            $mobile = $image($banner->mobile_image_url ?? null, $desktop);

            return "--ad-image-desktop:url('{$desktop}');--ad-image-tablet:url('{$tablet}');--ad-image-mobile:url('{$mobile}')";
        };
        $products = static function ($items) {
            return collect($items ?? [])
                ->filter()
                ->values();
        };
        $renderProduct = static function ($product) use ($image, $localized) {
            $title = $localized($product->title ?? null, __('ecommerce::ecommerce.product_label'));
            $productImage = $image($product->image_path ?? ($product->image ?? null));
            $price = (float) ($product->default_price ?? ($product->price ?? 0));
            return compact('title', 'productImage', 'price');
        };
    @endphp

    <div id="home" class="container home-page">
        @php
            $heroSlides = collect($mainSlider ?? [])
                ->map(function ($slide) {
                    if (is_array($slide)) {
                        $slide = (object) $slide;
                    }
                    $defaultImg = asset('assets/ecommerce/images/slider/4.webp');
                    $resolveImage = function (string $device) use ($slide) {
                        $image = method_exists($slide, 'getImageUrl')
                            ? $slide->getImageUrl($device)
                            : ($slide->{$device . '_image_url'} ??
                                data_get($slide, "content.images.{$device}") ??
                                ($slide->{'image_' . $device} ?? null));

                        if ($image && !filter_var($image, FILTER_VALIDATE_URL) && !str_starts_with($image, '/') && file_exists(public_path($image))) {
                            $image = asset($image);
                        }

                        return $image ?: null;
                    };
                    $slide->resolved_images = [
                        'desktop' => $resolveImage('desktop'),
                        'tablet' => $resolveImage('tablet'),
                        'mobile' => $resolveImage('mobile'),
                    ];
                    $slide->resolved_image = $slide->resolved_images['desktop']
                        ?: $slide->resolved_images['tablet']
                        ?: $slide->resolved_images['mobile']
                        ?: $defaultImg;
                    return $slide;
                })
                ->values();

            if ($heroSlides->isEmpty()) {
                $heroSlides = collect([
                    (object) [
                        'localized_title' => __('ecommerce::ecommerce.home_hero_title', [], $currentLocale),
                        'localized_subtitle' => __('ecommerce::ecommerce.home_hero_subtitle', [], $currentLocale),
                        'click_url' => '#products',
                        'resolved_image' => asset('assets/ecommerce/images/slider/4.webp'),
                    ],
                ]);
            }
        @endphp

        @if ($heroSlides->isNotEmpty())
            <section class="hero" id="hero-slider" aria-label="@lang('ecommerce::ecommerce.home_hero_aria')"
                @if (!empty($mainSliderBackground)) style="background-image: url('{{ $mainSliderBackground }}'); background-size: cover; background-position: center;" @endif>
                @foreach ($heroSlides as $idx => $slide)
                    @php
                        $slideCount = $heroSlides->count();
                        $galleryIndexes = array_values(
                            array_unique([$idx, ($idx + 1) % $slideCount, ($idx + 2) % $slideCount]),
                        );
                        $buttonText = data_get($slide, "click_action.button_text.{$currentLocale}");
                    @endphp
                    <div class="hero-slide {{ $idx === 0 ? 'active' : '' }}" data-slide="{{ $idx }}">
                        <div class="hero-content">
                            <h1>{{ $slide->localized_title ?: __('ecommerce::ecommerce.home_hero_title', [], $currentLocale) }}
                            </h1>
                            <p>{{ $slide->localized_subtitle ?: __('ecommerce::ecommerce.home_hero_subtitle', [], $currentLocale) }}
                            </p>
                            @if($slide->click_url)
                            <a class="hero-cta" href="{{ $slide->click_url }}" target="{{ $slide->target ?? '_self' }}" @if(($slide->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif>
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="4" />
                                    <line x1="12" y1="2" x2="12" y2="5" />
                                    <line x1="12" y1="19" x2="12" y2="22" />
                                    <line x1="4.22" y1="4.22" x2="6.34" y2="6.34" />
                                    <line x1="17.66" y1="17.66" x2="19.78" y2="19.78" />
                                    <line x1="2" y1="12" x2="5" y2="12" />
                                    <line x1="19" y1="12" x2="22" y2="12" />
                                    <line x1="4.22" y1="19.78" x2="6.34" y2="17.66" />
                                    <line x1="17.66" y1="6.34" x2="19.78" y2="4.22" />
                                </svg>
                                {{ $buttonText ?: __('ecommerce::ecommerce.shop_now', [], $currentLocale) }}
                            </a>
                            @endif
                        </div>
                        <div class="hero-images">
                            <div class="hero-gallery {{ $slideCount === 1 ? 'hero-gallery--single' : '' }}">
                                @foreach ($galleryIndexes as $galleryIndex)
                                    @php
                                        $gallerySlide = $heroSlides[$galleryIndex];
                                        $galleryImage = $gallerySlide->resolved_image ?? asset('assets/ecommerce/images/slider/4.webp');
                                        $galleryLabel = $gallerySlide->localized_title ?? __('ecommerce::ecommerce.home_hero_title', [], $currentLocale);
                                        $galleryAlt = is_object($gallerySlide) && method_exists($gallerySlide, 'getAltText')
                                            ? $gallerySlide->getAltText($currentLocale)
                                            : ($gallerySlide->localized_title ?? '');
                                    @endphp
                                    @if($gallerySlide->click_url)
                                        <a class="hero-gallery-card {{ $galleryIndex === $idx ? 'is-current' : '' }}"
                                            href="{{ $gallerySlide->click_url }}"
                                            target="{{ $gallerySlide->target ?? '_self' }}"
                                            @if(($gallerySlide->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif
                                            aria-label="{{ $galleryLabel }}">
                                            <picture>
                                                @if(!empty($gallerySlide->resolved_images['mobile']))
                                                    <source media="(max-width: 767px)" srcset="{{ $gallerySlide->resolved_images['mobile'] }}">
                                                @endif
                                                @if(!empty($gallerySlide->resolved_images['tablet']))
                                                    <source media="(max-width: 1024px)" srcset="{{ $gallerySlide->resolved_images['tablet'] }}">
                                                @endif
                                                <img src="{{ $galleryImage }}" alt="{{ $galleryAlt }}"
                                                    loading="{{ $galleryIndex === $idx ? 'eager' : 'lazy' }}">
                                            </picture>
                                        </a>
                                    @else
                                        <div class="hero-gallery-card is-disabled {{ $galleryIndex === $idx ? 'is-current' : '' }}"
                                            aria-label="{{ $galleryLabel }}">
                                            <picture>
                                                @if(!empty($gallerySlide->resolved_images['mobile']))
                                                    <source media="(max-width: 767px)" srcset="{{ $gallerySlide->resolved_images['mobile'] }}">
                                                @endif
                                                @if(!empty($gallerySlide->resolved_images['tablet']))
                                                    <source media="(max-width: 1024px)" srcset="{{ $gallerySlide->resolved_images['tablet'] }}">
                                                @endif
                                                <img src="{{ $galleryImage }}" alt="{{ $galleryAlt }}"
                                                    loading="{{ $galleryIndex === $idx ? 'eager' : 'lazy' }}">
                                            </picture>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                            <div class="hero-pagination" role="tablist" aria-label="Slides">
                                @foreach ($heroSlides as $dotIdx => $banner)
                                    <button class="hero-dot {{ $idx === $dotIdx ? 'active' : '' }}" type="button"
                                        role="tab" aria-selected="{{ $idx === $dotIdx ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $dotIdx + 1 }}" data-index="{{ $dotIdx }}"></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        @endif
        <section id="categories" class="categories">
            @foreach (collect($categories ?? []) as $category)
                <a class="category" href="{{ route('categories.index', ['main_category' => $category->id]) }}"><i
                        class="category-icon {{ $category->image ? 'has-image' : '' }}">
                        @if ($category->image)
                            <img src="{{ $category->image }}" alt="" loading="lazy">
                        @else
                            <svg viewBox="0 0 24 24" aria-hidden="true">
                                <path d="M5 4h14v16H5zM8 8h8M8 12h8M8 16h5" />
                            </svg>
                        @endif
                    </i><span>{{ $localized($category->title ?? ($category->name ?? null), __('ecommerce::ecommerce.home_category_fallback')) }}</span></a>
            @endforeach
        </section>
        <section id="offers" class="promo-grid">
            @foreach (collect($productBanners ?? [])->take(3) as $banner)
                @php $bannerButtonText = data_get($banner, "click_action.button_text.{$currentLocale}"); @endphp
                @if($banner->click_url)
                    <a class="promo ad-responsive-bg" href="{{ $banner->click_url }}" target="{{ $banner->target ?? '_self' }}"
                        @if(($banner->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif
                        style="{{ $bannerBackgroundStyle($banner) }}">
                @else
                    <article class="promo ad-responsive-bg" style="{{ $bannerBackgroundStyle($banner) }}">
                @endif
                    <div class="promo-content">
                        <h3>{{ $localized($banner->localized_title ?? ($banner->title ?? null), __('ecommerce::ecommerce.home_banner_fallback')) }}
                        </h3>
                        <p>{{ $localized($banner->localized_subtitle ?? ($banner->subtitle ?? null)) }}</p>
                        @if($banner->click_url)
                            <span class="small-btn">{{ $bannerButtonText ?: __('ecommerce::ecommerce.shop_now', [], $currentLocale) }}</span>
                        @endif
                    </div>
                @if($banner->click_url)
                    </a>
                @else
                    </article>
                @endif
            @endforeach
        </section>
        @php
            $productSections = collect([
                [
                    'products' => $bestSellers ?? [],
                    'title' => __('ecommerce::ecommerce.best_sellers'),
                    'id' => 'best-sellers',
                    'collection' => 'best-sellers',
                ],
                [
                    'products' => $featuredProducts ?? [],
                    'title' => __('ecommerce::ecommerce.featured_products'),
                    'id' => 'featured-products',
                    'collection' => 'featured-products',
                ],
                [
                    'products' => $newProducts ?? [],
                    'title' => __('ecommerce::ecommerce.new_products'),
                    'id' => 'new-products',
                    'collection' => 'new-products',
                ],
                [
                    'products' => $exclusiveProducts ?? [],
                    'title' => __('ecommerce::ecommerce.exclusive_collections'),
                    'id' => 'exclusive-products',
                    'collection' => 'exclusive-products',
                ],
            ])
                ->filter(fn($section) => collect($section['products'])->isNotEmpty())
                ->values();
            $midProductBanner = collect($largeBanner ?? [])->first();
            $bannerAfterSection = min(2, $productSections->count());
        @endphp
        @foreach ($productSections as $section)
            <section id="{{ $section['id'] }}"
                class="extra-section product-section{{ $loop->odd ? ' product-section--soft' : '' }}{{ in_array($loop->index, [0, 2, 4], true) ? ' product-section--watermark product-section--watermark-' . (intdiv($loop->index, 2) % 2 === 0 ? 'left' : 'right') : '' }}">
                <div class="section-head">
                    <h2>{{ $section['title'] }}</h2><a class="view-all"
                        href="{{ route('ecommerce.collection', ['section' => $section['collection']]) }}">@lang('ecommerce::ecommerce.view_all')
                        ></a>
                </div>
                @php $sectionSlides = $products($section['products'])->chunk(6)->values(); @endphp
                <div class="products-carousel" data-product-carousel>
                    @foreach ($sectionSlides as $slideIndex => $slideProducts)
                        <div class="products" data-product-slide
                             aria-hidden="{{ $slideIndex > 0 ? 'true' : 'false' }}"
                             @if($slideIndex > 0) hidden @endif>
                            @foreach ($slideProducts as $product)
                                @php $card = $renderProduct($product); @endphp
                                <article class="product"><button type="button" data-product-id="{{ $product->id }}"
                                        class="favorite{{ isset($favoriteIds[$product->id]) ? ' is-favorite' : '' }}"
                                        aria-label="@lang('ecommerce::ecommerce.add_to_wishlist')"><i
                                            class="{{ isset($favoriteIds[$product->id]) ? 'fa fa-heart' : 'ti-heart' }}"></i></button><a
                                        class="product-img-wrap" href="{{ route('product.static', $product->id) }}"><img
                                            class="product-image" src="{{ $card['productImage'] }}" alt="{{ $card['title'] }}"
                                            loading="lazy">
                                    @if (!$product->isInStock())
                                        <span class="product-stock-badge">@lang('ecommerce::ecommerce.out_of_stock')</span>
                                    @endif
                                </a>
                                <h3><a href="{{ route('product.static', $product->id) }}"
                                        style="color:inherit;text-decoration:none">{{ $card['title'] }}</a></h3>
                                @include('ecommerce::partials.product.rating', ['product' => $product])
                                <div class="price">{{ \App\Domain\AppBase\Models\Setting\Currency::format($card['price']) }}
                                </div>
                                <form class="add-cart-form" action="{{ route('cart.add') }}" method="POST">@csrf<input
                                        type="hidden" name="item_id" value="{{ $product->id }}"><input type="hidden"
                                        name="qty" value="1">
                                    @if (!$product->isInStock())
                                        <button class="add-cart" type="button" disabled
                                        style="background:#e5e7eb !important;color:#9ca3af !important;cursor:not-allowed">@lang('ecommerce::ecommerce.out_of_stock')</button>@else<button
                                            class="add-cart" type="submit"><i class="ti-shopping-cart"></i>
                                            @lang('ecommerce::ecommerce.add_to_cart')</button>
                                    @endif
                                </form>
                            </article>
                            @endforeach
                        </div>
                    @endforeach
                    @if($sectionSlides->count() > 1)
                        <div class="products-carousel-controls">
                            <button type="button" data-products-prev
                                    aria-label="@lang('ecommerce::ecommerce.previous')">{{ $isRTL ? '→' : '←' }}</button>
                            <span class="products-carousel-status" data-products-status dir="ltr" aria-live="polite">1 / {{ $sectionSlides->count() }}</span>
                            <button type="button" data-products-next
                                    aria-label="@lang('ecommerce::ecommerce.next')">{{ $isRTL ? '←' : '→' }}</button>
                        </div>
                    @endif
                </div>
            </section>
            @if ($midProductBanner && $loop->iteration === $bannerAfterSection)
                <section class="wide-banner ad-responsive-bg" style="{{ $bannerBackgroundStyle($midProductBanner) }}">
                    @if($midProductBanner->click_url)
                        <a class="wide-banner-image-link" href="{{ $midProductBanner->click_url }}"
                            target="{{ $midProductBanner->target ?? '_self' }}"
                            @if(($midProductBanner->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif
                            aria-label="{{ $localized($midProductBanner->localized_title ?? ($midProductBanner->title ?? null), __('ecommerce::ecommerce.home_banner2_fallback')) }}"></a>
                    @endif
                    <div class="wide-banner-content">
                        <p>{{ $localized($midProductBanner->localized_subtitle ?? ($midProductBanner->subtitle ?? null)) }}
                        </p>
                        <h2>{{ $localized($midProductBanner->localized_title ?? ($midProductBanner->title ?? null), __('ecommerce::ecommerce.home_banner2_fallback')) }}
                        </h2>
                        @if($midProductBanner->click_url)
                            <a class="primary-btn" href="{{ $midProductBanner->click_url }}"
                                target="{{ $midProductBanner->target ?? '_self' }}"
                                @if(($midProductBanner->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif>
                                {{ data_get($midProductBanner, "click_action.button_text.{$currentLocale}") ?: __('ecommerce::ecommerce.shop_now', [], $currentLocale) }}
                                <span class="arrow-end" aria-hidden="true">→</span></a>
                        @endif
                    </div>
                </section>
            @endif
        @endforeach
    </div>
    <section class="benefits">
        <div class="container" style="display:contents">
            <div class="benefit"><i class="benefit-icon">♧</i>
                <div><strong>@lang('ecommerce::ecommerce.home_feature_fast_title')</strong><span>@lang('ecommerce::ecommerce.home_feature_fast_desc')</span></div>
            </div>
            <div class="benefit"><i class="benefit-icon">♢</i>
                <div><strong>@lang('ecommerce::ecommerce.home_feature_secure_title')</strong><span>@lang('ecommerce::ecommerce.home_feature_secure_desc')</span></div>
            </div>
            <div class="benefit"><i class="benefit-icon">⚙</i>
                <div><strong>@lang('ecommerce::ecommerce.home_feature_quality_title')</strong><span>@lang('ecommerce::ecommerce.home_feature_quality_desc')</span></div>
            </div>
            <div class="benefit"><i class="benefit-icon">♧</i>
                <div><strong>@lang('ecommerce::ecommerce.home_feature_support_title')</strong><span>@lang('ecommerce::ecommerce.home_feature_support_desc')</span></div>
            </div>
        </div>
    </section>
    @if (collect($merchantSlider ?? [])->isNotEmpty())
        <section id="stores" class="stores-section">
            <div class="container">
                <div class="stores-heading">
                    <h2>@lang('ecommerce::ecommerce.merchants')</h2><a href="{{ route('merchants.index') }}">@lang('ecommerce::ecommerce.view_all') ></a>
                </div>
                <div class="stores-grid">
                    @foreach ($merchantSlider as $merchant)
                        @php
                            $merchantName = $localized(
                                $merchant->company_name ?? ($merchant->name ?? null),
                                __('ecommerce::ecommerce.home_merchant_fallback'),
                            );
                            $branch = collect($merchant->branches ?? [])->first();
                            $cover = $branch->cover ?? ($branch->cover_image ?? null);
                        @endphp
                        <article class="store-card">
                            <div class="store-cover" style="background-image:url('{{ $image($cover) }}')">
                                @if (($merchant->status ?? null) === 'active')
                                    <span class="trusted-badge">@lang('ecommerce::ecommerce.verified_seller')
                                        ✓</span>
                                @endif
                            </div>
                            <div class="store-info">
                                <div class="store-title-row">
                                    <h3>{{ $merchantName }}</h3>
                                    <div class="store-rating">{{ number_format((float) ($merchant->rate ?? 1), 1) }} ★
                                        <span>({{ $merchant->reviews_count ?? 0 }})</span>
                                    </div>
                                </div>
                                <div class="store-tags">
                                    @foreach (collect($merchant->productCategories ?? [])->take(3) as $category)
                                        <span>#
                                            {{ $localized($category->title ?? ($category->name ?? null)) }}</span>
                                    @endforeach
                                </div>
                                <div class="store-stats"><span><i class="fa fa-building-o"></i>
                                        {{ $merchant->branches_count ?? 0 }} @lang('ecommerce::ecommerce.branches')</span><span><i
                                            class="fa fa-cube"></i>
                                        {{ $merchant->items_count ?? 0 }} @lang('ecommerce::ecommerce.items_label')</span></div><a
                                    class="store-link"
                                    href="{{ route('merchant.show', ['merchant' => $merchant->id]) }}">@lang('ecommerce::ecommerce.visit_store')
                                    ></a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @if (collect($recentNews ?? [])->isNotEmpty())
        <section class="extra-section">
            <div class="container">
                <div class="section-head">
                    <h2>{{ __('ecommerce::ecommerce.recent_news') }}</h2>
                </div>
                <div class="news-grid">
                    @foreach (collect($recentNews)->take(3) as $news)
                        @php
                            $newsTitle = $localized($news->localized_title ?? ($news->title ?? null), '');
                            $newsSubtitle = $localized($news->localized_subtitle ?? ($news->subtitle ?? null), '');
                            $newsImage = $news->desktop_image_url ?? null;
                            $newsHref = $news->click_url;
                        @endphp
                        @if($newsHref)
                        <a class="news-card" href="{{ $newsHref }}" target="{{ $news->target ?? '_self' }}"
                            @if(($news->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif>
                        @else
                        <article class="news-card">
                        @endif
                            @if ($newsImage)
                                <picture>
                                    @if($news->mobile_image_url)
                                        <source media="(max-width: 767px)" srcset="{{ $news->mobile_image_url }}">
                                    @endif
                                    @if($news->tablet_image_url)
                                        <source media="(max-width: 1024px)" srcset="{{ $news->tablet_image_url }}">
                                    @endif
                                    <img class="news-card-img" src="{{ $newsImage }}" alt="{{ $newsTitle }}" loading="lazy">
                                </picture>
                            @else
                                <img class="news-card-img" src="{{ asset('assets/ecommerce/images/slider/4.webp') }}"
                                    alt="{{ $newsTitle }}" loading="lazy">
                            @endif
                            <div class="news-card-body">
                                <h3>{{ $newsTitle }}</h3>
                                @if ($newsSubtitle)
                                    <p>{{ $newsSubtitle }}</p>
                                @endif
                            </div>
                        @if($newsHref)
                        </a>
                        @else
                        </article>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @if (collect($clientBrands ?? [])->isNotEmpty())
        <section class="extra-section">
            <div class="container">
                <div class="section-head">
                    <h2>{{ __('ecommerce::ecommerce.our_brands') }}</h2>
                </div>
                <div class="brands-grid">
                    @foreach (collect($clientBrands)->take(5) as $brand)
                        @php
                            $brandTitle = $localized($brand->localized_title ?? ($brand->title ?? null), '');
                            $brandLogo = $brand->desktop_image_url ?? null;
                        @endphp
                        @if($brand->click_url)
                        <a class="brand-card" title="{{ $brandTitle }}" href="{{ $brand->click_url }}"
                            target="{{ $brand->target ?? '_self' }}"
                            @if(($brand->target ?? '_self') === '_blank') rel="noopener noreferrer" @endif>
                        @else
                        <div class="brand-card" title="{{ $brandTitle }}">
                        @endif
                            @if ($brandLogo)
                                <picture>
                                    @if($brand->mobile_image_url)
                                        <source media="(max-width: 767px)" srcset="{{ $brand->mobile_image_url }}">
                                    @endif
                                    @if($brand->tablet_image_url)
                                        <source media="(max-width: 1024px)" srcset="{{ $brand->tablet_image_url }}">
                                    @endif
                                    <img src="{{ $brandLogo }}" alt="{{ $brandTitle }}" loading="lazy">
                                </picture>
                            @else
                                <span style="font-size:14px;font-weight:700;color:#343a42">{{ $brandTitle }}</span>
                            @endif
                        @if($brand->click_url)
                        </a>
                        @else
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
    @endif
    @if (collect($testimonials ?? [])->isNotEmpty())
        <section class="extra-section">
            <div class="container">
                <div class="section-head">
                    <h2>{{ __('ecommerce::ecommerce.testimonials') }}</h2>
                </div>
                <div class="testimonials-slider" data-testimonials-slider>
                    <div class="testimonials-track" data-testimonials-track>
                        @foreach ($testimonials as $testimonial)
                            <article class="testimonial-card">
                                <h3>{{ $testimonial['name'] ?? '' }}</h3>
                                <div class="testimonial-position">{{ $testimonial['position'] ?? '' }}</div>
                                <p>{{ $testimonial['content'] ?? '' }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>
                @if (count($testimonials) > 1)
                    <div class="testimonials-slider-controls">
                        <button class="testimonials-slider-arrow" type="button" data-testimonials-previous
                            aria-label="{{ __('ecommerce::ecommerce.previous') }}">
                            <i class="fa {{ $isRTL ? 'fa-angle-right' : 'fa-angle-left' }}" aria-hidden="true"></i>
                        </button>
                        <div class="testimonials-slider-dots" aria-label="{{ __('ecommerce::ecommerce.testimonials') }}">
                            @foreach ($testimonials as $index => $testimonial)
                                <button class="testimonials-slider-dot" type="button" data-testimonials-dot="{{ $index }}"
                                    aria-label="{{ __('ecommerce::ecommerce.testimonial_slide', ['number' => $index + 1]) }}"
                                    aria-current="{{ $index === 0 ? 'true' : 'false' }}"></button>
                            @endforeach
                        </div>
                        <button class="testimonials-slider-arrow" type="button" data-testimonials-next
                            aria-label="{{ __('ecommerce::ecommerce.next') }}">
                            <i class="fa {{ $isRTL ? 'fa-angle-left' : 'fa-angle-right' }}" aria-hidden="true"></i>
                        </button>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <button class="testimonial-float" type="button" id="open-testimonial-dialog"
        title="{{ __('ecommerce::ecommerce.share_your_opinion_hint') }}"
        aria-label="{{ __('ecommerce::ecommerce.share_your_opinion') }}">
        <i class="fa fa-commenting-o" aria-hidden="true"></i>
        <span>{{ __('ecommerce::ecommerce.share_your_opinion') }}</span>
    </button>

    <dialog class="testimonial-dialog" id="testimonial-dialog" aria-labelledby="testimonial-dialog-title">
        <div class="testimonial-dialog-header">
            <h2 id="testimonial-dialog-title">{{ __('ecommerce::ecommerce.testimonial_form_title') }}</h2>
            <button type="button" class="testimonial-dialog-close" data-close-testimonial-dialog
                aria-label="{{ __('ecommerce::ecommerce.testimonial_close') }}">&times;</button>
        </div>
        <div class="testimonial-dialog-body">
            @if (session('testimonial_submitted'))
                <div class="testimonial-form-alert" role="status">
                    {{ __('ecommerce::ecommerce.testimonial_submitted') }}
                </div>
            @endif
            <p class="testimonial-dialog-note">{{ __('ecommerce::ecommerce.testimonial_form_description') }}</p>
            <form action="{{ route('customer-testimonials.store') }}" method="POST">
                @csrf
                <div class="testimonial-honeypot" aria-hidden="true">
                    <label for="testimonial-website">Website</label>
                    <input id="testimonial-website" type="text" name="website" tabindex="-1" autocomplete="off">
                </div>
                <label for="testimonial-name">{{ __('ecommerce::ecommerce.testimonial_name') }}</label>
                <input id="testimonial-name" name="name" type="text" maxlength="100" required
                    value="{{ old('name') }}" placeholder="{{ __('ecommerce::ecommerce.testimonial_name_placeholder') }}">
                @error('name') <span class="testimonial-field-error">{{ $message }}</span> @enderror

                <label for="testimonial-position">{{ __('ecommerce::ecommerce.testimonial_position') }}</label>
                <input id="testimonial-position" name="position" type="text" maxlength="100" value="{{ old('position') }}">
                @error('position') <span class="testimonial-field-error">{{ $message }}</span> @enderror

                <label for="testimonial-content">{{ __('ecommerce::ecommerce.testimonial_content') }}</label>
                <textarea id="testimonial-content" name="content" minlength="10" maxlength="2000" required
                    placeholder="{{ __('ecommerce::ecommerce.testimonial_content_placeholder') }}">{{ old('content') }}</textarea>
                @error('content') <span class="testimonial-field-error">{{ $message }}</span> @enderror

                <button class="hero-cta" type="submit">{{ __('ecommerce::ecommerce.testimonial_submit') }}</button>
            </form>
        </div>
    </dialog>
@endsection

@section('scripts')
    <script>
        document.querySelectorAll('[data-testimonials-slider]').forEach(function(slider) {
            const track = slider.querySelector('[data-testimonials-track]');
            const cards = Array.from(track.querySelectorAll('.testimonial-card'));
            const dots = Array.from(slider.parentElement.querySelectorAll('[data-testimonials-dot]'));
            const previous = slider.parentElement.querySelector('[data-testimonials-previous]');
            const next = slider.parentElement.querySelector('[data-testimonials-next]');
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let activeIndex = 0;
            let autoplayTimer;

            function goTo(index) {
                activeIndex = (index + cards.length) % cards.length;
                const card = cards[activeIndex];
                const trackBounds = track.getBoundingClientRect();
                const cardBounds = card.getBoundingClientRect();
                const scrollOffset = cardBounds.left < trackBounds.left
                    ? cardBounds.left - trackBounds.left
                    : cardBounds.right > trackBounds.right
                        ? cardBounds.right - trackBounds.right
                        : 0;

                if (scrollOffset !== 0) {
                    track.scrollBy({ left: scrollOffset, behavior: 'smooth' });
                }

                dots.forEach(function(dot, dotIndex) {
                    dot.setAttribute('aria-current', dotIndex === activeIndex ? 'true' : 'false');
                });
            }

            previous?.addEventListener('click', function() {
                goTo(activeIndex - 1);
            });
            next?.addEventListener('click', function() {
                goTo(activeIndex + 1);
            });
            dots.forEach(function(dot) {
                dot.addEventListener('click', function() {
                    goTo(Number(dot.dataset.testimonialsDot));
                });
            });

            function stopAutoplay() {
                window.clearInterval(autoplayTimer);
                autoplayTimer = null;
            }

            function startAutoplay() {
                stopAutoplay();
                if (cards.length < 2 || prefersReducedMotion || document.hidden ||
                    slider.matches(':hover') || slider.contains(document.activeElement)) {
                    return;
                }

                autoplayTimer = window.setInterval(function() {
                    goTo(activeIndex + 1);
                }, 5000);
            }

            slider.addEventListener('mouseenter', stopAutoplay);
            slider.addEventListener('mouseleave', startAutoplay);
            slider.addEventListener('focusin', stopAutoplay);
            slider.addEventListener('focusout', function(event) {
                if (!slider.contains(event.relatedTarget)) startAutoplay();
            });
            document.addEventListener('visibilitychange', startAutoplay);
            startAutoplay();

            track.addEventListener('scroll', function() {
                const center = track.getBoundingClientRect().left + track.clientWidth / 2;
                const nearestIndex = cards.reduce(function(nearest, card, index) {
                    const cardCenter = card.getBoundingClientRect().left + card.offsetWidth / 2;
                    const nearestCenter = cards[nearest].getBoundingClientRect().left + cards[nearest].offsetWidth / 2;
                    return Math.abs(cardCenter - center) < Math.abs(nearestCenter - center) ? index : nearest;
                }, 0);

                if (nearestIndex !== activeIndex) {
                    activeIndex = nearestIndex;
                    dots.forEach(function(dot, dotIndex) {
                        dot.setAttribute('aria-current', dotIndex === activeIndex ? 'true' : 'false');
                    });
                }
            }, { passive: true });
        });

        (function() {
            const dialog = document.getElementById('testimonial-dialog');
            const openButton = document.getElementById('open-testimonial-dialog');
            if (!dialog || !openButton) return;

            openButton.addEventListener('click', function() {
                dialog.showModal();
            });
            dialog.querySelectorAll('[data-close-testimonial-dialog]').forEach(function(button) {
                button.addEventListener('click', function() {
                    dialog.close();
                });
            });
            dialog.addEventListener('click', function(event) {
                if (event.target === dialog) dialog.close();
            });
            @if ($errors->has('name') || $errors->has('position') || $errors->has('content') || session('testimonial_submitted'))
                dialog.showModal();
            @endif
        })();

        document.querySelectorAll('[data-product-carousel]').forEach(function(carousel) {
            const slides = Array.from(carousel.querySelectorAll('[data-product-slide]'));
            if (slides.length < 2) return;

            const status = carousel.querySelector('[data-products-status]');
            let current = 0;

            function showSlide(index) {
                slides[current].hidden = true;
                slides[current].setAttribute('aria-hidden', 'true');
                current = (index + slides.length) % slides.length;
                slides[current].hidden = false;
                slides[current].setAttribute('aria-hidden', 'false');
                status.textContent = (current + 1) + ' / ' + slides.length;
            }

            carousel.querySelector('[data-products-prev]').addEventListener('click', function() {
                showSlide(current - 1);
            });
            carousel.querySelector('[data-products-next]').addEventListener('click', function() {
                showSlide(current + 1);
            });

            let touchStartX = null;
            carousel.addEventListener('touchstart', function(event) {
                touchStartX = event.changedTouches[0].clientX;
            }, { passive: true });
            carousel.addEventListener('touchend', function(event) {
                if (touchStartX === null) return;
                const delta = touchStartX - event.changedTouches[0].clientX;
                if (Math.abs(delta) > 40) {
                    showSlide(current + (delta > 0 ? 1 : -1));
                }
                touchStartX = null;
            }, { passive: true });
        });

        /* ── Hero Slider ─────────────────────────────────────────────── */
        (function() {
            const slider = document.getElementById('hero-slider');
            if (!slider) return;

            const slides = [...slider.querySelectorAll('.hero-slide')];
            const allDots = [...slider.querySelectorAll('.hero-dot')];
            let current = 0;
            let autoTimer = null;
            let touchStartX = null;

            function goTo(index) {
                if (!slides.length) return;
                slides[current].classList.remove('active');

                current = (index + slides.length) % slides.length;

                slides[current].classList.add('active');
                slider.querySelectorAll('.hero-dot').forEach(function(dot) {
                    const isCurrent = parseInt(dot.getAttribute('data-index'), 10) === current;
                    dot.classList.toggle('active', isCurrent);
                    dot.setAttribute('aria-selected', isCurrent ? 'true' : 'false');
                });
            }

            function startAuto() {
                stopAuto();
                autoTimer = setInterval(function() {
                    goTo(current + 1);
                }, 4800);
            }

            function stopAuto() {
                clearInterval(autoTimer);
            }

            /* Pagination dot clicks */
            allDots.forEach(function(dot) {
                dot.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const targetIdx = parseInt(this.getAttribute('data-index'), 10);
                    goTo(targetIdx);
                    startAuto();
                });
            });

            /* Touch / swipe support */
            slider.addEventListener('touchstart', function(e) {
                touchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });

            slider.addEventListener('touchend', function(e) {
                if (touchStartX === null) return;
                const diff = touchStartX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 40) {
                    goTo(diff > 0 ? current + 1 : current - 1);
                    startAuto();
                }
                touchStartX = null;
            }, {
                passive: true
            });

            /* Mouse drag support (desktop) */
            let mouseStartX = null;
            slider.addEventListener('mousedown', function(e) {
                mouseStartX = e.clientX;
            });
            slider.addEventListener('mouseup', function(e) {
                if (mouseStartX === null) return;
                const diff = mouseStartX - e.clientX;
                if (Math.abs(diff) > 50) {
                    goTo(diff > 0 ? current + 1 : current - 1);
                    startAuto();
                }
                mouseStartX = null;
            });

            /* Pause on hover */
            slider.addEventListener('mouseenter', stopAuto);
            slider.addEventListener('mouseleave', startAuto);

            startAuto();
        })();

        const unavailableMessages = [
            @json(__('ecommerce::ecommerce.product_not_available')),
            @json(__('ecommerce::ecommerce.product_out_of_stock'))
        ];
        const search = document.querySelector('.search input');
        search?.addEventListener('input', event => {
            const query = event.target.value.trim().toLowerCase();
            document.querySelectorAll('.product').forEach(card => card.hidden = query && !card.textContent
                .toLowerCase().includes(query))
        });
        document.querySelectorAll('.add-cart-form').forEach(form => form.addEventListener('submit', async event => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const originalText = button ? button.innerHTML : '';
            if (button) {
                button.disabled = true;
                button.textContent = @json(__('ecommerce::ecommerce.product.adding'));
            }

            try {
                if (!window.submitCartForm) throw new Error(@json(__('ecommerce::ecommerce.cart_service_unavailable')));
                const result = await window.submitCartForm(form, {});
                if (result && (result.login || result.cancelled)) return;
                if (button) {
                    button.textContent = @json(__('ecommerce::ecommerce.added'));
                    button.classList.add('added');
                }
            } catch (error) {
                const message = error && error.message ? error.message : @json(__('ecommerce::ecommerce.cart.add_error'));
                if (unavailableMessages.includes(message)) {
                    form.closest('.product')?.remove();
                }
                if (typeof window.showNotification === 'function') {
                    window.showNotification(message, 'danger');
                } else {
                    window.alert(message);
                }
            } finally {
                if (button && !button.classList.contains('added')) {
                    button.disabled = false;
                    button.innerHTML = originalText;
                }
            }
        }));
        document.addEventListener('click', async function(event) {
            const favorite = event.target.closest('.favorite');
            if (!favorite) return;
            event.preventDefault();
            event.stopPropagation();
            const form = favorite.closest('.product')?.querySelector('form.add-cart-form');
            const itemId = favorite.dataset.productId;
            const token = form?.querySelector('input[name="_token"]')?.value;
            if (!itemId || !token || favorite.dataset.loading === '1') return;
            favorite.dataset.loading = '1';
            try {
                const body = new FormData();
                body.append('_token', token);
                body.append('product_id', itemId);
                const response = await fetch('{{ route('wishlist.add') }}', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: body
                });
                const data = await response.json().catch(function() {
                    return null;
                });
                if ((!data && response.url.includes('/login')) || (data && data.login)) {
                    window.location.href = data && data.redirect ? data.redirect : response.url;
                    return;
                }
                const isFavorite = data && data.status === 'added';
                favorite.classList.toggle('is-favorite', isFavorite);
                favorite.innerHTML = isFavorite ? '<i class="fa fa-heart"></i>' : '<i class="ti-heart"></i>';
                if (typeof window.updateWishlistBadge === 'function' && data && typeof data.count !==
                    'undefined') {
                    window.updateWishlistBadge(data.count);
                }
            } finally {
                delete favorite.dataset.loading;
            }
        }, true);
    </script>
@endsection
