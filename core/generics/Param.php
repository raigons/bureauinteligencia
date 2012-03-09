<?php

/**
 * Description of Param
 *
 * @author Ramon
 */
abstract class Param {
    
    private $id;
    
    private $name;
    
    public function Param($name = null, $id = null){
        $this->name = $name;
        $this->id = $id;
    }
    
    public function name(){
        return $this->name;
    }
    
    public function setId($id){
        $this->id = $id;
    }
    
    public function id(){
        return $this->id;
    }
    
    public function toArray(){
        $array = array();
        if($this->id != null)
            $array['id'] = $this->id();
        if($this->name != null)
            //$array['name'] = utf8_encode($this->name());
            $array['name'] = ($this->name());
        return $array;
    }
    
    public function toJson(){
        return json_encode($this->toArray());
    }
    
    public abstract function getType();
}

?>
