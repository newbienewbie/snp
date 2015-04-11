//namespace 
var AuthDatagridNS={};

//DetailModal类
AuthDatagridNS.DetailModal=function(modalConfig){

    this.modalSelector=modalConfig.modalSelector;
    this.modalBodySelector=modalConfig.modalBodySelector;
    this.urlModalBody=modalConfig.urlModalBody;

    if(typeof this.show !='function'){

        AuthDatagridNS.DetailModal.prototype.showSelection=function(){
            that=this;
            $.get(that.urlModalBody,function(data){
                //load the remote 
                that.modalSelector.html(data);
                //show the Modal 
                that.modalBodySelector.modal({
                    'backdrop':false,
                    'keyboard':true,
                    'show':true
                });
            });
        };
    }
}


/**
 * AuthDatagrid类
 * 提供接口方法：
 *     createDatagrid()
 *     loadData()
 *     showSelection()
 *     passSelection()
 *     refuseSelection()
 */   
AuthDatagridNS.AuthDatagrid=function(dgConfig){

    this.dgSelector=dgConfig.dgSelector;
    this.datagridConfig=dgConfig.datagrid;
    //数据url
    this.urlRemote=dgConfig.urlRemote;
    //通过和退回url
    this.urlPass=dgConfig.urlPass;
    this.urlRefuse=dgConfig.urlRefuse;
    //明细对话框配置
    this.modalSelector=dgConfig.modalConfig.modalSelector;
    this.modalBodySelector=dgConfig.modalConfig.modalBodySelector;
    this.urlModalBody=dgConfig.modalConfig.urlModalBody;

    //如果该对象及其原型链中找不到loadData()函数，则一次性添加相关方法到原型链
    if(typeof this.loadData != 'function'){

        //createDatagrid()
        AuthDatagridNS.AuthDatagrid.prototype.createDatagrid=function(){
            this.dgSelector.datagrid(this.datagridConfig);
        };


        //loadData()
        AuthDatagridNS.AuthDatagrid.prototype.loadData=function(){
            //为了在jQuery ajax函数中闭包函数中使用本对象
            var that=this;
            $.get(that.urlRemote,function(data){
                that.dgSelector.datagrid('loadData',{rows:$.parseJSON(data)});
            });

        };


        //showSelection
        AuthDatagridNS.AuthDatagrid.prototype.showSelection=function(){
            that=this;
            $.get(that.urlModalBody,function(data){
                alert('xxxxxxxxxx');
                //load the remote 
                that.modalSelector.html(data);
                //show the Modal 
                that.modalBodySelector.modal({
                    'backdrop':false,
                    'keyboard':true,
                    'show':true
                });
            });
        }


        //passSelection()
        AuthDatagridNS.AuthDatagrid.prototype.passSelection=function(){
            var rowsSelected=this.dgSelector.datagrid("getSelections");
            if(rowsSelected.toString()==''){
                alert('请选择行项目！');
                return;
            }
            var that=this;
            $.get(that.urlPass,function(data){
                if($.parseJSON(data).authPass=='success'){
                    alert('审批通过');
                    that.loadData();
                }else{
                    alert("审批未能通过，请稍后再试");
                }
            });
        };

        //refuseSelection()
        AuthDatagridNS.AuthDatagrid.prototype.refuseSelection=function(){
            var rowsSelected=this.dgSelector.datagrid("getSelections");
            if(rowsSelected.toString()==''){
                alert('请选择行项目！');
                return;
            }
            var that=this;
            $.get(that.urlRefuse,function(data){
                if($.parseJSON(data).authRefuse=='success'){
                    alert('退回成功');
                    that.loadData();
                }else{
                    alert('退回未能成功，请稍后再试');
                }    
            });
        };
    }
}

