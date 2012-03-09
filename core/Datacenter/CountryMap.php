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
        $this->map->put("Brasil", 1);
        $this->map->put("Colombia",2);
        $this->map->put("Colômbia",2);
        $this->map->put("Vietnã", 3);
        $this->map->put("Vietna",3);
        $this->map->put("Guatemala",4);
        $this->map->put("Peru", 5);
        $this->map->put("Quênia", 6);
        $this->map->put("Quenia",6);
        $this->map->put("Outros", 7);
    }
   
    private function populateDestinyCountries(){
        $this->map2->put("EUA", 8);
        $this->map2->put("França", 9);
        $this->map2->put("Franca", 9);
        $this->map2->put("Alemanha", 10);
        $this->map2->put("Canadá", 11);
        $this->map2->put("Canada", 11);
        $this->map2->put("Itália", 12);
        $this->map2->put("Italia", 12);
        $this->map2->put("Japão", 13);
        $this->map2->put("Japao", 13);
        $this->map2->put("Outros", 14);
    }
    
    public function getCountryId($countryName){
        if($this->map->containsKey($countryName))
            return $this->map->get($countryName);
        if($this->map2->containsKey($countryName))
            return $this->map2->get ($countryName);
        return null;
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
