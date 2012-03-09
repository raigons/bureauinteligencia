<?php

/**
 * @author cim
 */
interface Map {
   
   public function clear();

   public function containsKey($key);

   public function get($key);

   public function isEmpty();

   public function put($key, $newval);

   public function remove($key);

   public function size();

   /**
    * @return ArrayObject
    */
   public function values();
   
    /**
     * @return array 
     */
    public function keys();
}
?>
