<?php 

class SwipeController extends Controller{


	protected $layout = 'swipe';	
	protected $pos_amount;
	protected $funds ;
	
	
	public function getposamount($amount){
		$param = new Model('params');
		$param_data  = $param->get();
		foreach($param_data as $param);
		if($amount <= 100000){
			return $param['posamount'];
		}
		else if($amount > 100000 && $amount <= 200000){
			return $param['posamount10_20'];
		}
		else if($amount > 200000 && $amount <= 300000){
			return $param['posamount20_30'];
		}
		else if($amount > 300000 && $amount <= 400000){
			return $param['posamount30_40'];
		}
		else if($amount > 400000 && $amount <= 500000){
			return $param['posamount40_50'];
		}
		else if($amount > 500000 && $amount <= 600000){
			return $param['posamount50_60'];
		}
		else if($amount > 600000 && $amount <= 700000){
			return $param['posamount60_70'];
		}
		else if($amount > 700000 && $amount <= 800000){
			return $param['posamount70_80'];
		}
		else if($amount > 800000 && $amount <= 900000){
			return $param['posamount80_90'];
		}
		else if($amount > 900000 && $amount <= 1000000){
			return $param['posamount90_100'];
		}																		
	}	
	
	public function __construct(){
		if((date('w') == 5) || (date('w') == 6)){
     		//$this->display('xiuxi.php');
			//exit();
		}	
		$param = new Model('params');
		$param_data  = $param->get();
		foreach($param_data as $this->param);
		$this->funds = $this->param[funds];

		//figure out funds
		$this->pos_amount = $this->param['posamount'];
		$this->bindParam(array('posamount'=>$this->pos_amount));
	}
	
	public function show_list(){
		$this->display('list.php');
	}


	public function show_add(){
		$this->display('add.php');
	}

	public function pos_machine_list(){
		$this->display('pos_machine_add.php');
	}
	public function swipe_total(){
		$this->display('total.php');
		
	}
	
	public function posmachine_add(){
		$d = array(
		'rate'=>$_GET['rate'],
		'shopname'=>$_GET['shopname'],
		'realname'=>$_GET['realname'],
		'passportid'=>$_GET['passportid'],
		'bank'=>$_GET['bank'],
		'cardnumber'=>$_GET['cardnumber'],
		'phone'=>$_GET['phone'],
		'timepart'=>$_GET['timepart'],
		'memo'=>$_GET['memo']
		);
		
		$pos = new Model('posmachine');
		$pos->save($d);
		$pos_data = $pos->get();
		echo json_encode($pos_data);
	}
	
	
	public function posmachine_edit(){
		$bank = new Model('posmachine');
		$newBank = array(
		'rate'=>$_GET['rate'],
		'shopname'=>$_GET['shopname'],
		'realname'=>$_GET['realname'],
		'passportid'=>$_GET['passportid'],
		'bank'=>$_GET['bank'],
		'cardnumber'=>$_GET['cardnumber'],
		'phone'=>$_GET['phone'],
		'timepart'=>$_GET['timepart'],
		'memo'=>$_GET['memo']
		);
		
		$bank->update($newBank,$_GET[id]);
		$bank_data = $bank->get();
		echo json_encode($bank_data);		
	}
	
	public function posmachine_del(){
		$pr = new Model('posmachine');
		for($i = 0;$i < count($_GET[ids]);$i++){
			$pr->del('id = ' . $_GET[ids][$i]);
		}
		echo json_encode(array("success"=>true));		
	}	
	
	public function posmachine(){
		$bank = new Model('posmachine');
		$bank_data = $bank->get();
		echo json_encode($bank_data);
	}
	
	public function json_swipe_record(){
	
		$card = new Model('swipe');
		//echo $_GET[queryString];exit();
		if(!empty($_GET[queryString])){
			//echo $_GET[queryType].$_GET[queryString];
			if($_GET[queryType] == 0){
				$card_data = $card->get('cardnumber',$_GET[queryString]);
			}else{
				$card_data = $card->get('name',$_GET[queryString]);
			}
			
			echo json_encode($card_data);			
		}
		else{
			$card->orderBy('id','desc');
			$card_data = $card->get();
			echo json_encode($card_data);
		}		
	}
	
