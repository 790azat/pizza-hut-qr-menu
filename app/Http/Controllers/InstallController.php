<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

/**
 * Vercel has no shell, so migrations run through this URL:
 *   /install?token=SETUP_TOKEN
 * Safe to call again after each deploy: it only applies new migrations
 * and imports the starter menu when the database is empty.
 */
class InstallController extends Controller
{
    public function __invoke(Request $request)
    {
        $token = (string) config('app.setup_token');
        abort_if($token === '' || ! hash_equals($token, (string) $request->query('token')), 404);

        try {
            Artisan::call('migrate', ['--force' => true]);
            $out = Artisan::output();
            Artisan::call('db:seed', ['--force' => true]);
            $out .= Artisan::output();
        } catch (\Throwable $e) {
            // Token-protected, so it is safe to show the owner what went wrong.
            return response('FAILED: '.get_class($e).': '.$e->getMessage().' @ '.$e->getFile().':'.$e->getLine(), 500, ['Content-Type' => 'text/plain; charset=utf-8', 'Cache-Control' => 'no-store']);
        }

        return response("OK\n\n".$out, 200, ['Content-Type' => 'text/plain; charset=utf-8', 'Cache-Control' => 'no-store']);
    }
}
