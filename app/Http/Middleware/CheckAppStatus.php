<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAppStatus
{
    public function handle(Request $request, Closure $next)
    {
        $url = 'https://dimaswildanr3.github.io/app-kill-switch/status.txt';

        $status = @file_get_contents($url);

        if ($status === false) {
            abort(503, 'Tidak bisa terhubung ke server status aplikasi.');
        }

        if (trim($status) !== 'active') {
            abort(503, 'Aplikasi ini telah dinonaktifkan oleh pengembang.');
        }

        return $next($request);
    }
}
