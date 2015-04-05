<?php namespace snp\pagination{

class Pagination extends PagesInfo{

    public $baseUrl='index.php?';
    public $cssClass='';
    public $queryParamName='page';
    public $firstPageText="<<";
    public $lastPageText=">>";

    private $cssClassSting=""; 

    public function __construct($params=array()){
        parent::__construct($params);
    }

    //在生成连接的时候调用
    private function caculateCssClass(){
        return $this->cssClassSting=$this->cssClass==''?
            '':
            " class='{$this->cssClass}' ";
    }


    public function generatePagesLinks($currentPage){

        $info=$this->generatePagesInfo($currentPage);
        $this->caculateCssClass();

        ob_start();

        //输出firstPage
        echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}={$info['firstPage']}'>";
        echo $this->firstPageText;
        echo "</a>";

        $digits=range($info['firstDigit'],$info['lastDigit']);

        foreach($digits as $v){
            echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}=$v'>$v</a>";
        }
        
        //输出lastPage
        echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}={$info['lastPage']}'>";
        echo $this->lastPageText ;
        echo "</a>";

        $out=ob_get_contents();
        ob_end_clean();
        return $out;
    }

    






}




}?>
