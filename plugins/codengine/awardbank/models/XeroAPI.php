<?php namespace Codengine\Awardbank\Models;

use League\OAuth2\Client\Token\AccessToken;
use Calcinai\OAuth2\Client\Provider\Xero;
use XeroPHP\Application;
use Model;
use Backend\Facades\BackendAuth;
use Illuminate\Support\Facades\Log;

/**
 * XeroAPI Model
 */
class XeroAPI extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'codengine_awardbank_xero_api';

    public static function getXero()
    {
        static::getNewAccessToken();

        $token = static::getAccessToken();

        $tenantId = static::getEVTtenants();

        return new Application($token, $tenantId);
    }

    public static function getEVTtenants()
    {
        $provider = static::getProvider();

        $token = static::getAccessToken();

        $tenants = $provider->getTenants($token);

        foreach ($tenants as $tenant) {
            if ($tenant->tenantName == env('XERO_OATH_TWO_CLIENT_TENANT_NAME')) {
                return $tenant->tenantId;
            }
        }
    }

    public static function getProvider()
    {
        return new Xero([
            'clientId'          => env('XERO_OATH_TWO_CLIENT_ID'),
            'clientSecret'      => env('XERO_OATH_TWO_CLIENT_SECRET'),
            'redirectUri'       => env('XERO_OATH_TWO_CLIENT_REDIRECT_URI'),
        ]);
    }

    public static function getAccessToken(): AccessToken
    {
        $model = static::latest()->first();
        $options = [
            'access_token' => $model->access_token,
            'refresh_token' => $model->refresh_token
        ];

        return  new AccessToken($options);
    }


    /**
     * To get new access token and refresh token
     */
    public static function getNewAccessToken($type = 'refresh_token', $options = [])
    {
        $model = static::latest()->first();

        $provider = static::getProvider();

        if ($type == 'refresh_token') {
            $options = [
                'refresh_token' => $model->refresh_token
            ];
        }

        $newAccessToken = $provider->getAccessToken($type, $options);
        $accessToken =  $newAccessToken->getToken();

        $model->access_token = $accessToken;

        $newAccessToken->getExpires();

        $model->refresh_token = $newAccessToken->getRefreshToken();

        $model->save();

        return $newAccessToken;
    }

    public static function getUser()
    {
        return BackendAuth::getUser();
    }
}
