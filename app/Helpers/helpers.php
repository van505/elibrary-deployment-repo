<?php

if (!function_exists('maskEmail')) {
    function maskEmail(string $email): string {
        [$local, $domain] = explode('@', $email);
        $masked = substr($local, 0, 2) . str_repeat('*', max(strlen($local) - 2, 0));
        return $masked . '@' . $domain;
    }
}
