<?php
/**
 * Description of ExcelInputFile
 * @author Ramon
 */
class ExcelInputFile {

    /**
     * @var Spreadsheet_Excel_Reader 
     */
    private $spreadSheetReader;

    /**
     *
     * @var SpreadsheetValidator 
     */
    private $validator;
 
    public function ExcelInputFile(Spreadsheet_Excel_Reader $reader){
        $this->spreadSheetReader = $reader;
        $this->validator = new SpreadsheetValidator($this);
        $this->validSpreadsheetFormat();
    }
    
    public function setNewSpreadsheet(Spreadsheet_Excel_Reader $reader){
        $this->spreadSheetReader = $reader;
    }
    
    private function validSpreadsheetFormat(){        
        $message = "Esta planilha contem um formato inválido de acordo com os padrões definidos.";
        if(!$this->validator->spreadsheetHasAValidFormat())
            throw new WrongFormatException($message);
    }

    public function getValuesFromAllCountries() {
        $countries = $this->column(1);        
        $allCountriesValues = array();
        foreach($countries as $country){
            $valuesFromACountry = $this->getValuesFromACountry($country);
            $allCountriesValues[$country] = $valuesFromACountry[$country];
        }
        return $allCountriesValues;
    }
   
    public function getValuesFromACountry($country) {
        $values = array();
        $countryRowNumber = $this->getRowNumberOfACountry($country);
        $countryLine = $this->line($countryRowNumber);
        $associativeValueToYear = array();        
        $years = $this->getYears();
        foreach($years as $year){
            $colNumber = $this->getColumnNumberOfAYear($year);
            if(isset($countryLine[$colNumber]))
                $associativeValueToYear[$year] = $countryLine[$colNumber];            
        }        
        $values[$country] = $associativeValueToYear; 
        return $values;
    }  
    
    private function getRowNumberOfACountry($country){
        return $this->getRowOfAIntemInAColumn(1, $country);
    }
    
    private function getRowOfAIntemInAColumn($col, $item){
        $linesOfAColumn = $this->getValuesOfColumn($col);
        foreach($linesOfAColumn as $index => $lineItem){
            if($lineItem == $item)
                return ($index+2);
        }
    }
    
    public function getValuesFromAllYears() {
        $years = $this->getYears();
        $values = array();
        foreach($years as $year){                        
            $valuesOfAYear = $this->getValuesFromAYear($year);
            $values[$year] = $valuesOfAYear[$year];
        }
        return $values;
    }
    
    public function getValuesFromAYear($year) {
        $values = array();
        $columnNumber = $this->getColumnNumberOfAYear($year);
        $lines = $this->lines();        
        foreach($this->allTheLinesButTheFirst() as $line){
            if(isset($line[$columnNumber]))
                $values[$line[1]] = $line[$columnNumber];
        }        
        return array($year => $values);
    }
    
    private function getColumnNumberOfAYear($year){
        $years = $this->getYears();
        foreach($years as $col => $y){
            //+2 because the year array has had one element remove
            //and it starts with index 1
            if($y == $year) return ($col+2);
        }
    }
    
    public function getYears(){        
        $years = $this->firstRow();
        array_shift($years);
        return $years;
    }

    public function getValuesOfColumn($col) {                
        return $this->column($col);
    }
    
    public function firstRow(){
        return $this->line(1);
    }    

    private function column($col){                
        $column = array();
        $lines = $this->lines();
        for($line = 2; $line <= sizeof($lines); $line++ ){
            array_push($column, $lines[$line][$col]);
        }
        return $column;
    }
    
    private function line($numberLine){
        $lines = $this->lines();
        return $lines[$numberLine];
    }
    
    public function allTheLinesButTheFirst(){
        $lines = $this->lines();
        array_shift($lines);
        return $lines;
    }
    
    private function lines(){
        return $this->spreadSheetReader->sheets[0]['cells'];
    }
}
?>
