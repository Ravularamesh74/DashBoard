<?php

use AuthMiddleware;
use GuestMiddleware;
use RoleMiddleware;

return [

    /*
    |--------------------------------------------------------------------------
    | PUBLIC ROUTES (GUEST ONLY)
    |--------------------------------------------------------------------------
    */

    ['GET', '/login', 'AuthController@showLogin', [GuestMiddleware::class]],
    ['POST', '/login', 'AuthController@login', []],

    ['GET', '/register', 'AuthController@showRegister', [GuestMiddleware::class]],
    ['POST', '/register', 'AuthController@register', []],

    ['GET', '/logout', 'AuthController@logout', [AuthMiddleware::class]],


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    ['GET', '/', 'DashboardController@index', [AuthMiddleware::class]],
    ['GET', '/dashboard-data', 'DashboardController@getDashboardData', [AuthMiddleware::class]],


    /*
    |--------------------------------------------------------------------------
    | USERS (RESOURCE ROUTES)
    |--------------------------------------------------------------------------
    */

    ['GET', '/users', 'UserController@index', [AuthMiddleware::class]],

    ['GET', '/users/create', 'UserController@create', [AuthMiddleware::class, RoleMiddleware::class]],
    ['POST', '/users', 'UserController@store', [AuthMiddleware::class, RoleMiddleware::class]],

    ['GET', '/users/{id}', 'UserController@show', [AuthMiddleware::class]],

    ['GET', '/users/{id}/edit', 'UserController@edit', [AuthMiddleware::class, RoleMiddleware::class]],
    ['PUT', '/users/{id}', 'UserController@update', [AuthMiddleware::class, RoleMiddleware::class]],

    ['DELETE', '/users/{id}', 'UserController@delete', [AuthMiddleware::class, RoleMiddleware::class]],

    ['POST', '/users/bulk-delete', 'UserController@bulkDelete', [AuthMiddleware::class, RoleMiddleware::class]],


    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    ['GET', '/profile', 'UserController@profile', [AuthMiddleware::class]],
    ['POST', '/change-password', 'UserController@changePassword', [AuthMiddleware::class]],


];