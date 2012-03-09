<?php
/**
 * Description of CacheCountry
 *
 * @author raigons
 */
require_once 'CacheBehavior.php';
require_once 'MemcachedBehavior.php';
require_once 'SessionBehavior.php';
class CacheCountry {    
    /**
     * @var CacheBehavior 
     */
    private static $cache;
    
    public static function setCacheBehavior(CacheBehavior $cache){
        self::$cache = $cache;
    }
    
    public static function cacheCountries(){
        if(self::$cache == null) 
            die('Antes de chamar o método "cacheCountries" você deve setar qual tipo de cache vai usar através do método "setCacheBehavior"');
        self::$cache->cache(self::populateCountryMap());
    }
    
    /**
     *
     * @return CountryMap
     */
    public static function getCountries(){
        return self::$cache->getCountries();
    }
    
    public static function addCountry(Country $country, $typeCountry){
        $countryMap = self::$cache->getCountries();
        if($typeCountry == 'origin')
            $countryMap->addOrigin ($country);
        elseif($typeCountry == 'destiny')
            $countryMap->addOrigin ($country);
        self::$cache->addCountry($countryMap);
    }
    
    public static function destroyCache(){
        self::$cache->destroyCache();
    }
    
    /**
    * @return \CountryMap 
    */
    private static function populateCountryMap(){
        $dao = new GenericDao(Connection::connect());
        $controller = new Controller($dao);
        $countryMap = new CountryMap();
        $countryMap->addOrigins($controller->listOrigins());
        $countryMap->addDestinies($controller->listDestinies());

        return $countryMap;
    }    
}
?>
