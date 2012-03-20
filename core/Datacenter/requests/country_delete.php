<?php
    require_once '../core/generics/Param.php';
    require_once '../core/generics/datacenter/Country.php';
    require_once '../core/generics/Controller.php';
    require_once '../core/generics/GenericDao.php'; 
    $id_country = $_POST['id'];
    $country = new Country();
    $country->setId($id_country);
    
    $json = new JsonResponse();
    $dao = new GenericDao(Connection::connect());
    $controller = new Controller($dao);
    try{
        if($controller->deleteCountry($country)){
            print_r($json->response(true, "País excluído com sucesso!")->serialize());  
        }else{
            print_r($json->response(false, "Falha ao excluir o país.")->serialize());  
        }
    }catch(Exception $err){
        print_r($json->response(false, $err->getMessage())->serialize());
    }
?>
