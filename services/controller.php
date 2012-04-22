<?php
	require("library/SQL.php");
	
	$METHOD = '_POST';
	if(isset($_GET["debug"])){
		$METHOD = '_GET';
	}
	$variable = &$$METHOD;
	
	//input
	$pm=""; //libreria a instanciar
	$dt=""; //datos que se reciben
	$isu=""; //identificador de usuario
	$sid=""; //identificador de session
	$ifmt="json"; //formato ingreso
	$bXls = '0'; //flag de exportacion a excel
	
	if(isset($variable['export'])){
		require_once("library/excel.php");
		require_once("library/excel-ext.php");
		$bXls = '1';
	}
	if(isset($variable['pm'])){
		$pm = $variable['pm'];
	}else{
		returnInfo('plain',messageFormated('No hay libreria a instanciar'));
		exit();
	}
	
	if(isset($variable['dt'])){
		$dt = $variable['dt'];
	}else{
		returnInfo('plain',messageFormated('No hay datos a evaluar'));
		exit();
	}
	if(isset($variable['isu'])){
		$isu = $variable['isu'];
	}
	if($variable['isu']=="none"){
		$isu = 1;
	}
	if(isset($variable['sid'])){
		$sid = $variable['sid'];
	}else{
		$sid = $_COOKIE['PHPSESSID'];
	}
	if($variable['sid']=="none"){
		$sid = session_id();
	}
	if(isset($variable['iformat'])){
		$ifmt =$variable['iformat'];
	}
	$response = null;
		
	//debug
	/*
	$pm = 'mLogin/Login';
	$dt = '<xml><username>admin</username><password>admin</password></xml>';
	$sid = '1234';
	*/
	
	// Transformando datos de ingreso
	if($ifmt=='json'){
		// input as json
		$response = array(); 
		//echo ">>".$dt[0]."<<";
		$dtXML = $dt; //json_decode($dt[0]);
	}
	if($ifmt=='xml'){
		// input as xml
		$response = '<xml/>';
		$dtXML = simplexml_load_string($dt);
	}
	$i = 0; 

	$filename = $pm.".php";

	if(file_exists($filename))
	{
		require($filename);
	}
	else
	{
		$response[$i]['id'] = '0'; 
		$response[$i]['type'] = "BAD"; 
		$response[$i]['data'] = "Procedimiento no existe ".$filename."\n\nRequest:\n".$dt."\n\nSSID:".$sid;
		$response[$i]['nivel'] = '0';
		$response[$i]['action'] = "errorInfo"; 

		#En caso que el archivo no exista
		returnInfo();
		exit();
	}

	try
	{
		conect();
		$rp = run($dtXML,$bXls);
		disconect();
	}
	catch (Exception $e) {
		$ex = $e->getMessage();
		$response[$i]['id'] = '0'; 
		$response[$i]['type'] = "BAD"; 
		$response[$i]['data'] = "Error en ".$filename."\n\nRequest:\n".$dt."\n\nError Dump:\n".$ex."\nSSID:".$sid;
		$response[$i]['nivel'] = '0';
		$response[$i]['action'] = "errorInfo"; 

		#En caso que el ocurra un error exista
		returnInfo();
	    exit();
	};

	#Si llega hasta aca todo esta ok
	if($bXls=='1'){
		$response = $rp;
		returnInfo('xls');
	}else{
		$response[$i]['id'] = $rp[0]['dt']['data']['reg']['reg']['responseid'][0];
		$response[$i]['type'] = "OK";
		$response[$i]['data'] = $rp[0]['dt']['data']['reg']['reg'];
		$response[$i]['nivel'] = $rp[0]['dt']['data']['reg']['reg']['nivel'][0];
		$response[$i]['action'] = $rp[0]['dt']['data']['reg']['reg']['actions'][0];

		returnInfo();
	}

	//usefull functions
	function messageFormated($cadena){
		$salida = array();
		$salida[0]['id']=0;
		$salida[0]['type']='BAD';
		$salida[0]['data']=$cadena;
		$salida[0]['nivel']=0;
		$salida[0]['action']='showmessage';
		return json_encode($salida);
	}

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
			print_r($value);
		}
		if($type=='xls'){
			print createExcel("exportacion.xls",$value);
		}
	}
?>