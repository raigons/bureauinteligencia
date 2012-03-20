<?php
/**
 * Description of Controller
 *
 * @author ramon
 */
class Controller {

    /**
     *
     * @var GenericDao
     */
    private $dao;

    public function Controller(GenericDao $dao){
        $this->dao = $dao;
    }

    
    public function areas(){
        $areas = $this->dao->getAreas();
        if($areas->count() > 0){
            return $this->returnJson($areas);
        }
        $json = new JsonResponse();
        return $json->response(false, "Nenhuma área encontrada!")->serialize();
    }

    
    public function subareas($id){        
        $subareas = $this->dao->getSubareas($id);        
        if($subareas->count() > 0){
            return $this->returnJson($subareas);
        }
        $json = new JsonResponse();
        return $json-response(false, 'Nenhuma subárea encontrada')->serialize();
    }

    public function states(){
        $states = $this->dao->getStates();        
        return $this->returnJson($states);
    }
    
    public function cities($stateId){
        $cities = $this->dao->getCities($stateId);
        return $this->returnJson($cities);
    }
    
    public function activities(){
        $activities = $this->dao->getActivities();
        return $this->returnJson($activities);
    }   
    
    public function publicationTypes(){
        $publicationTypes = $this->dao->getPublicationType();
        return $this->returnJson($publicationTypes);
    }
    
    public function getTypeToDatacenter($type, $id = null){
        switch($type){
            case 'groups':    return $this->groups();           break;
            case 'subgroup':  return $this->subgroups($id);     break;
            case 'variety':   return $this->varieties();        break;
            case 'coffetype': return $this->coffeTypes();       break;
            case 'origin':    return $this->origincountries();  break;
            case 'destiny':   return $this->destinycountries(); break;
            case 'font':      return $this->fonts($id);         break;
        }
    }
    
    public function groups(){
        $groups = $this->dao->getGroups();
        return $this->returnJson($groups);  
    }
    
    public function subgroups($groupId) {
        $subgroups = $this->dao->getSubgroups($groupId);
        return $this->returnJson($subgroups);
    }
    
    public function varieties() {
        $varieties = $this->dao->getVarieties();
        return $this->returnJson($varieties);
    }
    
    public function coffeTypes() {
        $coffeTypes = $this->dao->getCoffeTypes();
        return $this->returnJson($coffeTypes);
    }
    
    public function getCountry($country_id) {
        return $this->dao->getCountry($country_id);
    }
    
    public function getCountryByName(Country $country, $type){
        return $this->dao->getCountryByName($country, $type);
    }
    
    public function editCountry(Country $object) {
        return $this->dao->editCountry($object);
    }
    
    public function deleteCountry(Country $country){
        if(!$this->dao->thisCountryCanBeDeleted($country)){
            throw new Exception("Este país não pode ser excluído pois possui dados relacionados a ele.");
        }
         return $this->dao->deleteCountry($country);
    }
    
    public function listOrigins(){
      return $this->dao->getOriginCountries();
    }
    
    public function listDestinies(){
        return $this->dao->getDestinyCountries();
    }

    public function createNewOriginCountry(Country $country){
        return $this->dao->createCountry($country, "origin");
    }
    
    public function createNewDestinyCountry(Country $country){
        return $this->dao->createCountry($country, "destiny");
    }
        
    public function origincountries() {
        $countries = $this->dao->getOriginCountries();
        return $this->returnJson($countries);
    }

    public function destinycountries() {
        $countries = $this->dao->getDestinyCountries();
        return $this->returnJson($countries);
    }
    
    public function fonts($group = null) {        
        $fonts = $this->dao->getFonts();        
        if($group != null)
            $fonts = $this->filterFontsByGroup ($group, $fonts);
        return $this->returnJson($fonts);
    }
    
    /**     
     * @param type $groupId
     * @param ArrayObject $fonts
     * @return ArrayObject 
     */
    private function filterFontsByGroup($groupId, ArrayObject $fonts){
        $group = new Group();
        $group->setId($groupId);
        return $group->filterFontsByGroup($fonts);
    }
    
    private function returnJson(ArrayObject $listResults){
        $json = '[';
        if($listResults->count() > 0){
            foreach($listResults as $result){
                $json .= $result->toJson();
                $json .= ',';
            }
            $json = substr($json, 0, -1);            
        }
        $json .= ']';        
        return $json;
    }
}
?>
