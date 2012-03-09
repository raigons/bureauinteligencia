<?php
/**
 * Description of MemcachedBehavior
 *
 * @author raigons
 */
class MemcachedBehavior implements CacheBehavior{
    
    /**
     *
     * @var Memcached
     */
    private $memcached;
        
    
    public function MemcachedBehavior(){
    
    }
    
    public function cache(CountryMap $map) {
      $this->configMemcached();
      $this->memcached($map);
    }
    
    private function configMemcached(){        
        $this->memcached = new Memcached();
        $this->memcached->addServer("localhost", 11211);
    }
    
    private function memcached(CountryMap $map){
        if($this->memcached->get(CacheBehavior::countries) == null ){            
            $this->memcached->add(CacheBehavior::countries, $map);            
        }
    }    
    
    public function getCountries() {
        return $this->memcached->get(CacheBehavior::countries);
    }

    public function addCountry(CountryMap $map) {
        $this->memcached->flush();
        $this->memcached($map);
    }

    public function destroyCache() {
        //do nothing;
    }
}

?>
