<?php
class Redirect extends url{
	static function to($uri,$istop = null){
		//header("location: ".self::baseUrl(trim($uri,'/')));
		if(!$istop){
			
			header("location: ".self::baseUrl(trim($uri,'/')));
			ob_end_flush();
		}else{
			$url = self::baseUrl(trim($uri,'/'));
			echo '<script type="text/javascript">top.location.href="'.$url.'";</script>';
		}
	}
}