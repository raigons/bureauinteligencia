<?
    if(!isset($_POST['fromAdmin'])){
        $repository = new DatacenterDao(Connection::connect());
        $service = new DatacenterService($repository);
        $statistic = new Statistic();
        
        $grouper = new DataGrouper();

        $controller = new DatacenterController($service, $statistic, $jsonResponse, $grouper, $factory);    
        $controller->setReport($report);
        
        $years = $_GET["ano"];
        if(isset($_GET[0]) && isset($_GET[1])){
            $g1 = $g2 = null;
            $dataParam = fillParams($_GET[0], $subgroup, $font, $type, $variety, $origin, $destiny, $g1);   
            $dataParam2 = fillParams($_GET[1], $subgroup, $font, $type, $variety, $origin, $destiny, $g2);
            $json = $controller->getDistinctGroupReport($dataParam,$dataParam2, $years);
            echo $json;
        }else{
            $subgroup = $font = $type = $variety = $origin = $destiny = null;
            $dataParam = fillParams($_GET, $subgroup, $font, $type, $variety, $origin, $destiny);
            $json = $controller->getReport($dataParam, $years);
            echo $json;
        }
    }
?>
<?
function fillParams($param, &$subgroup, &$font, &$type, &$variety, &$origin, &$destiny, &$array_group = null){
        $subgroup = $param['subgrupo']; 
        $font = $param['fonte'];
        $type = $param['tipo'];
        $variety = $param['variedade']; 
        $origin = $param['origem'];
        $destiny = $param['destino'];         
        $array = array("subgroup"=>$subgroup,"font"=>$font,"type"=>$type,"variety"=>$variety,"origin"=>$origin,"destiny"=>$destiny);
        $dataParam = new DataParam();
        $dataParam->setParams($array);
        return $dataParam;
}
?>