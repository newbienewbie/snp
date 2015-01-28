<?php namespace snp\db{

class DB{

    public $schema;
    public $host;
    public $port;
    public $dbname;
    public $user;
    public $passwd;

    function __construct($config){

        $config_default=array(
            "schema"=>"mysql",
            "host"=>"localhost",
            "port"=>3306,
            "dbname"=>"test",
            "user"=>"root",
            "passwd"=>"toor",
        );

        $_config=array_merge($config_default,$config);

        foreach($_config as $k=>$v){
            if(array_key_exists($k,$_config)){
                $this->$k=$v;
            }
        }
    }

    function getConn($encoding="utf8"){

        $dsn="{$this->schema}:host={$this->host};port={$this->port};dbname={$this->dbname};";

        try{
            $pdo=new \PDO($dsn,$this->user,$this->passwd);
            $pdo->exec("set names $encoding");
            return $pdo;

        }catch(Exception $e){
            die($e->getMessage());
            
        }


    }
}
}?>
