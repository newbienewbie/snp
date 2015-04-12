# snp

## 简介
这是一个自动模块，包含常用类库代码

## webbot
网络机器人模块，爬虫相关



## pagination
分页模块
### 分页模块使用
```php
<?php
set_include_path(get_include_path().PATH_SEPARATOR.dirname(__FILE__));
spl_autoload_register();

$config=array(
    'totalRows'=>102,
    'perPage'=>10,
    'numlinks'=>2
);
$pagesinfo=new \snp\pagination\PagesInfo($config);
print_r($pagesinfo->generatePagesInfo(4));

$pagination=new \snp\pagination\Pagination($config);
print_r($pagination->generatePagesLinks(4));

?>

```
会输出类似于
```HTML
Array
(
    [currentPage] => 4
    [firstPage] => 1
    [lastPage] => 11
    [prevPage] => 3
    [nextPage] => 5
    [firstDigit] => 2
    [lastDigit] => 6
)
<a  href='index.php?page=1'>&lt;&lt;</a><a  href='index.php?page=2'>2</a><a  href='index.php?page=3'>3</a><a  href='index.php?page=4'>4</a><a  href='index.php?page=5'>5</a><a  href='index.php?page=6'>6</a><a  href='index.php?page=11'>&gt;&gt;</a>

```


## authdatagrid
审批数据网格
```JavaScript
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
```
