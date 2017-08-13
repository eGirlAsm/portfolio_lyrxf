<?php

/**
*生成csv方法
*@param array
*/
function tocsv($info) {
    $subject =mb_convert_encoding("编号,资金,操作时间,操作人,\n", "CP936", "UTF-8");
    foreach($info as $v) {
        foreach($v as $value) {
            $value = preg_replace('/\s+/', ' ', $value);
            $detail .= strlen($value) > 11 && is_numeric($value) ? '['.$value.'],' : $value.',';
        }
        $detail = $detail."\n";
    }
   
    $filename = date('Ymd-G:i:s') . '_funds.csv';
  
    ob_end_clean();
    header('Content-Encoding: none');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $subject;
    echo $detail;
}

Class HomeController extends Controller{

	protected $layout = '';

	public function __construct(){
		//echo "hello world";
		$this->load('function');
		$this->load('privilege');
	}

	public function export_funds(){
		$param = new Model('funds_record');
		$param_data = $param->get();
		tocsv($param_data);		
	}


	public function funds_clear(){
		$param = new Model('funds_record');
		$param->truncate();
		Redirect::to('system/param');
	}

	public function main(){
		
		$this->display('main.php');
	}
	
	public function index(){
		$this->display('index.php');
	}
	
	public function show_login(){
		if (!Auth::check()) {
			$this->display('login.php');
		}else{
			Redirect::to('/');
		}
	}
	
	public function addfunds(){
		$param = new Model('params');
		$param_data = $param->get();
		$param_data[0][funds] = Input::get('amount')+ $param_data[0][funds];
		$param->update($param_data[0],1);	
		
		
		$addfunds = new Model('funds_record');
		$data = array(
		'amount'=>Input::get('amount'),
		'created_at'=>date('Y-m-d G:i:s'),
		'author'=>Session::get('realname')
		);
		
		$addfunds->save($data);
		
		echo json_encode(array('success'=>true));	
	}
	
	public function system_config(){
		$param = new Model('params');
		$param_data = $param->get();
		$this->bindParam(array('param_data'=>$param_data));
		$this->display('system/config.php');
	}
	
	public function save_config(){
		
		$model = new Model('card');
		$model_data = $model->get();
		//重置还款状态
		for($i = 0;$i<count($model_data);$i++){
			$model_data[$i]['today_repay_status']  = 0;
			$model->update($model_data[$i],$model_data[$i]['id']);
		}

		
		$param = new Model('params');
		$d = array('system_date'=>Input::get('system_date'));
		$param->update($d,1);
		$param_data = $param->get();
		$this->bindParam(array('param_data'=>$param_data));
		$this->display('system/config.php');
	}
	
	public function system_param(){
		$param = new Model('params');
		$param_data  = $param->get();
		
		$this->bindParam(array('param'=>$param_data));
		
		
		$funds_record = new Model('funds_record');
		$funds_record->orderBy('id','desc');
		$funds_record_data = $funds_record->get();
		$this->bindParam(array('json_record'=>json_encode($funds_record_data)));
		
		$this->display('system/param.php');
	}
	
	public function system_bank(){
		$this->display('system/bank.php');
	}

	public function save_param(){
		$param = new Model('params');
		$d = array(
		//'funds'=>Input::get('funds'),
		'paypercent'=>Input::get('paypercent'),
		'posamount'=>Input::get('posamount'),
		'paypercent10_20'=>Input::get('paypercent10_20'),
		'posamount10_20'=>Input::get('posamount10_20'),
		'paypercent20_30'=>Input::get('paypercent20_30'),
		'posamount20_30'=>Input::get('posamount20_30'),
		'paypercent30_40'=>Input::get('paypercent30_40'),
		'posamount30_40'=>Input::get('posamount30_40'),
		'paypercent40_50'=>Input::get('paypercent40_50'),
		'posamount40_50'=>Input::get('posamount40_50'),
		'paypercent50_60'=>Input::get('paypercent50_60'),
		'posamount50_60'=>Input::get('posamount50_60'),
		'paypercent60_70'=>Input::get('paypercent60_70'),
		'posamount60_70'=>Input::get('posamount60_70'),
		'paypercent70_80'=>Input::get('paypercent70_80'),
		'posamount70_80'=>Input::get('posamount70_80'),
		'paypercent80_90'=>Input::get('paypercent80_90'),
		'posamount80_90'=>Input::get('posamount80_90'),	
		'paypercent90_100'=>Input::get('paypercent90_100'),
		'posamount90_100'=>Input::get('posamount90_100'),																		
		'last_modify'=>date('Y-m-d G:i:s')
		);
		$param->update($d,1);
		
		Redirect::to('system/param');
	}
	
	public function data_bank(){
		$bank = new Model('bank');
		$bank_data = $bank->get();
		echo json_encode($bank_data);
	}
	
	public function data_bank_add(){
		$bank = new Model('bank');
		$newBank = array(
			'name'=>$_GET['name'],
			'term3'=>$_GET['term3'],
			'term6'=>$_GET['term6'],
			'term9'=>$_GET['term9'],
			'term10'=>$_GET['term10'],
			'term12'=>$_GET['term12'],
			'term18'=>$_GET['term18'],
			'term24'=>$_GET['term24'],
			'isfirst'=>$_GET['isfirst'],
			'memo'=>$_GET['memo']
		);
		
		$bank->save($newBank);
		$bank_data = $bank->get();
		echo json_encode($bank_data);		
	}
	
	public function data_bank_edit(){
		$bank = new Model('bank');
		$newBank = array(
			'name'=>$_GET['name'],
			'term3'=>$_GET['term3'],
			'term6'=>$_GET['term6'],
			'term9'=>$_GET['term9'],
			'term10'=>$_GET['term10'],
			'term12'=>$_GET['term12'],
			'term18'=>$_GET['term18'],
			'term24'=>$_GET['term24'],
			'isfirst'=>$_GET['isfirst'],
			'memo'=>$_GET['memo']
		);
		
		$bank->update($newBank,$_GET[id]);
		$bank_data = $bank->get();
		echo json_encode($bank_data);		
	}
	
	public function data_bank_del(){
		$pr = new Model('bank');
		for($i = 0;$i < count($_GET[ids]);$i++){
			$pr->del('id = ' . $_GET[ids][$i]);
		}
		echo json_encode(array("success"=>true));		
	}
	
	public function data_reset(){
		$code = Input::get('code');
		
		$model = new Model('card');
		$model_data = $model->get();
		//print_r($model_data);exit();
		if($code == 1){

			for($i = 0;$i<count($model_data);$i++){
				$model_data[$i]['today_repay_status']  = 0;
				$model_data[$i]['currentbalance'] = $model_data[$i]['bill'];
				$model->update($model_data[$i],$model_data[$i]['id']);
			}
			
		}
		else if($code == 2){
			for($i = 0;$i<count($model_data);$i++){
				$model_data[$i]['today_swipe_status']  = 0;
				$model_data[$i]['paid_amount'] = 0;
				$model->update($model_data[$i],$model_data[$i]['id']);
			}
		}
		
		echo json_encode(array("success"=>true));	
	}
	
}

?>