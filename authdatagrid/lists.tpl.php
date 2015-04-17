<?php include $this->admin_tpl('header','product')?>
<div class="container">
    <div class='main' class='row'>

        <div class='row'>

            <!--检索条件-->
            <div class='col-md-3'>
                <label for='stateFieldSelector'>审批状态</label>
                <select id='stateFieldSelector' name='state' />
                    <option  value='0'>0</option>
                    <option  value='1'>1</option>
                    <option  value='2'>2</option>
                </select>
                <a id='search' class='btn btn-warning'>检索</a>
            </div>

        </div>

        <div class='row'> <!--功能列表-->
            <div  class='col-md-4 '>
                <button id='btnSelectionPass' class='btn btn-success' >通过</a>
            </div>
            <div  class='col-md-4 '>
                <button id='btnSelectionRefuse' class='btn btn-warning'  >驳回</a>
            </div>
            <div class='col-md-4'>
                <button id='btnSelectionDetailModal' class='btn btn-info'>明细</a>
            </div>
        </div>

        <div class="col-md-12"> <!--数据-->
            <table class="table table-hover" id="dgPO"></table> <!--数据表格之PO-->
        </div>

        <div  class='col-md-6'> <!--分页-->
            <ul id='pager' class='pagination'> </ul>
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

    var pg=new AuthDatagridNS.Pagination({
        'paginationSelector':'#pager',
        'baseUrl':'http://localhost/phpcms/index.php?m=product&c=product&a=jsonList&'
    });

    var md=new AuthDatagridNS.DetailModal({
        'modalSelector':'#detailModal',
        'modalBodySelector':'#selectionDlgBody',
        'urlModalBody':'index.php?m=product&c=product&a=detailModal&pc_hash='+pc_hash+'&id=',
    });

    //数据表格审批配置
    var dgConfig={
        'dgSelector':'#dgPO',
        'datagrid':{
            columns:[[
                {title:"订单号",field:"id"},
                {title:"用户",field:"user"},
                {title:"奖品",field:"product"},
                {title:"单价",field:"price"},
                {title:"审批状态",field:"state"},
            ]],
            singleSelect:true,
            selectedClass:"danger"
        },

        "urlRemote":'index.php?m=product&c=product&a=jsonList&pc_hash='+pc_hash,

        //以后要改成post方式
        "passProcessor":{
            'url':'index.php?m=product&c=product&a=authPass&pc_hash='+pc_hash+'&id=',
            'method':'get',
            'data':'',
        },

        "refuseProcessor":{
            'url':'index.php?m=product&c=product&a=authRefuse&pc_hash='+pc_hash+'&id=',
            'method':'get',
            'data':'',
        },

        //明细对话框
        'detailModal':md,
        //分页器
        'pagination':pg,

    };

    var dg=new AuthDatagridNS.AuthDatagrid(dgConfig);

    //配置datagrid
    dg.createDatagrid();
    //向其他控制器发送请求并加载数据
    dg.loadData();
    //添加按钮处理程序
    $('#btnSelectionPass').click(function(){
        dg.passSelection();
    });
    $('#btnSelectionRefuse').click(function(){
        dg.refuseSelection();
    });
    $('#btnSelectionDetailModal').click(function(){
        dg.showSelection();
    });

    $('#stateFieldSelector').change(function(){
        var state=$(this).children('option:selected').val();
        dg.urlRemote='index.php?m=product&c=product&a=jsonList&state='+state+'&pc_hash='+pc_hash;
        dg.loadData();
    });


})();


</script>
</body>
</html>
