<?php
$mc = new Memcached();
$mc->addServer("localhost", 11211);

if($mc->get('origin') == null){
    $countryMap = populateCountryMap();
    $mc->set("origin", $countryMap->getOrigins());
    $mc->set("destiny", $countryMap->getDestinies());
    $timer = 60/*seconds*/ * 2/*minutes*/;
    //$mc->flush($timer);
}
?>
<?
/**
 * @return \CountryMap 
 */
function populateCountryMap(){
    $dao = new GenericDao(Connection::connect());
    $controller = new Controller($dao);        
    $countryMap = new CountryMap();
    $countryMap->addOrigins($controller->listOrigins());
    $countryMap->addDestinies($controller->listDestinies());
    
    return $countryMap;
}
?>