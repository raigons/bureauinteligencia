<?php
    require_once '../core/generics/Param.php';
    require_once '../core/generics/datacenter/Country.php';
    require_once '../core/generics/Controller.php';
    require_once '../core/generics/GenericDao.php';        
    
    $json = new JsonResponse();

    $dao = new GenericDao(Connection::connect());
    $controller = new Controller($dao);

    $country_id  = $_REQUEST['country'];
    
    $object = new Country($_POST['name']);
    $object->setId($country_id);
    if(isset($_POST['reexport']) && $_POST['reexport'] == true) $object->setReexport ();   
    if($controller->editCountry($object)){
        print_r($json->response(true, "País editado com sucesso!")->serialize());
    }else{
        print_r($json->response(false, "O país não foi editado!")->serialize());
    }
?>
