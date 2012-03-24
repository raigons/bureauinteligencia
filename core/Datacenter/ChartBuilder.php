<?php
//if(!defined(__DIR__)) define(__DIR__, dirname(__FILE__));
/**
 * Description of ChartBuilder
 *
 * @author Ramon
 */
class ChartBuilder implements Builder {

    /**
     *
     * @var XmlMultiSeriesCombinationColumnLine 
     */
    private $xml;
    
    private $path; 
    
    public function ChartBuilder(XmlMultiSeriesCombinationColumnLine $xml) {
        $this->xml = $xml;        
        //$this->path = __DIR__.'/xml/';
        $this->path = dirname(__FILE__).'/xml/';
    }
    
    public function build($mapWithGroupedValues, array $years) {        
        $this->years($years);
        $this->xml->addChartAttribute("showValues", "0");
        if(is_array($mapWithGroupedValues)){
            //if($mapWithGroupedValues[0]->containsKey(0) && $mapWithGroupedValues[1]->containsKey(0)){
            $this->setTitleToMultigroupsValuesWhenOneOfThenHasNoValue($mapWithGroupedValues);
            $this->setMultigroupsValues($mapWithGroupedValues);
            $this->setMultiChartValues($years, $mapWithGroupedValues); 
            //}
        }else{
            $title = $mapWithGroupedValues->get(0)->offsetGet(0)->getSubgroupName();            
            $this->xml->setChartTitle($title);
            $this->setValues($years, $mapWithGroupedValues);
        }
        $xml = $this->xml->buildXml($this->path."chart_".rand(1,28304908).".xml");
        return $xml;
    }
        
    
    private function years(array $years){
        foreach($this->arrayYears($years) as $year){
            $this->xml->addCategory($year);
        }
    }
    
    private function setMultigroupsValues(array $mapWithGroupedValues){
        if($mapWithGroupedValues[0]->containsKey(0))
            $this->xml->setPYAxisName($mapWithGroupedValues[0]->get(0)->offsetGet(0)->getSubgroupName());
        if($mapWithGroupedValues[1]->containsKey(0))
            $this->xml->setSYAxisName($mapWithGroupedValues[1]->get(0)->offsetGet(0)->getSubgroupName());        
    }
    
    private function setTitleToMultigroupsValuesWhenOneOfThenHasNoValue(array $mapWithGroupedValues){
        $group1 = $mapWithGroupedValues[0];
        $group2 = $mapWithGroupedValues[1];
        if($group1->containsKey(0) && !$group2->containsKey(0)){
            $title = $group1->get(0)->offsetGet(0)->getSubgroupName();            
            $this->xml->setChartTitle($title);
        }
        if(!$group1->containsKey(0) && $group2->containsKey(0)){
            $title = $group2->get(0)->offsetGet(0)->getSubgroupName();            
            $this->xml->setChartTitle($title);
        }
    }
    
    private function setMultiChartValues(array $years, array $mapWithGroupedValues){
        $i = 1;
        foreach($mapWithGroupedValues as $mapWithValues){
            if($i++ == 2)
                $this->setValues($years, $mapWithValues,true,true);
            else
                $this->setValues($years, $mapWithValues,true);
        }
    }
    
    private function setValues(array $years, Map $mapGroupedData,$dualY=false,$setToAxis=false){        
        $groups = $mapGroupedData->values();
        foreach($groups as $groupedData){
            $group = $groupedData;            
            $this->groupValuesByYear($years, $group, $dualY, $setToAxis);
        }
    }
  
    private function setValuesIfThereIsSomeToThisYear($value, $seriesName, $axis){
        if(!is_null($value)){
            $this->xml->setValue($value->getValue(), $seriesName);
        }else{
            $this->xml->setValue(0, $seriesName);            
        }
        if($axis) $this->xml->setLineToAnAxis ($seriesName, "S");
    }
    
    private function groupValuesByYear(array $years, ArrayObject $group, $dualY=false,$setToAxis=false){        
        $listYears = array();        
        for($yr = $years[0]; $yr <= $years[1]; $yr++){
            array_push($listYears, $yr);
        }
        foreach($listYears as $y){
            $foundValueOfThisYear = null;
            $seriesName = '';
            foreach($group as $data){
                if($dualY)
                    $seriesName = $data->getSubgroupName().'-'.$data->getOriginName().'-'.$data->getDestinyName();
                else
                    $seriesName = $data->getOriginName().'-'.$data->getDestinyName();
                if($data->getYear() == $y){
                    $foundValueOfThisYear = $data;
                }
            }            
            $this->setValuesIfThereIsSomeToThisYear($foundValueOfThisYear, $seriesName, $setToAxis);
        }
    }
    
    private function arrayYears(array $years){
        $listYears = array();
        for($y = $years[0]; $y <= $years[1]; $y++){
            array_push($listYears, $y);
        }        
        return $listYears;
    }
}

?>
