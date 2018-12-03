<?php
class Registry Implements ArrayAccess {
    private $vars = array();
    const SETTINGS_THEME = "theme";
    const DB_OBJECT = "db";
    const TEMPLATER_OBJECT = "templater";
    const USER_OBJECT = "user_manager";

    public function keyExists($key) {
        return (isset($this->vars[$key]) == true);
    }

    public function set($key, $var) {
        if (isset($this->vars[$key]) == true) {
            throw new Exception('Unable to set var `' . $key . '`. Already set.');
        }
        $this->vars[$key] = $var;
        return true;
    }

    public function get($key) {
        if (isset($this->vars[$key]) == false) {
            return null;
        }
        return $this->vars[$key];
    }

    public function remove($var) {
        unset($this->vars[$key]);
    }


//--------------------ArrayAccess members--------------------------//
    function offsetExists($offset) {
        return isset($this->vars[$offset]);
    }

    function offsetGet($offset) {
        return $this->get($offset);
    }

    function offsetSet($offset, $value) {
        $this->set($offset, $value);
    }

    function offsetUnset($offset) {
        unset($this->vars[$offset]);
    }
//--------------------End of ArrayAccess members--------------------------//


//--------------------Singleton implementation--------------------------//
    private static $instance;

    private function __construct() {
        $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
        $db->query("SET NAMES utf8");
        $this->set (self::DB_OBJECT, $db);

        $this->set(self::SETTINGS_THEME, "user");
        
        $templater = new Templater;
        $templater->compile_check = true;
        $templater->config_dir = site_path . "templates/" .
                $this[self::SETTINGS_THEME] . "/" . "configs";
        $templater->template_dir = site_path . "templates/" .
                $this[self::SETTINGS_THEME] . "/" . "templates";
        $templater->compile_dir = site_path . "templates/" .
                $this[self::SETTINGS_THEME] . "/" . "templates_c";
        
        $templater->assign("template_dir", "/templates/".$this[self::SETTINGS_THEME]."/");

        $this->set (self::TEMPLATER_OBJECT, $templater);
    }

    private function __clone() {
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self;
        }
        return self::$instance;
    }
//--------------------End of Singleton implementation-------------------------//
}
?>