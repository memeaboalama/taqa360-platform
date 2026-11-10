<style>
    :root {
        --primary-rgb: {{ $platformBrand['primary_rgb'] }};
        --primary-color: {{ $platformBrand['primary_color'] }};
        --primary-border: {{ $platformBrand['primary_color'] }};
        --primary-hover: {{ $platformBrand['primary_hover_color'] }};
        --primary-light: {{ $platformBrand['primary_light_color'] }};
        --accent-color: {{ $platformBrand['accent_color'] }};
    }

    .card.custom-card.crm-highlight-card {
        background-color: {{ $platformBrand['primary_color'] }} !important;
    }
</style>
