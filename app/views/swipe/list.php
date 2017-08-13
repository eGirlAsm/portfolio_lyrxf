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
      <p>

    <div class="tips tips-small tips-notice"> <span class="x-icon x-icon-small x-icon-warning"><i class="icon icon-white icon-volume-up"></i></span>
      <div class="tips-content">应刷金额是 <span style="color:red;">(初始输入进去的账单 - 当前已消费的金额) ÷ <?=$posamount?> </span>算出来的,其中的被除数&nbsp;<strong style="color:#3C763D"><i><?=$posamount?></i></strong>&nbsp;&nbsp;可在系统参数里更改. </div>
    </div>
    </p>
    <div id="content" class="hide">
      <form id="J_Form" class="form-horizontal">
        <div class="row">
          <div class="control-group span8">
            <label class="control-label"><s>*</s>刷卡金额:</label>
            <div class="controls">
              <input name="swipeamount" type="text" data-rules="{required:true}" class="input-normal control-text">
            </div>
          </div>
        </div>
               <div class="row">
          <div class="control-group span8">
            <label class="control-label"><s>*</s>POS机(编号):</label>
            <div class="controls">
			<input name="posid" type="text" data-rules="{required:true}" class="input-normal control-text">
            </div>
          </div>
        </div> 
      </form>
    </div>
  </div>
  <div class="row">
  <div class="search-grid-container">
    <h2>今日刷卡表</h2>
    <hr>
    <div id="grid"></div>
  </div>
  </div>

<div id="grid2"></div>
</div>
<script type="text/javascript" src="../assets/js/jquery-1.8.1.min.js"></script> 
<script type="text/javascript" src="../assets/js/bui-min.js"></script> 
<script type="text/javascript" src="../assets/js/config-min.js"></script> 
<script type="text/javascript">
    BUI.use('common/page');
  </script> 
<script type="text/javascript">

function   sleep(n)   
    {   
        var   start=new   Date().getTime();   
        while(true)   if(new   Date().getTime()-start> n)   break;   
    }  

function JSONLength(obj) {
	var size = 0, key;
	for (key in obj) {
		if (obj.hasOwnProperty(key)) 
			size++;
	}
	return size;
};

  BUI.use(['common/search','bui/overlay'],function (Search,Overlay) {
    
	editing = new BUI.Grid.Plugins.DialogEditing({
        contentId : 'content', //设置隐藏的Dialog内容
        autoSave : true, //添加数据或者修改数据时，自动保存
        triggerCls : 'btn-edit'
      }),
      columns = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:200,renderer:function(v,obj){
            return Search.createLink({
              id : 'detail' + v,
              title : '点击查看详情',
              text : v,
              href : 'detail/'+ obj.id
            });
          }},
          {title:'姓名',dataIndex:'name',width:100,},
		 /* {title:'信用额度',dataIndex:'limit',width:100,renderer : function(v,obj){
				  return "<strong><font color='#5BA1CF'>&yen;&nbsp;" + v + "</font></strong>";
		}},
		 /* {title:'初始账单',dataIndex:'bill',width:100,renderer : function(v,obj){
				  return "<strong><font color='red'>&yen;&nbsp;" + v + "</font></strong>";
		}},		
		  {title:'当前已消费',dataIndex:'currentbalance',width:100,renderer : function(v,obj){
				  return "<strong><font color='red'>&yen;&nbsp;" + v + "</font></strong>";
		}},
		  {title:'可刷金额',dataIndex:'avaliable',width:100,renderer : function(v,obj){
				  return "<font color='#3C763D'>&yen;&nbsp;" + v + "</font>";
		}},*/
          {title:'应刷金额',dataIndex:'amount',width:80,renderer : function(v,obj){
				  return "<strong><font  color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
		}},
          {title:'次数',dataIndex:'times',width:180,renderer : function(v,obj){
			  if(obj.amount && obj.times)
			     amount =Math.round( obj.amount / obj.times );
			   else
			   	amount = 0;  
				  return "<strong><font  color='red'>" + v + "&nbsp;次&nbsp;</font></strong><font color='green'>每笔&nbsp;&yen;&nbsp;" + amount + "</font>";
		}},
          {title:'分配的POS机(编号)',dataIndex:'pos_list',width:250,renderer : function(v,obj){
			  
			  
			  var plist = "";
			  var error = 0;
				for(var p in obj.pos_list){//遍历json对象的每个key/value对,p为key
				
				   plist += obj.pos_list[p].id + ",";
				   //if('undefined' == obj.pos_list[p].error)
				     //error = 0;
					//else 
					 //error = 1;
					//alert(plist);
				}			
			 if(obj.pos_error == 1)
			 	plist += "<font color='red'>POS机不足以分配!</font>";
			//alert(JSONLength(obj.pos_list));
				//var poslist;
				//for(var i = 0;i < JSONLength(obj.pos_list);i++){
				   //alert(obj.post_list.pos0);
				//}
			//return obj.pos_list.pos_id;
			  return plist;
		  }},
          {title:'操作',dataIndex:'',width:200,renderer : function(value,obj){
           /* var editStr =  Search.createLink({ //链接使用 此方式
                id : 'edit' + obj.id,
                title : '刷卡',
                text : '刷卡',
                href : 'search/edit.html'
              })*/
           var editStr = '<span class="grid-command btn-edit" title="刷卡">刷卡</span>';
            return editStr;
          }}
        ],
      store = Search.createStore('../data/swipe/'),
      gridCfg = Search.createGridCfg(columns,{
        tbar : {
          items : [
			 {text : '<i class="icon-print"></i>打印',btnCls : 'button button-small',handler:function(){alert('打印');}}
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

    //监听事件，删除一条记录
    grid.on('cellclick',function(ev){
      var sender = $(ev.domTarget); //点击的Dom
      if(sender.hasClass('btn-del')){
        var record = ev.record;
        delItems([record]);
      }
    });
	
	
/*//////////////////////////////////////////////////////////
      columns2 = [
          {title:'编号',dataIndex:'id',width:50},
          {title:'卡号',dataIndex:'cardnumber',width:200},
          {title:'姓名',dataIndex:'realname',width:100},
		  {title:'银行类别',dataIndex:'bank',width:80},
		  {title:'刷卡金额',dataIndex:'amount',width:100,renderer:function(v,obj){
				
				return "<strong><font color='#3C763D'>&yen;&nbsp;" + v + "</font></strong>";
			}},
		  {title:'POS机编号',dataIndex:'posid',width:100},
          {title:'刷卡日期',dataIndex:'created_at',width:200},
		  {title:'操作员',dataIndex:'operator',width:100},
        ],
      store2 = Search.createStore('../data/swipe_record/'),
      gridCfg2 = Search.createGridCfg(columns2,{
        tbar : {
          items : [
			 {text : '<i class="icon-print"></i>刷卡记录',btnCls : 'button button-small',handler:function(){}}
          ]
        }
      });

    var  search2 = new Search({
        store : store2,
        gridCfg : gridCfg2
      }),
      grid2 = search2.get('grid2');*/
	  
	  editing.on('accept',function(){
		  
		  search.load();
		  });
	  	
  });
</script>

<body>
</html>
