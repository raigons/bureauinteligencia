<?php
/**
 * Description of CountryMap
 *
 * @author Ramon
 */
class CountryMap {
    
    /**
     *
     * @var HashMap 
     */
    private $map;
    
    /**
     * @var HashMap 
     */
    private $map2;
    
    /**
     *
     * @var Map 
     */
    private $origin; 
    
    /**
     *
     * @var Map
     */
    private $destiny;
    
    public function CountryMap(){
        $this->map = new HashMap();
        $this->map2 = new HashMap();
        $this->origin = new HashMap();
        $this->destiny = new HashMap();
        
        $this->populateMap();
        $this->populateDestinyCountries();
    }
    
    private function populateMap(){
        $this->map->put("Outros", 7);
    }
   
    private function populateDestinyCountries(){
        $this->map2->put("Outros", 14);
    }
    
    public function getOuthersForOrigin(){
        return $this->map->get("Outros");
    }
    
    public function getOthersForDestiny(){
        return $this->map2->get("Outros");
    }
    
    public function addOrigins($origins){
        foreach($origins as $origin)
            $this->addOrigin ($origin);
    }
    
    public function addOrigin(Country $country){
        //$this->origin->put($country->name(), $country->id());
        $this->origin->put($country->name(), $country);
    }
    
    public function addDestinies($destinies){
        foreach($destinies as $destiny)
            $this->addDestiny($destiny);
    }
    
    public function addDestiny(Country $destiny){
        //$this->destiny->put($destiny->name(), $destiny->id());
        $this->destiny->put($destiny->name(), $destiny);
    }

    public function getOrigins(){
        return $this->origin;
    }
    
    public function getDestinies(){
        return $this->destiny;
    }
}
?>
