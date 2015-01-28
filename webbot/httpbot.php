<?php  namespace snp\webbot{

class HttpBot{

    public $curlopts_default;

    public function __construct(){

        if(!function_exists("curl_init")){
            die("cannot load the cURL... ");
        }

        $this->curlopts_default=array(
            CURLOPT_HTTPHEADER=>array(
                'User-Agent: Mozilla/5.0 (X11; Linux i686; rv:31.0) Gecko/20100101 Firefox/31.0 Iceweasel/31.4.0',
                "DNT: 1",
                "Connection: keep-alive",
            ),
            CURLOPT_TIMEOUT=>15,
            CURLOPT_AUTOREFERER=>true,
            CURLOPT_FOLLOWLOCATION=>true,
            CURLOPT_RETURNTRANSFER=>true,
        );

    }


    //执行并获取信息
    private function getCurlExecResult($ch){

        $response["content"]=curl_exec($ch);
        $response["status"]=curl_getinfo($ch);
        $response["error"]=curl_error($ch);

        return $response;
    }



    //http get request
    public function get($url,$cj=null,array $curlopts=array()){

        $ch=curl_init($url);
        $_curlopts=array_replace(
            $this->curlopts_default,
            array(
                CURLOPT_COOKIEFILE=>$cj,
                CURLOPT_COOKIEJAR=>$cj,
            ),
            $curlopts
        );
        curl_setopt_array($ch,$_curlopts);

        $response=$this->getCurlExecResult($ch);
        curl_close($ch);
        return  $response;
    }



    //http post request
    public function post($url,$data,$cj=null,array $curlopts=array()){

        $ch=curl_init($url);

        $_curlopts=array_replace(
            $this->curlopts_default,
            array(
                CURLOPT_POSTFIELDS=>$data,
                CURLOPT_COOKIEFILE=>$cj,
                CURLOPT_COOKIEJAR=>$cj,
            ),
            $curlopts
        );

        curl_setopt_array($ch,$_curlopts);
        $response=$this->getCurlExecResult($ch);
        curl_close($ch);
        return $response;
    }


    

    //download file
    public function downloadFile($url,$filename,$cj=null,array $curlopts=array()){

        $fh=fopen($filename,"w");

        $ch=curl_init($url);

        $_curlopts=array_replace(
            $this->curlopts_default,
            array(
                CURLOPT_FILE=>$fh,
                CURLOPT_COOKIEFILE=>$cj,
                CURLOPT_COOKIEJAR=>$cj,
            ),
            $curlopts
        );

        curl_setopt_array($ch,$_curlopts);

        $response=$this->getCurlExecResult($ch);
        curl_close($ch);
        fclose($fh);
        return $response;
    }




}
}?>
