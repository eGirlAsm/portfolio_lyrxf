<?php

/**
*生成csv方法
*@param array
*/
function tocsv($info) {
    $subject =mb_convert_encoding("编号,卡号,姓名,银行,最迟还款日,人民币账单,剩余应还款,今日还款,\n", "CP936", "UTF-8");
    foreach($info as $v) {
        foreach($v as $value) {
            $value = preg_replace('/\s+/', ' ', $value);
            $detail .= strlen($value) > 11 && is_numeric($value) ? '['.$value.'],' : $value.',';
        }
        $detail = $detail."\n";
    }
   
    $filename = date('Ymd-G:i:s') . '.csv';
  
    ob_end_clean();
    header('Content-Encoding: none');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $subject;
    echo $detail;
}


class RepayController extends Controller{
	
	protected $layout = "repay";
	protected $param;
	protected $todayTotal = 0;
	protected $tomorrowTotal = 0;	
	protected $dayTotal = 0;
	protected $funds ;
	protected $percent = 0;
	
	
	public function getLeftDay($day){
		
		$param = new Model('params');
		$param_data  = $param->get();
		foreach($param_data as $param);
		
		$month = date('m') + 1;
		$repayDate = date('Y-'.$month.'-'.($day + 1));
		//$today =  date('Y-m-d');
		$today = $param[system_date];
		$repayDate1 = new DateTime($repayDate);
		$today1 = new DateTime($today);
		$interval = $repayDate1->diff($today1);
		return $interval->format('%a') > date('t') ? $interval->format('%a') - date('t') : $interval->format('%a'); //date('t') 当月 有几天 比如 30 31
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
	
	public function __construct(){
		$this->load('cardmath');
		if((date('w') == 5) || (date('w') == 6)){
     		//$this->display('xiuxi.php');
			//exit();
		}	
		
		$param = new Model('params');
		$param_data  = $param->get();
		foreach($param_data as $param);
		

		//figure out funds
		$this->funds = $param['funds'];

		
		//计算今天的总还款金额
		$card = new Model('card');
		$card_data = $card->get();
		for($i = 0;$i < count($card_data);$i++){
				$bill = $card_data[$i]['currentbalance'];
				//获取百分比
				$this->percent =$this->getPaypercent($bill);
						
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
				$bill  = round($need_pay  * ($this->percent / 100)) - $already_pay; 
				
				
				$card_data[$i]['em'] = 0;
				//判断是不是剩余三天
				$remDay = $this->getLeftDay($card_data[$i]['repaydate']);
				if($remDay > 0 && $remDay <= 3){
					$this->todayTotal +=  round(($bill/$remDay));
				}
				//判断是不是今天明天还款金额加起来 超过了 系统资金
				else if($this->dayTotal > $this->funds){
					$this->todayTotal +=  round($bill * ($this->percent / 100) / 13);
				}
				//正常需要还款的.
				else{
					$this->todayTotal +=  round($bill / 13);
				}
		}
		//计算明天的总还款金额
		for($i = 0;$i < count($card_data);$i++){
				$bill = $card_data[$i]['currentbalance'];
				$this->percent =$this->getPaypercent($bill);
				
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
				$bill  = round($need_pay  * ($this->percent / 100)) - $already_pay; 
				
				
				$card_data[$i]['em'] = 0;
				//判断是不是剩余三天
				$remDay = $this->getLeftDay($card_data[$i]['repaydate']) -1;
				if($remDay > 0 && $remDay <= 3){
					$this->tomorrowTotal +=  round(($bill/$remDay));
				}
				//判断是不是今天明天还款金额加起来 超过了 系统资金
				else if($this->dayTotal > $this->funds){
					$this->tomorrowTotal +=  round( $bill * ($this->percent / 100) / 13);
				}
				//正常需要还款的.
				else{
					$this->tomorrowTotal +=  round( $bill / 13);
				}
		}					
		$this->dayTotal = $this->todayTotal + $this->tomorrowTotal;
		//$this->bindParam(array('param'=>$param_data));		
	}
	
	public function day_payment_table(){
		

		$this->bindParam(array('funds'=>$this->funds));
		$this->bindParam(array('today_total'=>$this->todayTotal));
		$this->bindParam(array('tomorrow_total'=>$this->tomorrowTotal));
		$this->display('day_payment_table.php');
	}
	
	public function day_record_add(){
		$card = new Model('card');
		$card_data = $card->get();
		$this->bindParam(array('card_data'=>$card_data));
		$this->display('day_record_add.php');
	}
	public function payment_total(){

				
		$this->display('payment_total.php');
	}
	
	/*
	*	还款记录添加
	*/
	public function post_recored_add(){
		
		$repay = new Model('repay');
		
		$d = array(
		'cardnumber'=>Input::get('cardnumber'),
		'banktype'=>Input::get('banktype'),
		'realname'=>Input::get('realname'),
		'repayamount'=>Input::get('payamount'),
		'paytime'=>date('Y-m-d G:i:s'),
		'operator'=>Input::get('operator'),
		'comment'=>Input::get('memo')
		);
		$repay->save($d);
		
		
		//echo json_encode(array("success"=>true));
		$this->display('success.php');
	}
	/*
	*	还款记录函数
	*/
	public function json_payment_record(){
		$card = new Model('repay');
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

	/*
	*	计算函数 抓取时计算还款额
	*/
	public function figure_out(){
		
		$card = new Model('card');
		$card_data = $card->get();	
		/*
		* 这里删除掉 已还款的数组
		*/
		function delValue($arr)
		{
			if($arr['today_repay_status'] === 1 || $arr['currentbalance'] === 0) return false;
			else return true;
		}
		$card_data=array_filter($card_data,"delValue");
	
		sort($card_data);//重新生成索引下标				

		
		for($i = 0;$i < count($card_data);$i++){
				$bill = $card_data[$i]['currentbalance'];
				$this->percent =$this->getPaypercent($bill);
				
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
				$bill = round($need_pay  * ($this->percent / 100)) - $already_pay; 
		
				$card_data[$i]['currentbalance'] = $bill;
		
				$card_data[$i]['em'] = 0;
				//判断是不是剩余三天
				$remDay = $this->getLeftDay($card_data[$i]['repaydate']);
				if($remDay > 0 && $remDay <= 3){
					$card_data[$i]['today'] = round(($bill/$remDay));
					$card_data[$i]['em'] = 1;
				}
				//判断是不是今天明天还款金额加起来 超过了 系统资金
				else if($this->dayTotal > $this->funds){
					$card_data[$i]['today'] = round($bill * ($this->percent / 100) / 13);
					$card_data[$i]['em'] = -1;
				}
				//正常需要还款的.
				else{
					$card_data[$i]['today'] = round($bill / 13);
				}
				$card_data[$i]['paypercent'] = $this->percent;
		}
		echo json_encode($card_data);		
	}
	/*
	*	excel 导出专用函数
	*/
	public function export(){
		
		$card = new Model('card');
		$card_data = $card->get();	
		/*
		* 这里删除掉 已还款的数组
		*/
		function delValue($arr)
		{
			if($arr['today_repay_status'] === 1  || $arr['currentbalance'] === 0) return false;
			else return true;
		}
		$card_data=array_filter($card_data,"delValue");
	
		sort($card_data);//重新生成索引下标				

		$ex_data = array();
		
		for($i = 0;$i < count($card_data);$i++){
				$bill = $card_data[$i]['currentbalance'];
				$this->percent =$this->getPaypercent($bill);
								
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
				$bill  = round($need_pay  * ($this->percent / 100)) - $already_pay; 
				
				
				$card_data[$i]['em'] = 0;
				//判断是不是剩余三天
				$remDay = $this->getLeftDay($card_data[$i]['repaydate']);
				if($remDay > 0 && $remDay <= 3){
					$card_data[$i]['today'] = round(($bill/$remDay));
					$card_data[$i]['em'] = 1;
				}
				//判断是不是今天明天还款金额加起来 超过了 系统资金
				else if($this->dayTotal > $this->funds){
					$card_data[$i]['today'] = round($bill * ($this->percent / 100) / 13);
					$card_data[$i]['em'] = -1;
					
				}
				//正常需要还款的.
				else{
					$card_data[$i]['today'] = round($bill / 13);

				}
				$card_data[$i]['paypercent'] = $this->percent;
				
				//$ex_data = array();
				$encode = mb_convert_encoding($card_data[$i]['name'], "CP936", "UTF-8");
				$bank = mb_convert_encoding($card_data[$i]['banktype'], "CP936", "UTF-8");
				array_push($ex_data,array($card_data[$i]['id'],$card_data[$i]['cardnumber'],$encode,$bank,$card_data[$i]['repaydate'],$card_data[$i]['limit'],$card_data[$i]['currentbalance'],$card_data[$i]['today']));
		}
		tocsv($ex_data);		
	}
	/*
	*	table抓取JSON
	*/
	public function json_peyment_table(){
			$card = new Model('card');
			
			if($_GET['today']){
				
				$id = $_GET['id'];
				$today_pay_amount = $_GET['today'];
				$card_data = $card->get('id',$id);
				//设置已还款标记 //扣除应还金额.

				$total = $card_data[0]['currentbalance'] - $today_pay_amount;
				$paid_amount = $today_pay_amount + $card_data[0]['paid_amount'];
				$update_data = array('today_repay_status'=>1,'currentbalance'=>$total,'paid_amount'=>$paid_amount);
				
				$card->update($update_data,$id);
				
				//扣除总资金
				$param = new Model('params');
				$balance = array('funds'=>$this->funds - $today_pay_amount);
				$param->update($balance,1);
				
				//添加还款记录.

				$repay = new Model('repay');
				
				$d = array(
				'cardnumber'=>$card_data[0]['cardnumber'],
				'banktype'=>$card_data[0]['banktype'],
				'realname'=>$card_data[0]['name'],
				'repayamount'=>$today_pay_amount,
				'paytime'=>date('Y-m-d G:i:s'),
				'operator'=>Session::get('realname'),
				'comment'=>$_GET['memo']
				);
				
				//print_r($d);exit();
				$repay->save($d);				
				
				
				
				
				$this->figure_out();
				
			}else{
				$this->figure_out();
				
			}
					
	}
	
}


?>