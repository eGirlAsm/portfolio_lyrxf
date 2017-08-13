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
   
    $filename = date('Ymd') . '.csv';
  
    ob_end_clean();
    header('Content-Encoding: none');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename='.$filename);
    header('Pragma: no-cache');
    header('Expires: 0');
    echo $subject;
    echo $detail;
}



class ExcelController extends Controller{

	public function __construct(){
		
	}
	
	public function index(){
		$data = array(
			array('sally',30),
			array('lucy',20),
		);

			tocsv($data);

	}
}


?>