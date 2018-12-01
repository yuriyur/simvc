<?php
abstract class ControllerBase {
    protected $registry;
    protected $db_worker;
    protected $needsAuthentication = false;

    function __construct($registry) {
        $this->registry = $registry;
        $db_worker = $this->get_db_worker();
        if ($this->needsAuthentication){
            if (!UserManager::_authenticated()) DeathManager::NotAuthenticated();
        }
    }
    abstract function index();

    public abstract function get_db_worker();

    public function redirect ($url) {
        header('Location: ' . $url);
        die();
    }
}
?>