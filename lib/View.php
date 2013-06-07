<?php

require_once(ROOT_DIR . '/lib/External/Smarty/Smarty.class.php');

class View extends Smarty
{

    public function __construct()
    {
        parent::__construct();

        $this->setTemplateDir(ROOT_DIR . '/view/')
            ->setCompileDir(ROOT_DIR . '/var/compiled/');
    }
}