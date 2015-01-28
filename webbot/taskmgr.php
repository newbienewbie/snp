<?php namespace snp\webbot{

    class TaskMgr{

        public $done=array();
        private $todo;
        private $level=0;    //currentLevel

        public function __construct($todo,$done){
            $this->todo=$todo;
            $this->done=array($done);
            $this->level=0;
        }


        public function isScratched($url){
            return in_array($url,$this->done)?true:false;
        }


        public function getCurrentLevel(){
            return $this->level;
        }

        public function stepIntoNextLevel(){
            return $this->level=$this->level+1;
        }


        public function dispatchTasks(){

            $tasks=$this->todo;
            var_dump($this->todo);
            $this->todo=array();
            $this->stepIntoNextLevel();
            return $tasks;
        }

        public function completeTask($urlsToDo,$urlDone){

            //排除掉已经实现了的url
            $urlsToDo=array_filter($urlsToDo,function($v){
                return !($this->isScratched($v));
            });

            $this->todo=array_merge($this->todo,$urlsToDo);
            $this->todo=array_unique($this->todo);
            $this->done[]=$urlDone;
            echo "current level:{$this->level} \ttodo remaining:",count($this->todo),
                "\talready done:",count($this->done),PHP_EOL;
        }




    }




}?>
