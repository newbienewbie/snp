<?php namespace snp\db{


    //基类
    //派生类继承自此类
    //默认情况下，类名即为前缀+表名
class Table extends Crud{

    public $tablePrefix;
    public $tableName;



    public function __construct(DB $db,$tablePrefix="",$tableName=""){
        parent::__construct($db);
        $this->tablePrefix=$tablePrefix;
        $this->setTableName($tableName);
    }


    public function setTableName($tableName=""){
        if($tableName==""){
            $className=\get_class($this);
            $temp=array_slice(explode("\\",$classname),-1,1);
            $this->tableName=($this->tablePrefix).($temp[0]);
        }else{
            $this->tableName=($this->tablePrefix).$tableName;
        }
    }

    public function getTableName(){
        return $this->tableName;
    }




    public function insert(array $fields){
        $preparedfields=$this->getPreparedFieldsToInsert($fields);
        extract($preparedfields);

        $sql="insert into ".$this->getTableName() .
            "( $fieldNames )".
            " values ".
            "( $fieldBindingNames )";

        echo $sql;
        return $this->execute($sql,$fields)->rowCount() ;
    }


    public function update(array $fields,$wheres){

        $preparedfields=$this->getPreparedFieldsToUpdate($fields);

        $wheres=is_string($wheres)?$wheres:join(" and ",$wheres);

        $sql="update ".$this->getTableName().
            " set ".
            "$preparedfields"
            " where $wheres";
        echo $sql;
        return $this->execute($sql,$fields)->rowCount() ;
    }

    public function find(){
    }

}




}?>
