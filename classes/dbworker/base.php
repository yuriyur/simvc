<?php
class dbworker_base {
    protected $db;
    protected function queryFail() {
        die("Database Error");
    }

    function __construct($db) {
        $this->db = $db;
    }
}
?>