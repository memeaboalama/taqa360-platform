<?php

function normalizeAdminPath($path)
{
    $path = rtrim(parse_url($path, PHP_URL_PATH), '/');

    $localePrefix = ['ar', 'en'];
    $segments = explode('/', trim($path, '/'));
    if (count($segments) && in_array($segments[0], $localePrefix)) {
        array_shift($segments);
        $path = '/' . implode('/', $segments);
    }

    return $path;
}

function isActive($routeName)
{
    if (request()->routeIs($routeName)) {
        return 'active';
    }

    $currentPath = normalizeAdminPath(request()->url());
    $targetPath = normalizeAdminPath($routeName);

    if ($currentPath === $targetPath) {
        return 'active';
    }

    // Match detail pages (numeric ids) only, e.g. /admin/merchants/5 -> /admin/merchants
    if ($targetPath && str_starts_with($currentPath . '/', $targetPath . '/')) {
        $rest = trim(substr($currentPath, strlen($targetPath)), '/');
        if ($rest !== '' && preg_match('/^\d+(\/\d+)*$/', $rest)) {
            return 'active';
        }
    }

    return '';
}

function isActiveSection($urls)
{
    $currentPath = normalizeAdminPath(request()->url());

    foreach ((array) $urls as $url) {
        if (isActive($url)) {
            return 'open active';
        }

        $targetPath = normalizeAdminPath($url);
        if ($targetPath && str_starts_with($currentPath . '/', $targetPath . '/')) {
            return 'open active';
        }
    }

    return '';
}
