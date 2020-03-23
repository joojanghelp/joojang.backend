<?php
namespace App\Traits;

use Symfony\Component\HttpFoundation\Request;
use Illuminate\Support\Facades\DB;

trait PassportTrait
{

    /**
     * Oauth2 토큰 생성. ( passport )
     *
     * @param string $email
     * @param string $password
     * @return void
     */
    public function getNewToken(string $email, string $password) {

        $client = DB::table('oauth_clients')->where('password_client', true)->first();

        $dataObject = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '',
        ];

        $tokenRequest = Request::create('/oauth/token', 'POST', $dataObject);
        $tokenRequestResult = json_decode(app()->handle($tokenRequest)->getContent());

        if(isset($tokenRequestResult->error_message) && $tokenRequestResult->error_message) {
            throw new \App\Exceptions\CustomException($tokenRequestResult->error_message);
        }

        return [
            'token_type' => $tokenRequestResult->token_type,
            'expires_in' => $tokenRequestResult->expires_in,
            'access_token' => $tokenRequestResult->access_token,
            'refresh_token' => $tokenRequestResult->refresh_token
        ];
    }

    /**
     * Oauth2 토큰 리프레쉬 ( passport )
     *
     * @param string $refresh_token
     * @return array
     */
    public function getRefreshTokenTrait(string $refresh_token) : array
    {
        $client = DB::table('oauth_clients')->where('password_client', true)->first();

        $dataObject = [
            'grant_type' => 'refresh_token',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'refresh_token' => $refresh_token,
            'scope' => '',
        ];

        $tokenRequest = Request::create('/oauth/token', 'POST', $dataObject);
        $tokenRequestResult = json_decode(app()->handle($tokenRequest)->getContent());

        if(isset($tokenRequestResult->error_message) && $tokenRequestResult->error_message) {
            throw new \App\Exceptions\CustomException($tokenRequestResult->error_message);
        }

        return [
            'token_type' => $tokenRequestResult->token_type,
            'expires_in' => $tokenRequestResult->expires_in,
            'access_token' => $tokenRequestResult->access_token,
            'refresh_token' => $tokenRequestResult->refresh_token
        ];
    }

}
