<?php

class DB_CORE{
    
    var $result;

    function db_init(){
        $this->Connect();
        $this->Select_DB();
    }

	function Connect(){
		global $config;
                $id=mysql_connect($config['dbhost'],$config['dbuname'],$config['dbpass']) or die (mysql_errno()." :".mysql_error());
		return $id;
    }

    function Select_DB(){
    	    global $config;
            mysql_select_db($config['dbname'],$this->Connect()) or die (mysql_errno()." :".mysql_error());
            mysql_set_charset('cp1251', $this->Connect());
    }

    function Query($query){
    	   //print $query."<br>\r\n"; //die;
            $result=mysql_query($query) or die (mysql_errno()." :".mysql_error());
            return $result;
    }

    /*
           ------ Select (функция для выбора значений)---
    $table_db - имя таблица;
    $who - какие поля необходимо выбирать (через ",") или *;
    $conditions - условие выбора ( можно использовать AND или OR);
    $name - столбец по которому будет сортировка;
    $sort - порядок сортировка (по умолчанию "ASC"-по возрастанию , "DESC"- по убыванию);
    $offset - номер начальной строки выбора;
    $rows -   количество возвращаемых строк;
             -------    ------
    */
    function Select($table_db, $who, $conditions="", $group=0, $name=0, $sort="ASC", $offset='0', $rows='0'){
    	$query="SELECT ".$who." FROM ".$table_db;
    	if($conditions!=''){
                $query .= " WHERE ".$conditions;
    	}
    	if($group!=''){
    		$query .= " GROUP BY ".$group;
    	    }
    	if($name!=''){
    		$query .= " ORDER BY ".$name . ' ' .$sort;
    	}
    	    if($offset<>0 OR $rows<>0){
    		      
                      $query .= " LIMIT ".$offset.",".$rows;
    	}
     $res=$this->Query($query);
       $this->result=$res;
       return $res;
    }

    /*
    $table_db - имя таблицы;
    $column - значения столбцов (column=expression);
    $condition - условие обновения;

    */
    function Update($table_db,$column,$condition=0){
    	$query="UPDATE ".$table_db." SET ".$column;
    	if($condition!=''){
    		$query.=" WHERE ".$condition;
    	}
    	$result=$this->Query($query);
    	return $result;
    }

    function Delete($table_db,$condition=0){
    	$query="DELETE FROM ".$table_db;
    	if($condition!=''){
    		$query.=" WHERE ".$condition;
    	}
    	    
            $result=$this->Query($query);
    	return $result;
    }
    
    
    function Insert($table_db,$values,$column=""){
    	  if(gettype($values)<>"string")
    	  $fields=array_keys($values);
          $query = "INSERT INTO ".$table_db;
          if($column<>""){
          	$query.=" ( ";
            if(gettype($column)=="string"){$query.=$column;}
            if(gettype($column)=="array"){
               for($i=0;$i<count($column);$i++){
               	   if($i!=0)
                   $query .= ", ";
            	   $query.='"'.$column[$i].'"';
               }
            }
            $query.=")";
          }
          $query.=" VALUES ( ";
          if(gettype($values)=="string"){$query.=$values;}
          if(gettype($values)=="array"){
          for($i=0;$i<count($values);$i++){
          	if($i==0)$query.="null, ";
          	if($i!=0){
            $query .= ", ";}
          	$query.='"'.$values[$fields[$i]].'"';
          }}
          $query.=" )";
          $this->Query($query);
    }

    function Show($row=0,$field=0){
    	return mysql_result($this->result,$row,$field);
    }

    function Id(){
        return mysql_insert_id();
    }

    function NumRows($table_db,$who,$conditions=0,$group=0,$name=0,$sort="ASC",$offset=0,$rows=0){
        $result=$this->Select($table_db,$who,$conditions,$group,$name,$sort,$offset,$rows);
    	$num=mysql_num_rows($result);
    	return $num;
    }

    function GetValues($table_db,$who,$conditions=0,$group=0,$name=0,$sort="ASC",$offset=0,$rows=0){
    	    global $config;
        $res=$this->Select($table_db,$who,$conditions,$group,$name,$sort,$offset,$rows);
        $field = mysql_list_fields($config['dbname'], $table_db);
        $fieldsn = mysql_num_fields($field);
        for ($j = 0; $j < mysql_num_rows($res); $j++)
         for ($i = 0; $i < $fieldsn; $i++){
            @$list[$j][mysql_field_name($field, $i)]=mysql_result($this->result,$j,mysql_field_name($field, $i));
         }
        return @$list;
    }

    function GetValue($table_db,$who,$cond){
        $result=$this->Select($table_db,$who,$cond);
        $r=@mysql_result($result,0,$cond);
        return $r;
    }

}
?>
