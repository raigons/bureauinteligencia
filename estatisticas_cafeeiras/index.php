<?  session_start();?>
<?
    require_once '../core/Link/LinkController.php';
    require_once '../util/Session.php';
    require_once '../core/User/User.php';
    $baseUrl = LinkController::getBaseURL();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Bureau do Café</title>
    <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/reset.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/main.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/text.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/grid.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="styles/style.css" media="screen" />
    <link rel="stylesheet" type="text/css" href="styles/spreadsheet.css" media="screen" />
    <link type="text/css" href="styles/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	
    <!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>-->
</head>
<body>

<div id="header" class="container_12">

	<div class="grid_12 topo" id="topo-banner">

	                    <!-- #################### -->
	                    <!-- BLOCO ESQUERDA       -->
	                    <!-- #################### -->
	                    <div class="grid_7 box-logo omega" id="box-logo">
	                        <div id="logo">
	                           <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" 
                                           codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" 
                                           width="110" height="110" align="middle">
	                                <param name="allowScriptAccess" value="sameDomain" />
	                                <param name="allowFullScreen" value="false" />
	                                <param name="movie" value="<?echo $baseUrl;?>/images/logo.swf" />
                                        <param name="quality" value="high" />
                                        <param name="wmode" value="transparent" />
                                        <param name="bgcolor" value="#990000" />
                                        <embed src="<?echo $baseUrl;?>/images/logo.swf" 
                                               quality="high" 
                                               wmode="transparent" 
                                               bgcolor="#990000" 
                                               width="110" 
                                               height="110" 
                                               name="logo" 
                                               align="middle" 
                                               allowScriptAccess="sameDomain" 
                                               allowFullScreen="false" 
                                               type="application/x-shockwave-flash" 
                                               pluginspage="http://www.adobe.com/go/getflashplayer" />
	                            </object>
	                        </div>

	                        <a href="<?echo $baseUrl;?>/">
                                    <img src="<?echo $baseUrl;?>/images/escrito-bureau.gif" style="float:left;" id="escrito" />
                                </a>
	                    </div>

	                    <!-- #################### -->
	                    <!-- BLOCO DIREITA        -->
	                    <!-- #################### -->
	                    <div class="grid_5 omega" id="banner-on-right">

	                        <!-- #################### -->
	                        <!-- MENU SUPERIOR        -->
	                        <!-- #################### -->
	                        <div class="grid_12 menu-superior" style="float:right;">
	                            <a href="">Quem Somos</a> | <a href="">Ajuda</a> | <a href="">Contato</a> | <a href="?restaurar">Restaurar</a> |
                                    <?if(!Session::isLogged()):?>
                                    <a href="<?echo $baseUrl;?>/cadastro" class="l1">Cadastre-se</a>
                                    <?endif;?>
                                </div>
	                        <div class="clear"></div>
	                    </div>
	                <!-- Fim Topo -->
	                </div>
	
	<div class="clear"></div>
</div>

<div id="menu_selecoes" class="container_12">
	
	<div id="grupo" class="selector">
		<div class="header">Grupo</div>
		<div class="options">
			<ul>
				
			</ul>
		</div>
	</div>
	
	<div id="subgrupo" class="selector">
		<div class="header">Sub-Grupo</div>
		<div class="options">
			
		</div>
	</div>
	
	<div id="tipo" class="selector">
		<div class="header">Tipo</div>
		<div class="model" style="display:none;">
			<ul>
				
			</ul>
		</div>
		<div class="options">
			<ul>
				
			</ul>
		</div>
	</div>

	<div id="variedade" class="selector">
		<div class="header">Variedade</div>
		<div class="model" style="display:none;">
			<ul>
				
			</ul>
		</div>
		<div class="options">
			<ul>
				
			</ul>
		</div>
	</div>

	<div id="origem" class="selector">
		<div class="header">Origem</div>
		<div class="model" style="display:none;">
			<ul>
				
			</ul>
		</div>
		<div class="options">
			<ul>
				
			</ul>
		</div>
	</div>
	
	<div id="destino" class="selector">
		<div class="header">Destino</div>
		<div class="model" style="display:none;">
			<ul>
				
			</ul>
		</div>
		<div class="options">
			<ul>
				
			</ul>
		</div>
	</div>
	
	<div id="fonte" class="selector">
		<div class="header">Fonte</div>
		<div class="model" style="display:none;">
                                        <ul id="fonte_grupo_1">				
                                        </ul>
                                        <ul id="fonte_grupo_2">                                            
                                        </ul>
                                        <ul id="fonte_grupo_3">                                            
                                        </ul>
                                        <ul id="fonte_grupo_4">                                            
                                        </ul>                    
		</div>                
		<div class="options">
			
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div id="periodo" class="selector">
		<div class="header">Período</div>
		<div class="options">
			<label>De: <select name="de" id="de"></select></label>
			<label>Até: <select name="ate" id="ate"></select></label>
		</div>
	</div>
	
</div>

<div id="confirm" class="container_12">
	<a class="confirmar" href="#confirm">Confirmar</a>
	
	<div class="clear"></div>
</div>

<div id="dados" class="container_12">
	
	<div id="tabs">
		<ul>
			<li id="tab-1" class="tab">
				<div>
					<span class="tab-left"></span>
					<span class="title">Tabela</span>
				</div>
			</li>
			<li id="tab-2" class="tab sel">
				<div>
					<span class="tab-left"></span>
					<span class="title">Gráfico</span>
				</div>
			</li>
			<li id="tab-3" class="tab">
				<div>
					<span class="tab-left"></span>
					<span class="title">Excel</span>
				</div>
			</li>
			<li id="tab-4" class="tab">
				<div>
					<span class="tab-left"></span>
					<span class="title">Estatísticas</span>
				</div>
			</li>
		</ul>
	</div>
	
	<div id="content-1" class="tabcontent">
		
	</div>
	
	<div id="content-2" class="tabcontent">
		
	</div>
	
	<div id="content-3" class="tabcontent">

	</div>
	
	<div id="content-4" class="tabcontent">
		
	</div>
	<?if(!Session::isLogged()):?>
        <center>
            <strong style="font-size: 16px">Você deve fazer login para acessar o sistema.
                <br />
                Se ainda não possui um usuário, 
                <a style="cursor: pointer; text-decoration: underline; color: #9CD168" 
                   href="<?echo $baseUrl?>/cadastro">clique aqui</a> 
                para se cadastrar.
            </strong>
        </center>
        <?endif;?>
</div>
    <script src="js/jquery-1.7.1.min.js" type="text/javascript"></script>
    <script src="js/jquery-ui.min.js" type="text/javascript"></script>
    <script src="js/slimScroll.min.js" type="text/javascript"></script>    
    <script src="js/FusionCharts.js" type="text/javascript"></script>
    <script src="js/chart_builder.js" type="text/javascript"></script>
    <?if(Session::isLogged()):?>
    <script src="js/bureau.js" type="text/javascript"></script>
    <?endif;?>
</body>
</html>
