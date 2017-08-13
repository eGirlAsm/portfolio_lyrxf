<?php

class CardController extends Controller{
	
	protected $layout = 'card';	
	
	protected $percent;
	
	
	public function __construct(){
	}
	
	public function getPaypercent($amount){
		$param = new Model('params');
		$param_data  = $param->get();
		foreach($param_data as $param);
		if($amount <= 100000){
			return $param['paypercent'];
		}
		else if($amount > 100000 && $amount <= 200000){
			return $param['paypercent10_20'];
		}
		else if($amount > 200000 && $amount <= 300000){
			return $param['paypercent20_30'];
		}
		else if($amount > 300000 && $amount <= 400000){
			return $param['paypercent30_40'];
		}
		else if($amount > 400000 && $amount <= 500000){
			return $param['paypercent40_50'];
		}
		else if($amount > 500000 && $amount <= 600000){
			return $param['paypercent50_60'];
		}
		else if($amount > 600000 && $amount <= 700000){
			return $param['paypercent60_70'];
		}
		else if($amount > 700000 && $amount <= 800000){
			return $param['paypercent70_80'];
		}
		else if($amount > 800000 && $amount <= 900000){
			return $param['paypercent80_90'];
		}
		else if($amount > 900000 && $amount <= 1000000){
			return $param['paypercent90_100'];
		}																		
	}	
	
	public function show_add(){
		
		$bank = new Model('bank');
		$bank_data = $bank->get();
		$this->bindParam(array('bank_data'=>$bank_data));
		
		
		$this->display('add.php');
		
	}

	public function repay_clear(){
		$repay = new Model('repay');
		$repay->truncate();
		
		
	}
	public function swipe_clear(){
		$swipe = new Model('swipe');
		$swipe->truncate();
	}

	public function show_list(){
		$this->display('list.php');
	}
	
	
	public function card_details($id){
		
		$this->load('cardmath');
		
		$card = new Model('card');
		$card_data = $card->get('id',$id);
		foreach($card_data as $data);
		
		$bill = $data['currentbalance'];
			//获取百分比
		$this->percent =$this->getPaypercent($bill);					
		//减去分期金额
		
		
		$eve = getEachInstallmentRepay($data); //每期还款
		$data['eve'] = $eve;
		
		$bankcharges = getEachInstallmentCharges($data);
		$data['bankcharges'] = $bankcharges;
		
		$eve += $bankcharges;
		
		//$data['lixi'] = $lixi;
		//有分期就减掉分期总金额 + 每期还款额 
		$need_pay = $data['bill'] - $data['installment_bill'] + $eve;
		//再算出已经还过的款
		//$already_pay = $data['bill'] - $data['currentbalance'];
		//应还款
		$currentbalance  = round($need_pay  * ((100 -$this->percent) / 100));// - $already_pay; 
		$card_data[0]['otherbalance'] = $currentbalance;	
		
		
		
		
		$this->bindParam(array('card_data'=>$card_data));
		
		$repay = new Model('repay');
		$repay_data = $repay->get('cardnumber',$card_data[0]['cardnumber']);
		$this->bindParam(array('repay_data'=>json_encode($repay_data)));
		
		$repay = new Model('swipe');
		$repay_data = $repay->get('cardnumber',$card_data[0]['cardnumber']);
		$this->bindParam(array('swipe_data'=>json_encode($repay_data)));		
		
		$this->display('details.php');
	}
	
	public function card_details2($id){
		
		$card = new Model('card');
		$card_data = $card->get('cardnumber',$id);
		$this->bindParam(array('card_data'=>$card_data));
		
		$repay = new Model('repay');
		$repay_data = $repay->get('cardnumber',$card_data[0]['cardnumber']);
		$this->bindParam(array('repay_data'=>json_encode($repay_data)));
		
		$repay = new Model('swipe');
		$repay_data = $repay->get('cardnumber',$card_data[0]['cardnumber']);
		$this->bindParam(array('swipe_data'=>json_encode($repay_data)));		
		
		$this->display('details.php');
	}	
	
