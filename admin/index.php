<?session_start();?>
<?ob_start();?>
<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
require_once "../core/Link/LinkController.php";
require_once "../core/DataBase/Connection.php";
require_once "../core/Exceptions/FileNotFoundException.php";
require_once "../core/Exceptions/LoginException.php";
require_once "../util/Maps/HashMap.php";

//cache files
require_once '../core/Datacenter/CountryMap.php';
//require_once '../core/Datacenter/cache/CacheBehavior.php';
//require_once '../core/Datacenter/cache/MemcachedBehavior.php';
//require_once '../core/Datacenter/cache/SessionBehavior.php';
require_once '../core/Datacenter/cache/CacheCountry.php';

require_once "../util/SessionAdmin.php";
require_once '../util/Services_JSON.php';
require_once '../util/StringManager.php';
require_once "../core/User/User.php";
require_once '../core/GenericHandler.php';
require_once 'AdminUser.php';

if(empty ($_POST) && empty ($_GET) && LinkController::link() != 'login-fail/empty' && LinkController::link() != 'login-fail'){
    $baseUrl = LinkController::getBaseURL();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <title>Bureau de Intelig&ecirc;ncia do Caf&eacute;</title>
        <!--<link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/ui/jquery-ui-1.8.8.custom.css" media="screen" />-->
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/ui/jquery-ui-1.8.15.custom.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/reset.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/text.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/grid.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/main.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/login.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/admin.css" media="screen" />
        <link rel="stylesheet" type="text/css" href="<?echo $baseUrl?>/assets/css/admin-table.css" media="screen" />

        <!--<script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery.min.js"></script>-->
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery-1.6.2.min.js"></script>
        <!--<script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery-ui-1.8.8.custom.min.js"></script>-->
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery-ui-1.8.15.custom.min.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery.form.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery.cooquery.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/jquery.cycle.min.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/events_admin.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/admin_ajax.js"></script>
        <script type="text/javascript" src="<?echo $baseUrl?>/assets/js/admin.js"></script>
        <!--<script type="text/javascript" src="<?echo $baseUrl?>/admin/View/tinymce/jscripts/tiny_mce/tiny_mce_gzip.js"></script>-->
        <script type="text/javascript" src="<?echo $baseUrl?>/admin/View/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
    </head>
    <body>
        <div class="container_12">
            <?if(SessionAdmin::isLogged()):?>
            <div class="user-header">
                <span class="adminlogged-name"><?echo SessionAdmin::getAdminName()?></span>
                <span><a href="<?echo LinkController::getBaseURL()?>/admin/logoutAdmin">sair</a></span>
            </div>
            <?endif?>

            <h1 style="width: 100%" class="titleadmin">Administrador Bureau Inteligência</h1>
            <div class="grid_12 menu-apps">
                <ul>
                    <li><a href="<?echo $baseUrl?>/admin/publicacoes" id="lnk-publicacoes">Publica&ccedil;&otilde;es</a></li>
                    <li><a href="<?echo $baseUrl?>/admin/noticias" id="lnk-noticias">Not&iacute;cias</a></li>
                    <li><a href="" id="lnk-cotacoes">Cota&ccedil;&otilde;es</a></li>
                    <li><a href="<?echo $baseUrl?>/admin/videos" id="lnk-videoteca">Videoteca</a></li>
                    <li><a href="" id="lnk-metereologia">Meteorologia</a></li>
                    <li class="ultimo"><a href="<?echo $baseUrl?>/admin/datacenter" id="lnk-datacenter">Data Center</a></li>
                </ul>
            <!-- Fim Menu Apps -->
            </div>
            <div style="clear:both"></div>
            <div id="aplicacoes">
                <div id="loading">
                    <img alt="loading" src="<?echo LinkController::getBaseURL()?>/images/ajax-loader.gif" />
                </div>
                <div id="content-middle">
                    <?php                    
                        try{
                            include LinkController::routeAdminPage();
                        }catch(LoginException $err){                            
                            include $err->loginPage();
                        }catch(Exception $err){
                            echo $err->getMessage();
                        }
                    ?>                    
                </div>
            </div>
            <div class="clear"></div>
            <div class="grid_12 rodape">
                <p>Bureau de Intelig&ecirc;ncia do Caf&eacute;</p>
            </div>
        </div>
    </body>
</html>
<?
}else{    
    if(LinkController::link() == 'login-fail'){
        $_GET['loginfail'] = true;
        include 'login_error.php';
    }elseif(LinkController::link() == 'login-fail/empty'){
        $_GET['empty'] = true;
        include 'login_error.php';
    }else{        
        try{
            require_once "../util/RequestsPatterns.php";
            require_once "../util/JsonResponse.php";
            require_once '../util/SessionAdmin.php';
            require_once 'AdminUser.php';
            //if(LinkController::isRequestToInsertPublication())
                //SessionAdmin::login(new AdminUser("ramonox", "ramonoxido"));            
            include LinkController::restAdmin();
        }catch(Exception $err){
            if(LinkController::isRequestToInsertPublication()){
                $message = $err->getMessage();
                echo '{"status": "'.false.'", "message":"'.$message.'"}';
                print_r($_POST);
            }else{
                $jsonResponse = new JsonResponse();
                print_r($jsonResponse->response(FALSE, $err->getMessage())->serialize());
            }
        }

    }
}
?>