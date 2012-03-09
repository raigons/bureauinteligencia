<?php
require_once 'Map.php';
/**
 * Description of HashMap
 *
 * @author cim
 */
class HashMap implements Map {
    private $map = array();

    public function HashMap(){
        $this->map = new ArrayObject($this->map);
    }

    public function clear(){
        $iterator = $this->map->getIterator();
        while ($iterator->valid()){
            $this->map->offsetUnset($iterator->key());
            $iterator->next();
        }
    }

    /**
     * @param <type> $key
     * @return bool
     */
    public function containsKey($key){
        return $this->map->offsetExists($key);
    }

    public function get($key){
        return $this->map->offsetGet($key);
    }

    public function isEmpty() {
        return $this->map->count() == 0;
    }
    
    public function put($index, $newval){
        $this->map->offsetSet($index, $newval);
    }
    
    public function remove($key){
        $this->map->offsetUnset($key);
    }
    
    /**     
     * @return int
     */
    public function size(){
        return $this->map->count();
    }

    /**
     * @return ArrayObject
     */
    public function values(){
        return new ArrayObject($this->map->getArrayCopy());
    }
    
    /**
     * @return array 
     */
    public function keys() {
        $keys = array();
        foreach($this->map as $key => $item){
            array_push($keys, $key);
        }
        return $keys;
    }

}
?>
