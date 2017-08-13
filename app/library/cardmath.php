<?php
/*
*	信用卡计算函数集
*/

	/*
	*	
	*	
	*	每期还款本金  = 分期金额  / 分期期数
	*	
	*/
	function getEachInstallmentRepay($card_data){
			if(!$card_data['installment']) return 0;
			return $eve_installment_bill = round($card_data['installment_bill'] / $card_data['installment'],2);	
	}
	/*
	*	获取银行手续费率
	*/
	function getBankCharges($period,$bankName){
			$bank = new Model('bank');
			$bank_data = $bank->get('name',$bankName);
			$term = "term".$period;
			return $bank_data[0][$term]; //银行 [term] 分期手续费率		
	}
	
	/*
	*	判断手续费是一次性付的还是 分期入账的
	*/
	function isOnetimePayment($bankName){
			$bank = new Model('bank');
			$bank_data = $bank->get('name',$bankName);
			return $bank_data[0]['isfirst'];		
	}
	/*
	*	每期手续费 = 分期金额 * 银行利息
	*/
	function getEachInstallmentCharges($card_data){
			$im_day = $card_data['installment'];//分期期限
			$im_bill = $card_data['installment_bill'] ;//分期总金额
			
			if(!$im_day) return 0;

			$charges = getBankCharges($im_day,$card_data['banktype']);
		
			$lixi = round($im_bill * $charges / 100,2); //算出来手续费
			//return $lixi;
			if(isOnetimePayment($card_data['banktype'])  == 0)	
				return $lixi;
			//如果是一次性付手续费的,还要判断 是不是付过手续费了。
			if($card_data['ispaidcharges'] == 0)
				//没付过 那就付把
				return $lixi;
				
			//付过 那就这次 没有手续费了。
			return 0;	
	}
?>