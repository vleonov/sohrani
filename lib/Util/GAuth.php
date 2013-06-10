<?php

require_once ROOT_DIR . '/lib/External/GoogleApi/Google_Client.php';
require_once ROOT_DIR . '/lib/External/GoogleApi/contrib/Google_Oauth2Service.php';

class U_GAuth
{
    private static $_salt = 'OD<oo|z6 Oot7dae. jei\Lai1 Ea9do`sh quu*iY1E pu2lae%Z oowoh;W4 Roj`ijo5';

    public static function loginUrl()
    {

        $gApi = self::_getGAuthClient();
        $uri = $gApi->createAuthUrl();

        return $uri;
    }

    public static function login()
    {
        $backUri = '/';//Request()->get('state') ? base64_decode(Request()->get('state')) : '/';
        if (Request()->get('error')) {
            return $backUri;
        }

        $code = Request()->get('code');

        $gApi = self::_getGAuthClient();
        $gApi->setClientSecret(Config()->auth['gAuth']['clientSecret']);

        try {

            if (!$code || !$gApi->authenticate($code)) {
                return $backUri;
            }

            $accessToken = $gApi->getAccessToken();

            $gApiService = self::_getGAuthClient();
            $gApiService->setClientSecret(Config()->auth['gAuth']['clientSecret']);
            $gApiService->setDeveloperKey(Config()->auth['gAuth']['developerKey']);

            $oauth2 = new Google_Oauth2Service($gApiService);
            $gApiService->setAccessToken($accessToken);
            $userInfo = $oauth2->userinfo->get();
        } catch (Google_AuthException $e) {
            return $backUri;
        }

        if ($userInfo['email'] != Config()->auth['login']) {
            return $backUri;
        }

        $hash = md5(Session()->id() . self::$_salt . $code);

        Response()->setCookie('gauth', $hash);
        Session()->set('gauthCode', $code);
        Session()->set('userInfo', $userInfo);

        return $backUri;
    }

    public static function logout()
    {
        Response()->delCookie('gauth');
        Session()->delete('gauthCode');

        $backUri = Request()->backUrl('/');
        return $backUri;
    }

    public static function check()
    {
        $hash = Request()->cookie('gauth');
        if (!$hash) {
            return false;
        }

        $code = Session()->get('gauthCode');
        return $hash == md5(Session()->id() . self::$_salt . $code);
    }

    private static function _getGAuthClient()
    {
        $gApi = new Google_Client();

        $gApi->setClientId(Config()->auth['gAuth']['clientId']);
        $gApi->setRedirectUri(PROJECT_HOST . '/login/gauth');
        $gApi->setScopes(array('https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile'));
        $gApi->setState(base64_encode(Request()->backUrl() ? Request()->backUrl() : '/'));

        return $gApi;
    }
}