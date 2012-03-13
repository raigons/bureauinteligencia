<?php
/**
 *
 * @author Ramon
 */
interface DatacenterRepository {

    const ALL = "all";

    public function editValue(Data $data) ;
    
    /**
     * @return Data 
     */
    public function getSingleDataValue($id);

    public function totalValues();
    
    /**
     * @return ArrayIterator
     */
    //public function getValuesWithSimpleFilter($subgroup, $variety, $type, $origin, $destiny, $font, $year = null);    
    public function getValuesWithSimpleFilter($params, $years = null);
    
    /**
     * @return ArrayIterator
     */
    public function getValuesWithMultipleParamsSelected($params, $year = null);
    
    /**
     * @return boolean
     */
    public function save(ArrayObject $dataList);
    
    public function getValuesWhenTheOptionAllWasSelected($sg, $variety, $type, $origin, $destiny, $font, $year);
    
    /**
     * @return ArrayIterator
     */
    public function getValuesFromAGroup($group);

    /**,
     * @return ArrayObject
     */
    public function getAllValues($underLimit, $maxValues) ;
}

?>
