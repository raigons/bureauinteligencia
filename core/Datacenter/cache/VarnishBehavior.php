<?php
/**
 * Description of VarnishBehavior
 *
 * @author raigons
 */
class VarnishBehavior {

    private $varnish; 
    
    public function VarnishBehavior(){
        $args = array(
            "host" => "::1",
            "port" => 6082,
            "secret" => "47fd6870-8858-44e7-9e64-10c880d5cf21",
            "timeout" => 300,
        );
        
        $this->varnish = new VarnishAdmin($args);
        $this->varnish->connect();
        $this->varnish->start();
    }
    
    
}

?>
