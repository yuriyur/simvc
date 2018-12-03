<?php
class Router {
    private $registry;
    private $path;
    private $args = array();

    function __construct($registry) {
        $this->registry = $registry;
    }

    function setPath($path) {
        $path = trim($path, '/\\');
        $path .= DIRSEP;
        $path = "/".$path;
        if (is_dir($path) == false) {
            throw new Exception ('Invalid controller path: `' . $path . '`');
        }
        $this->path = $path;
    }

    function delegate_cache() {
        if (!empty($_POST)) return $this->delegate();

        $this->getController($file, $controller, $action, $args);

        $get_content = "";
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $get_content .= $key."-".$value;
            }
        }
        $path = $controller."-".$action."-".$get_content;

        $cacher = new Cacher($path);
        if ($cacher->existsInCache()) return $cacher->getFromCache();
        else {
            $cacher->startCaching();
            $this->delegate();
            $cacher->saveCache();
        }
    }

    function delegate() {
        $this->getController($file, $controller, $action, $args);
        if (is_readable($file) == false) {
            die ('404 Not Found');
        }
        include ($file);
        // Create a controller instance
        $class = 'Controller_' . $controller;
        $controller = new $class($this->registry);
        
        $registry = Registry::getInstance();
        $templater = $registry[Registry::TEMPLATER_OBJECT];
        if (is_callable(array($controller, $action)) == false) {
            $controller = new Controller_Index($this->registry);
            $controller->index();
            $templater->assign("controller", "index");
            $templater->assign("controller_action", "index");
        }
        else {
            $templater->assign("controller", $controller);
            $templater->assign("controller_action", $action);
            $controller->$action();
        }
    }

    private function getController(&$file, &$controller, &$action, &$args) {
        $route = (empty($_GET['route'])) ? '' : $_GET['route'];
        if (empty($route)) {
    	   $parts = explode("?", $_SERVER["REQUEST_URI"]);
	       $route = $parts[0];
        }
        $route = trim($route, '/\\');
        $parts = explode('/', $route);
        $cmd_path = $this->path;
        foreach ($parts as $part) {
            $fullpath = $cmd_path . $part;
            if (is_dir($fullpath)) {
                $cmd_path .= $part . DIRSEP;
                if ($part == "admin") {
                    $registry = Registry::getInstance();
                    $registry["templater"]->SetAdminPath();
                }
                array_shift($parts);
                continue;
            }
            if (is_file($fullpath . '.php')) {
                $controller = $part;
                array_shift($parts);
                break;
            }
        }
        if (empty($controller)) {
            $controller = 'index';
        };
        $action = array_shift($parts);
        if (empty($action)) {
            $action = 'index';
        }
        $file = $cmd_path . $controller . '.php';
        $args = $parts;
    }
}
?>