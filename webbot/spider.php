<?php namespace snp\webbot{

    class Spider{

        public $seed;
        public $maxDepth;
        public $currentDepth;


        public $bot;
        public $urlhelper;


        public function __construct(
            $seed="http://baidu.com", $depth=1,
            $bot=null, $urlhelper=null, $taskmgr=null
        ){

            echo "initializing the Spider....",PHP_EOL;
            $this->seed=$seed;
            $this->maxDepth=$depth;

            //设置默认的bot
            if($bot==null){
                $this->bot=new HttpBot();
            }else{
                $this->bot=$bot;
            }

            //设置默认的url帮助器
            if($urlhelper==null){
                $this->urlhelper=new UrlHelper();
            }else{
                $this->urlhelper=$urlhelper;
            }


            if($taskmgr==null){
                $links=$this->getAnchors($seed);
                $this->taskmgr=new TaskMgr($links,$this->seed);
            }else{
                $this->taskmgr=$taskmgr;
            }
            echo "Spider initialized!",PHP_EOL;
        }


        //未来的依赖注入控制点
        private function getParser($parserOrHtml=null,$url=null){

            if(is_string($parserOrHtml)){
                $parser=new HtmlParser($parserOrHtml,$url);
            }
            return $parser;
        }

        public function getParserForUrl($url,$charset="UTF-8"){

            echo "[*]trying to retrieve $url";
            $response=$this->bot->get($url);
            echo "...done!",PHP_EOL;
            $html=$response["content"];
            if($charset!="ISO-8859-1"){
                $html=mb_convert_encoding($html,"HTML-ENTITIES",$charset);
            }
            echo "[*]trying to parse the html";
            $parser=$this->getParser($html,$url);
            echo "...done!",PHP_EOL;
            return $parser;
        }



        public function getAnchors($url,$charset="UTF-8",$queryString="//a/@href"){
            $parser=$this->getParserForUrl($url,$charset);
            $nodes=$parser->query($queryString);
            $links=$parser->parseNodeValueArrayFromAttrNodes($nodes);
            echo "[+] ",count($links)," links found",PHP_EOL;
            return $links;
        }




        public function scratch(){

            echo PHP_EOL;
            echo "now start to scratch on the ",$this->seed,PHP_EOL;
            while(true){

                $level=$this->taskmgr->getCurrentLevel();
                if($level>$this->maxDepth){
                    break;
                }
                echo PHP_EOL,PHP_EOL,PHP_EOL;
                echo "[+]current level : $level ,",
                    "\tmax depth : ",$this->maxDepth,PHP_EOL;
                $urlsToDo=$this->taskmgr->dispatchTasks();
                echo "[+]get ",count($urlsToDo)," tasks in level $level:",PHP_EOL;


                $count=0;
                foreach($urlsToDo as $url ){

                    if(!($this->taskmgr->isScratched($url))){
                        $nextLevelTodos=$this->getAnchors($url,"UTF-8");
                        $this->payload($url);
                        $this->taskmgr->completeTask($nextLevelTodos,$url);
                        echo "[-]task completed: '$url' ",
                            PHP_EOL,PHP_EOL;
                    }else{
                        echo "[-]the '$url' has already done before....skip",
                            PHP_EOL,PHP_EOL;
                        continue;
                    }
                }
            }

        }




        public function downloadNodesWithinPage(
            $url,$charset="UTF-8",$queryString="//img/@src",
            $path="/tmp",$prefix="spd_"
        ){
            $parser=$this->getParserForUrl($url,$charset);
            $imgs=$parser->query($queryString);
            //获取图像节点nodeList对应的href Array;
            $imghrefs=$parser->parseNodeValueArrayFromAttrNodes($imgs);
            $n=count($imghrefs);

            echo "[+] $n images found :",PHP_EOL;
            if($n!=0){
                foreach($imghrefs as $href){
                    $filename=$path."/".$prefix.mt_rand(0,9999);
                    $remoteFileName=$this->urlhelper->getRemoteFileName($href);
                    $filename=$filename."___".$remoteFileName;
                    $this->bot->downloadFile($href,$filename);
                    echo "\t[-]img saved:$filename".PHP_EOL;
                }
            }

        }


        //do with every page
        public function payload($url){
        }






    }




}?>
