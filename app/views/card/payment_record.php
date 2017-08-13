<!DOCTYPE HTML>
<html>
   <head>
   <title>搜索表单</title>
   <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
   <link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
   <link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
   <link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
   </head>
   <body>
<div class="container">
     <div class="row">
    <h2>所有还款记录</h2>
    <hr>
    <form id="searchForm" class="form-horizontal span24">
         <div class="row">
        <div class="control-group span12">
             <label class="control-label">查询类别：</label>
             <div class="controls">
            <select name="queryType">
                 <option value="0">卡号</option>
                 <option value="1">姓名</option>
               </select>
          </div>
             <div class="controls">
            <input name="queryString" type="text" class="control-text" data-rules="{required:true}">
          </div>
           </div>
        <div class="control-group span8">
             <div class="controls">
            <button  type="button" id="btnSearch" class="button button-primary">搜索</button>
          </div>
           </div>
      </div>
       </form>
  </div>
     <div class="search-grid-container">
    <div id="grid"></div>
  </div>
   </div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 

<script type="text/javascript">
  BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {
    

      columns = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:200,renderer:function(v,obj){
            return Search.createLink({
              id : 'card_detail' + v,
              title : '点击查看详情',
              text : v,
              href : 'card_detail/'+ obj.cardnumber
            });
          }},
          {title:'姓名',dataIndex:'realname',width:100,},
          {title:'银行类别',dataIndex:'banktype',width:80},
          {title:'还款金额',dataIndex:'repayamount',width:100,renderer:function(v,obj){
				
				return "<strong><font color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
			}},
          {title:'还款日期',dataIndex:'paytime',width:200},
		  {title:'操作员',dataIndex:'operator',width:100},
		  {title:'备注',dataIndex:'comment',width:250}
        ],
      store = Search.createStore('../data/payment_record/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
			 {text : '<i class="icon-print"></i>打印',btnCls : 'button button-small',handler:function(){alert('打印');}}
          ]
        }// 插件形式引入多选表格
      });

    var  search = new Search({
        store : store,
        gridCfg : gridCfg
      }),
      grid = search.get('grid');

  });
</script>

<body>
</html>
