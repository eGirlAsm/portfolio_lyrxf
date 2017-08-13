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
    <form id="searchForm" class="form-horizontal span24">
      <h2>权限管理</h2>
      <hr>
    </form>
  </div>
  <div class="search-grid-container">
    <div id="grid"></div>
  </div>
</div>
<div id="content" class="hide">
  <form id="J_Form" class="form-horizontal" action="../data/edit.php?a=1">
    <input type="hidden" name="a" value="3">
    <div class="row">
      <div class="control-group span8">
        <label class="control-label"><s>*</s>名称</label>
        <div class="controls">
          <input name="name" type="text" data-rules="{required:true}" class="input-normal control-text">
        </div>
      </div>
      <div class="control-group span8">
        <label class="control-label checkbox">
          <input type="checkbox" name="kapian">
          卡片管理 </label>
        <label class="control-label checkbox">
          <input type="checkbox" name="huankuan">
          还款管理 </label>
        <label class="control-label checkbox">
          <input type="checkbox" name="shuaka">
          刷卡管理 </label>
      </div>
    </div>
  </form>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<script type="text/javascript">
  BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {

	editing = new BUI.Grid.Plugins.DialogEditing({
        contentId : 'content', //设置隐藏的Dialog内容
        autoSave : true, //添加数据或者修改数据时，自动保存
        triggerCls : 'btn-edit'
      }),
      columns = [
          {title:'编号',dataIndex:'id',width:80},
          {title:'权限名称',dataIndex:'name',width:100},
          {title:'权限等级',dataIndex:'privilege',width:300},
          {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
       var editStr =  Search.createLink({ //链接使用 此方式
              
              }),
              editStr1 = '<span class="grid-command btn-edit" title="选择权限">选择权限</span>',
              delStr = '<span class="grid-command btn-del" title="删除权限">删除</span>';//页面操作不需要使用Search.createLink
            return editStr +  editStr1 + delStr;
          }}
        ],
      store = Search.createStore('../data/privilege/',{
        proxy : {
          save : { //也可以是一个字符串，那么增删改，都会往那么路径提交数据，同时附加参数saveType
            addUrl : '../data/privilege/add/',
            updateUrl : '../data/privilege/edit/',
            removeUrl : '../data/privilege/del/'
          }/*,
          method : 'POST'*/
        },
        autoSync : true //保存数据后，自动更新
      }),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
            {text : '<i class="icon-plus"></i>新建',btnCls : 'button button-small',handler:addFunction},
            {text : '<i class="icon-remove"></i>删除',btnCls : 'button button-small',handler : delFunction}
          ]
        },
        plugins : [editing,BUI.Grid.Plugins.CheckSelection,BUI.Grid.Plugins.AutoFit] // 插件形式引入多选表格
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

    function addFunction(){
      var newData = {isNew : true}; //标志是新增加的记录
      editing.add(newData,'name'); //添加记录后，直接编辑
    }

    function delItems(items){
      var ids = [];
      BUI.each(items,function(item){
        ids.push(item.id);
      });

      if(ids.length){
        BUI.Message.Confirm('确认要删除选中的记录么？',function(){
          $.ajax({
            url : '../data/privilege/del/',
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
