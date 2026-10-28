@php
    /*
     * Shared direction / text-alignment utilities.
     *
     * The new "static" store design was originally written RTL-only, so several
     * stylesheets hard-coded `direction: rtl` and `text-align: right`. That made
     * the English version render right-aligned / right-to-left.
     *
     * These helpers let templates stay direction-agnostic:
     *   .ta-start / .ta-end  -> logical alignment that follows the document direction
     *   .ltr                 -> always-LTR content (emails, URLs, codes) isolated from
     *                            the surrounding bidi run and aligned to the inline start
     *   .num                 -> numbers, money and codes; same as .ltr but never wraps oddly
     *   .arrow-start         -> a horizontal arrow that must point LEFT in English
     *                           and RIGHT in Arabic
     *   .arrow-end           -> the opposite: RIGHT in English, LEFT in Arabic
     *                           (category filter carets, "visit store" buttons)
     *   .arrow-next          -> the "next" half of a carousel pair
     */
    $dirIsRtl = $dirIsRtl ?? in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true);
@endphp
<style>
    /* Logical alignment - replaces hard-coded left/right */
    .ta-start {
        text-align: start
    }

    .ta-end {
        text-align: end
    }

    /* Always-LTR content embedded in an RTL paragraph */
    .ltr,
    .num {
        direction: ltr;
        unicode-bidi: isolate;
        text-align: left
    }

    [dir="rtl"] .ltr,
    [dir="rtl"] .num {
        text-align: right
    }

    /* Numerals that must stay in reading order (prices, totals, qty, dates) */
    .num {
        font-variant-numeric: tabular-nums;
        white-space: nowrap
    }

    /* Inline-start offset helper (replaces margin-left / margin-right hacks) */
    .ms-auto {
        margin-inline-start: auto
    }

    /* ------------------------------------------------------------------
       Directional arrows
       ------------------------------------------------------------------
       Project rule: a directional arrow (back, continue, view details,
       breadcrumb, list drill-down) points LEFT in English and RIGHT in
       Arabic. Author the glyph pointing left and add .arrow-start - it is
       mirrored automatically in RTL.

       .arrow-end is the mirror image of that: author the glyph pointing
       RIGHT and it becomes LEFT in Arabic. Used by affordances that mean
       "drill into / go to" rather than "go back" - the category filter
       carets in the merchant sidebar and the "visit store" buttons - where
       the expected reading is forward along the current text direction.

       Carousel prev/next keep the natural convention instead:
         prev -> left  in English, right in Arabic  (.arrow-start)
         next -> right in English, left  in Arabic  (.arrow-next)
       Vertical arrows (up/down) must never be marked.
    ------------------------------------------------------------------ */
    .arrow-start,
    .arrow-end,
    .arrow-next {
        display: inline-block
    }

    [dir="rtl"] .arrow-start,
    [dir="rtl"] .arrow-end,
    [dir="rtl"] .arrow-next {
        transform: scaleX(-1)
    }
</style>
