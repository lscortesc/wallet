<?php

/**
 * Oauth Routes
 *
 * Login
 * Logout
 * Refresh Token
*/

Route::post('/register', 'ProxyController@register');
Route::post('/login', 'ProxyController@login');
Route::post('/logout', 'ProxyController@logout')->middleware('auth:api');
Route::post('/login/refresh', 'ProxyController@refresh');