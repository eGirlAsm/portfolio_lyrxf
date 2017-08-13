<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */

// +----------------------------------------------------------------------+

// | PHP version 5                                                        |

// +----------------------------------------------------------------------+

// | Copyright (c) 1997-2004 The PHP Group                                |

// +----------------------------------------------------------------------+

// | This source file is subject to version 3.0 of the PHP license,       |

// | that is bundled with this package in the file LICENSE, and is        |

// | available through the world-wide-web at the following url:           |

// | http://www.php.net/license/3_0.txt.                                  |

// | If you did not receive a copy of the PHP license and are unable to   |

// | obtain it through the world-wide-web, please send a note to          |

// | license@php.net so we can mail you a copy immediately.               |

// +----------------------------------------------------------------------+

// | Authors: Original Author <author@example.com>                        |

// |          Your Name <you@example.com>                                 |

// +----------------------------------------------------------------------+

//

// $Id:$
//模板类
class View{

    protected static $cacheDir = "cache";

    protected static $var = array();
	
	/* 直接执行 php 不使用 模板引擎*/
    public static function display($template, $array = null) {
 		$templateFile = APP . '/views/' . $template;

        $cacheFile = realpath(APP . DIRECTORY_SEPARATOR . self::$cacheDir) . DIRECTORY_SEPARATOR . basename($template);

		if(count(self::$var)){

			foreach(self::$var as $var_arr){

				foreach($var_arr as $k){

					extract($k, EXTR_SKIP);

				}

			}

		}
		if(count($array))
        	extract($array, EXTR_SKIP);

        if (file_exists($templateFile)) require $templateFile;
		else echo "can not find template file";

	}

	
	/*			使用模板引擎,		*/
    public static function make($template, $array = null) {

 		$templateFile = APP . '/views/' . $template;

        $cacheFile = realpath(APP . DIRECTORY_SEPARATOR . self::$cacheDir) . DIRECTORY_SEPARATOR . basename($template);
		//echo $cacheFile;
		//return;

		if(count(self::$var)){
			foreach(self::$var as $var_arr){
				foreach($var_arr as $k){
					extract($k, EXTR_SKIP);
				}
			}
		}
		if(count($array))
        	extract($array, EXTR_SKIP);


        ob_start();
        if (file_exists($templateFile)) require $templateFile;
		else echo "can not find template file";
		
		$buffer = ob_get_contents();
		ob_end_clean();
		$str = view::mb($buffer,"{{","}}");
		//echo $str;
		//return;
 		//打开模板文件
		//$fp = fopen($templateFile,"r");
		//$filecontent=fread($fp,filesize($templateFile));
		//fclose($fp);
		//替换变量
		//$str = view::mb($filecontent,"{{","}}");
		//写入缓存目录.
		$fp = fopen($cacheFile, "w+");

		if (is_writable($cacheFile)) {

			if (fwrite($fp, $str)) {

				fclose($fp); //关闭指针

				require $cacheFile;

			}

		}
       

       

    }

    public static function setTemplateCacheDir($templateCacheDir) {

        self::$cacheDir = $templateCacheDir;

    }

	//文件进行MD5比较;
	/*
	$result = md5FileCheck('001.rmvb', '002.rmvb');
	if($result['compare'] == 1){
	echo '文件相同。<br />'.$result['intro'];
	}else{
	echo '文件不同。<br />'.$result['intro'];
	}
	*/
	public static function md5FileCheck($filename1, $filename2)
	{
		$m1 = md5_file($filename1);
		$m2 = md5_file($filename2);
		$result     = array();
		
		if($m1 == $m2)
		{
		     $result['compare'] = 1;
			 $result['intro']   = $filename1.' md5:'.$m1.'<br />'.$filename2.' md5:'.$m2;
		  
		}else{
			 $result['compare'] = 0;
			 $result['intro']   = $filename1.' md5:'.$m1.'<br />'.$filename2.' md5:'.$m2;
		}
		return $result;
	
	}

	public static function bindParam($value){

		self::$var[] = array($value);

	}	
//preg_match('/\{[0-9a-zA-Z]*\}/'
	public static function mb($str, $left, $right) {
		/*$matches = array();
		echo preg_replace("/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/","<?php foreach(\\1  as $value); echo \\1[\\2]  ?>",$str);
		print_r($matches);
		return;*/
        //if操作
        /*$str = preg_replace( "/".$left."if([^{]+?)".$right."/", "<?php if \\1 { ?>", $str );
        $str = preg_replace( "/".$left."else".$right."/", "<?php } else { ?>", $str );
        $str = preg_replace( "/".$left."elseif([^{]+?)".$right."/", "<?php } elseif \\1 { ?>", $str );
        //foreach操作
        $str = preg_replace("/".$left."foreach([^{]+?)".$right."/","<?php foreach \\1 { ?>",$str);
        $str = preg_replace("/".$left."\/foreach".$right."/","<?php } ?>",$str);
        //for操作
        $str = preg_replace("/".$left."for([^{]+?)".$right."/","<?php for \\1 { ?>",$str);
        $str = preg_replace("/".$left."\/for".$right."/","<?php } ?>",$str);*/
        //输出变量
        $str = preg_replace( "/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/", "<?php echo \\1;?>", $str );
        //常量输出
        $str = preg_replace( "/".$left."([A-Z_\x7f-\xff][A-Z0-9_\x7f-\xff]*)".$right."/s", "<?php echo \\1;?>", $str );
        //标签解析
        $str = preg_replace ( "/".$left."\/if".$right."/", "<?php } ?>", $str );
		//数组解析
		$str =  preg_replace("/".$left."(\\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)\.([a-zA-Z_\x7f-\xff][a-zA-Z0-9_$\x7f-\xff\[\]\'\']*)".$right."/",
		"<?php foreach(\\1  as \$value); echo \$value[\\2]  ?>",$str);

        $pattern = array('/'.$left.'/', '/'.$right.'/');
        $replacement = array('<?php ', ' ?>');
        return preg_replace($pattern, $replacement, $str);
     }

}

?>



