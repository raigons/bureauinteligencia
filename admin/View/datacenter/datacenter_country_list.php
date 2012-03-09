<?php
    require_once '../core/generics/Param.php';
    require_once '../core/generics/datacenter/Country.php';
    require_once '../core/generics/Controller.php';
    require_once '../core/generics/GenericDao.php';
    
    $dao = new GenericDao(Connection::connect());
    $controller = new Controller($dao);
    
    $page = $_REQUEST['page'];
    $type = $_REQUEST['type_country'];
    
    $list = new ArrayObject();
    $table_type = "";    
    CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
    //CacheCountry::cacheCountries();
    $countries = CacheCountry::getCountries();
    if($type == 'origin'){
        $table_type = "Origem";
        $list = $countries->getOrigins()->values();
        //$list = $controller->listOrigins();
    }elseif($type == 'destiny'){
        $table_type = "Destino";
        $list = $countries->getDestinies()->values();
        //$list = $controller->listDestinies();
    }
?>
<?if($list->count() >  0):?>
<div class="pagination">
</div>
<div id="list-results">
    <table class="list-publications">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th style='width: 100px;'>Exclusão</th>
                <th style='width: 100px;'>Edição</th>
            </tr>
        </thead>
        <tfoot>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tfoot>
        <tbody>
            <?foreach($list as $country):?>
            <tr>
                <!--<td><?//echo utf8_encode($country->name())?></td>-->
                <td><?echo $country->name()?></td>
                <td><?echo $table_type?></td>
                <td>
                    <a class="delete" href="<?echo LinkController::getBaseURL()?>/admin/datacenter/country/delete" id="<?echo $country->id();?>">
                        excluir
                    </a>
                </td>
                <td>
                    <a href="<?echo LinkController::getBaseURL()?>/admin/datacenter/country/edit/<?echo $country->id();?>">editar</a>
                </td>
            </tr>
            <?endforeach;?>
        </tbody>
    </table>
</div>
<div class="pagination">
    
</div>
<?else:?>
<strong>Nenhum país encontrado</strong>
<?endif;?>