	public function card_del(){
		$pr = new Model('card');
		for($i = 0;$i < count($_GET[ids]);$i++){
			$pr->del('id = ' . $_GET[ids][$i]);
		}
		echo json_encode(array("success"=>true));		
	}
	
	public function post_add(){
		
		$d = array(
		'cardnumber'=>Input::get('cardid'),
		'name'=>Input::get('realname'),
		'banktype'=>Input::get('banktype'),
		'activedate'=>Input::get('activedate'),
		'statedate'=>Input::get('statedate'),
		'repaydate'=>Input::get('repaydate'),
		'limit'=>Input::get('limit'),
		'bill'=>Input::get('bill'),
		'currentbalance'=>Input::get('bill'),
		'installment'=>Input::get('installment'),
		'installment_bill'=>Input::get('installment_bill'),
		'each_bill'=>round(Input::get('installment_bill') / Input::get('installment')),
		'memo'=>Input::get('memo'),
		'created_at'=>date('Y-m-d G:i:s')
		);
		
		$card = new Model('card');
		$card->save($d);
		
		Redirect::to('card/list');
	}
	
	public function cardJson($id){
		$this->load('cardmath');
		$card = new Model('card');
		//echo $_GET[queryString];exit();
		if(!empty($_GET[id])){
			//echo $_GET[queryType].$_GET[queryString];
			$d = array(
			'cardnumber'=>$_GET['cardnumber'],
			'name'=>$_GET['name'],
			'banktype'=>$_GET['banktype'],
			'statedate'=>$_GET['statedate'],
			'repaydate'=>$_GET['repaydate'],
			'limit'=>$_GET['limit'],
			'bill'=>$_GET['bill'],
			'installment'=>$_GET['installment'],
			'installment_bill'=>$_GET['installment_bill'],
			'memo'=>$_GET['memo'],
			);
			
			$card = new Model('card');
			$card->update($d,$_GET[id]);
			$card_data = $card->get();
			echo json_encode($card_data);			
		}
		else{
			
			$card_data = $card->get();
			
		
			
			for($i = 0;$i< count($card_data);$i++){
				$bill = $card_data[$i]['currentbalance'];
					//获取百分比
				$this->percent =$this->getPaypercent($bill);					
				//减去分期金额
				
				
				$eve = getEachInstallmentRepay($card_data[$i]); //每期还款
				$card_data[$i]['eve'] = $eve;
				
				$bankcharges = getEachInstallmentCharges($card_data[$i]);
				$card_data[$i]['bankcharges'] = $bankcharges;
				
				$eve += $bankcharges;
				
				//$card_data[$i]['lixi'] = $lixi;
				//有分期就减掉分期总金额 + 每期还款额 
				$need_pay = $card_data[$i]['bill'] - $card_data[$i]['installment_bill'] + $eve;
				//再算出已经还过的款
				$already_pay = $card_data[$i]['bill'] - $card_data[$i]['currentbalance'];
				//应还款
				$card_data[$i]['currentbalance']  = round($need_pay  * ($this->percent / 100)) - $already_pay; 
			}
			
			echo json_encode($card_data);
		}
	}
	
	public function payment_record(){
		$this->display('payment_record.php');
	}
	
	public function swite_record(){
		$this->display('swite_record.php');
	}
	
	public function payment_details(){
		$this->display('payment_details.php');
	}
	
	public function swipte_details(){
		$this->display('swipte_details.php');
	}
	
	public function card_info(){
		$card = new Model('card');
		$card_data = $card->get();
		
		for($i = 0;$i < count($card_data); $i++){
			$json[$i]['view'] = $card_data[$i]['name'];
			$json[$i]['word'] = $card_data[$i]['cardnumber'];
		}
		
		/*foreach($card_data as $data){
		  $
		  $json['view'] = $data['name'];
		  $json['word'] = $data['cardnumber']; 
		}*/
		$json_arr['data'] = $json;
		//echo json_encode($card_data);
		//$arr = array('data'=>array(array('view'=>'黄恩龙','word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf'),array('view'=>15,'word'=>'alsdfjasdf')));
		//echo json_encode($arr);
		echo json_encode($json_arr);
	}
}

?>