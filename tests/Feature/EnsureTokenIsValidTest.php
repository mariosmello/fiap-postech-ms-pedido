<?php

use Firebase\JWT\JWT;
use Illuminate\Validation\UnauthorizedException;

beforeEach(function () {
    $this->request = \Illuminate\Support\Facades\Request::create(route('orders.store'));
    $this->next = function () {
        return response('');
    };
});

test('cant request without bearer token', function () {

    $middleware = new \App\Http\Middleware\EnsureTokenIsValid();
    $middleware->handle($this->request, $this->next);

})->throws(UnauthorizedException::class);

test('cant request with valid bearer token', function () {

    $token = JWT::encode([], 'some-invalid-key', 'HS256');
    $this->request->headers->set('Authorization', 'Bearer ' . $token);

    $middleware = new \App\Http\Middleware\EnsureTokenIsValid();
    $middleware->handle($this->request, $this->next);

})->throws(UnauthorizedException::class);

test('can request with valid bearer token', function () {

    $token = JWT::encode([], env('JWT_SECRET'), 'HS256');
    $this->request->headers->set('Authorization', 'Bearer ' . $token);

    $middleware = new \App\Http\Middleware\EnsureTokenIsValid();
    $response = $middleware->handle($this->request, $this->next);

    expect($response->getStatusCode())->toBe(200);
});
