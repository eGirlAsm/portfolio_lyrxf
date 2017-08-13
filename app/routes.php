<?php



Route::get('login','HomeController@show_login');
Route::group('auth', function () {
	Route::get('/','HomeController@main');
	Route::get('index','HomeController@index');
	//card management
	Route::get('export','RepayController@export');
	Route::get('export_funds','HomeController@export_funds');
	Route::get('funds_clear','HomeController@funds_clear');	
	
	Route::post('addfunds','HomeController@addfunds');
	
	Route::get('card/add','CardController@show_add');
	Route::get('card/list','CardController@show_list');
	Route::post('card/add','CardController@post_add');
	Route::get('card/payment_record','CardController@payment_record');
	Route::get('card/swite_record','CardController@swite_record');
	Route::get('card/payment_details','CardController@payment_details');
	Route::get('card/swipte_details','CardController@swipte_details');



	Route::post('card/info','CardController@card_info');
	
	
	Route::get('data/card/{jsonname}','CardController@cardJson');
	Route::get('data/card/del/{data}','CardController@card_del');
	Route::get('detail/{id}','CardController@card_details');
	Route::get('card_detail/{id}','CardController@card_details2');
	//cleaner
	Route::post('data/clear/repay','CardController@repay_clear');
	Route::post('data/clear/swipe','CardController@swipe_clear');
	
	//member
	Route::post('member/add','UserController@post_add');
	
	Route::get('member/add','UserController@show_add');
	Route::get('member/list','UserController@show_list');
	Route::get('member/privilege','UserController@show_privilege');
	Route::get('member/edit','UserController@show_edit');
	
	Route::get('data/privilege/list','UserController@get_privilege_list');	
	Route::get('data/privilege/{data}','UserController@privilege_list');
	Route::get('data/privilege/add/{data}','UserController@privilege_add');
	Route::get('data/privilege/edit/{data}','UserController@privilege_edit');
	Route::get('data/privilege/del/{data}','UserController@privilege_del');
	
	Route::get('data/member/list/{data}','UserController@member_list');
	Route::get('data/member/del/{data}','UserController@member_del');
	
	
	
	
	//swipe
	Route::get('swipe_table','SwipeController@show_list');
	Route::get('swipe_record_add','SwipeController@show_add');
	Route::get('post_machine_add','SwipeController@pos_machine_list');
	Route::get('swipe_total','SwipeController@swipe_total');
	Route::get('data/swipe_record/{data}','SwipeController@json_swipe_record');
	
	Route::get('data/swipe/{data}','SwipeController@day_swipe_table');
	
	Route::post('post_machine_add','SwipeController@add_new_machine');
	
	
	Route::get('data/posmachine/{data}','SwipeController@posmachine');
	Route::get('data/posmachine/add/{data}','SwipeController@posmachine_add');
	Route::get('data/posmachine/edit/{data}','SwipeController@posmachine_edit');
	Route::get('data/posmachine/del/{data}','SwipeController@posmachine_del');
	
	//payment
	Route::get('day_payment_table','RepayController@day_payment_table');
	Route::get('day_record_add','RepayController@day_record_add');
	Route::get('payment_total','RepayController@payment_total');
	Route::post('day_record_add','RepayController@post_recored_add');
	
	
	Route::get('data/payment_record/{data}','RepayController@json_payment_record');
	Route::get('data/repay/{data}','RepayController@json_peyment_table');
	
	Route::get('system/config','HomeController@system_config');
	Route::get('system/param','HomeController@system_param');
	Route::get('system/bank','HomeController@system_bank');
	Route::post('system/config','HomeController@save_config');
	Route::post('system/param','HomeController@save_param');
	
	Route::get('data/bank/{data}','HomeController@data_bank');
	Route::get('data/bank/add/{data}','HomeController@data_bank_add');
	Route::get('data/bank/edit/{data}','HomeController@data_bank_edit');
	Route::get('data/bank/del/{data}','HomeController@data_bank_del');
	
	Route::post('data/reset/','HomeController@data_reset');
	
Route::post('changepwd',function(){
	
		if(Session::get('realname') == 'admin'){
			echo "admin 用户密码不可更改.请联系相关技术人员!";
		}
		else{
			$users  = new User;
			$users->get('realname',Session::get('realname'));
			
			$d = array('password'=>md5(Input::get('password')));
			$users->update($d,$user_data['id']);
			echo "您的密码修改成功,请退出后重新登录!";
		}
	});	
	
});

Route::get('logout', 'auth', function () {
    Session::destroy();
    Redirect::to('login');
});

Route::post('login', function () {
    $data = array(
        'username' => Input::get('u') ,
        'password' => Input::get('p')
    );
    //print_r($data);
    Auth::login($data);
    Redirect::to('/');
});

Route::filter('auth', function () {
    if (!Auth::check()) {
        Redirect::to('login',1);
    }
});

?>