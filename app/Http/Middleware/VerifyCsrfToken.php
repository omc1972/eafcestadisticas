<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Cookie\CookieValuePrefix;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Uses an app-specific CSRF cookie/header name instead of Laravel's default
 * "XSRF-TOKEN" / "X-XSRF-TOKEN". Several local Laravel projects served from
 * 127.0.0.1 on different ports share that cookie name (cookies aren't
 * port-scoped), so one project's token overwrites another's and CSRF checks
 * fail with a 419 for whichever app didn't set it last.
 */
class VerifyCsrfToken extends ValidateCsrfToken
{
    protected const COOKIE_NAME = 'EAFC-XSRF-TOKEN';

    protected const HEADER_NAME = 'X-EAFC-XSRF-TOKEN';

    protected function getTokenFromRequest($request)
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');

        if (! $token && $header = $request->header(self::HEADER_NAME)) {
            try {
                $token = CookieValuePrefix::remove($this->encrypter->decrypt($header, false));
            } catch (DecryptException) {
                $token = '';
            }
        }

        return $token;
    }

    protected function newCookie($request, $config)
    {
        return new Cookie(
            self::COOKIE_NAME,
            $request->session()->token(),
            $this->availableAt(60 * $config['lifetime']),
            $config['path'],
            $config['domain'],
            $config['secure'],
            false,
            false,
            $config['same_site'] ?? null,
            $config['partitioned'] ?? false
        );
    }
}
