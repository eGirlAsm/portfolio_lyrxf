<!DOCTYPE HTML>
<html>
<head>
<title>洛阳RXF办公管理系统</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="assets/css/main-min.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div class="header">
  <div class="dl-title"> <span class="lp-title-port">洛阳RXF</span><span class="dl-title-text">办公管理系统</span> </a> </div>
  <div class="dl-log">欢迎您，<span class="dl-log-user">
    <?=Session::get('realname')?>
    [
    <?php if(Session::get('privilege') == '9'){ ?>
    超级管理员
    <?php }else{ ?>
    <?php $pr = getPF();echo "<font color='red'>".$pr['0'][name]."</font>";?>
    <?php } ?>
    ]</span>&nbsp;<a id="changepwd" class="dl-log-quit" href="#">[修改密码]</a><a href="logout" title="退出系统" class="dl-log-quit">[退出]</a> </div>
</div>
<div class="content">
  <div class="dl-main-nav">
    <div class="dl-inform">
      <div class="dl-inform-title">贴心小秘书<s class="dl-inform-icon dl-up"></s></div>
    </div>
    <ul id="J_Nav"  class="nav-list ks-clear">
      <li class="nav-item dl-selected">
        <div class="nav-item-inner nav-home">首页</div>
      </li>
      <?php if(getPrivilege("卡片管理")){ ?>
      <li class="nav-item">
        <div class="nav-item-inner nav-order">卡片管理</div>
      </li>
      <?php } ?>
      <?php if(getPrivilege("还款管理")){ ?>
      <li class="nav-item">
        <div class="nav-item-inner nav-inventory">还款管理</div>
      </li>
      <?php } ?>
      <?php if(getPrivilege("刷卡管理")){ ?>
      <li class="nav-item">
        <div class="nav-item-inner nav-monitor">刷卡管理</div>
      </li>
      <?php } ?>
      <?php if(Session::get('privilege') == 9){ ?>
      <li class="nav-item">
        <div class="nav-item-inner nav-user">会员管理</div>
      </li>
      <li class="nav-item">
        <div class="nav-item-inner nav-marketing">系统管理</div>
      </li>
      <?php } ?>
    </ul>
  </div>
  <ul id="J_NavContent" class="dl-tab-conten">
  </ul>
</div>
<script type="text/javascript" src="assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="./assets/js/bui.js"></script> 
<script type="text/javascript" src="./assets/js/config.js"></script> 
<script>
    BUI.use('common/main',function(){
      var config = [{
          id:'menu', 
          homePage : 'index',
          menu:[{
              text:'首页内容',
              items:[
                {id:'index',text:'首页',href:'index',closeable : false},
              ]
            }]
          }
		    <?php if(getPrivilege("卡片管理")){ ?>  
,{
            id:'card',
			homePage : 'list',
			reload:true,isClose:true,
            menu:[{
                text:'卡片管理',
                items:[
                  {id:'add',text:'添加卡片',href:'card/add'},
				  {id:'list',text:'卡片列表',href:'card/list',reload:true,isClose:true},
                  {id:'payment_record',text:'所有还款记录',href:'card/payment_record',reload:true,isClose:true},
				  {id:'swite_record',text:'所有刷卡记录',href:'card/swite_record',reload:true,isClose:true},
                 // {id:'payment_details',text:'还款详情表',href:'card/payment_details',reload:true,isClose:true},
                  //{id:'swipte_details',text:'刷卡详情表',href:'card/swipte_details',reload:true,isClose:true}
                  
                ]
              }]
          }
        <?php }?>  
		    <?php if(getPrivilege("还款管理")){ ?> 
		  ,{
            id:'repay',
			homePage : 'day_payment_table',
			reload:true,isClose:true,
            menu:[{
                text:'还款管理',
                items:[
                  {id:'day_payment_table',text:'当日还款总表',href:'day_payment_table'},
				  {id:'swipe_total',text:'还款记录',href:'card/payment_record',reload:true,isClose:true}
                  //{id:'day_record_add',text:'还款记录添加',href:'day_record_add'},
                  //{id:'payment_total',text:'还款总计',href:'payment_total'}
                ]
              }]
          }
		  
		  <?php }?> 
		      <?php if(getPrivilege("刷卡管理")){ ?>  
		  ,{
            id:'swipe',
			homePage : 'swipe_table',
            menu:[{
                text:'刷卡管理',
                items:[
                  {id:'swipe_table',text:'当日刷卡总表',href:'swipe_table'},
                  //{id:'switpe_record_add',text:'刷卡记录添加',href:'swipe_record_add'},
                  {id:'post_machine_add',text:'POS机管理',href:'post_machine_add'},
				  {id:'swipe_total',text:'刷卡记录',href:'card/swite_record',reload:true,isClose:true}
                ]
              }]
          }
		  
		  <?php }?> 
		  <?php if(Session::get('privilege') == 9){ ?>
		  ,{
            id : 'member',
			homePage : 'list',
            menu : [{
              text : '会员管理',
              items:[
                  {id:'add',text:'会员添加',href:'member/add'},
                  {id:'list',text:'会员管理',href:'member/list'},
                  {id:'privilege',text:'权限管理',href:'member/privilege'}
              ]
            }]
          },{
            id : 'system',
			homePage : 'config',
            menu : [{
              text : '系统管理',
              items:[
                  {id:'config',text:'系统设置',href:'system/config'},
                  {id:'param',text:'参数设置',href:'system/param'},
				  {id:'bank',text:'银行管理',href:'system/bank'}
              ]
            }]
          }
		  
		  <?php } ?>
		  ];
	 
     var page =  new PageUtil.MainPage({
        modulesConfig : config
      });
	
    });
  </script>
  <div style="display:none">
<div id="content" class="hidden" >
  <form id="form" class="form-horizontal">
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">新的密码：</label>
        <div class="controls">
          <input id="newpwd" type="password" class="input-normal control-text" data-rules="{required : true}">
        </div>
      </div>
</div>
  </form>
</div>
<script type="text/javascript">
BUI.use(['bui/overlay','bui/form'],function(Overlay,Form){
var form = new Form.HForm({
srcNode : '#form'
}).render();
 
var dialog = new Overlay.Dialog({
title:'修改密码',
width:400,
height:120,
//配置DOM容器的编号
contentId:'content',
success:function () {
	

  txt=$("input").val();
  $.post("changepwd",{password:$('#newpwd').val()},function(result){
 		BUI.Message.Alert(result,function(){
			//alert('确认');
			},'warning');
  });

	

this.close();
}
});

$('#changepwd').on('click',function () {
dialog.show();
});
});

</script>
</body>
</html>
