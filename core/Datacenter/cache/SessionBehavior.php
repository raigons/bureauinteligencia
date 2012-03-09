<?php
/**
 * Description of SessionBehavior
 *
 * @author raigons
 */
define("start", "start");
class SessionBehavior implements CacheBehavior{
    
    private $timeout = 10; 
        
    public function SessionBehavior(){
        if(!isset($_SESSION[CacheBehavior::countries])){
            $_SESSION[CacheBehavior::countries] = null;            
        }
    }
    
    public  function cache(CountryMap $map) {
        $this->sessionCache($map);
    }
    
    private function sessionCache(CountryMap $map){
        if($this->sessionExpired() || (empty($_SESSION[CacheBehavior::countries]) && $_SESSION[CacheBehavior::countries] == null)){
            $_SESSION[CacheBehavior::countries] = serialize($map);
            $_SESSION[start] = time();
        }
    }

    private function sessionExpired(){
        return !isset($_SESSION[start]) || time() - $_SESSION[start] >= $this->timeout;
    }
    
    public function getCountries() {
            return unserialize($_SESSION[CacheBehavior::countries]);
    }

    public function addCountry(CountryMap $map) {
        $_SESSION[CacheBehavior::countries] = null;
        $this->sessionCache($map);
    }

    public function destroyCache() {
        $_SESSION[CacheBehavior::countries] = null;
        $_SESSION[start] = null;        
    }
}

?>
