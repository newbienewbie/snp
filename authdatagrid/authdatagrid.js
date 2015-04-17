//namespace 

var AuthDatagridNS={};


/** DetailModal类
 *  提供接口config(config)和show(id);
 */
AuthDatagridNS.DetailModal=function(modalConfig){

    this.modalSelector=modalConfig.modalSelector;
    this.modalBodySelector=modalConfig.modalBodySelector;
    this.urlModalBody=modalConfig.urlModalBody;

    if(typeof this.config !='function'){

        AuthDatagridNS.DetailModal.config=function(config){
            if(typeof config.modalSelector !='undefined'){
                this.modalSelector=modalConfig.modalSelector;
            }
            if(typeof config.modalBodySelector !='undefined'){
                this.modalBodySelector=modalConfig.modalBodySelector;
            }
            if(typeof config.urlModalBody !='undefined'){
                this.urlModalBody=modalConfig.urlModalBody;
            }
        };


        AuthDatagridNS.DetailModal.prototype.show=function(id){
            that=this;
            //get the url
            $.get(that.urlModalBody,function(data){
                //load the remote 
                jQuery(that.modalBodySelector).html(data);
                //show the Modal 
                jQuery(that.modalSelector).modal({
                    'backdrop':false,
                    'keyboard':true,
                    'show':true
                });
            });
        };
    }
}



/**
 * 分页类
 */
AuthDatagridNS.Pagination=function(config){

    this.paginationSelector=config.paginationSelector;
    this.baseUrl=config.baseUrl;
    this.pagesInfo=config.pagesInfo;
    this.dg=config.dg;

    if(typeof this.config !='function'){

        //配置
        AuthDatagridNS.Pagination.prototype.config=function(config){

            //分页器选择符
            if(typeof paginationSelector !='undefined' ){
                this.paginationSelector=config.paginationSelector;
            }
            //分页的<a>的href属性中除了page=之外的部分
            if(typeof config.baseUrl !='undefined'){
                this.baseUrl=config.baseUrl;
            }
            //从服务器返回的分页信息
            if(typeof config.pagesInfo !='undefined'){
                this.pagesInfo=config.pagesInfo;
            }
            //绑定的datagrid对象
            if(typeof config.dg !='undefined'){
                this.dg=config.dg;
            }
        };

        //生成<a>分页列表
        AuthDatagridNS.Pagination.prototype.generateAnchorList=function(){

            var firstPage="",lastPage="",
                prevPage='',nextPage='',currentPage='',
                firstDigit='',lastDigit='',
                li='',temp='';
            firstPage="<li><a href='" +this.baseUrl+"page="+this.pagesInfo.firstPage+"' >" +"&lt;&lt;</a></li>";
            lastPage="<li><a href='"+this.baseUrl+"page="+this.pagesInfo.lastPage+"' >" +"&gt;&gt;</a></li>";
            prevPage="<li><a href='"+this.baseUrl+"page="+this.pagesInfo.prevPage+"' >" +"&lt;</a></li>";
            nextPage="<li><a href='"+this.baseUrl+"page="+this.pagesInfo.nextPage+"' >" +"&gt;</a></li>";
            currentPage="<li><a href='#'>"+currentPage+'</a></li>';
            for(var i=this.pagesInfo.firstDigit;i<this.pagesInfo.lastDigit+1;i++){
                temp+="<li><a href='"+this.baseUrl+"page="+i+"'>"+i+"</a></li>";
            }
            li=firstPage+prevPage+temp+nextPage+lastPage;
            jQuery(this.paginationSelector).html(li);
            return li;
        };

        //为每个<a>绑定处理事件
        AuthDatagridNS.Pagination.prototype.bindClick=function(pagesInfo){
            that=this;
            jQuery(this.paginationSelector).find('a').each(function(){
                $(this).click(function(){
                    var url=$(this).attr('href');
                    url=url+'&pc_hash='+pc_hash;
                    $.get(url,function(data){
                        //load data 
                        that.dg.loadData(url);
                    });
                    return false;
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

    
    this.dgSelector=jQuery(dgConfig.dgSelector);
    this.datagridConfig=dgConfig.datagrid;
    //数据url
    this.urlRemote=dgConfig.urlRemote;
    //通过和退回url
    this.passProcessor=dgConfig.passProcessor;
    this.refuseProcessor=dgConfig.refuseProcessor;
    //明细对话框配置
    this.detailModal=dgConfig.detailModal;
    //分页
    this.pagination=dgConfig.pagination;

    //如果该对象及其原型链中找不到createDatagrid()函数，则一次性添加以下所有方法到原型链
    if(typeof this.createDatagrid != 'function'){

        //createDatagrid()
        AuthDatagridNS.AuthDatagrid.prototype.createDatagrid=function(){
            this.dgSelector.datagrid(this.datagridConfig);
        };


        /** loadData()
         *
         * 向指定url请求JSON格式的字符串，
         * 其内容类似于
         * {
         *     'rows':[],
         *     'pagesInfo':[]
         * }
         *
         */
        AuthDatagridNS.AuthDatagrid.prototype.loadData=function(url){
            //为了在jQuery ajax函数中闭包函数中使用本对象
            var that=this;
            var urlJSON=typeof url=="undefined"?this.urlRemote:url;
            $.get(urlJSON,function(data){

                //为datagrid加载数据
                data=$.parseJSON(data);
                that.dgSelector.datagrid('loadData',{rows:data.rows});

                //更新分页器
                that.pagination.config({
                    'dg':that,
                    'pagesInfo':data.pagesInfo 
                });
                that.pagination.generateAnchorList();
                that.pagination.bindClick();
            });
        };


        //showSelection
        AuthDatagridNS.AuthDatagrid.prototype.showSelection=function(){

            var rowsSelected=this.dgSelector.datagrid("getSelections");
            if(rowsSelected.toString()==''){
                alert('请选择行项目！');
                return;
            }
            
            this.detailModal.show(rowsSelected[0].id);
        }


        //passSelection()
        AuthDatagridNS.AuthDatagrid.prototype.passSelection=function(){
            var rowsSelected=this.dgSelector.datagrid("getSelections");
            if(rowsSelected.toString()==''){
                alert('请选择行项目！');
                return;
            }
            if(!confirm('确定要执行 通过 操作吗？')){
                return ;
            }
            that=this;
            $.ajax(
                    that.passProcessor.url+rowsSelected[0].id ,
                    {
                        'method':that.passProcessor.method,
                        'data':that.passProcessor.data,
                    }

            ).done(function(data){
                    if($.parseJSON(data).authPass=='success'){
                        alert('审批通过');
                        that.loadData();
                    }else{
                        alert("审批未能通过，请稍后再试");
                    }
                }
            );
        };

        //refuseSelection()
        AuthDatagridNS.AuthDatagrid.prototype.refuseSelection=function(){
            var rowsSelected=this.dgSelector.datagrid("getSelections");
            if(rowsSelected.toString()==''){
                alert('请选择行项目！');
                return;
            }
            if(!confirm('确定要执行 退回 操作吗？')){
                return ;
            }

            that=this;
            $.ajax(
                    that.refuseProcessor.url+rowsSelected[0].id ,
                    {
                        'method':that.refuseProcessor.method,
                        'data':that.refuseProcessor.data,
                    }

            ).done(function(data){
                    if($.parseJSON(data).authRefuse=='success'){
                        alert('退回成功');
                        that.loadData();
                    }else{
                        alert("退回未能成功，请稍后再试");
                    }
                }
            );
        };
    }
}
