<?php
class Templater extends Smarty {
    public function assigned($value) {
        if (array_key_exists($value, $this->_tpl_vars)) return true;
        return false;
    }
	
    public function SetPath($path) {
        $this->config_dir = site_path . $path . "configs";
        $this->template_dir = site_path . $path . "templates";
        $this->compile_dir = site_path . $path . "templates_c";
        $this->assign("template_dir", "/".$path);
    }
}
?>