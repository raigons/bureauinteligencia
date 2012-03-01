<?php
    require_once 'build.php';
    require_once 'core/Datacenter/TableBuilder.php';
    require_once 'core/Datacenter/TableJsonBuilder.php';
?>
<?
    $years = $_GET['ano'];
    if(isset($_GET[0]) && isset($_GET[1])){
        $g1 = $g2 = null;
        $dataParam = fillParams($_GET[0], $subgroup, $font, $type, $variety, $origin, $destiny, $g1);    
        $dataParam2 = fillParams($_GET[1], $subgroup, $font, $type, $variety, $origin, $destiny, $g2);
        $json = $controller->getDistinctGroupsTable($dataParam,$dataParam2,$years);
        echo $json;
    }else{
        $subgroup = $font = $type = $variety = $origin = $destiny = null;
        $dataParam = fillParams($_GET, $subgroup, $font, $type, $variety, $origin, $destiny);
        $json = $controller->getTable($dataParam, $years);
        echo $json;
    }
?>