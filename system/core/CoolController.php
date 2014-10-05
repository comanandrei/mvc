<?php

namespace Cool\system\core;

use Cool\system\core\App;
use Cool\system\core\Input;
use Cool\system\core\Output;

/**
 * Class CoolController
 * @package Cool\system\core
 */
class CoolController {

    /** @var Input $input */
    var $input;

    /** @var Output $output*/
    var $output;

    var $app;

    function __construct()
    {
        $this->app      = App::instance();

        $this->input    = new Input($this->app->config);
        $this->output   = new Output($this->app->config);
    }
}