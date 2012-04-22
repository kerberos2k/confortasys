<?php
session_start();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
	<title>Conforte Hostal Control v 1.0</title>
<link href="css/960_16_col.css" rel="stylesheet" type="text/css" media="all" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.6/jquery-ui.min.js"></script>

<script type="text/javascript" src="js/jquery.inputfocus-0.9.min.js"></script>
<script type="text/javascript" src="js/jquery.md5.js"></script>
<script type="text/javascript" src="js/jquery.main.js"></script>
<script type="text/javascript" src="js/jquery.jqDock.min.js"></script>
</head>
<body>
	<div id="mdl_bg" style="display:none;">
		<div id="mdl_windows" style="display:none;">
			<div id="mtitle">TITLE</div>
			<div id="mcontent"><img src="images/loading.gif" class="loading" border=0/></div>
		</div>
		<div id="mdl_blocker"><img src="images/_loading.gif" border=0/></div>
	</div>
	<div class="container_16">
	  <div class="grid_3"><img src="images/logo-hostal.png" width="160" height="80"></div>
	  <div class="grid_10"><img src="images/soft_title.png" width="400" height="80" align="middle" class="center_img"></div>
	<div class="clear"></div>

	<div class="container_16">
	  <div class="grid_12 prefix_2 suffix_2">
	  	<form action="#" method="post">
	  		 <!-- #login_step -->
            <div id="login_step">
                <div class="centered" id="syslogo">&nbsp;</div>
                <div class="form">
                    <label for="username">Usuario:</label>
                    <input type="text" name="username" id="username" value="usuario" />
                    <div class="clear"></div>
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password" value="password" />
                	<input class="submit" type="submit" name="submit_login" id="submit_login" value=""/>
                	<div class="clear"></div>
                	<span id="login_messages" class="label">&nbsp;<a href="#" onclick="javascript:showOwnerMessage();">def</a><a href="#" onclick="javascript:showOwnerMessage('Advertencia de');">wrn</a><a href="#" onclick="javascript:showOwnerMessage(null,'untitled');">alr</a><a href="#" onclick="javascript:showOwnerMessage(null,null,true);">nwin</a></span>
                </div>
            </div>
            <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
            <!-- #welcome_step -->
            <div id="welcome_step">
                <div class="centered" id="syslogo">&nbsp;</div>
                <div class="form">
                    <label for="loading">Verificando Credenciales</label>
					<img src="images/loading.gif" border="0"/>
                </div>
            </div>
            <!-- clearfix --><div class="clear"></div><!-- /clearfix -->
	  	</form>
	  </div>
	</div>
	<div class="clear"></div>

	<div id="footer_fondo"></div>
	<div class="clear"></div>

	<div id="footer">
	  <div id="multimedia_logo"><a href="http://www.multimedia.pe" target="_blank"><img src="images/multimedia-logo.png" width="171" height="81"></a></div>
	  <div id="footer_creditos">© Todos los derechos reservados, Hostal Control 1.0 - Sistema de administración para hospedaje. Implementado por: Multimedia - Publicidad &amp; Sistemas - Arequipa - Perú 2012</div>
	</div>
	  <div class="clear"></div>
	</div>
</body>
</html>