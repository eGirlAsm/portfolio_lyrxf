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
////////////////////////// start php ///////////////////////
// 微信公众平台接口封装功能类文件
// author  TIM
// Date 2013.10.24



class WeChat {
	
private static $number= "/(^1350459[0-9]{4}$)|(^1360459[0-9]{4}$)|(^1370459[0-9]{4}$)|(^1380464[0-9]{4}$)|(^1390369[0-9]{4}$)|(^1590459[0-9]{4}$)|(^1580459[0-9]{4}$)|(^1500457[0-9]{4}$)|(^1516452[0-9]{4}$)|(^1524590[0-9]{4}$)|(^1880459[0-9]{4}$)|(^1470459[0-9]{4}$)|(^1571459[0-9]{4}$)|(^1361459[0-9]{4}$)|(^1870459[0-9]{4}$)|(^1820457[0-9]{4}$)|(^1830459[0-9]{4}$)|(^1350465[0-9]{4}$)|(^1360465[0-9]{4}$)|(^1370465[0-9]{4}$)|(^1380465[0-9]{4}$)|(^1390459[0-9]{4}$)|(^1594590[0-9]{4}$)|(^1584529[0-9]{4}$)|(^1500458[0-9]{4}$)|(^1516453[0-9]{4}$)|(^1524591[0-9]{4}$)|(^1884590[0-9]{4}$)|(^1478459[0-9]{4}$)|(^1571461[0-9]{4}$)|(^1361464[0-9]{4}$)|(^1874590[0-9]{4}$)|(^1820459[0-9]{4}$)|(^1834540[0-9]{4}$)|(^1350466[0-9]{4}$)|(^1360466[0-9]{4}$)|(^1370466[0-9]{4}$)|(^1380466[0-9]{4}$)|(^1390469[0-9]{4}$)|(^1594591[0-9]{4}$)|(^1584689[0-9]{4}$)|(^1500459[0-9]{4}$)|(^1516454[0-9]{4}$)|(^1524592[0-9]{4}$)|(^1884648[0-9]{4}$)|(^1479459[0-9]{4}$)|(^1570459[0-9]{4}$)|(^1361465[0-9]{4}$)|(^1874591[0-9]{4}$)|(^1824569[0-9]{4}$)|(^1834541[0-9]{4}$)|(^1350467[0-9]{4}$)|(^1360467[0-9]{4}$)|(^1370467[0-9]{4}$)|(^1380467[0-9]{4}$)|(^1390486[0-9]{4}$)|(^1594592[0-9]{4}$)|(^1584580[0-9]{4}$)|(^1504688[0-9]{4}$)|(^1516455[0-9]{4}$)|(^1524593[0-9]{4}$)|(^1884649[0-9]{4}$)|(^1474598[0-9]{4}$)|(^1573459[0-9]{4}$)|(^1361466[0-9]{4}$)|(^1874592[0-9]{4}$)|(^1824570[0-9]{4}$)|(^1834542[0-9]{4}$)|(^1350489[0-9]{4}$)|(^1360489[0-9]{4}$)|(^1370489[0-9]{4}$)|(^1380468[0-9]{4}$)|(^1390489[0-9]{4}$)|(^1584581[0-9]{4}$)|(^1504689[0-9]{4}$)|(^1516456[0-9]{4}$)|(^1524594[0-9]{4}$)|(^1884591[0-9]{4}$)|(^1474599[0-9]{4}$)|(^1575459[0-9]{4}$)|(^1362459[0-9]{4}$)|(^1874593[0-9]{4}$)|(^1824571[0-9]{4}$)|(^1834543[0-9]{4}$)|(^1355550[0-9]{4}$)|(^1360464[0-9]{4}$)|(^1376650[0-9]{4}$)|(^1380469[0-9]{4}$)|(^1393670[0-9]{4}$)|(^1584582[0-9]{4}$)|(^1504587[0-9]{4}$)|(^1516457[0-9]{4}$)|(^1524595[0-9]{4}$)|(^1884592[0-9]{4}$)|(^1576459[0-9]{4}$)|(^1362466[0-9]{4}$)|(^1874594[0-9]{4}$)|(^1824572[0-9]{4}$)|(^1834544[0-9]{4}$)|(^1355551[0-9]{4}$)|(^1363457[0-9]{4}$)|(^1376677[0-9]{4}$)|(^1383670[0-9]{4}$)|(^1393671[0-9]{4}$)|(^1584583[0-9]{4}$)|(^1504588[0-9]{4}$)|(^1516458[0-9]{4}$)|(^1524596[0-9]{4}$)|(^1884593[0-9]{4}$)|(^1577459[0-9]{4}$)|(^1364459[0-9]{4}$)|(^1874595[0-9]{4}$)|(^1824573[0-9]{4}$)|(^1834550[0-9]{4}$)|(^1355552[0-9]{4}$)|(^1363459[0-9]{4}$)|(^1376678[0-9]{4}$)|(^1383671[0-9]{4}$)|(^1393672[0-9]{4}$)|(^1584584[0-9]{4}$)|(^1504589[0-9]{4}$)|(^1516459[0-9]{4}$)|(^1524597[0-9]{4}$)|(^1884594[0-9]{4}$)|(^1573465[0-9]{4}$)|(^1364469[0-9]{4}$)|(^1874596[0-9]{4}$)|(^1824574[0-9]{4}$)|(^1834551[0-9]{4}$)|(^1355553[0-9]{4}$)|(^1363466[0-9]{4}$)|(^1376679[0-9]{4}$)|(^1383672[0-9]{4}$)|(^1393673[0-9]{4}$)|(^1584585[0-9]{4}$)|(^1504590[0-9]{4}$)|(^1514592[0-9]{4}$)|(^1524598[0-9]{4}$)|(^1884595[0-9]{4}$)|(^1573466[0-9]{4}$)|(^1366466[0-9]{4}$)|(^1874597[0-9]{4}$)|(^1824575[0-9]{4}$)|(^1834552[0-9]{4}$)|(^1355555[0-9]{4}$)|(^1363489[0-9]{4}$)|(^1379659[0-9]{4}$)|(^1383673[0-9]{4}$)|(^1393674[0-9]{4}$)|(^1584586[0-9]{4}$)|(^1504591[0-9]{4}$)|(^1514593[0-9]{4}$)|(^1524599[0-9]{4}$)|(^1884596[0-9]{4}$)|(^1576465[0-9]{4}$)|(^1366459[0-9]{4}$)|(^1874598[0-9]{4}$)|(^1824576[0-9]{4}$)|(^1834553[0-9]{4}$)|(^1355556[0-9]{4}$)|(^1379698[0-9]{4}$)|(^1383674[0-9]{4}$)|(^1393675[0-9]{4}$)|(^1584587[0-9]{4}$)|(^1504592[0-9]{4}$)|(^1514594[0-9]{4}$)|(^1520459[0-9]{4}$)|(^1884597[0-9]{4}$)|(^1576466[0-9]{4}$)|(^1367459[0-9]{4}$)|(^1874599[0-9]{4}$)|(^1824577[0-9]{4}$)|(^1834554[0-9]{4}$)|(^1355549[0-9]{4}$)|(^1379699[0-9]{4}$)|(^1383675[0-9]{4}$)|(^1393676[0-9]{4}$)|(^1584588[0-9]{4}$)|(^1504593[0-9]{4}$)|(^1514595[0-9]{4}$)|(^1524600[0-9]{4}$)|(^1884598[0-9]{4}$)|(^1576590[0-9]{4}$)|(^1368459[0-9]{4}$)|(^1874660[0-9]{4}$)|(^1824578[0-9]{4}$)|(^1834555[0-9]{4}$)|(^1355554[0-9]{4}$)|(^1373455[0-9]{4}$)|(^1383676[0-9]{4}$)|(^1393677[0-9]{4}$)|(^1584589[0-9]{4}$)|(^1504594[0-9]{4}$)|(^1514596[0-9]{4}$)|(^1524601[0-9]{4}$)|(^1884599[0-9]{4}$)|(^1576595[0-9]{4}$)|(^1369459[0-9]{4}$)|(^1874661[0-9]{4}$)|(^1824579[0-9]{4}$)|(^1834556[0-9]{4}$)|(^1355557[0-9]{4}$)|(^1373456[0-9]{4}$)|(^1383677[0-9]{4}$)|(^1393678[0-9]{4}$)|(^1584590[0-9]{4}$)|(^1504595[0-9]{4}$)|(^1514597[0-9]{4}$)|(^1524602[0-9]{4}$)|(^1881459[0-9]{4}$)|(^1576596[0-9]{4}$)|(^1369460[0-9]{4}$)|(^1874662[0-9]{4}$)|(^1824580[0-9]{4}$)|(^1834557[0-9]{4}$)|(^1373457[0-9]{4}$)|(^1383678[0-9]{4}$)|(^1393679[0-9]{4}$)|(^1584591[0-9]{4}$)|(^1504596[0-9]{4}$)|(^1514598[0-9]{4}$)|(^1524603[0-9]{4}$)|(^1884660[0-9]{4}$)|(^1576597[0-9]{4}$)|(^1874663[0-9]{4}$)|(^1824588[0-9]{4}$)|(^1834558[0-9]{4}$)|(^1373458[0-9]{4}$)|(^1383679[0-9]{4}$)|(^1393680[0-9]{4}$)|(^1584592[0-9]{4}$)|(^1504597[0-9]{4}$)|(^1514599[0-9]{4}$)|(^1524604[0-9]{4}$)|(^1884661[0-9]{4}$)|(^1576598[0-9]{4}$)|(^1874664[0-9]{4}$)|(^1824590[0-9]{4}$)|(^1834559[0-9]{4}$)|(^1373459[0-9]{4}$)|(^1383680[0-9]{4}$)|(^1393681[0-9]{4}$)|(^1584593[0-9]{4}$)|(^1504598[0-9]{4}$)|(^1514634[0-9]{4}$)|(^1524605[0-9]{4}$)|(^1884662[0-9]{4}$)|(^1577610[0-9]{4}$)|(^1874665[0-9]{4}$)|(^1824591[0-9]{4}$)|(^1832459[0-9]{4}$)|(^1383681[0-9]{4}$)|(^1393682[0-9]{4}$)|(^1584594[0-9]{4}$)|(^1504599[0-9]{4}$)|(^1514635[0-9]{4}$)|(^1524606[0-9]{4}$)|(^1884663[0-9]{4}$)|(^1577611[0-9]{4}$)|(^1874666[0-9]{4}$)|(^1824592[0-9]{4}$)|(^1834660[0-9]{4}$)|(^1383682[0-9]{4}$)|(^1393683[0-9]{4}$)|(^1584595[0-9]{4}$)|(^1514636[0-9]{4}$)|(^1524607[0-9]{4}$)|(^1884664[0-9]{4}$)|(^1577612[0-9]{4}$)|(^1874667[0-9]{4}$)|(^1824593[0-9]{4}$)|(^1834661[0-9]{4}$)|(^1383683[0-9]{4}$)|(^1393684[0-9]{4}$)|(^1584596[0-9]{4}$)|(^1514637[0-9]{4}$)|(^1524608[0-9]{4}$)|(^1884665[0-9]{4}$)|(^1577613[0-9]{4}$)|(^1874668[0-9]{4}$)|(^1824594[0-9]{4}$)|(^1834662[0-9]{4}$)|(^1383684[0-9]{4}$)|(^1393685[0-9]{4}$)|(^1584597[0-9]{4}$)|(^1524609[0-9]{4}$)|(^1884666[0-9]{4}$)|(^1577614[0-9]{4}$)|(^1824595[0-9]{4}$)|(^1834663[0-9]{4}$)|(^1383685[0-9]{4}$)|(^1393686[0-9]{4}$)|(^1584598[0-9]{4}$)|(^1524610[0-9]{4}$)|(^1884667[0-9]{4}$)|(^1577615[0-9]{4}$)|(^1824596[0-9]{4}$)|(^1834664[0-9]{4}$)|(^1383686[0-9]{4}$)|(^1393687[0-9]{4}$)|(^1584599[0-9]{4}$)|(^1524611[0-9]{4}$)|(^1884668[0-9]{4}$)|(^1577616[0-9]{4}$)|(^1824597[0-9]{4}$)|(^1834665[0-9]{4}$)|(^1383687[0-9]{4}$)|(^1393688[0-9]{4}$)|(^1584616[0-9]{4}$)|(^1524612[0-9]{4}$)|(^1884669[0-9]{4}$)|(^1577617[0-9]{4}$)|(^1824598[0-9]{4}$)|(^1834666[0-9]{4}$)|(^1383688[0-9]{4}$)|(^1393689[0-9]{4}$)|(^1584617[0-9]{4}$)|(^1524613[0-9]{4}$)|(^1884628[0-9]{4}$)|(^1577618[0-9]{4}$)|(^1824599[0-9]{4}$)|(^1834667[0-9]{4}$)|(^1383689[0-9]{4}$)|(^1393690[0-9]{4}$)|(^1584618[0-9]{4}$)|(^1524614[0-9]{4}$)|(^1884629[0-9]{4}$)|(^1577619[0-9]{4}$)|(^1824667[0-9]{4}$)|(^1834668[0-9]{4}$)|(^1383690[0-9]{4}$)|(^1393691[0-9]{4}$)|(^1584690[0-9]{4}$)|(^1524615[0-9]{4}$)|(^1881461[0-9]{4}$)|(^1576580[0-9]{4}$)|(^1824678[0-9]{4}$)|(^1834669[0-9]{4}$)|(^1383691[0-9]{4}$)|(^1393692[0-9]{4}$)|(^1584691[0-9]{4}$)|(^1524616[0-9]{4}$)|(^1881476[0-9]{4}$)|(^1576581[0-9]{4}$)|(^1824679[0-9]{4}$)|(^1834590[0-9]{4}$)|(^1383692[0-9]{4}$)|(^1393693[0-9]{4}$)|(^1584692[0-9]{4}$)|(^1524617[0-9]{4}$)|(^1881479[0-9]{4}$)|(^1576582[0-9]{4}$)|(^1824683[0-9]{4}$)|(^1834591[0-9]{4}$)|(^1383693[0-9]{4}$)|(^1393694[0-9]{4}$)|(^1584693[0-9]{4}$)|(^1524618[0-9]{4}$)|(^1576583[0-9]{4}$)|(^1824684[0-9]{4}$)|(^1834592[0-9]{4}$)|(^1383694[0-9]{4}$)|(^1393695[0-9]{4}$)|(^1584694[0-9]{4}$)|(^1524619[0-9]{4}$)|(^1576584[0-9]{4}$)|(^1824691[0-9]{4}$)|(^1834593[0-9]{4}$)|(^1383695[0-9]{4}$)|(^1393696[0-9]{4}$)|(^1584695[0-9]{4}$)|(^1521459[0-9]{4}$)|(^1576585[0-9]{4}$)|(^1824692[0-9]{4}$)|(^1834594[0-9]{4}$)|(^1383696[0-9]{4}$)|(^1393697[0-9]{4}$)|(^1584696[0-9]{4}$)|(^1521460[0-9]{4}$)|(^1576586[0-9]{4}$)|(^1824693[0-9]{4}$)|(^1834595[0-9]{4}$)|(^1383697[0-9]{4}$)|(^1393698[0-9]{4}$)|(^1521461[0-9]{4}$)|(^1576587[0-9]{4}$)|(^1824694[0-9]{4}$)|(^1834596[0-9]{4}$)|(^1383698[0-9]{4}$)|(^1393699[0-9]{4}$)|(^1576588[0-9]{4}$)|(^1824695[0-9]{4}$)|(^1834597[0-9]{4}$)|(^1383699[0-9]{4}$)|(^1394560[0-9]{4}$)|(^1576589[0-9]{4}$)|(^1824960[0-9]{4}$)|(^1834598[0-9]{4}$)|(^1384590[0-9]{4}$)|(^1394561[0-9]{4}$)|(^1577650[0-9]{4}$)|(^1824961[0-9]{4}$)|(^1834599[0-9]{4}$)|(^1384591[0-9]{4}$)|(^1394562[0-9]{4}$)|(^1577651[0-9]{4}$)|(^1824962[0-9]{4}$)|(^1384592[0-9]{4}$)|(^1394590[0-9]{4}$)|(^1577652[0-9]{4}$)|(^1824963[0-9]{4}$)|(^1384593[0-9]{4}$)|(^1394591[0-9]{4}$)|(^1577653[0-9]{4}$)|(^1824964[0-9]{4}$)|(^1384594[0-9]{4}$)|(^1394592[0-9]{4}$)|(^1577654[0-9]{4}$)|(^1824965[0-9]{4}$)|(^1384595[0-9]{4}$)|(^1394593[0-9]{4}$)|(^1577657[0-9]{4}$)|(^1824966[0-9]{4}$)|(^1384596[0-9]{4}$)|(^1394594[0-9]{4}$)|(^1577658[0-9]{4}$)|(^1824967[0-9]{4}$)|(^1384597[0-9]{4}$)|(^1394595[0-9]{4}$)|(^1577659[0-9]{4}$)|(^1824968[0-9]{4}$)|(^1384598[0-9]{4}$)|(^1394596[0-9]{4}$)|(^1824969[0-9]{4}$)|(^1384599[0-9]{4}$)|(^1394597[0-9]{4}$)|(^1824955[0-9]{4}$)|(^1394598[0-9]{4}$)|(^1824956[0-9]{4}$)|(^1394599[0-9]{4}$)|(^1824957[0-9]{4}$)|(^1394690[0-9]{4}$)|(^1824958[0-9]{4}$)|(^1394691[0-9]{4}$)|(^1394692[0-9]{4}$)|(^1394693[0-9]{4}$)|(^1394694[0-9]{4}$)|(^1394695[0-9]{4}$)|(^1394696[0-9]{4}$)|(^1394697[0-9]{4}$)|(^1394698[0-9]{4}$)|(^1394699[0-9]{4}$)/";
	
