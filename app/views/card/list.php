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
    <h2>卡片列表</h2>
    <div class="span24">
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
      </hr>
    </div>
  </div>
  <div class="search-grid-container">
    <div id="grid"></div>
  </div>
  <div id="content" class="hide">
    <form id="J_Form" class="form-horizontal" action="../data/edit.php?a=1">
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>卡号：</label>
          <div class="controls">
            <input name="cardnumber"  type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>姓名：</label>
          <div class="controls">
            <input name="name"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>银行：</label>
          <div class="controls">
            <input name="banktype"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>每月账单日：</label>
          <div class="controls">
            <input name="statedate" type="text" data-rules="{required:true}" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>到期还款日：</label>
          <div class="controls">
            <input name="repaydate"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>信用额度：</label>
          <div class="controls">
            <input name="limit"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>人民币账单：</label>
          <div class="controls">
            <input name="bill"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>是否分期：</label>
          <div class="controls">
            <input name="installment"  type="text" class="input-normal control-text">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="control-group span8">
          <label class="control-label"><s>*</s>分期总金额：</label>
          <div class="controls">
            <input name="installment_bill"  type="text" class="input-normal control-text">
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
    </form>
  </div>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<script type="text/javascript">
  BUI.use(['common/search','bui/overlay','bui/grid'],function (Search,Overlay,Grid) {
    
	editing = new BUI.Grid.Plugins.DialogEditing({
        contentId : 'content', //设置隐藏的Dialog内容
        autoSave : true, //添加数据或者修改数据时，自动保存
        triggerCls : 'btn-edit'
      }),
      columns = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:130,renderer:function(v,obj){
            return Search.createLink({
              id : 'detail' + v,
              title : '点击查看详情',
              text : v,
              href : 'detail/'+ obj.id
            });
          }},
          {title:'姓名',dataIndex:'name',width:60,},
          {title:'银行类别',dataIndex:'banktype',width:80},
		  {title:'卡激活日期',dataIndex:'activedate',width:80},
          {title:'每月账单日',dataIndex:'statedate',width:80},
          {title:'到期还款日',dataIndex:'repaydate',width:80},
		  {title:'信用额度',dataIndex:'limit',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>&yen;&nbsp;" + v + "</font></strong>";
		}},
		  {title:'人民币账单',dataIndex:'bill',width:100,renderer : function(v,obj){
				  return "<strong><font color='red'>&yen;&nbsp;" + v + "</font></strong>";
		}},
		  {title:'剩余应还款',dataIndex:'currentbalance',width:100,renderer : function(v,obj){
				  return "<font color='red'>&yen;&nbsp;" + v + "</font>";
		}},
		  {title:'是否分(期)',dataIndex:'installment',width:80,renderer : function(v,obj){
				  return "<strong><font color=''>" + v + "&nbsp;期</font></strong>";
		}},
		  {title:'分期总金额',dataIndex:'installment_bill',width:100,renderer : function(v,obj){
				  return "<strong><font  color=''>&yen;&nbsp;" + v + "</font></strong>";
		}},
		  {title:'每期应还款',dataIndex:'each_bill',width:100,renderer : function(v,obj){
			  	   var eve = obj.eve + obj.bankcharges;
				  return "<strong><font  color=''>&yen;&nbsp;" + eve + "</font></strong>";
		}},
		  {title:'备注',dataIndex:'memo',width:200},
		  {title:'创建时间',dataIndex:'created_at',width:130},
          {title:'操作',dataIndex:'',width:150,renderer : function(value,obj){
              editStr = '<span class="grid-command btn-edit" title="编辑">编辑</span>',
              delStr = '<span class="grid-command btn-del" title="删除">删除</span>';//页面操作不需要使用Search.createLink
            return editStr + delStr;
          }}
        ],
      store = Search.createStore('../data/card/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
            /*{text : '<i class="icon-plus"></i>新建',btnCls : 'button button-small',handler:function(){alert('新建');}},*/
            {text : '<i class="icon-remove"></i>删除',btnCls : 'button button-small',handler : delFunction},
			 {text : '<i class="icon-print"></i>打印',btnCls : 'button button-small',handler:function(){alert('打印');}}
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

	editing.on('accept',function(e){
		BUI.Message.Alert('更改成功！','success');
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
