<?php

 require_once 'params.php';

	$pgsql_cn = ""; 
	$mssql_cn = "";
	$pgsql_rs = null;
	$mssql_rs = null;
	$GLOBALS["conector"] = 'pgsql';
	
	// $conector = $GLOBALS["conector"];

function swapConector($conector){
    disconect();
    $GLOBALS["conector"] = $conector;
    conect();
}

function conect(){
    if($GLOBALS["conector"] == 'pgsql'){
	pgconect();
    }else{
	msconect();
    }
}

function pgconect(){
    $conn_string = "host=".$GLOBALS["pg_host"]." port=".$GLOBALS["pg_port"]." dbname=".$GLOBALS["pg_dbname"]." user=".$GLOBALS["pg_user"]." password=".$GLOBALS["pg_passwd"];
    if (!$GLOBALS["pgsql_cn"] = pg_connect($conn_string)) {
	echo "Could not connect to PGSQL database!\n";
    }
}

function msconect(){
    if(!$GLOBALS["mssql_cn"] = mssql_connect($GLOBALS["ms_host"], $GLOBALS["ms_user"], $GLOBALS["ms_passwd"])) {
	echo "Could not connect to MSSQL database!\n";
    }else{
	if(!mssql_select_db($GLOBALS["ms_dbname"],$GLOBALS["mssql_cn"])){
	    echo "Unable to select database!\n";
	}
    }
}

function querySQLi_result($sentence){
    if($GLOBALS["conector"] == 'pgsql'){
	return pgquerySQLi_result($sentence);
    }else{
	return msquerySQLi_result($sentence);
    }
}

function pgquerySQLi_result($sentence){
    $query = $sentence;
    $GLOBALS["pgsql_rs"] = pg_query($GLOBALS["pgsql_cn"], $query) or die('DB QUERY ERROR '.pg_last_error($GLOBALS["pgsql_cn"]).' - '.$query);
    return $GLOBALS["pgsql_rs"];
}

function msquerySQLi_result($sentence){
    $query = $sentence;
    $GLOBALS["mssql_rs"] = mssql_query($query,$GLOBALS["mssql_cn"]) or die('DB QUERY ERROR '.mssql_get_last_message().' - '.$query);
    return $GLOBALS["mssql_rs"];
}

function query($sentence,$array = "0"){
    //echo "PETICION a ".$GLOBALS["conector"]; 
    if($GLOBALS["conector"] == 'pgsql'){
	return pgquery($sentence,$array);
    }else{
	return msquery($sentence,$array);
    }
}

function pgquery($sentence,$array)
{
	
	$length = 0;
	$result = pgquerySQLi_result($sentence);
	
	if($array == '1'){
		// Creamos el array con los datos
		while($datatmp = pg_fetch_assoc($result)) {
			$data[] = $datatmp;
		}
		return $data;
	}
	
 	$sqlXML = simplexml_load_string('<xml/>');

	# Recupera los fieldnames.
	$fieldnames = array();
	//$sqlXML->columns->name = array();
	$numfields = pg_num_fields($result);
//	print "\n$numfields Columnas\n";
	
	for($i=0; $i<$numfields; $i++)
	{
		$field = pg_field_name($result,$i);
		$fieldnames[] = $field;
		$sqlXML->columns->name[$i] = $field;
//		print "\n$i Columna: $field\n";
	}
	# recorrer los fielnames y crea la estructura del data
	$numrows = pg_num_rows($result);
	
//	print "\n$numrows Registros\n";
	for($j=0; $j<$numrows;$j++){
		$row = pg_fetch_array($result,$j,PGSQL_BOTH);
		for($i=0; $i<$numfields; $i++)
		{
			$campo = $fieldnames[$i];
			$sqlXML->data->reg[$j]->{$campo}[0] = $row[$campo];
		}
	}
	$sqlXML->num[0] = $numfields;
	//$sqlXML->columns->name = array();
	//$sqlXML->columns->name = $fieldnames;
	$sqlXML->length[0] = $numrows;
	$sqlXML->error[0] = pg_last_error($GLOBALS["pgsql_cn"]);
	return $sqlXML;
}

