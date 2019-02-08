<?php
/**
 * @name          : Apptha Eventz
 * @version       : 1.0
 * @package       : apptha
 * @since         : Joomla 1.6
 * @subpackage    : Apptha Eventz.
 * @author        : Apptha - http://www.apptha.com
 * @copyright     : Copyright (C) 2012 Powered by Apptha
 * @license       : GNU/GPL http://www.gnu.org/licenses/gpl-3.0.html
 * @abstract      : Apptha Eventz.
 * @Creation Date : November 3 2012
 **/
//no direct access
defined('_JEXEC') or die();
jimport('joomla.application.component.model');
jimport('joomla.user.helper');

class appthaeventzModeleventlist extends JModel {
    public function eventList()
    {
        $db = JFactory::getDBO();
        global $mainframe;
        $id=JRequest::getVar('category_id','0');
        $tagname=JRequest::getVar('tag_name');
        $locid=JRequest::getVar('location_id','0');

        $query = " SELECT location_name FROM `#__em_locations` WHERE location_id = $locid";
        $db->setQuery($query);
        $location = $db->loadResult();

        $where = '';
        if($id != '0')
            $where = " AND FIND_IN_SET($id,a.`category_id` )";
        if($locid != '0')
            $where = " AND b.`location_name` LIKE '$location' ";
        if($tagname != '')
            $where = " AND c.`tag_name` LIKE '$tagname' ";

        $option = 'com_appthaeventz';
        // page navigation
        $page='';
        $limit = 5; //how many items to show per page

        $pageName = JRequest::getVar('page');
        if(isset($pageName) )
            $page = $pageName;
        if($page)
                $start = ($page - 1) * $limit; 	//first item to display on this page
        else
                $start = 0;

        $query =  "SELECT  a.`id` ".
                  " FROM  `#__em_events` as a ".
                  " LEFT JOIN `#__em_locations` as b ".
                  " ON b.event_id=a.id ".
                  " LEFT JOIN `#__em_tags` as c ".
                  " ON c.event_id=a.id ".
                  " WHERE a.`published` =1 ".
                  " $where GROUP BY a.id";
        $db->setQuery($query);
        $db->query();
        $total = $db->getNumRows();

        $query =  "SELECT  a.`id`,a.`event_name`,a.`description`,a.`start_date`,a.`end_date`,a.`image_name`,a.`category_id`,b.`location_id`,b.`location_name`,c.tag_name ".
                  " FROM  `#__em_events` as a ".
                  " LEFT JOIN `#__em_locations` as b ".
                  " ON b.event_id=a.id ".
                  " LEFT JOIN `#__em_tags` as c ".
                  " ON c.event_id=a.id ".
                  " WHERE a.`published` =1 $where GROUP BY a.id ORDER BY a.start_date ASC LIMIT $start, $limit ";
         $db->setQuery($query);
         $event_list = $db->loadObjectList();

         //Get dateformat in settings
         $query =  "SELECT `json_data_general` FROM `#__em_eventsettings` WHERE 1 ";
         $db->setQuery($query);
         $dateSet = $db->loadObject();

         $total_pages = $total;
         $pagination = $this->getPagination($total_pages,$page,$limit);

         return array('pageNav' => $pagination, 'event_list' => $event_list, 'dateSet'=>$dateSet);

    }

    public function categoriesList(){
        $db = JFactory::getDBO();

         $query =  "SELECT  a.`id`,a.`name` ".
                   " FROM `#__em_categories` as a ".
                   " WHERE a.`published` =1 ";
         $db->setQuery($query);
         $cat_list = $db->loadObjectList('id');
         return $cat_list;
    }

    function getPagination($total_pages,$page,$limit)
    {
        // How many adjacent pages should be shown on each side?
        $adjacents = 3;
        $targetpage = "index.php?option=com_appthaeventz&view=eventlist"; 	//your file name  (the name of this file)

        /* Setup page vars for display. */
        if ($page == 0) $page = 1;
        $prev = $page - 1;
        $next = $page + 1;
        $lastpage = ceil($total_pages/$limit);
        $lpm1 = $lastpage - 1;

        $pagination = '';//array();
        $pagination1 = "";
        $pagination2 = "";
        $pagination3 = "";
        if($lastpage > 1)
        {
            $pagination .= "<ul class=\"pagination\">";
            //previous button
            if ($page > 1)
                    $pagination.= "<li><a href=\"$targetpage&page=$prev\"  > « Previous </a></li>";
            else
                    $pagination.= "<li><a href=\"#\"  > « Previous </a></li>";

            //pages
            if ($lastpage < 7 + ($adjacents * 2))	//not enough pages to bother breaking it up
            {
                    for ($counter = 1; $counter <= $lastpage; $counter++)
                    {
                            if ($counter == $page)
                                    $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>"; 
                            else
                                    $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                    }
            }
            elseif($lastpage > 5 + ($adjacents * 2))	//enough pages to hide some
            {
                    //close to beginning; only hide later pages
                    if($page < 1 + ($adjacents * 2))
                    {
                            for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>";  
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                            $pagination.= "...";
                            $pagination.= "<li><a href=\"$targetpage&page=$lpm1\" >$lpm1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=$lastpage\" >$lastpage</a></li>";
                    }
                    //in middle; hide some front and some back
                    elseif($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2))
                    {
                            $pagination.= "<li><a href=\"$targetpage&page=1\" >1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=2\" >2</a></li>";
                            $pagination.= "...";
                            for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>"; 
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                            $pagination.= "...";
                            $pagination.= "<li><a href=\"$targetpage&page=$lpm1\" >$lpm1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=$lastpage\" >$lastpage</a></li>";
                    }
                    //close to end; only hide early pages
                    else
                    {
                            $pagination.= "<li><a href=\"$targetpage&page=1\" >1</a></li>";
                            $pagination.= "<li><a href=\"$targetpage&page=2\" >2</a></li>";
                            $pagination.= "...";
                            for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++)
                            {
                                    if ($counter == $page)
                                            $pagination.= "<li><a href=\"#\" class=\"current\"  > $counter </a></li>"; 
                                    else
                                            $pagination.= "<li><a href=\"$targetpage&page=$counter\" >$counter</a></li>";
                            }
                    }
            }

            if ($page < $counter - 1)
                    $pagination.= "<li><a href=\"$targetpage&page=$next\" > Next » </a></li>";
            else
                    $pagination.= "<li><a href=\"#\" class=\"current\"  > Next » </a></li>"; 
            $pagination.= "</ul>";
        }

        return $pagination;
    }
    

}

?>