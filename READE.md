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
