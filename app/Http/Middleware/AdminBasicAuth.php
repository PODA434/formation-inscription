<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protège la page d'administration par identifiant + mot de passe (HTTP Basic).
 * À utiliser uniquement en HTTPS.
 */
class AdminBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = (string) config('formation.admin.user');
        $hash = (string) config('formation.admin.password_hash');

        if ($user === '' || $hash === '') {
            abort(503, 'Accès administrateur non configuré (ADMIN_USER / ADMIN_PASSWORD_HASH).');
        }

        $ok = hash_equals($user, (string) $request->getUser())
            && Hash::check((string) $request->getPassword(), $hash);

        if (! $ok) {
            return response('Accès refusé', 401, ['WWW-Authenticate' => 'Basic realm="Administration", charset="UTF-8"']);
        }

        return $next($request);
    }
}
