<?php
/**
 *
 * @author raigons
 */
interface CacheBehavior {
    
    const countries = "countries";
    
    public function cache(CountryMap $map);
    
    /**
     * @return CountryMap 
     */
    public function getCountries();
    
    public function addCountry(CountryMap $map);
    
    public function destroyCache();
}

?>
