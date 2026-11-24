<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->passNeonEndpointId();
    }

    public function boot(): void
    {
        //
    }

    /**
     * The libpq bundled with vercel-php has no SNI support, so Neon cannot tell
     * which database we want. Neon accepts the endpoint id inside the password
     * ("endpoint=ep-xxx;password"), see https://neon.com/sni.
     */
    private function passNeonEndpointId(): void
    {
        $url = config('database.connections.pgsql.url');
        if (! $url || ! str_contains($url, '.neon.tech')) {
            return;
        }

        $p = parse_url($url);
        if (! isset($p['host'], $p['pass']) || str_starts_with(urldecode($p['pass']), 'endpoint=')) {
            return;
        }

        $endpoint = preg_replace('/-pooler$/', '', explode('.', $p['host'])[0]);
        $pass = rawurlencode('endpoint='.$endpoint.';'.urldecode($p['pass']));

        $rebuilt = ($p['scheme'] ?? 'postgresql').'://'
            .($p['user'] ?? '').':'.$pass.'@'.$p['host']
            .(isset($p['port']) ? ':'.$p['port'] : '')
            .($p['path'] ?? '')
            .(isset($p['query']) ? '?'.$p['query'] : '');

        config(['database.connections.pgsql.url' => $rebuilt]);
    }
}
