<?php

/**
 * Format an amount to the given currency
 *
 * @return response()
 */

if (! function_exists('formatCurrency')) {
    function formatCurrency($amount, $currency)
    {
        $fmt = new NumberFormatter(app()->getLocale(), NumberFormatter::CURRENCY);

        return $fmt->formatCurrency($amount, $currency);
    }
}

if (! function_exists('percentage')) {
    function percentage($total, $part): int
    {
        $rest = $total - $part;

        return $rest / $total * 100;
    }
}

if (! function_exists('canonical_url')) {
    function canonical_url(): string
    {
        if (! \Illuminate\Support\Str::startsWith($current = url()->current(), config('app.url'))) {
            // replace domain if it's not the configured one
            $current = preg_replace('/^(?:https?:\/\/)?(?:[^@\/\n]+@)?(?:www\.)?([^:\/\n]+)/', config('app.url'), $current);
        }

        return LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), $current);
    }
}
