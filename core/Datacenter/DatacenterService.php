<?php
/**
 * Description of DatacenterService
 *
 * @author Ramon
 */
class DatacenterService {
    
    /**
     * @var DatacenterRepository
     */
    private $repository;

    /**    
     * @var CountryMap
     */
    private $countryMap;
    
    /**
     * @var Statistic 
     */
    private $statistic;

    public function DatacenterService(DatacenterRepository $repository, CountryMap $countryMap = null, Statistic $statistic = null){
        $this->repository = $repository;     
        $this->countryMap = $countryMap;
        $this->statistic = $statistic;
    }
    
    public function insertValues(ExcelInputFile $excelInputFile, $subgroup, $destiny, $type, $variety, $font, $typeCountry = null) {
        $countries = $excelInputFile->getValuesOfColumn(1);        
        $dataToSave = new ArrayObject();
        foreach($countries as $country){
            if($typeCountry == 'origin'){
                //$origin = $this->countryMap->getCountryId($country);
                $origin = CacheCountry::getCountries()->getOrigins()->get(utf8_encode($country))->id();
                if(is_null($origin)){
                    $origin = $this->countryMap->getOuthersForOrigin();
                }
                $destiny = 0;
            }elseif($typeCountry == 'destiny'){
                //$destiny = $this->countryMap->getCountryId($country);
                $destiny = CacheCountry::getCountries()->getDestinies()->get(utf8_encode($country))->id();
                if(is_null($destiny))
                    $destiny = $this->countryMap->getOthersForDestiny();
                $origin = 0;
            }else{
                //$origin = $this->countryMap->getCountryId($country);
                $origin = CacheCountry::getCountries()->getOrigins()->get(utf8_encode($country))->id();
            }
            $dataParam = new DataParam($subgroup,$font,$type,$variety,$origin,$destiny);
            $dataOfCurrentCountry = $this->getValuesWithSimpleFilter($dataParam);
            $yearsToInsert = $this->insertValuesIfACountryDoesNotHaveItStoredYet($dataOfCurrentCountry, $excelInputFile, $country);
            if($yearsToInsert->count() > 0){         
                $this->getDataToSave($dataToSave,$yearsToInsert, $excelInputFile, $country, $subgroup, $font, $type, $variety, $origin, $destiny);
            }
        }
        return $this->repository->save($dataToSave);
    }
 
    private function getDataToSave(ArrayObject &$dataToInsert, ArrayObject $years, ExcelInputFile $inputFile, $country, $subgroup, $font, $type, $variety, $origin, $destiny){
        $valuesFromACountry = $inputFile->getValuesFromACountry($country);
        foreach($years as $year){
            $value = $valuesFromACountry[$country][$year];
            $value = (float) str_replace(",","",$value);
            $data = $this->buildDataToInsert($year, $subgroup, $font, $type, $variety, $origin, $destiny);
            $data->setValue($value);
            $dataToInsert->append($data);
        }
    }
 
    private function buildDataToInsert($year, $subgroupId, $fontId, $typeId, $varietyId, $originId, $destinyId){        
        $font = new Font(); $font->setId($fontId);
        $type = new CoffeType(); $type->setId($typeId);
        $variety = new Variety; $variety->setId($varietyId);
        $origin = new Country(); $origin->setId($originId); 
        $destiny = new Country(); $destiny->setId($destinyId);
        $subgroup = new Subgroup(); $subgroup->setId($subgroupId);        
        return new Data($year, $subgroup, $font, $type, $variety, $origin, $destiny);
    }
    
    private function insertValuesIfACountryDoesNotHaveItStoredYet(ArrayIterator $dataValues, ExcelInputFile $excelInputFile, $country){        
        $yearsToInsert = new ArrayObject();
        $i = 0;
        foreach($excelInputFile->getYears() as $year){
            if(!$this->thisYearIsAlreadyStored($dataValues, $year)){
                $yearsToInsert->append($year);
            }
        }
        return $yearsToInsert;
    }
    
    private function thisYearIsAlreadyStored(ArrayIterator $dataValues, $year){
        while($dataValues->valid()){
            if($dataValues->current()->getYear() == $year){
                return true;
            }
            $dataValues->next();
        }
        return false;
    }
    
    /**     
     * Este método faz a consultas no repositório que são filtradas apenas por um item de cada variável
     * @param DataParam $params
     * @param array $years
     * @return ArrayIterator 
     */
    public function getValuesWithSimpleFilter(DataParam $params, array $years = null){
        if($params->theOptionAllHasBeenSelected()){
            return $this->repository->getValuesWhenTheOptionAllWasSelected($params->getSubgroup(),$params->getVariety(), $params->getType(), $params->getOrigin(), $params->getDestiny(), $params->getFont(), $years);
        }
        return $this->repository->getValuesWithSimpleFilter($params,$years);        
    }

    /**
     *
     * @param DataParam $params    
     * @param array $years
     * @return ArrayIterator
     */
    public function getValuesFilteringWithMultipleParams(DataParam $params, array $years){
        if(is_array($params->getSubgroup())){       
            $subgroup = $params->getSubgroup();
            $paramsForList1 = $params; 
            $paramsForList1->setSubgroup($subgroup[0]);            
            $listValues1 = $this->repository->getValuesWithMultipleParamsSelected($paramsForList1,$years);
            $paramsForList2 = $params;
            $paramsForList2->setSubgroup($subgroup[1]);
            $listValues2 = $this->repository->getValuesWithMultipleParamsSelected($paramsForList2,$years);
            $map = new HashMap();
            $map->put(0, $listValues1);
            $map->put(1, $listValues2);
            return $map;
        }else{
            if($params->theOptionAllHasBeenSelected()){                          
                $listValues = $this->repository->getValuesWhenTheOptionAllWasSelected($params->getSubgroup(),$params->getVariety(), $params->getType(), $params->getOrigin(), $params->getDestiny(), $params->getFont(), $years);
            }else{                
                $listValues = $this->repository->getValuesWithMultipleParamsSelected($params,$years);        
            }
            return $listValues;           
        }        
    }

    public function getAllValues($underLimit, $upperLimit) {
        return $this->repository->getAllValues($underLimit, $upperLimit);
    }
    
    public function gelTotalValues(){
        return $this->repository->totalValues();
    }
}
?>
