<?php

namespace Cool\app\controller;

use Cool\system\core\CoolController;

class Ex_Controller extends CoolController {

    function __construct()
    {
        parent::__construct();
    }

    /**
     * Test function
     */
    public function index($aw)
    {


//        print_r($this->input->functionParam);
        $post_get = $this->input->vars;

        $this->output->setVars = array('some vars');
        $this->output->setView('Ex_View');
        $this->output->renderView();
    }
}