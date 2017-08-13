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
    <h2>银行管理</h2>
    <hr>
  </div>
  <div class="search-grid-container">
    <div id="grid"></div>
  </div>
  <div id="content" class="hide">
  <form id="J_Form" class="form-horizontal" >
    <div class="row">
      <div class="control-group span8">
        <label class="control-label"><s>*</s>银行名称</label>
        <div class="controls">
          <input name="name" type="text" data-rules="{required:true}" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">3期利率</label>
        <div class="controls">
          <input name="term3" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">6期利率</label>
        <div class="controls">
          <input name="term6" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">9期利率</label>
        <div class="controls">
          <input name="term9" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">10期利率</label>
        <div class="controls">
          <input name="term10" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">12期利率</label>
        <div class="controls">
          <input name="term12" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">18期利率</label>
        <div class="controls">
          <input name="term18" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">24期利率</label>
        <div class="controls">
          <input name="term24" type="text" class="input-normal control-text">
        </div>
      </div>
    </div>
    <div class="row">
      <div class="control-group span8">
        <label class="control-label">一次性收取手续费吗</label>
        <div class="controls">
              <select  data-rules="{required:true}"  name="isfirst" class="input-normal"> 
                <option value="">请选择</option>
                <option value="1">是</option>
                <option value="0">否</option>
              </select>
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
          {title:'银行名称',dataIndex:'name',width:80},
          {title:'3期利率',dataIndex:'term3',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
          {title:'6期利率',dataIndex:'term6',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'9期利率',dataIndex:'term9',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'10期利率',dataIndex:'term10',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'12期利率',dataIndex:'term12',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'18期利率',dataIndex:'term18',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'24期利率',dataIndex:'term24',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
				  {title:'一次性收取手续费',dataIndex:'isfirst',width:100,renderer : function(v,obj){
					  if(v){
						  return "是";
					  }else{
						 return "否";
					  }
				  //return "<strong><font color='#5BA1CF'>" + v + "&nbsp;%</font></strong>";
		}},
		  {title:'备注',dataIndex:'memo',width:300},
          {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
       var editStr =  Search.createLink({ //链接使用 此方式
              
              }),
              editStr1 = '<span class="grid-command btn-edit" title="编辑">编辑</span>',
              delStr = '<span class="grid-command btn-del" title="删除">删除</span>';//页面操作不需要使用Search.createLink
            return editStr +  editStr1 + delStr;
          }}
        ],
      store = Search.createStore('../data/bank/',{
        proxy : {
          save : { //也可以是一个字符串，那么增删改，都会往那么路径提交数据，同时附加参数saveType
            addUrl : '../data/bank/add/',
            updateUrl : '../data/bank/edit/',
            removeUrl : '../data/bank/del/'
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
            url : '../data/bank/del/',
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
