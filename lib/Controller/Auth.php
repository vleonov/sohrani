<?php

class C_Auth extends Controller
{
    public function login()
    {
        return Response()->redirect(U_GAuth::loginUrl());
    }

    public function logout()
    {
        return Response()->redirect(U_GAuth::logout());
    }

    public function gauthCallback()
    {
        return Response()->redirect(U_GAuth::login());
    }
}