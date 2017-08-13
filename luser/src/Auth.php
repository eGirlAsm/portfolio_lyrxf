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



class Auth {

    static $db = null;

    static function check() {

        if (Session::get('username')) return true;

        return false;

    }

    static function isAdmin() {

        if (Session::get('privilige') == 9) return true;

    }

	static function register($user){
        if (trim($user['username']) == 'admin') //这里过滤掉 一些不恰当的帐号
        {
			return false;
        }		
		//这里判断用户是否已经存在/
		$users = new User;
		$email_data = $users->get('email',trim($user['email']));
		if(count($email_data))
			return false; //同样邮箱被注册过了
		
		//真正的注册了.
		//设置普通会员
		//$user['privilige'] = 1;
		$user['created_at'] = date('Y-m-d G:i:s');
		$user['created_ip'] = $_SERVER["REMOTE_ADDR"];

		//设置令牌
		$username = $user['realname'];
		$password = $user['password'];
		
		$regtime = time();  
 		$token = md5($username.$password.$regtime);		
		$user['token'] = $token;
		$user['expire_time'] = date('Y-m-d G:i:s',time()+60*60*24);
		$users->save($user);
		
		self::send_active_email($username,$user['email'],url::baseUrl()."active/".$token); 
		
		Session::set('email',$r['email']);	//重新发送的时候有用 
		
		return true;
		
	}


	static function send_active_email($username,$email,$url){
		
		$smtp['host'] = "smtp.qq.com";
		$smtp['port'] = "25";
		$smtp['user'] = "account@ihc114.com";
		$smtp['pass'] = "lulu5211314";
		$smtp['email'] = "account@ihc114.com";
		
		//$email = "egirlasm@qq.com";
		//$username = "爱慧创-洛阳生活馆";
		$subject = '请激活您在'."爱慧创-洛阳生活馆".'注册的账号！';
		$message = "
			<html>
				<head>
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
				</head>
				<body>
					尊敬的用户 ".$username."，您好！<br />
					您在本站注册的账号还需一步完成注册，请点击以下链接激活您的账号：<br />
					<a href=\"$url\">$url</a>
				</body>
			</html>";
		$message = str_replace("<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />", "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=GBK\" />", $message);
		
		$username = "洛阳生活馆";
		$subject = iconv('UTF-8', 'GBK', $subject);
		$message = iconv('UTF-8', 'GBK', $message);
		$username = iconv('UTF-8', 'GBK', $username);	
		return lus_mail::send($smtp, $username, $email, $subject, $message);		
	}

	static function setSession($r){
		Session::set('username', $r['username']);
		Session::set('email',$r['email']);	
		Session::set('realname',$r['realname']);
		Session::set('is_logged_in',TRUE);
		Session::set('privilege',$r['privilege']);
		//echo $r['privilege'];exit();
	}

	static function checkEmail($user){
		$users = new User;
		$data = $users->get('email', $user['email']);
			foreach ($data as $r) {
				if ($r['password'] == $user['password']) {
					Session::set('email',$r['email']);	
					if($r[status] == 0)
						return -2;
					
					self::setSession($r);
					return true;
				}
			}	
		return false;				
	}

	static function checkUserName($user){
		
		$users = new User;
		$data = $users->get('username', $user['username']);
				foreach ($data as $r) { 
					//var_dump($r['password']);var_dump(md5(trim($user['password'])));exit();
					if (trim($r['password']) == md5(trim($user['password']))) {
						//echo "login ok";exit();
						Session::set('email',$r['email']);	
						//if($r[status] == 0)
							//return -2;
						
						self::setSession($r);
						return true;
			} 
		}
		return false;			
	}

    static function login($user) {
		if(isset($user['username'])){
			if (trim($user['username']) == 'admin') //为了便于权限控制和 对客户的限制.
			{ 
				if ($user['password'] == 'hcwl65112022') {
	
					Session::set('username', 'admin');
					Session::set('realname','admin');
					Session::set('privilege',9);
	
					return true;
	
				}
			}
		}
		$vcode = trim(Session::get('authnum_session'));
        if(empty($vcode)){
				if(isset($user['email'])){
					return self::checkEmail($user);
				}else{
					return self::checkUserName($user); 
				}
		}else{
			//判断验证码
			if(!strcasecmp($vcode,trim($user['vcode']))){
				if(isset($user['email'])){
					return self::checkEmail($user);
				}else{
					return self::checkUserName($user); 
				}
			}else{
				return -1;  
			}
			return false;
		}

    }

}

?>

