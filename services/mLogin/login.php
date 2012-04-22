<?php
	function run($data,$fmt)
	{
		$dtObj = $data;

		//vars
		$v1 = $dtObj['user'];//usuario
		$v2 = $dtObj['pass'];//clave
		$v3 = $GLOBALS['sid'];//session
		$v4 = $_SERVER['REMOTE_ADDR'];//ip
		 
		$sqlQUERY="select *, common.spu_getUserInfo('$v1','$v2','nivel') as nivel, common.spu_getUserInfo('$v1','$v2','sunat') as sunat from common.spu_login('$v1','$v2','$v3','$v4');";
		 
		$sqlXML = query($sqlQUERY);

		$rpta = array();
		 
		$simpleobj = convertArr($sqlXML->{data});
		 
		if($sqlXML->length[0] == '1')
		{
			$rpta[0] = array();
			$rpta[0]['id']=$simpleobj[data][reg][0][reg][responseid][0][content];
			$rpta[0]['dt']=$simpleobj;
			$rpta[0]['level'][0]=$simpleobj[data][reg][0][reg][nivel][0][content];
			$rpta[0]['action'][0]=$simpleobj[data][reg][0][reg][actions][0][content];
		}
		else	
		{
			$rpta[0] = array();
			$rpta[0]['id']=-1;
			$rpta[0]['dt']=json_encode("msg:'No se pudo obtener datos de acuerdo a los parametros suministrados'");
			$rpta[0]['level']=-1;
			$rpta[0]['action']='erroInfo';
		}
		return $rpta;
	}
?>