function msquery($sentence,$array)
{
	$length = 0;
	
	$result = msquerySQLi_result($sentence);
	
	if($array == '1'){
		// Creamos el array con los datos
		while($datatmp = mssql_fetch_assoc($result)) {
			$data[] = $datatmp;
		}
		return $data;
	}
	
	$sqlXML = simplexml_load_string('<xml/>');
	
	#Recupera los fieldnames
	$fieldnames = array();
	$numfields = mssql_num_fields($result);
	
	for($i=0; $i<$numfields; $i++)
	{
	    $field = mssql_field_name($result,$i);
	    $fieldnames[] = $field;
	    $sqlXML->columns->name[$i] = $field;
	}
	
	$numrows = mssql_num_rows($result);
	
	for($j=0; $j<$numrows;$j++){
		$row = mssql_fetch_array($result, MSSQL_BOTH);
		for($i=0; $i<$numfields; $i++)
		{
			$campo = $fieldnames[$i];
			$sqlXML->data->reg[$j]->{$campo}[0] = $row[$campo];
		} 
	}
	$sqlXML->num[0] = $numfields;
	$sqlXML->length[0] = $numrows;
	return $sqlXML;
}

function make($sentence){
    if($GLOBALS["conector"]=='pgsql'){
	return pgmake($sentence);
    }else{
	return msmake($sentence);
    }
}

function pgmake($sentence)
{
	$row = pgquerySQLi_result($sentence);
 	$sqlXML = simplexml_load_string('<xml/>');
	$sqlXML->{make}[0] = $row;
	return $sqlXML;
}

function msmake($sentence)
{
	$row = msquerySQLi_result($sentence);
	$sqlXML = simplexml_load_string('<xml/>');
	$sqlXML->{make}[0] = $row;
	return $sqlXML;
}

function clear_result($resultado){
    if($GLOBALS["conector"]=='pgsql'){
	pgclear_result($resultado);
    }else{
	msclear_result($resultado);
    }
}
function pgclear_result($resultado){
	// Free resultset
	pg_free_result($resultado);
}
function msclear_result($resultado){
	mssql_free_result($resultado);
}

function clear(){
    if($GLOBALS["conector"]=='pgsql'){
	pgclear_result($GLOBALS["pgsql_rs"]);
    }else{
	msclear_result($GLOBALS["mssql_rs"]);
    }
}

function disconect(){
    if($GLOBALS["conector"]=='pgsql'){
	pg_close($GLOBALS["pgsql_cn"]);
    }else{
	mssql_close($GLOBALS["mssql_cn"]);
    }
}

function convertArr($node, &$parent=array(), $only_child=true)
{
        //Current node name
        $node_name = $node->getName();
          
        //Let's count children
        $only_child = true;
        
        if(count($node->children())>1) $only_child = false;

        // //If there is no child, then there may be text data
        if($only_child){
        	$content="$node";            
        	if (strlen($content)>0) $parent=$content;
        }
        
        // //Get attributes of current node
        // foreach ($node->attributes() as $k=>$v) {
        //     $parent['@attributes'][$k]="$v";
        // }
       
        //Get children
        //$count = 0;
        foreach ($node->children() as $child_name=>$child_node) {
             if(!$only_child){
                convertArr($child_node, $parent[$node_name][$child_name][], $only_child);
             }
             else
             {
                convertArr($child_node, $parent[$node_name][$child_name], $only_child);
             } 
             //$count++;
        }
        
        return $parent;
}

function superCopy($obj1,$obj2,$clave){
	$dest = &$obj2->{$clave}[sizeof($obj2->$clave)];
	foreach($obj1->children() as $a => $b){
		if($b->children()){
			superCopy($b,$dest,$a);
		}else{
			$dest->$a=$b;
		}
	}	
}
?>