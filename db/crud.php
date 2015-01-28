<?php namespace snp\db{


    class Crud{



        public $conn;



        public function __construct(DB $db){
            $this->conn=$db->getConn();
        }



        public function getPreparedFieldsToInsert(array $fields,$prefix=":"){

            $fieldNames=array_keys($fields);
            $fieldBindingNames=array_map(
                function($v)use($prefix){
                    return " ($prefix"."$v) ";
                },
                $fieldNames
            );

            return array(
                "fieldNames"=>join(",",$fieldNames),
                "fieldBindingNames"=>join(",",$fieldBindingNames),
            );

        }

        public function getPreparedFieldsToUpdate(array $fields,$prefix=":"){

            $setFields=array();
            foreach($fields as $k=>$v){
                $setFields[]=" $k=(".$prefix."$k) ";
            }
            return join(",",$setFields);

        }


        public function execute($preparedSql,array $array){
            $stmt=$this->conn->prepare($preparedSql);
            $stmt->execute($array);
            return $stmt;
        }

        

    }



}?>
