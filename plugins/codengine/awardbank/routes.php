<?php

use Firebase\JWT\JWT;
use RainLab\User\Facades\Auth;
use Backend\Facades\BackendAuth;

header('Access-Control-Allow-Origin:  *');
header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');

Route::get('/post/all', 'Codengine\Awardbank\Classes\Posts@all');

Route::post('/postas', 'Codengine\Awardbank\Classes\Posts@postas');
Route::post('/postto', 'Codengine\Awardbank\Classes\Posts@postto');


Route::group(['domain' => '{account}.xtendsystem.com'], function () {
     Route::get('/', function($account) {

        return Redirect::to('https://'.$account.'.xtendsystem.com/login/'.$account);

    });
});

Route::any('/', function () {
    return Redirect::to('dashboard');
});



Route::group(['middleware' => 'web'], function () {
    Route::get('/xero-login', function () {
        if (BackendAuth::check()) {
            $provider = \Codengine\Awardbank\Models\XeroAPI::getProvider();
            $authUrl = $provider->getAuthorizationUrl([
                'scope' => 'openid offline_access email profile accounting.transactions accounting.contacts accounting.settings'
            ]);

            return Redirect::to($authUrl);
        } else {
            return Redirect::to('/404');
        }
    });
});


Route::get('/callback', function () {
    $code = request()->query('code');

    \Codengine\Awardbank\Models\XeroAPI::getNewAccessToken('authorization_code', [
        'code' => $code
    ]);

    // redirect to this page because this is the only page that have the Get new Xero API button
    return Redirect::to('/backend/codengine/awardbank/billingcontacts');
});

Route::group(['middleware' => 'web'], function () {
    Route::get('/ckeditor/token-endpoint', function () {
        if (Auth::check()) {
            $accessKey = 'M2SmMhy59fzcVXvRzUjJCtaaicwlzyCdc8Vr7tHISwEx6brbpYApXaLvDrZk';
            $environmentId = 'hAb3abuP75NMHkCd7J1x';

            $payload = array(
                "aud" => $environmentId,
                "iat" => time()
            );

            $jwt = JWT::encode($payload, $accessKey, 'HS256');
            echo $jwt;
            die;
        } else {
            return Redirect::to('/404');
        }
    });
});
