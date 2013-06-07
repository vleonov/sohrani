<?php

class U_GAuth
{
    private static $_clientId = '317079635200.apps.googleusercontent.com';
    private static $_basePath = 'https://accounts.google.com/o/oauth2/auth';
    private static $_salt = 'OD<oo|z6 Oot7dae. jei\Lai1 Ea9do`sh quu*iY1E pu2lae%Z oowoh;W4 Roj`ijo5';

    public static function loginUrl()
    {
        $params = array(
            'response_type' => 'code',
            'client_id' => self::$_clientId,
            'redirect_uri' => PROJECT_HOST . '/login/gauth',
            'scope' => 'https://www.googleapis.com/auth/userinfo.email https://www.googleapis.com/auth/userinfo.profile',
            'state' => base64_encode(Request()->backUrl() ? Request()->backUrl() : '/'),
        );

        $uri = self::$_basePath . '?' . http_build_query($params);

        return $uri;
    }

    public static function login()
    {
        $backUri = '/';//Request()->get('state') ? base64_decode(Request()->get('state')) : '/';
        if (Request()->get('error')) {
            return $backUri;
        }

        $code = Request()->get('code');
        $hash = md5(Session()->id() . self::$_salt . $code);

        Response()->setCookie('gauth', $hash);
        Session()->set('gauthCode', $code);

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
}