<?php

namespace Cool\system\core;

use Twig_Autoloader;
use Twig_Environment;
use Twig_Loader_Filesystem;

/**
 * Class Output
 * @package Cool\system\core
 */
class Output {

    private $twig;
    private $view;
    private $vars = array();


    function __construct()
    {
        Twig_Autoloader::register();
        $loader     = new Twig_Loader_Filesystem(BASE_URL . VIEW_PATH);
        $this->twig = new Twig_Environment($loader);
    }

    /**
     * @param mixed $view
     * Set view
     */
    public function setView($view)
    {
        $this->view = $view.'.twig';
    }

    /**
     * @param array $vars
     * Set vars
     */
    public function setVars($vars)
    {
        $this->vars = $vars;
    }

    /**
     * Render view
     */
    public function renderView() {
//        //TODO: understand why verify rendered
//        if ($this->rendered) {
//            return false;
//        }
//        $this->rendered = true;

        if(file_exists(VIEW_PATH . $this->view)){
            $view = $this->twig->render($this->view, $this->vars);
            echo $view;
        } else {
            exit('Unknown view file: '.$this->view);
        }

        return true;
    }


} 