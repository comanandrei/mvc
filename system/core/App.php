<?php

namespace Cool\system\core;

use Cool\system\core\Input;

/**
 * Class App
 * @package Cool\system\core
 */
class App {


    var $config                 = array();
    private static $instance    = null;
//    private static $databases   = array();


    function __construct()
    {

        $this->get_app_constants();
        $this->init_config();

        spl_autoload_extensions(PHP_EXT);
        spl_autoload_register(array($this, 'autoload'));
    }


    public static function instance() {
        if(!isset(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Get app constants
     */
    public function get_app_constants()
    {
        include_once('app_constants.php');
    }

    /**
     * @return array
     * Initiation configuration
     */
    private function init_config()
    {
        static $conf = null;
        if($conf !== null) {
            return $conf;
        }

        $conf = array();
        foreach (scandir(BASE_URL . 'app\config') as $file) {
            if($this->phpFile($file)) {
                $conf[rtrim($file, PHP_EXT)] = include_once(CONFIG_PATH . $file );
            }
        }

        $this->config = $conf;

    }

    /**
     * @param $file
     * @return bool
     * Verify if is php file
     */
    public function phpFile($file)
    {
        return (substr($file, -4) === PHP_EXT);
    }

    /**
     * @param string $namespace
     * Autoloads a class
     */
    private function autoload($namespace)
    {
        $class = ucfirst($this->get_class_name($namespace));

        if ($class == 'Twig_Autoloader') {
            include SYSTEM_PATH.'library/Twig/Autoloader.php';
            return;
        }

        if (file_exists(BASE_URL.'system/core/'.$class.'.php')) {
            include BASE_URL.'system/core/'.$class.'.php';
        } elseif (file_exists(APP_PATH.'controller/'.$class.'.php')) {
            include APP_PATH.'controller/'.$class.'.php';
        } elseif (file_exists(APP_PATH.'model/'.$class.'.php')) {
            include APP_PATH.'model/'.$class.'.php';
        } elseif (strpos($namespace, '\Database\\')) {
            include APP_PATH.'database/'.$class.'.php';
        }
    }

    /**
     * @param string $namespace
     * @return string
     * Separates the class name from namespace
     */
    private function get_class_name($namespace)
    {
        $class = explode('\\', $namespace);
        $class = end($class);
        return $class;
    }

    /*
     * @return Input
     * Input Container
     */
    public function input()
    {
        static $input = null;
        if ($input !== null) {
            return $input;
        }

        $input = new Input($this->config);
        return $input;
    }

    public function execute()
    {

        /** @var Input $input */
        $input = $this->input();

        $controller = $input->getController();
        $class = 'Cool\app\controller\\' . $controller;

        if (class_exists($class)) {
            $class = new $class();
        } else {
            exit('Unknown controller: '.$class);
        }

        $function = $input->getFunction();
        $arrClassFunction = array($class, $function);
        $arrParams = $input->functionParam;

        if (method_exists($class, $function) && is_callable($arrClassFunction, false, $input->functionParam)) {

            call_user_func_array($arrClassFunction, $arrParams);

        } else {
            exit('Unknown function: '.$function.' in '.$class);
        }

    }

//    /**
//     * @param string $name
//     * @return mixed
//     * Get database
//     */
//    public function getDatabase($name = 'db') {
//        if (!isset(self::$databases[$name])) {
//            self::$databases[$name] = new Db($this->config['database'][$name]);
//        }
//        return self::$databases[$name];
//    }


}