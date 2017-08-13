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
  <div class="search-grid-container">
    <div id="grid"></div>
  </div>

</div>
</div>
  <div id="content" class="hide">
    <form id="J_Form" class="form-horizontal" action="../data/edit.php?a=1">
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>帐号：</label>
          <div class="controls">
            <input name="username" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>密码：</label>
          <div class="controls">
            <input name="new_password" type="password" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>姓名：</label>
          <div class="controls">
            <input name="realname" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
              <div class="control-group span16">
          <label class="control-label"><s>*</s>权限：</label>
          <div class="controls">
             <select name="privilege">
             <?php foreach($privilege as $p){ ?>
              <option value="<?=$p[name]?>"><?=$p[name]?></option>
              <?php }?>

            </select>
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

function print_array(arr){
	for(var key in arr){
		if(typeof(arr[key])=='array'||typeof(arr[key])=='object'){//递归调用  
			print_array(arr[key]);
		}else{
			document.write(key + ' = ' + arr[key] + '<br>');
		}
	}
}

function writeObj(obj){
	var description = "";
	for(var i in obj){  
		var property=obj[i];  
		description+=i+" = "+property+"\n"; 
	}  
	alert(description);
}

  BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {
	editing = new BUI.Grid.Plugins.DialogEditing({
        contentId : 'content', //设置隐藏的Dialog内容
        autoSave : true, //添加数据或者修改数据时，自动保存
        triggerCls : 'btn-edit'
      }),
      columns = [
          {title:'用户名',dataIndex:'username',width:100},
          {title:'姓名',dataIndex:'realname',width:100},
		  {title:'权限',dataIndex:'privilege',width:100},
 		  {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
			  if(obj.status)
			  	BUI.Message.Alert('修改成功！');
              editStr = '<span class="grid-command btn-edit" title="修改">修改</span>',
              delStr = '<span class="grid-command btn-del" title="删除权限">删除</span>';//页面操作不需要使用Search.createLink
            return editStr + delStr;
          }}
        ],
      store = Search.createStore('../data/member/list/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
            {text : '<i class="icon-plus"></i>新建',btnCls : 'button button-small',handler:function(){alert('新建');}},
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

    function delItems(items){
      var ids = [];
      BUI.each(items,function(item){
        ids.push(item.id);
      });

      if(ids.length){
        BUI.Message.Confirm('确认要删除选中的记录么？',function(){
          $.ajax({
            url : '../data/member/del/',
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

	editing.on('accept',function(){
		
		//确认编辑后 重新加载 
		search.load();
		});

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
