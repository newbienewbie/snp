<?php namespace snp\pagination{

class Pagination extends PagesInfo{

    public $baseUrl='index.php?';
    public $cssClass='';
    public $queryParamName='page';
    public $firstPageText="&lt;&lt;";
    public $lastPageText="&gt;&gt;";
    public $firstDigitText="&lt;";
    public $lastDigitText="&gt;";
    public $space="<span>&nbsp;&nbsp;</span>";

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
        
        echo $this->space;
        
        echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}={$info['firstDigit']}'>";
        echo $this->firstDigitText;
        echo "</a>";
        echo $this->space;
        
        $digits=range($info['firstDigit'],$info['lastDigit']);
        foreach($digits as $v){
            echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}=$v'>$v</a>";
            echo $this->space;
        }
        
        echo "<a {$this->cssClassSting} href='{$this->baseUrl}{$this->queryParamName}={$info['lastDigit']}'>";
        echo $this->lastDigitText;
        echo "</a>";
        echo $this->space;  
       
      
        
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
