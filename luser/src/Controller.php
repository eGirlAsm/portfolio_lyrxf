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

// |          Your Name <egirlasm@.com>                                 |

// +----------------------------------------------------------------------+

//

// $Id:$



class Controller {
	
	private static $app;
	
	public function display($template, $array = null){
		$this->bindParam(array('base_url'=>url::baseUrl()));  
		view::display($this->layout.DIRECTORY_SEPARATOR.$template,$array);//添加 layout;
	}	
	
	public function make($template, $array = null){
		$this->bindParam(array('base_url'=>url::baseUrl()));
		view::make($this->layout.DIRECTORY_SEPARATOR.$template,$array);//添加 layout;
	}

	public function bindParam($value){
		view::bindParam($value);
	}		
	
	public function load($file){
		self::$app 		 = realpath(BASE_PATH.'/app/');
         if (file_exists(self::$app . DIRECTORY_SEPARATOR .'library/'.$file . '.php')) {
                require  self::$app . DIRECTORY_SEPARATOR .'library/'.$file . '.php';
				//echo "success load model->".self::$app . DIRECTORY_SEPARATOR . $file . '.php'."<br>";
          } 
	} 	
		
}



?>