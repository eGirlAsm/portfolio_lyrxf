<!DOCTYPE HTML>
<html>
<head>
<title>搜索表单</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../assets/css/dpl-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/bui-min.css" rel="stylesheet" type="text/css" />
<link href="../assets/css/page-min.css" rel="stylesheet" type="text/css" />
<style>
.tips-content{color:#aaaaaa !important;}
.tips-content strong{color:#000 !important;}
</style>
</head>
<body>
<div class="container">
<div class="row">
  <div id="calendar">
    <p>

    <div class="tips tips-small tips-notice"> <span class="x-icon x-icon-small x-icon-warning"><i class="icon icon-white icon-volume-up"></i></span>
      <div class="tips-content">今日有6张卡需要还款,今日需还款总金额 <strong><i><?=$today_total?></i></strong>&nbsp;元,明天估计需还款总金额<strong><i><?=$tomorrow_total?></i></strong>&nbsp;元, 当前资金还剩 <strong><i><?=$funds?></i></strong>&nbsp;元</div>
    </div>
    </p>
    <div id="content" class="hide">
<form id="J_Form" class="form-horizontal" >
        <div class="row">
          <div class="control-group span8">
            <label class="control-label"><s>*</s>还款金额:</label>
            <div class="controls">
              <input name="today" type="text" data-rules="{required:true}" class="input-normal control-text">
            </div>
          </div>
        </div>
        <div class="row">
        <div class="control-group span15">
          <label class="control-label">备注：</label>
          <div class="controls control-row4">
            <textarea name="memo" class="input-large" data-tip="{text:'请填写备注信息！'}" type="text"></textarea>
          </div>
        </div>
        </div>        
    </form>
    </div>

  </div>
  <div class="search-grid-container">
    <h2>今日还款表</h2>
    <hr>
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
    
			 var dialog = new Overlay.Dialog({
						title:'导出还款表到EXCEL',
						width:500,
						height:250,
						bodyContent:"<p><a class='button button-success' href='../export'>确定导出</a></p>",
						success:function () {
						
						this.close();
						}
					});	
	
	editing = new BUI.Grid.Plugins.DialogEditing({
        contentId : 'content', //设置隐藏的Dialog内容
        autoSave : true, //添加数据或者修改数据时，自动保存
        triggerCls : 'btn-edit'
      }),
      columns = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:200},
          {title:'姓名',dataIndex:'name',width:100,},
          {title:'银行类别',dataIndex:'banktype',width:80},
		  {title:'最迟还款日',dataIndex:'repaydate',width:80},
		  {title:'人民币账单',dataIndex:'bill',width:80,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>&yen;&nbsp;" + v + "</font></strong>";
		}},
		  {title:'剩余应还款',dataIndex:'currentbalance',width:130,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>&yen;&nbsp;" + v + "</font></strong>";
		}},
          {title:'今日还款',dataIndex:'today',width:130,renderer:function(v,obj){
			  	var color;
				if(obj.em == 1){ //就剩三天以下了
			  		color = 'red';
				}else if(obj.em == -1){ //资金不足
					color = '#EC971F';
				}else{
					color = '#3C763D'; //normal
				}
				
				return "<strong><font color='" + color + "'>&yen;&nbsp;" + v + "</font></strong>";
			}},
		  
          {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
           var editStr = '<span class="grid-command btn-edit" title="还款">还款</span>';
            return editStr;
          }}
        ],
      store = Search.createStore('../data/repay/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
			 {text : '<i class="icon-print"></i>打印',btnCls : 'button button-small',handler:function(){}},
			  {text : '<i class="icon-file"></i>导出',btnCls : 'button button-small',handler:function(){
				  	dialog.show();

				  }}
          ]
        },
		 plugins : [editing] // 插件形式引入多选表格
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

	editing.on('accept',function(){
		
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
