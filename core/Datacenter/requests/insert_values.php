<?php    
    $jsonResponse = new JsonResponse();
    $_POST['fromAdmin'] = true;
    //require_once 'build.php';
    require_once 'requires_build.php';
if(RequestsPatterns::postParamsSetted('subgroup', 'font', 'coffetype', 'variety', 'destiny')){
    if(RequestsPatterns::postParamsSent('subgroup', 'font', 'coffetype', 'variety', 'destiny')){
        require_once '../core/Exceptions/WrongTypeException.php';
        require_once '../core/Exceptions/WrongFormatException.php';
        require_once '../core/Datacenter/CountryMap.php';        
        require_once '../util/excel/reader/ExcelInputFile.php';
        require_once '../util/excel/reader/SpreadsheetValidator.php';
        require_once '../util/excel/reader/excel_reader2.php';        
        $file = $_FILES['Planilha']['tmp_name'];
        
        $subgroup = $_POST['subgroup'];
        $font = $_POST['font'];
        $coffeType = $_POST['coffetype'];
        $variety = $_POST['variety'];
        
        if($variety == "none") $variety = 0;
        $destiny = $_POST['destiny'];
        $origin = $_POST['origin'];
        
        $typeCountry = null;        
        
        if(isset($_POST['typeCountry'])){
            $typeCountry = $_POST['typeCountry'];
        }
        
        if(!insertingValuesForInternationalTrade($subgroup)){
            $destiny = 0;
            $origin = 0;
        }

        $repository = new DatacenterDao(Connection::connect());
        
        CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
        $cache = CacheCountry::getCountries();
        
        $service = new DatacenterService($repository, $cache);//$countryMap);
        $statistic = new Statistic();
        $grouper = new DataGrouper();
        $controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory); 
                
        $reader = new Spreadsheet_Excel_Reader($_FILES['Planilha']['tmp_name']);        
        try{
            $inputFile = new ExcelInputFile($reader);
            if(insertingValuesForInternationalTrade($subgroup)){
                //$typeCountry = 'origin';
                $response = $controller->saveValues($inputFile, $subgroup, $font, $origin, $destiny, $coffeType, $variety,$typeCountry,true);
            }else{
                $response = $controller->saveValues($inputFile, $subgroup, $font, $origin, $destiny, $coffeType, $variety,$typeCountry);
            }            
            print_r($response);
        }catch(WrongFormatException $exception){
            print_r($jsonResponse->response(false, $exception->getMessage())->withoutHeader()->serialize());
        }catch(Exception $exception){
            print_r($jsonResponse->response(false, $exception->getMessage())->withoutHeader()->serialize());
        }
    }else{
        print_r($jsonResponse->response(false, "Todos os campos devem ser preenchidos e/ou marcados.")->withoutHeader()->serialize());
    }
}else{
    print_r($jsonResponse->response(false, "Parâmetros não configurados corretamente.")->withoutHeader()->serialize());    
}
?>
<?
function insertingValuesForInternationalTrade($subgroup){
   $subgroups = array(1,2,3,4,5,6);
   return (in_array($subgroup, $subgroups));
}
?>