    private static $fromUsername; //发送方帐号（一个OpenID）
    private static $toUsername; //开发者微信号
    private static $times; //消息创建时间 （整型）
    private $type; //请求的信息类型 text,image,location...
    private $msgid; //消息id，64位整型
    private $token = 'fuwu'; //自行修改自己的token
    private $postStr;
    private $postObj;
    private $keyword; //文本消息内容
    private $location_x; //地理位置纬度
    private $location_y; //地理位置经度
    private $scale; //地图缩放大小
    private $label; //地理位置信息
    private $title; //消息标题
    private $description; //消息描述
    private $url; //消息链接
    private $event; //事件类型，subscribe(订阅)、unsubscribe(取消订阅)、CLICK(自定义菜单点击事件)
    private $eventkey; //事件KEY值，与自定义菜单接口中KEY值对应
	private $__callback = array();
    //构造函数
    function __construct($postStr = null) {
        if ($postStr) {
            $this->postStr = $postStr;
        }
    }

	//用来判断是否是 本地 的电话
	public static function isLocal($num) {
	
		if (preg_match(self::$number, $num)) {
			return 1;
		} else {
			return 0;
		}
	}
	
	public static function getuser(){
		return self::$fromUsername;
	}
	
	public function ProcessMsg(){
		$this->responseMsg(); //初始化数据
	}
	
