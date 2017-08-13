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
    <h2>POS机管理</h2>
    <hr>
  </div>
  <div class="search-grid-container">
    <div id="grid"></div>
  </div>
  <div id="content" class="hide">
  <form id="J_Form" class="form-horizontal" >
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">商户名称</label>
        <div class="controls">
          <input name="shopname" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">姓名</label>
        <div class="controls">
          <input name="realname" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">身份证</label>
        <div class="controls">
          <input name="passportid" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">银行</label>
        <div class="controls">
          <input name="bank" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">卡号</label>
        <div class="controls">
          <input name="cardnumber" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">手机号</label>
        <div class="controls">
          <input name="phone" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">费率</label>
        <div class="controls">
          <input name="rate" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">时段</label>
        <div class="controls">

        <input type="text" id="show" name="timepart1">
        <input type="hidden" id="hide" value="" name="timepart">

        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">备注：</label>
        <div class="controls">
          <textarea name="memo" class="input-normal" data-tip="{text:'请填写备注信息！'}" type="text"></textarea>
        </div>
      </div>
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
        triggerCls : 'btn-edit',
		title : 'title'
      }),
      columns = [
          {title:'编号',dataIndex:'id',width:50},
          
          {title:'商户名称',dataIndex:'shopname',width:230},
		  {title:'姓名',dataIndex:'realname',width:80},
		  {title:'身份证',dataIndex:'passportid',width:150},
		  {title:'银行',dataIndex:'bank',width:80},
		  {title:'卡号',dataIndex:'cardnumber',width:150},
		  {title:'手机号',dataIndex:'phone',width:100},
		  {title:'费率',dataIndex:'rate',width:80,renderer:function(v,obj){
				
				return "<strong><font color='#3C763D'>" + v + "</font></strong>&nbsp;%";
			}},
		  {title:'时段(点)',dataIndex:'timepart',width:100},		
		  {title:'备注',dataIndex:'memo',width:300},
          {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
       var editStr =  Search.createLink({ //链接使用 此方式
              
              }),
              editStr1 = '<span class="grid-command btn-edit" title="编辑">编辑</span>',
              delStr = '<span class="grid-command btn-del" title="删除">删除</span>';//页面操作不需要使用Search.createLink
            return editStr +  editStr1 + delStr;
          }}
        ],
      store = Search.createStore('../data/posmachine/',{
        proxy : {
          save : { //也可以是一个字符串，那么增删改，都会往那么路径提交数据，同时附加参数saveType
            addUrl : '../data/posmachine/add/',
            updateUrl : '../data/posmachine/edit/',
            removeUrl : '../data/posmachine/del/'
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
            url : '../data/posmachine/del/',
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
  
  
 BUI.use(['bui/picker','bui/list'],function(Picker,List){
		var items = [
		{text:'1点',value:'1'},
		{text:'2点',value:'2'},
		{text:'3点',value:'3'},
		{text:'4点',value:'4'},
		{text:'5点',value:'5'},
		{text:'6点',value:'6'},
		{text:'7点',value:'7'},
		{text:'8点',value:'8'},
		{text:'9点',value:'9'},
		{text:'10点',value:'10'},
		{text:'11点',value:'11'},
		{text:'12点',value:'12'},
		{text:'13点',value:'13'},
		{text:'14点',value:'14'},
		{text:'15点',value:'15'},
		{text:'16点',value:'16'},
		{text:'17点',value:'17'},
		{text:'18点',value:'18'},
		{text:'19点',value:'19'},
		{text:'20点',value:'20'},
		{text:'21点',value:'21'},
		{text:'22点',value:'22'},
		{text:'23点',value:'23'},
		{text:'24点',value:'24'}
		
		],
		list = new List.Listbox({ //使用可勾选的列表
		elCls:'bui-select-list',
		items : items
		}),
		picker = new Picker.ListPicker({
		trigger : '#show',
		valueField : '#hide', //如果需要列表返回的value，放在隐藏域，那么指定隐藏域
		width:100, //指定宽度
		hideEvent : '', //清除点击列表时隐藏，因为需要选中多个
		children : [list] //配置picker内的列表
		});
		picker.render();
 
});  
</script>

<body>
</html>
