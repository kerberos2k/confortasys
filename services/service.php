<?php
session_start();

require("library/SQL.php");
	//definicion del metodo
	$METHOD = '_POST';
	if(isset($_GET["debug"])){
		$METHOD = '_GET';
	}
	$variable = &$$METHOD;
	
	//variables globales a usar
	$pm=""; //libreria a instanciar
	$dt=""; //datos que se reciben
	$isu=""; //identificador de usuario
	$sid=""; //identificador de session
	$ifmt="json"; //formato ingreso
	$bXls = '0'; //flag de exportacion a excel
	$response = null; //objeto contenedor de la respuesta 
	
	//verificacion si es para exportacion
	if(isset($variable['export'])){
		require_once("library/excel.php");
		require_once("library/excel-ext.php");
		$bXls = '1';
	}
	//verificacion de libreria
	if(isset($variable['pm'])){
		$pm = $variable['pm'];
	}else{
		returnInfo('plain',messageFormated('No hay libreria a instanciar'));
		exit();
	}
	
?>
<?php
	//funcion creadora de mensajes visualizables
	function messageFormated($cadena){
		$salida = array();
		$salida[0]['id']=0;
		$salida[0]['type']='BAD';
		$salida[0]['data']=$cadena;
		$salida[0]['nivel']=0;
		$salida[0]['action']='showmessage';
		return json_encode($salida);
	}

	// funcion que formatea la respuesta
	function returnInfo($type="json",$value=null){
		if($value == null){
			$value = $GLOBALS['response'];
		}

		if($type=='json'){
			echo json_encode($value);
		}
		if($type=='xml'){
			print $value->asXML();
		}
		if($type=='plain'){
			print_r($value,true);
		}
		if($type=='xls'){
			print createExcel("exportacion.xls",$value);
		}
	}
?>