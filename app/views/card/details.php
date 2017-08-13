<!DOCTYPE HTML>
<html>
<head>
<title>详情页</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<!-- 下面的样式，仅是为了显示代码，而不应该在项目中使用-->
<link href="../assets/css/prettify.css" rel="stylesheet" type="text/css" />
<style type="text/css">
code { padding: 0px 4px; color: #d14; background-color: #f7f7f9; border: 1px solid #e1e1e8; }
</style>
</head>
<body>
<div class="container">
  <div class="detail-page">
    <h2>卡片详情</h2>
    <hr>
    <?php foreach($card_data as $card) ;?>
    <div class="detail-section">
      <h3>基本信息</h3>
      <div class="row detail-row">
        <div class="span8">
          <label>卡号：</label>
          <span class="detail-text"><?=$card[cardnumber]?></span> </div>
        <div class="span8">
          <label>编号：</label>
          <span class="detail-text"><?=$card[id]?></span> </div>
        <div class="span8">
          <label>姓名：</label>
          <span class="detail-text"><?=$card[name]?></span> </div>

      </div>
      <div class="row detail-row">
              <div class="span8">
          <label>银行：</label>
          <span class="detail-text"><?=$card[banktype]?></span> </div>
        <div class="span8">
          <label>账单日：</label>
          <span class="detail-text"><?=$card[statedate]?></span> </div>
        <div class="span8">
          <label>最迟还款日：</label>
          <span class="detail-text"><?=$card[repaydate]?></span> </div>

      </div>
    </div>
    <div class="detail-section">
      <h3>扩展信息</h3>
      <div class="row detail-row">
        <div class="span8">
          <label>是否分期(月)：</label>
          <span class="detail-text"><?=$card[installment]?></span> </div>
        <div class="span8">
          <label>分期额度：</label>
          <span class="detail-text"><strong>&yen;&nbsp;<?=$card[installment_bill]?></strong></span> </div>
            <div class="span8">
          <label>剩余欠款：</label>
          <span class="detail-text"><strong>&yen;&nbsp;<?=$card[otherbalance]?></strong></span> </div>
                  <div class="span8">
          <label>备注：</label>
          <span class="detail-text"><?=$card[memo]?></span> </div>
      </div>
    </div>
    <div class="detail-section">
      <h3>还款记录</h3>
      <div class="row detail-row">
        <div class="span24">
          <div id="grid1"></div>
        </div>
      </div>
    </div>
    <div class="detail-section">
      <h3>刷卡记录</h3>
      <div class="row detail-row">
        <div class="span24">
          <div id="grid2"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<!-- 仅仅为了显示代码使用，不要在项目中引入使用--> 
<script type="text/javascript" src="../assets/js/prettify.js"></script> 
<script type="text/javascript">
    $(function () {
      prettyPrint();
    });
  </script> 
<script type="text/javascript">
  BUI.use('bui/grid',function (Grid) {
    var data1 = <?=$repay_data;?>;
 
    var data2 = <?=$swipe_data?>;
 	
        grid1 = new Grid.SimpleGrid({
          render : '#grid1', //显示Grid到此处
          width : 950,      //设置宽度
          columns : [
            {title:'还款日期',dataIndex:'paytime',width:150,renderer:Grid.Format.dateRenderer},
            {title:'发卡银行',dataIndex:'banktype',width:70},
            {title:'卡号',dataIndex:'cardnumber',width:130},
			{title:'姓名',dataIndex:'realname',width:80},
            {title:'还款金额',dataIndex:'repayamount',width:100,renderer:function(v,obj){
				return "<strong><font color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
			}},{title:'备注',dataIndex:'comment'},
          ]
        });
		
        grid2 = new Grid.SimpleGrid({
          render : '#grid2', //显示Grid到此处
          width : 950,      //设置宽度
          columns : [
            {title:'还款日期',dataIndex:'created_at',width:150,renderer:Grid.Format.dateRenderer},
            {title:'发卡银行',dataIndex:'bank',width:70},
            {title:'卡号',dataIndex:'cardnumber',width:130},
			{title:'姓名',dataIndex:'realname',width:80},
            {title:'刷卡金额',dataIndex:'amount',width:100,renderer:function(v,obj){
				return "<strong><font color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
			}},		  {title:'手续费',dataIndex:'fee',width:100,renderer:function(v,obj){
				
				return "<strong><font color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
			}},			  {title:'费率',dataIndex:'fee_rate',width:100,renderer:function(v,obj){
				
				return "<strong><font color='#3C763D'>&nbsp;" + v + "%</font></strong>";
			}},	
						{title:'POS机编号',dataIndex:'posid',width:100},
			{title:'备注',dataIndex:'memo'},

          ]
        });		
      grid1.render();
      grid1.showData(data1);
  	  grid2.render();	
	  grid2.showData(data2);	    
  });
</script>

<body>
</html>
