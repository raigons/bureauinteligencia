<?php
    require_once 'requires_build.php';    
    require_once 'core/Datacenter/TableBuilder.php';
    require_once 'core/Datacenter/TableJsonBuilder.php';
    require_once 'core/Datacenter/TableStatisticsJsonBuilder.php';
    require_once $baseFileUrlDatacenter . '/ControllerBehavior/TableReport.php';
?>
<?
    $report = new TableReport($factory->getBuilder('statistic'),$jsonResponse);
    require_once 'build.php';    
?>