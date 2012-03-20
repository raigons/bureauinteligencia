<?php
/**
 * Description of OriginCountry
 *
 * @author Ramon
 */
class Country extends Param{
    
    private $reexport = false;
    
    public function setReexport(){
        $this->reexport = true;
    }
    
    public  function isReexportCountry(){
        return $this->reexport == true;
    }
    
    public function toArray(){
        $array = parent::toArray();
        $array['reexport'] = $this->reexport;
        return $array;
    }
    
    public function getType() {
        return "country";
    }
}

?>
