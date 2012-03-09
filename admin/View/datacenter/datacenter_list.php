<?php
    $_POST['fromAdmin'] = true;    
    require_once '../util/JsonResponse.php';
    require_once '../core/Datacenter/requests/requires_build.php';
    require_once '../core/GenericHandler.php';
    require_once '../core/Datacenter/requests/DatacenterHelper.php';
    $page = 1;
    if(isset($_REQUEST['page']))
        $page = $_REQUEST['page'];
?>
<?
        $repository = new DatacenterDao(Connection::connect());
        $service = new DatacenterService($repository, new CountryMap());
        $statistic = new Statistic();
        $grouper = new DataGrouper();
        $controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory); 
?>
<?
    $data_values = $controller->listData($page);
    $total = $controller->total();
?>
<?if($data_values->count()>0):?>
<div class="pagination">
    <?DatacenterHelper::pagination($page, DatacenterController::$LIMIT_PER_PAGE, $total);?>
</div>
<?endif;?>
<div id="list-results">
    <?if($data_values->count() > 0):?>
    <table class="list-publications">
        <thead>
            <tr>
                <th>Subgrupo</th>
                <th>Ano</th>
                <th>Tipo</th>
                <th>Variedade</th>
                <th>País</th>
                <th>País</th>
                <th>Fonte</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <td><strong>Mostrando <?echo $data_values->count()?> valores de <?echo $total?></strong></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td><?DatacenterHelper::pageIndex($page, DatacenterController::$LIMIT_PER_PAGE, $total)?></td>
            </tr>
        </tfoot>
        <tbody>
            <?foreach($data_values as $data):?>
            <?$data = DatacenterHelper::data($data);?>
            <tr>
                <td><?echo $data->getSubgroupName();?></td>
                <td><?echo $data->getYear();?></td>
                <td><?echo utf8_encode($data->getTypeName());?></td>
                <td><?echo utf8_encode($data->getVarietyName());?></td>
                <td><?echo $data->getOriginName();?></td>
                <td><?echo $data->getDestinyName();?></td>
                <td><?echo $data->getFontName();?></td>
                <td><?echo $data->getValue()?></td>
            </tr>
            <?endforeach;?>
        </tbody>
    </table>
    <?else:?>
    <strong>Ainda não existem informações armazenadas no Banco de Dados</strong>
    <?endif;?>
</div>
<?if($data_values->count() > 0):?>
<div class="pagination">
    <?DatacenterHelper::pagination($page, DatacenterController::$LIMIT_PER_PAGE, $total);?>
</div>
<?endif;?>