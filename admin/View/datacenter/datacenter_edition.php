<?
$_POST['fromAdmin'] = true;
require_once '../util/JsonResponse.php';
require_once '../core/Datacenter/requests/requires_build.php';
?>
<?
$repository = new DatacenterDao(Connection::connect());
        
CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
$cache = CacheCountry::getCountries();

$service = new DatacenterService($repository, $cache);//$countryMap);
$statistic = new Statistic();
$grouper = new DataGrouper();
$controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory); 
?>
<?php
    $data_id = $_REQUEST['id_data'];
    $data = $controller->getSingleDataValue($data_id);
?>
<?if($data != null):?>
<div class="form-insert">
    <h2>Edição de dados</h2>
    <form title="country" action="<?echo LinkController::getBaseURL()?>/admin/datacenter/data/update/<?echo $data_id?>" method="post" id="form-data-value">
        <fieldset>
            <div class="fields-readonly">
                <div class="field right"><label>Ano:</label><?echo $data->getYear();?></div>
                <div class="field right"><label>Tipo:</label><?echo utf8_encode($data->getTypeName());?></div>
                <div class="field right"><label>Variedade:</label><?echo utf8_encode($data->getVarietyName());?></div>
                <div class="field right"><label>Origem:</label><?echo $data->getOriginName();?></div>
                <div class="field right"><label>Destino:</label><?echo $data->getDestinyName();?></div>
                <div class="field right"><label>Fonte:</label><?echo $data->getFontName();?></div>
            </div>
            <div class="field">
                <label>Valor:</label>
                <input type="text" value="<?echo $data->getValue()?>" id="data-value" />
            </div>
            <button type="submit" class="button-edit">Editar</button>
        </fieldset>
    </form>
</div>
<?else:?>
<strong>Dado inexistente no Banco de Dados</strong>
<?endif;?>