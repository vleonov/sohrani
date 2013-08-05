<?php

class C_Main extends Controller
{

    public function main()
    {
        $lCards = new L_Cards(array('is_active' => true), array('modified_at DESC'));

        return Response()
            ->assign(
                array(
                     'cards' => $lCards,
                     'nullCard' => new M_Card(),
                )
            )->fetch('index.tpl');

    }
}