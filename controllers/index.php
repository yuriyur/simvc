<?php
class ControllerIndex Extends ControllerBase {
    function  get_db_worker() {
        $db = $this->registry[Registry::DB_OBJECT];
        $this->db_worker = new dbworker_data($db);
    }


    function dologin() {
        if (empty ($_POST)) {
            return $this->login();
        }

        $um = new UserManager();

        if ($um->Authenticate($_POST["input_user"], $_POST["input_password"])) {
            $uid = $um->getCurrentUserId();
            if ($uid == 0) DeathManager::NotAuthenticated ();

            $url = "datawork=".$_POST["input_user"];
            DeathManager::RedirectTo($url);
        }
        else {
            return $this->login();
        }
    }

    function login() {
        $um = new UserManager();

        $templater = $this->registry[Registry::TEMPLATER_OBJECT];
        $templater->assign("users", $um->getUsersList());   
        $templater->display("login.tpl");
    }

    function index() {
        return $this->login();
    }
}
?>