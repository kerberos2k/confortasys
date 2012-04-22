<?php
	session_start();

	$id_sesion_antigua = session_id();

	session_regenerate_id();

	$id_sesion_nueva = session_id();

	echo "Sesión Antigua: $id_sesion_antigua<br />";
	echo "Sesión Nueva: $id_sesion_nueva<br />";

	print_r($_SESSION);
?>
<script type="text/javascript">

	var pagina = 'index.php';
	var segundos = 1;

	function redireccion() {

	document.location.href=pagina;

	}

	setTimeout("redireccion()",segundos);

</script>