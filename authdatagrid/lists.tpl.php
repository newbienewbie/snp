<html>
<head>
    <script src='statics/js/jquery.js' > </script>
    <link rel='stylesheet' href='statics/css/bootstrap.css'>
    <!--[if lt IE 9]>
      <script src="statics/js/html5shiv.js"></script>
      <script src="statics/js/respond.min.js"></script>
    <![endif]-->
    <script src="statics/js/bootstrap.min.js"> </script>
    <script src="statics/js/bootstrap.ourjs.js"> </script>
</head>

<body>


<div class="container">
    <div class='main' class='row'>

        <div class='row'> <!--功能列表-->
            <div  class='col-md-4 '>
                <a id='btnSelectionPass' class='btn btn-success' >通过</a>
            </div>

            <div  class='col-md-4 '>
                <a id='btnSelectionRefuse' class='btn btn-warning'  >驳回</a>
            </div>

            <div class='col-md-4'>
                <a id='btnSelectionDetailModal' class='btn btn-info'>明细</a>
            </div>
        </div>


        <div class="col-md-12"> <!--数据-->
            <table class="table table-hover" id="dgPO"></table> <!--数据表格之PO-->
        </div>

        <div id='pager' class='col-md-6'> <!--分页-->
            <a id='previousPage' class='btn'> &lt;&lt;</a>
            <span><span>
            <a id='nextPage' class='btn'> &gt;&gt;</a>
        </div>

    </div>
</div>




<!--用于显示详细内容的对话框-->
<div class="modal fade" id="detailModal" >
  <div class="modal-dialog model-md">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times; </span>
        </button>
        <h4 class="modal-title">Modal title</h4>
      </div>

      <div class="modal-body">
          <div id='selectionDlgBody'><!--body部分是远程加载的--> </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">ok</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script src='statics/js/authdatagrid.js' > </script>

<script>

//加载datagrid数据
(function(){

    var pc_hash=parent.window.pc_hash;

    //数据表格审批配置
    var dgConfig={
        'dgSelector':$('#dgPO'),
        'datagrid':{
            columns:[[
                {title:"status",field:"state"},
                {title:"id",field:"id"},
                {title:"user",field:"userid"},
                {title:"product",field:"product_id"},
                {title:"price",field:"price"},
                {title:"审批",field:"auth"},
            ]],
            singleSelect:true,
            selectedClass:"danger"
        },

        "urlRemote":'index.php?m=product&c=product&a=jsonList&pc_hash='+pc_hash,

        //以后要改成post方式
        'urlPass':'index.php?m=product&c=product&a=authPass&pc_hash='+pc_hash+'&id=',

        'urlRefuse':'index.php?m=product&c=product&a=authRefuse&pc_hash='+pc_hash+'&id=',

        'modalConfig':{
            'modalSelector':$('#detailModal'),
            'modalBodySelector':$('#selectionDlgBody'),
            'urlModalBody':'index.php?m=product&c=product&a=detailModal&pc_hash='+pc_hash+'&id=',
        }
    };

    var dg=new AuthDatagridNS.AuthDatagrid(dgConfig);

    //配置datagrid
    dg.createDatagrid();
    //向其他控制器发送请求并加载数据
    dg.loadData();
    //添加按钮处理程序
    $('#btnSelectionPass').click(
        function(){
            dg.passSelection();
        }
    );
    $('#btnSelectionRefuse').click(
        function(){
            dg.refuseSelection();
        }
    );
    $('#btnSelectionDetailModal').click(
        function(){
            dg.showSelection();
        }
    );



})();


</script>
</body>
</html>
