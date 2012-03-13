
<?
/**
 *
 * @param type $jsonResponse
 * @return DatacenterController 
 */
function requires($jsonResponse){
    require_once 'requires_build.php';
    $repository = new DatacenterDao(Connection::connect());

    CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
    $cache = CacheCountry::getCountries();

    $service = new DatacenterService($repository, $cache);//$countryMap);
    $statistic = new Statistic();
    $grouper = new DataGrouper();
    $controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory);     
    return $controller;
}
?>
<?php
    $json = new JsonResponse();    
    if($_POST['value'] != null && is_numeric($_POST['value'])){
        $id_data = $_REQUEST['id_data'];
        $_POST['fromAdmin'] = true;    
        $controller = requires($json);        
        $value = (float)$_POST['value'];
        $data = new Data(null, null, null, null, null, null, null);
        $data->setId($id_data);
        $data->setValue($value);        
        if($controller->editValue($data)){
            $message = "Valor alterado com sucesso"; 
            print_r($json->response(true, $message)->serialize());
        }else{
            $message = "Valor não foi alterado";
            print_r($json->response(false, $message)->serialize());
        }                    
    }else{
        print_r($json->response (false, "O valor deve ser um número válido!")->serialize ());        
    }
?>