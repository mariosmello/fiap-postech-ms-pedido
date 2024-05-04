<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\UnauthorizedException;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {

            $jwt = JWT::decode(
                $request->bearerToken() ?? "",
                new Key(env('JWT_SECRET'), 'HS256')
            );

            $authData = json_decode(
                json_encode($jwt, JSON_THROW_ON_ERROR),
                true, 512, JSON_THROW_ON_ERROR
            );
            $authData['token'] = $request->bearerToken();

            $request->merge(['auth' => Arr::only($authData, ['sub', 'name', 'email', 'token'])]);

            return $next($request);

        } catch (\Exception $e) {

            throw new UnauthorizedException('Invalid credentials');

        }

    }
}
