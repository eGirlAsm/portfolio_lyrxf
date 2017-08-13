<?php

class UserController extends Controller{

	protected $layout = "member";
	protected $mmc;
	
	public function __construct(){
		//初始化 mem_cache;
		$this->mmc=memcache_init();
		// memcache_set($this->mmc,"status","0");
		// echo memcache_get($mmc,"key");
	}
	
	
	public function post_add(){
		
		$users = new Model('users');
		if(!count($users->get('username',Input::get('username')))){
			$d = array(
			'username'=>trim(Input::get('username')),
			'password'=>md5(trim(Input::get('password'))),
			'realname'=>Input::get('realname'),
			'privilege'=>Input::get('privilege'),
			'created_at'=>date('Y-m-d G:i:s')
			);
			
			$users->save($d);
			$this->display('success.php');
		}
		else{
			$this->display('failed.php');
		}
	}
	
	public function member_list(){
		$user = new Model('users');
		
		if(!empty($_GET[id])){ //修改密码

			if(!empty($_GET[new_password])){
			
				$d = array(
					'password'=>md5(trim($_GET['new_password']))
				);				
					
				$user->update($d,$_GET[id]);
				
				memcache_set($this->mmc,'id',$_GET['id']);
				
			}
			$user_data = $user->get();
			echo json_encode($user_data);			
		}
		else{
	
			$user_data = $user->get();	
			for($i = 0;$i< count($user_data);$i++){
				if($user_data[$i]['id'] == memcache_get($this->mmc,"id"))//判断上次修改的id是谁
					$user_data[$i]['status'] = 1;//设置id的status为
				else
					$user_data[$i]['status'] = 0;
			}
				memcache_set($this->mmc,'id',0);	
			echo json_encode($user_data);
		}
	}
	
	
	public function show_add(){
		$pr = new Model('privilege');
		//$pr->orderBy('id','desc');
		$pr_data = $pr->get();
		$this->bindParam(array('privilege'=>$pr_data));
		$this->display('add.php');
	}
	
	public function show_list(){
		$pr = new Model('privilege');
		//$pr->orderBy('id','desc');
		$pr_data = $pr->get();
		$this->bindParam(array('privilege'=>$pr_data));		
		$this->display('list.php');
	}
	
	public function show_privilege(){
		$this->display('privilege.php');
	}
	
	public function show_edit(){
		$this->display('edit.php');
	}
	
	public function privilege_list(){
		$pr = new Model('privilege');
		$pr_data = $pr->get();
		for($i=0;$i < count($pr_data) ; $i++)
		$pr_data[$i]['privilege'] = json_decode($pr_data[$i]['privilege']);
		echo json_encode($pr_data);
	}
	
	public function privilege_add(){
		$privilege1 = isset($_GET['kapian']) ? "卡片管理" : "";
		$privilege2 = isset($_GET['huankuan']) ? "还款管理" : "";
		$privilege3 = isset($_GET['shuaka']) ? "刷卡管理" : "";
		$pr = new Model('privilege');
		$d = array(
		'name'=>$_GET['name'],
		'privilege'=> json_encode(array($privilege1,$privilege2,$privilege3))
		);
		$pr->save($d);
		$this->privilege_list();
		
	}
	public function privilege_edit(){
		$privilege1 = isset($_GET['kapian']) ? "卡片管理" : "";
		$privilege2 = isset($_GET['huankuan']) ? "还款管理" : "";
		$privilege3 = isset($_GET['shuaka']) ? "刷卡管理" : "";
		$pr = new Model('privilege');
		$d = array(
		'name'=>$_GET['name'],
		'privilege'=> json_encode(array($privilege1,$privilege2,$privilege3))
		);
		$pr->update($d,$_GET['id']);
		$this->privilege_list();
	}	
	
	public function privilege_del(){
		
		$pr = new Model('privilege');
		for($i = 0;$i < count($_GET[ids]);$i++){
			$pr->del('id = ' . $_GET[ids][$i]);
		}
		echo json_encode(array("success"=>true));
		//Redirect::to('data/privilege/refresh');
	}
	
	public function member_del(){
		$pr = new Model('users');
		for($i = 0;$i < count($_GET[ids]);$i++){
			$pr->del('id = ' . $_GET[ids][$i]);
		}
		echo json_encode(array("success"=>true));		
	}
	
}
?>