	public function setCallback($type,$callback){
		$this->__callback[] = array('type'=>$type,'callback'=>$callback);
	}
    //拦截器(__get)
    public function __get($_key) {
        return $this->$_key;
    }
    //微信封装类,
    //type: text 文本类型, news 图文类型
    //text,array(内容),array(ID)
    //news,array(array(标题,介绍,图片,超链接),...小于10条),array(条数,ID)
    public static function fun_xml($type, $value_arr, $o_arr = array(0)) {
		$fromuser = self::$fromUsername;
		$touser   = self::$toUsername;
		$time     = self::$times;
        //=================xml header============
        $con = "<xml>
				<ToUserName><![CDATA[{$fromuser}]]></ToUserName>
				<FromUserName><![CDATA[{$touser}]]></FromUserName>
				<CreateTime>{$time}</CreateTime>
				<MsgType><![CDATA[{$type}]]></MsgType>";
				
        switch ($type) {
            case "text":
                $con.= "<Content><![CDATA[{$value_arr[0]}]]></Content><FuncFlag>{$o_arr}</FuncFlag>";
                break;

            case "news":
                $con.= "<ArticleCount>{$o_arr[0]}</ArticleCount><Articles>";
                foreach ($value_arr as $id => $v) {
                    if ($id >= $o_arr[0]) break;
                    else null; //判断数组数不超过设置数
                    $con.= "<item><Title><![CDATA[{$v[0]}]]></Title> <Description><![CDATA[{$v[1]}]]></Description><PicUrl><![CDATA[{$v[2]}]]></PicUrl><Url><![CDATA[{$v[3]}]]></Url></item>";
                }
                $con.= "</Articles><FuncFlag>{$o_arr[1]}</FuncFlag>";
                break;
            } 
            echo $con . "</xml>";
        }
        //初始化基本数据
        private function responseMsg() {
            if (!empty($this->postStr)) {
                $this->postObj = simplexml_load_string($this->postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
				//print_r($this->postObj);
				//return;
                self::$fromUsername = $this->postObj->FromUserName;
                self::$toUsername = $this->postObj->ToUserName;
                self::$times = time();
                $this->type = $this->postObj->MsgType;
                switch ($this->type) {
                    case 'text': //文本消息
                        $this->responseText();
                        break;

                    case 'image': //图片消息
                        $this->responseImage();
                        break;

                    case 'location': //地理位置消息
                        $this->responseLocation();
                        break;

                    case 'link': //链接消息
                        $this->responseLink();
                        break;

                    case 'event': //事件推送
                        $this->responseEvent();
                        break;
                }
            } else {
                echo "this a file for weixin API!";
                exit;
            }
        }
        //文本消息
        private function responseText() {
			$this->keyword = trim($this->postObj->Content);
            $this->msgid = $this->postObj->MsgId;
			
			foreach($this->__callback as $callback){
					if(!strcmp($callback['type'],'text')){                                  
						call_user_func_array($callback['callback'],array($this->keyword,$this->msgid));
					}
			}
			
			//$arr=array('这张图，不错嘛！'.$this->keyword.' = '.$this->msgid);
			//$this->fun_xml("text",$arr,array(0));
        }
        //图片消息
        private function responseImage() {
            $this->PicUrl = trim($this->postObj->PicUrl);
            $this->msgid = $this->postObj->MsgId;
			
			
			foreach($this->__callback as $callback){
					if(!strcmp($callback['type'],'image')){                                  
						call_user_func_array($callback['callback'],array($this->PicUrl,$this->msgid));
					}
			}
        }
		
	
        //地理位置消息
        private function responseLocation() {
            $this->location_x = $this->postObj->Location_X;
            $this->location_y = $this->postObj->Location_Y;
            $this->scale = $this->postObj->Scale;
            $this->label = $this->postObj->Label;
            $this->msgid = $this->postObj->MsgId;

			
			foreach($this->__callback as $callback){
					if(!strcmp($callback['type'],'location')){                                  
						call_user_func_array($callback['callback'],array($this->location_x,$this->location_y,$this->scale,$this->label,$this->msgid));
					}
			}
        }
        //链接消息
        private function responseLink() {
            $this->title = trim($this->postObj->Title);
            $this->description = trim($this->postObj->Description);
            $this->url = trim($this->postObj->Url);
            $this->msgid = $this->postObj->MsgId;
			
			
			foreach($this->__callback as $callback){
					if(!strcmp($callback['type'],'link')){                                  
						call_user_func_array($callback['callback'],array($this->title,$this->description,$this->url,$this->msgid));
					}
			}
        }
        //事件推送
        private function responseEvent() {
            $this->event = trim($this->postObj->Event);
            $this->eventkey = trim($this->postObj->EventKey);
			
			foreach($this->__callback as $callback){
					if(!strcmp($callback['type'],'event')){                                  
						call_user_func_array($callback['callback'],array($this->event,$this->eventkey));
					}
			}
			
        }
        //对请求进行校验，若确认此次GET请求来自微信服务器
        public function valid($echoStr, $signature, $timestamp, $nonce) {
            $signature = $_GET["signature"];
            $timestamp = $_GET["timestamp"];
            $nonce = $_GET["nonce"];
            $tmpArr = array(
                'fuwu',
                $timestamp,
                $nonce
            );
            sort($tmpArr, SORT_STRING);
            $tmpStr = sha1(implode($tmpArr));
            if ($tmpStr == $signature) {
                echo $_GET["echostr"];
            }
            exit();
        }
}

