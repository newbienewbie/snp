<?php namespace snp\webbot{


    class UrlHelper{



        public  function getDomain($url){

            //移除http,https协议部分
            if(strpos($url,"://")){
                $url = str_replace("http://", "", $url);
                $url = str_replace("https://", "", $url);
            }

            //截取剩余url中的第一段，以/为标志
            if(stristr($url, "/")){
                $url = substr($url, 0, strpos($url, "/"));
            }

            return $url;
        }



        public function getDomainWithSchema($url){
            $url=$this->getDomain($url);

            $position=strpos($url,"://");
            $schema=($position!==false)?
                substr($url,"://",0,$position+3):
                "http://";

            return $schema.$url;
        }



        //获取远程链接文件名(作为本地存储时参考)
        public function getRemoteFileName($url,$extReserved=false){

            //截取最后一个/之后出现的字符串
            if(strpos($url,"/")){
                $url_segs=array_slice(explode("/",$url),-1);
                $url=$url_segs[0];
            } 
            if(!$extReserved){
                //去除html、php、aspx之类的后缀
                $url=substr($url,0,strpos($url,"."));
            }
            return $url;
        }




    }



}?>
