<?php
/**
 * Description of DatacenterController
 *
 * @author Ramon
 */
class DatacenterController {

    /**
     * @var DatacenterRepository 
     */
    private $datacenterRepository;
    
    /**
     * @var DatacenterService 
     */
    private $datacenterService;
    
    /**
     * @var Statistic 
     */
    private $statistic;
    
    /**
     *
     * @var JsonResponse 
     */
    private $jsonResponse;
    
    /**
     *
     * @var DataGrouper 
     */
    private $grouper;
    
    /**     
     * @var BuilderFactory 
     */
    private $builderFactory;
    
    private $asJson = false;
    
    /**
     * @var Report
     */
    private $report;
    
    public static $LIMIT_PER_PAGE = 15;
    
    public function DatacenterController(DatacenterService $service, Statistic $statistic, 
            JsonResponse $jsonResponse, DataGrouper $grouper, BuilderFactory $factory){
        $this->datacenterService = $service;
        $this->statistic = $statistic;
        $this->jsonResponse = $jsonResponse;
        $this->grouper = $grouper;        
        $this->builderFactory = $factory;
    }
    
    public function setReport(Report $report){
        $this->report = $report;
    }
    
    private function getBuilder($type) {
        return $this->builderFactory->getBuilder($type);
    }
    
    public function getValuesAsJson(){
        $this->asJson = true;
    }
    
    public function getReport(DataParam $dataParam, array $years){
        $asJson = $this->asJson;
        $this->asJson = false;
        $values = $this->getValues($dataParam,$years);
        $this->asJson = $asJson;
        return $this->report->getReport($values, $years, $this->grouper);
    }
    
    public function getDistinctGroupReport($g1, $g2, array $years){
        $values1 = $this->getValues($g1,$years);
        $values2 = $this->getValues($g2,$years);
        $values1 = $this->getListAsAnArrayObject($values1);
        $values2 = $this->getListAsAnArrayObject($values2);
        return $this->report->getDistinctGroupsReport($values1, $values2,$years,$this->grouper);
    }           
    
    //POST ://datacenter/save
    public function saveValues(ExcelInputFile $excelInputFile, $subgroup, $font, $destiny, $coffeType, $variety, $typeCountry = null){
        if(SessionAdmin::isLogged()){
            CacheCountry::setCacheBehavior(SessionAdmin::getCacheBehavior());
            try{
                if(!is_null($typeCountry)){
                    $countries = $excelInputFile->getValuesOfColumn(1);
                    $wrongSelected = $this->countriesSelectedAreCorrect($typeCountry, $countries);
                    if(sizeof($wrongSelected) > 0){
                        $nameOfWrongCountries = $this->countriesAsString($wrongSelected);
                        $message = "Os seguintes países presentes na planilha (".utf8_encode($nameOfWrongCountries).") não correspondem ao grupo que você selecionou";
                        throw new WrongFormatException($message);
                    }
                }
                if($this->datacenterService->insertValues($excelInputFile, $subgroup, $destiny, $coffeType, $variety, $font,$typeCountry)){
                    return $this->jsonResponse->response(true, "Dados inseridos com sucesso!")->serialize();
                }else{
                    $message = "Dados não inseridos. Verifique a possibilidade de já existirem dados referentes a esta planilha";
                    return $this->jsonResponse->response(true, $message)->serialize();
                }
            }catch(Exception $e){
                return $this->jsonResponse->response(false, $e->getMessage())->serialize();
            }
        }else{
            throw new LoginException();
        }
    }
    
    private function countriesAsString(array $countries){
        $string = "";
        $i = 0;
        foreach($countries as $country){
            if($i > 0){
                $string .= ", ";
            }
            $string .= $country;
            $i++;
        }
        return $string;
    }
    
    private function countriesSelectedAreCorrect($typeCountry, $countries){
        return (array_diff($countries, $this->getCountriesSelected($typeCountry)));
    }
    
    private function getCountriesSelected($typeCountry){
        if($typeCountry == 'destiny')
            return $this->destinyCountries ();
        elseif($typeCountry == 'origin')
            return $this->originCountries ();
    }
    
    private function originCountries(){
        //$countries = array("Brasil", "Colômbia", "Colombia", "Vietnã", "Vietna", "Guatemala", "Peru", "Quênia", "Quenia", "Outros");                
        $countries = CacheCountry::getCountries()->getOrigins()->keys();
        foreach($countries as $i => $country){
            $countries[$i] = utf8_decode($country);
        }
        return $countries;
    }
    
    private function destinyCountries(){        
        //$countries = array("EUA", "França", "Franca", "Alemanha", "Canadá", "Canada", "Itália", "Italia", "Japão", "Japao", "Outros");        
        $countries = CacheCountry::getCountries()->getDestinies()->keys();
        foreach($countries as $i => $country){
            $countries[$i] = utf8_decode($country);
        }
        return $countries;
    }
    
    public function listData($page) {
        return $this->datacenterService->getAllValues($this->calculateLimits($page), self::$LIMIT_PER_PAGE);
    }
    
    public function total(){
        return $this->datacenterService->gelTotalValues();
    }
    
    private function calculateLimits($page){
       $underLimit =  (self::$LIMIT_PER_PAGE*$page) - self::$LIMIT_PER_PAGE;
       return $underLimit;
    }
    
    private function getListAsAnArrayObject($list){
        if($list instanceof ArrayIterator){
            $list = new ArrayObject($list->getArrayCopy());
        }
        return $list;
    }
    
    public function getValues(DataParam $params,array $years = null) {
        if(!$params->anyValueIsArray()){
            return $this->getValuesWithSimpleParams($params,$years);
        }else{            
                return $this->getValuesWithMultipleParams($params,$years);
        }
    }
    
    public function getValuesWithSimpleParams(DataParam $params, array $years){
        $values = $this->datacenterService->getValuesWithSimpleFilter($params,$years);        
        if($this->asJson)
            return $this->toJson($values);        
        return $values;
    }
   
    public function getValuesWithMultipleParams(DataParam $params, array $years){
        $values = $this->datacenterService->getValuesFilteringWithMultipleParams($params,$years);
        if($this->asJson){
            if($values instanceof HashMap)
                return $this->hashMapFilteredToJSON($values);
            return $this->toJson($values);
        }
        return $values;        
    }
    
    public function calculateSampleStandardDeviation($group){
        $values = $this->datacenterRepository->getValuesFromAGroup($group);
        $standarDeviation = $this->statistic->sampleStandardDeviation($values);
        
        return $this->jsonResponse->response(true, null)
                ->addValue("value", $standarDeviation)
                ->withoutHeader()
                ->serialize();
    }
    
    private function hashMapFilteredToJSON(Map $map){        
        $json = '{';
        $listValues = $map->values()->getIterator();
        $n = 1;
        while($listValues->valid()){
            $json .= '"subgroup_'.$n++.'":';
            $json .= $this->toJson($listValues->current()->getIterator());
            if(($n-1) < $listValues->count())
                $json .= ',';
            $listValues->next();
        }
        $json .= '}';
        return $json;
    }
    
    private function toJson(ArrayIterator $list){
        $json = "[";
        while($list->valid()){
            $json .= $list->current()->toJson();
            $json .= ",";
            $list->next();
        }
        $json = substr($json, 0, -1);
        $json .= "]";
        return $json;
    }
}
?>
