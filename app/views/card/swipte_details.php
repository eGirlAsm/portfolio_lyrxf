<!DOCTYPE HTML>
<html>
   <head>
   <title>搜索表单</title>
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
     <div class="row">
    <h2>刷卡详情表</h2>
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
<!-- 仅仅为了显示代码使用，不要在项目中引入使用--> 
<script type="text/javascript" src="../assets/js/prettify.js"></script> 
<script type="text/javascript">
    $(function () {
      prettyPrint();
    });
  </script> 
<script type="text/javascript">
  BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {
    

      columns = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:200},
          {title:'姓名',dataIndex:'name',width:100,},
          {title:'刷卡金额',dataIndex:'banktype',width:80},
          {title:'POS机编号',dataIndex:'statedate',width:100},
          {title:'POS机费率',dataIndex:'repaydate',width:100},
		  {title:'刷卡日期',dataIndex:'repaydate',width:100},
		  {title:'POS机主姓名',dataIndex:'repaydate',width:100},
		  {title:'POS机所属银行',dataIndex:'repaydate',width:100},
		  {title:'POS机所属卡号',dataIndex:'repaydate',width:100},
		  {title:'是否成功',dataIndex:'repaydate',width:100},
		  {title:'备注',dataIndex:'repaydate',width:100},
        ],
      store = Search.createStore('../data/card/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
			 {text : '<i class="icon-print"></i>打印',btnCls : 'button button-small',handler:function(){alert('打印');}}
          ]
        },
        plugins : [BUI.Grid.Plugins.CheckSelection] // 插件形式引入多选表格
      });

    var  search = new Search({
        store : store,
        gridCfg : gridCfg
      }),
      grid = search.get('grid');
    //删除操作
    function delFunction(){
      var selections = grid.getSelection();
      delItems(selections);
    }

    function delItems(items){
      var ids = [];
      BUI.each(items,function(item){
        ids.push(item.id);
      });

      if(ids.length){
        BUI.Message.Confirm('确认要删除选中的记录么？',function(){
          $.ajax({
            url : '../data/card/del/',
            dataType : 'json',
            data : {ids : ids},
            success : function(data){
              if(data.success){ //删除成功
                search.load();
              }else{ //删除失败
                BUI.Message.Alert('删除失败！');
              }
            }
        });
        },'question');
      }
    }

    //监听事件，删除一条记录
    grid.on('cellclick',function(ev){
      var sender = $(ev.domTarget); //点击的Dom
      if(sender.hasClass('btn-del')){
        var record = ev.record;
        delItems([record]);
      }
    });
  });
</script>

<body>
</html>