	public function day_swipe_table(){
		
		//有人刷卡了
		if(!empty($_GET['posid'])){
			$card = new Model('card');
			$card_data = $card->get('id',$_GET['id']);
			
			//添加刷卡记录
			
			//pos机手续费
			$pos_machine = new Model('posmachine');
			$pos_machine_data = $pos_machine->get('id',$_GET['posid']);
			
			$fee =  round($_GET['swipeamount'] * ($pos_machine_data[0][rate] /100), 2);
						
			$d = array(
			'cardnumber'=>$card_data[0]['cardnumber'],
			'realname'=>$card_data[0]['name'],
			'bank'=>$card_data[0]['banktype'],
			'amount'=>$_GET['swipeamount'],
			'posid'=>$_GET['posid'],
			'fee'=>$fee,
			'fee_rate'=>$pos_machine_data[0][rate],
			'operator'=>Session::get('realname'),
			'created_at'=>date('Y-m-d G:i:s')
			);
			
			
			//卡上加钱
			$total = $card_data[0]['paid_amount'] - $_GET['swipeamount'];
			
			$update_data = array('today_swipe_status'=>0,'paid_amount'=>$total);
				
				
				
			$card->update($update_data,$_GET['id']);
			
			//回收资金
			//$param = new Model('params');
			//$balance = array('funds'=>$this->funds + $_GET['swipeamount']);
			//$param->update($balance,1);			
			
			
			//保存刷卡记录
			$sw = new Model('swipe');
			$sw->save($d);
		}
		
		$swipe = new Model('card');
		$swipe_data = $swipe->get();
		for($i = 0;$i < count($swipe_data); $i++){
			
			$useful_amount = $swipe_data[$i]['paid_amount'];//$swipe_data[$i]['bill'] -  $swipe_data[$i]['currentbalance'];  //可用金额
			$this->pos_amount = $this->getposamount($useful_amount);
			$times = round($useful_amount / $this->pos_amount);
			
			$times  = $times ? $times : ($useful_amount ? 1 : 0);// 没有次数 但是实际上确实有钱可以刷 只不过比600小了而已
			$swipe_data[$i]['times'] = $times;
			//$swipe_data[$i]['pos_amount'] = $this->pos_amount;
			$swipe_data[$i]['amount'] = $useful_amount;
			$swipe_data[$i]['avaliable'] = $swipe_data[$i]['limit'] - $swipe_data[$i]['currentbalance']; 
			
			//分配POS机
			$pos = new Model('posmachine');
			$pos_data = $pos->get();
			
			
			$sp_record = new Model('swipe');
			
			$pos_index = 0;
			$j = 0;
			for(;$j < $times;){
			
						$sp_record_data = $sp_record->get('cardnumber',$swipe_data[$i]['cardnumber']); //查询刷卡记录
						for($l = 0;$l < count($sp_record_data);$l++){
							
							
							if($sp_record_data[$l]['posid'] == $pos_data[$pos_index]['id']){
								$pos_index++;//刷卡记录显示 刷过这台POS机,那就下一个POS机吧。
								continue;
							}
						}
					
					
						if($swipe_data[$i]['name'] == $pos_data[$pos_index]['realname'])
						{
							$pos_index++;//同一个人 那么就刷掉吧

							continue;
						}
						
						if(!empty($pos_data[$pos_index]['id'])){
							$swipe_data[$i]['pos_list']['pos'.$j]['id'] = $pos_data[$pos_index]['id'];	
							$swipe_data[$i]['pos_list']['pos'.$j]['shopname'] = $pos_data[$pos_index]['shopname'];	
							$pos_index++;
						}else{
							$swipe_data[$i]['pos_error']  = 1;
						}
					
						$j++;

			
			}
		}
		
		
		echo json_encode($swipe_data);
		
	}

}

?>