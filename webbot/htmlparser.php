<?php namespace snp\webbot{

class HtmlParser{

    public $urlOfHtml;
    public $dom;
    public $xpath;
    public $urlhelper;

    public $baseUrl;

    public function __construct($html,$urlOfHtml,$urlhelper=null){

        $this->dom=new \DOMDocument();
        @$this->dom->loadHTML($html);
        $this->xpath=new \DOMXPath($this->dom);
        $this->urlOfHtml=$urlOfHtml;

        if($urlhelper==null){
            $this->urlhelper=new UrlHelper();
        }

        $this->parseBaseUrl();
    }


    public function query($queryString){
        return $this->xpath->query($queryString);
    }



    //如果以/结尾，则自动去除
    public function parseBaseUrl(){


        $pageBaseNodes=$this->query('//header/base/@href');

        if($pageBaseNodes->length==0){
            $baseUrl=$this->urlhelper->getDomainWithSchema($this->urlOfHtml);
        }else{
            $baseUrl=$pageBaseNodes->item(0)->nodeValue;
        }

        //去除末尾的/
        $this->baseUrl=(substr($baseUrl,-1,1)=="/")?
            substr($baseUrl,0,strlen($baseUrl)-1):
            $baseUrl;
        return $this->baseUrl;
    }



    //从nodeList逐个提取node的href属性值
    public function parseNodeValueArrayFromAttrNodes($attrNodes){

        $hrefs=array();
        foreach($attrNodes as $node){
            $value=$node->nodeValue;

            if(strpos($value,"javascript")!==false){
                continue;
            }
            if($value=="#"){
                continue;
            }

            //绝对url
            if(substr_compare($value,"http://",0,7,false)==0){
                $hrefs[]=$value;
            }else{//相对url
                //如果没有以/开头则自动加上
                $value=(substr_compare($value,"/",0,1)!==0)?
                    ("/".$value) : $value;
                $hrefs[]=($this->baseUrl).$value;
            }
        }
        $hrefs=array_unique($hrefs);
        return $hrefs;
    }



    public function parseAnchorsHrefArray(){

        $hrefNodes=$this->query('//a/@href');
        return $this->parseNodeValueArrayFromAttrNodes($hrefNodes);  
    }




    public function parseImgsSrcArray(){

        $hrefNodes=$this->query("//img/@src|//input[@type='image']/@src");
        return $this->parseNodeValueArrayFromAttrNodes($hrefNodes);
    }



    public  function parseSimpleTable($tableNode){

        $array=array();
        $trNodes=$tableNode->getElementsByTagName("tr");
        foreach($trNodes as $tr){
            $tmp=array();
            $tdNodes=$tr->getElementsByTagName("td");
            foreach($tdNodes as $td){
                $tmp[]=$td->nodeValue;
            }
            $array[]=$tmp;
        }
        return $array;
    }





}




}?>
