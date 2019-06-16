<?php
class ControllerIndex Extends ControllerBase {

    public function get_db_worker() {
        $db = $this->registry[Registry::DB_OBJECT];
        $this->db_worker = new dbworker_data($db);
    }

    protected function getData() {
        $data = $this->db_worker->getAllData();
        return $data;
    }

    public function index() {
        $templater = $this->registry[Registry::TEMPLATER_OBJECT];
        $templater->assign("data", $data);
        $templater->display("index.tpl");
   }
   
}
?>