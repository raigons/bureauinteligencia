<?php
//require_once '../../util/excel/writer/ExcelWriter.php';
//require_once '../../util/excel/writer/ExcelOutputFile.php';
/**
 * Description of TableExcelBuilder
 *
 * @author Ramon
 */
class TableExcelBuilder extends TableBuilder{
    
    /**     
     * @var DataToExcel 
     */
    private $dataToExcel;
    
    /**     
     * @var ExcelWriter 
     */
    private $writter;
    
    /**
     * @var Map 
     */
    private $valuesToSpreedsheet;
    
    private $titles = array();
    
    private $valuesOfARow = array();
    
    private $spreadSheetName;
        
    public function TableExcelBuilder(DataToExcel $dataToExcel, $name = "Planilha.xls"){
        $this->valuesToSpreedsheet = new HashMap();
        $this->dataToExcel = $dataToExcel;
        if($name != "Planilha.xls")
            $this->spreadSheetName = "spreadsheet/Planilha_".$name.".xls";
        else
            $this->spreadSheetName = "spreadsheet/".$name;
    }
        
    public function build($mapWithGroupedValues, array $years) {    
        if(is_array($mapWithGroupedValues)){
            $spreadSheet = new ExcelOutputFile($this->dataToExcel, $this->spreadSheetName);
            $this->writeOnWorkSheet($mapWithGroupedValues[0], $spreadSheet, $years);
            $this->dataToExcel->clearValues(); 
            $this->writeOnWorkSheet($mapWithGroupedValues[1], $spreadSheet, $years, 1);
            return $spreadSheet->getSpreadSheetFilename();
        }else{
            parent::titles($years);
            parent::addValuesToARow($mapWithGroupedValues, $years);
            $spreadSheet = new ExcelOutputFile($this->dataToExcel, $this->spreadSheetName);
            $spreadSheet->buildSpreadSheet();
            return $spreadSheet->getSpreadSheetFilename();  
        }
    }
    
    private function writeOnWorkSheet(Map $groupedValues, ExcelOutputFile $spreadSheet, array $years, $index = 0){
        if(!$groupedValues->isEmpty()){
            parent::titles($years);
            parent::addValuesToARow($groupedValues, $years);
            $spreadSheet->setNewDataToExcel($this->dataToExcel);
            $nameGroup = $groupedValues->get(0)->offsetGet(0)->getSubgroupName();
            $spreadSheet->buildSpreadSheet($nameGroup, $index);            
        }
    }
    
    public function getTitles(){
        return $this->dataToExcel->getLineWithTitles();
    }
    
    public function getValues() {
        return $this->dataToExcel->getAllLinesValues();
    }

    protected function setDefinedTitles(array $definedTitles, array $years = null) {
        $this->titles = $definedTitles;             
        parent::years($years);        
        $this->dataToExcel->setTitles($this->titles);
    }
        
    protected function config() {}
   
    protected function initTable($i = 1) {}

    protected function buildTitleYears($year) {        
        array_push($this->titles, $year);        
    }
    
    protected function setProperties(ArrayObject $group, Data $data, array $years) {
        array_push($this->valuesOfARow, $data->getTypeName());
        array_push($this->valuesOfARow, utf8_encode($data->getVarietyName()));        
        array_push($this->valuesOfARow, $data->getOriginName());
        array_push($this->valuesOfARow, $data->getDestinyName());
        array_push($this->valuesOfARow, $data->getFontName());
        parent::listValuesVerifyingTheYearOfThat($group, $years);
        $this->dataToExcel->setValues($this->valuesOfARow);
        $this->valuesOfARow = array();
    }
    
    protected function addValueIfThereIsSomeValueToThisYear($foundValueOfThisYear) {
        if(!is_null($foundValueOfThisYear)){
            array_push($this->valuesOfARow, $foundValueOfThisYear);
        }else{
            array_push($this->valuesOfARow,"-");
        }
    }
}

?>
