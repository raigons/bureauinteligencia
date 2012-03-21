<?php
    $_POST['fromAdmin'] = true;    
    require_once '../util/JsonResponse.php';
    require_once '../core/Datacenter/requests/requires_build.php';
    require_once '../core/GenericHandler.php';
    require_once '../core/Datacenter/requests/DatacenterHelper.php';
    require_once '../core/generics/Controller.php';
    require_once '../core/generics/GenericDao.php';
    require_once '../core/generics/Param.php';
    require_once '../core/generics/datacenter/Group.php';
    require_once '../core/generics/datacenter/Subgroup.php';
    $page = 1;
    if(isset($_REQUEST['page']))
        $page = $_REQUEST['page'];
?>
<?
        $session = Connection::connect();
        $repository = new DatacenterDao($session);
        $service = new DatacenterService($repository, new CountryMap());
        $statistic = new Statistic();
        $grouper = new DataGrouper();
        $controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory); 
?>
<?
    if(isset($_REQUEST['subgroup'])){
        $data_values = $controller->listData($page, $_REQUEST['subgroup']);
        $total = $controller->total($_REQUEST['subgroup']);
        DatacenterHelper::setLinkPaginationWithFilter($_REQUEST['subgroup']);
    }else{
        $data_values = $controller->listData($page);
        $total = $controller->total();
    }    
    $genericController = new Controller(new GenericDao($session));        
?>
<?
    $list_subgroups = array();
    $groups = json_decode($genericController->groups());    
    foreach($groups as $group){
        array_push($list_subgroups, json_decode($genericController->subgroups($group->id)));
    }
?>
<strong>Filtragem de dados</strong> (selecione o grupo que deseja vizualizar os dados)
<select id="subgroup_filter">
    <option value="">Todos</option>
    <?foreach($list_subgroups as $subgroup_of_group):?>
        <?foreach($subgroup_of_group as $subgroup):?>
            <?if(isset($_REQUEST['subgroup']) && $_REQUEST['subgroup'] == $subgroup->id):?>
                <option selected="selected" value="<?echo $subgroup->id?>"><?echo $subgroup->name?></option>
            <?else:?>
                <option value="<?echo $subgroup->id?>"><?echo $subgroup->name?></option>
            <?endif;?>        
        <?endforeach;?>   
    <?endforeach;?>        
</select>
<br /><br />
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
                <th>Edição</th>
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
                <td><?DatacenterHelper::linkEdit($data, "Editar");?></td>
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