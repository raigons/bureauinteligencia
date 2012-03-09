<?php

/**
 * Description of GenericDao
 *
 * @author ramon
 */
class GenericDao {
    
    /**     
     * @var PDO 
     */
    private $session;

    public function GenericDao(PDO $session){
        $this->session = $session;
    }

    /**
     *
     * @return ArrayObject
     */
    public function getAreas(){
        return $this->getObject("area");
    }

    /**
     *
     * @param <type> $id
     * @return ArrayObject 
     */
    public function getSubareas($id){
        return $this->getObject("subarea", true, "area_id", $id);
    }

    /**
     * @return ArrayObject
     */
    public function getStates(){
        return $this->getObject("estados");
    }
    
    public function getCities($state){
        return $this->getObject("city", true, "estado", $state);
    }
    
    public function getActivities(){
        return $this->getObject("activities");
    }
    
    public function getPublicationType(){
        return $this->getObject("publication_type");
    }
    
    public function getGroups(){
        return $this->getObject("groups");
    }
    
    public function getSubgroups($groupId) {        
        return $this->getObject("subgroup", true, "group_id", $groupId);
    }
        
    public function getVarieties() {
        return $this->getObject("variety");
    }
    
    public function getCoffeTypes() {
        return $this->getObject("coffetype");
    }

    public function getOriginCountries() {
        return $this->getObject("country", true, "type_country", "origin");
    }
    
    public function getDestinyCountries() {
        return $this->getObject("country", true, "type_country", "destiny");
    }
    
    public function countryExists(Country $country, $typeCountry){
        $query = $this->session->prepare("SELECT * FROM country WHERE name = :name AND type_country = :type");
        $query->bindParam(":name", $country->name());
        $query->bindParam(":type", $typeCountry);
        $query->execute();        
        return $query->rowCount() > 0;
    }

    public function createCountry(Country $country, $typeCountry){
        if($this->countryExists($country, $typeCountry))
                throw new Exception("O país ".$country->name ()." já existe!");
        $sql = "INSERT INTO country (name, type_country) VALUES (:name, :type)";
        $query = $this->session->prepare($sql);
        $query->bindParam(":name", utf8_decode($country->name()));
        $query->bindParam(":type", $typeCountry);
        $query->execute();      
        return $query->rowCount() > 0;        
    }
    
    public function editCountry(Country $country) {
        $sql = "UPDATE country SET name = :name WHERE id = :id LIMIT 1";
        $query = $this->session->prepare($sql);
        $query->bindParam(":name", utf8_decode($country->name()));
        $query->bindParam(":id", $country->id());
        
        $query->execute();
        return $query->rowCount() > 0;
    }
    
    public function getCountry($id){
        $sql = "SELECT * FROM country WHERE id = :id LIMIT 1";
        $query = $this->session->prepare($sql);
        $query->bindParam(":id", $id);
        $query->execute();
        $country = $query->fetch(PDO::FETCH_ASSOC);
        if($query->rowCount() > 0){
           return new Country(utf8_encode($country['name']), $country['id']);
        }
        return null;
    }
    
    public function getCountryByName(Country $country,$type){
        $sql = "SELECT * FROM country WHERE name = :name AND type_country = :type LIMIT 1";
        $query = $this->session->prepare($sql);
        $query->bindParam(":name", utf8_decode($country->name()));
        $query->bindParam(":type", $type);
        
        $query->execute();
        $country = $query->fetch(PDO::FETCH_ASSOC);
        return new Country(utf8_encode($country['name']), $country['id']);        
    }
    
    public function getFonts() {
        return $this->getObject("font");
    }
    /**
     * @return ArrayObject
     */
    private function getObject($type, $where = false, $dependenceName = '', $dependenceValue = ''){
        $statement = "SELECT * FROM $type ";        
        if($where){
            $query = $this->buildSqlWithWhere($statement, $dependenceName, $dependenceValue);            
        }else{
            $statement .= "ORDER BY id ASC";
            $query = $this->session->prepare($statement);
        }
        $query->execute();
        return $this->returnObject($query, $type);
    }
    
    /**    
     * @param PDOStatement $query
     * @param type $type
     * @return ArrayObject 
     */
    private function returnObject(PDOStatement $query, $type){
       $response = new ArrayObject();
       if($query->rowCount() > 0){
           $result = $query->fetchAll(PDO::FETCH_ASSOC);
           foreach($result as $object){
               $response->append($this->buildObject($type, $object));
           }
       }       
       return $response;
    }
    
    /**     
     * @return PDOStatement 
     */
    private function buildSqlWithWhere($statement, $dependenceName, $dependenceValue){
        $statement .= $this->addWhereClause($dependenceName);        
        $query = $this->session->prepare($statement);
        $query->bindParam(":value", $dependenceValue);
        return $query;
    }
    
    private function addWhereClause($dependenceName){
        $sql = "WHERE $dependenceName = :value ";
        if($dependenceName == 'estado')
            $sql .= "ORDER BY nome ASC";
        elseif($dependenceName == 'area_id')
            $sql .= "ORDER BY name ASC";        
        else{
            $sql .= "ORDER BY id ASC";
        }
        return $sql;
    }
    
    private function buildObject($type, $object){        
        $name = utf8_encode($object['name']);
        $id = $object['id'];
        switch($type){
            case "area": return new Area($object['name'], $object['id']); break;
            case "subarea": return new SubArea($object['name'], $object['id']); break;
            case "estados": return new State($object['id'], $object['uf'], $object['nome']); break;
            case "city": return new City($object['nome'], $object['id']); break;
            case "activities": return new Activity($object['name'], $object['id']); break;
            case "publication_type": return new PublicationType($object['name'], $object['id']); break;
            
            case "groups": return new Group($name, $id); break;
            case "subgroup": return new Subgroup($name, $id); break;
            case "variety": return new Variety($name, $id); break;
            case "coffetype": return new CoffeType($name, $id); break;
            case "country": return new Country($name, $id); break;
            case "font": return new Font($name, $id); break;
        }
    }
}
?>
