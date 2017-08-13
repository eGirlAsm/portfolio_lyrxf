<?php
class Application extends Route {
    protected $base_url;
    protected $path = array();
    public static $__instance;
    public function __construct() {
		
        self::$__instance = $this;
    }
    public static function getInstance() {
        return self::$__instance;
    }
    public function instance($pathName, $realpath) {
        $this->path[] = array(
            $pathName,
            $realpath
        );
    }
    public function getPath($pathName) {
        foreach ($this->path as $p) {
            if ($p[0] == $pathName) return $p[1];
        }
	}
    public function bindInstallPaths(array $paths) {
        $this->instance('path', realpath($paths['app']));
        foreach ($paths as $key => $value) {
            $this->instance("path.{$key}", realpath($value));
        }
    }
    public function run() {
        $_DocumentPath = $_SERVER['DOCUMENT_ROOT'];
        $_FilePath = __FILE__;
        $_RequestUri = $_SERVER['REQUEST_URI'];
        $_AppPath = str_replace($_DocumentPath, '', $_FilePath);
        $_UrlPath = $_RequestUri;
        $_AppPathArr = explode(DIRECTORY_SEPARATOR, $_AppPath);
        for ($i = 0; $i < count($_AppPathArr); $i++) {
            $p = $_AppPathArr[$i];
            if ($p) {
                $_UrlPath = preg_replace('/^\/' . $p . '\//', '/', $_UrlPath, 1);
            }
        }
        $_UrlPath = preg_replace('/^\//', '', $_UrlPath, 1);
        $_UrlPath = str_replace('public/', '', $_UrlPath);
		
        $this->dispath($_UrlPath);
		
    }
}