<?php
/**
 * Description of DatacenterHelper
 *
 * @author raigons
 */

class DatacenterHelper {
                   
    public static function pagination($page, $maxValuesPerPage, $totalValues){
        $link_type = "admin/datacenter/list/";
        echo GenericHandler::prevPage($page, $link_type);
        echo GenericHandler::pages($totalValues, $page, $maxValuesPerPage, $link_type);
        echo GenericHandler::nextPage($page, $maxValuesPerPage, $totalValues, $link_type);
    }
    
    public static function pageIndex($page, $maxValuesPerPage, $totalValues){
        $total_pages = ceil($totalValues/$maxValuesPerPage);
        echo "Página <strong>$page</strong> de <strong>$total_pages</strong>";
    }
          
    public static function linkEdit(Data $data, $textLink){
        $baseUrl = LinkController::getBaseURL();
        $idData = $data->getId();
        $link = "<a href='$baseUrl/admin/datacenter/dado/edit/$idData'>";
        $link .= $textLink;
        $link .= "</a>";
        echo $link;
    }
    
    /**
     * @return Data 
     */
    public static function data($data){
        return $data;
    }
}

?>
