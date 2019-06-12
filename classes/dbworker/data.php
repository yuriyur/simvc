<?php
class dbworker_data extends dbworker_base {

    public function getAllData() {
        $q = $this->db->query("SELECT * FROM `data` ORDER BY id ASC");
        $res = $q->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    
}
?>