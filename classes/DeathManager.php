<?php
class DeathManager {
    public static function Error404() {
        header("HTTP/1.0 404 Not Found");
        die();
    }

    public static function NotAuthenticated() {
        return self::RedirectTo("login");
    }

    public static function RedirectTo($url) {
        header("Location:".SITE_URL."/".$url);
        die();
    }
}
?>
