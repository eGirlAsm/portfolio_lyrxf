<?php

	function getPF(){ //privilege filed;
	
			$type = Session::get('privilege');
			
			//var_dump( $type);exit();
			$pr = new Model('privilege');
			$pr_data = $pr->get('name',$type);
			return $pr_data;
	}
	
	function getPrivilege($pr_name){
			$type = Session::get('privilege');
			if($type == 9) return true;
			//var_dump( $type);exit();
			$pr = new Model('privilege');
			$pr_data = $pr->get('name',$type);
			$pr_js = json_decode($pr_data[0]['privilege']);
			for($i = 0;$i < count($pr_js);$i++){
				if(strcmp($pr_js[$i],$pr_name) == 0)
					return true;
			}
			return false;
	}
	

?>