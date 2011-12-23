<?php

/**
 * Description of DatacenterDao
 *
 * @author Ramon
 */

require_once 'DatacenterRepository.php';
class DatacenterDao implements DatacenterRepository{
   
    /**     
     * @var PDO
     */
    private $session;
    
    public function DatacenterDao(PDO $session){
        $this->session = $session;
    }
    
    /**     
     * @param type $subgroup
     * @param type $variety
     * @param type $type
     * @param type $origin
     * @param type $destiny
     * @param type $font
     * @return ArrayIterator 
     */
    public function getValuesWithSimpleFilter($subgroup, $variety, $type, $origin, $destiny, $font) {
        $sql = "SELECT value.*, subgroup.name AS subgroup, font.name AS font, coffetype.name AS type, ";
        $sql .= "variety.name AS variety, origin.name AS origin, destiny.name AS destiny ";
        $sql .= "FROM data_test value ";
        $sql .= "LEFT OUTER JOIN subgroup ON subgroup.id = value.subgroup_id ";
        $sql .= "LEFT OUTER JOIN font ON font.id = value.font_id ";
        $sql .= "LEFT OUTER JOIN coffetype ON coffetype.id = value.type_id ";
        $sql .= "LEFT OUTER JOIN variety ON variety.id = value.variety_id ";
        $sql .= "LEFT OUTER JOIN country origin ON origin.id = value.origin_id ";
        $sql .= "LEFT OUTER JOIN country destiny ON destiny.id = value.destiny_id";
        $sql .= " WHERE ";
        $sql .= "value.subgroup_id = :subgroup ";
        $sql .= "AND value.variety_id = :variety ";
        $sql .= "AND value.type_id = :type ";
        $sql .= "AND value.origin_id = :origin ";
        $sql .= "AND value.destiny_id = :destiny ";
        $sql .= "AND value.font_id = :font";        
        $query = $this->session->prepare($sql);
        $query->execute(array(":subgroup"=>$subgroup,":variety"=>$variety,":type"=>$type,
                        ":origin"=>$origin,":destiny"=>$destiny,":font"=>$font));
        return $this->buildSimpleObjects($query->fetchAll(PDO::FETCH_ASSOC));
    }
    
    /**
     *
     * @param array $values
     * @return ArrayIterator 
     */
    private function buildSimpleObjects(array $values){
        $list = new ArrayObject();
        print_r($values);
        foreach($values as $value){
            $subgroup = new Subgroup($value['subgroup']);
            $font = new Font($value['font']);
            $type = new CoffeType($value['type']);
            $variety = new Variety($value['variety']);
            $origin = new Country($value['origin']);
            $destiny = new Country($value['destiny']);
            $data = new Data($value['ano'], $subgroup, $font, $type, $variety, $origin, $destiny);
            $data->setValue($value['value']);
            $list->append($data);
        }
        return $list->getIterator();
    }
    
    public function getValuesFromAGroup($group) {
        
    }
    
    public function getValuesWithMultipleParamsSelected($subgroup, $variety, $type, $origin, $destiny, $font) {
        
    }
}

?>