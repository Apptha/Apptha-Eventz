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

defined('_JEXEC') or die;

jimport('joomla.application.component.model');

class AppthaeventzModelevents extends JModel
{

    function getEvents()
     {
      global $option, $mainframe;
        $mainframe = JFactory::getApplication();
      
       $option = 'com_appthaeventz';
      // page navigation
      $limit = $mainframe->getUserStateFromRequest($option . '.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
       $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

 
       $filter_name = $mainframe->getUserStateFromRequest($option . '.filter_name', 'filter_name', '','string');
       $this->setState('filter_name', $filter_name);

       $filter_category = $mainframe->getUserStateFromRequest($option . '.filter_category', 'filter_category', '','string');
       $this->setState('filter_category', $filter_category);

       $filter_status = $mainframe->getUserStateFromRequest($option . '.filter_status', 'filter_status', '','string');
       $this->setState('filter_status', $filter_status);

      if($limitstart || $limit) {
      $query_limit = " LIMIT $limitstart,$limit";
      }
      else {
          $query_limit='';
      }

      //where condition
      $where=null;
       if($filter_status == '0' || $filter_status == '' )
            $where .= " published != '-2' ";
        if($filter_status == '1' )
            $where .= " published = '1' ";
        if($filter_status == '2' )
            $where .= " published = '0' ";
        if($filter_status == '3' )
            $where .= " published = '-2' ";

      if($filter_name):
      $where .=" AND e.event_name LIKE '%$filter_name%'";
      endif;
      if($filter_category):
      $where .=" AND FIND_IN_SET( '$filter_category', e.category_id )";
      endif;
       
      $sorting_f = $this->getState('filter_order', 'e.event_title');
      $sorting_o = $this->getState('filter_order_Dir', 'ASC');

    if($sorting_f)
        {
        $query_order= $sorting_f." ".$sorting_o;
        }
        else
        {
        $query_order= "e.id DESC";
        }

     // if($where):  $limitstart=0; endif;
      $db =  JFactory::getDBO();
//count total
      $query = "SELECT count(e.id)
                 From #__em_events as e
                 INNER JOIN #__em_locations as l
                 ON l.event_id=e.id
                 INNER JOIN #__em_contacts as con
                 ON con.event_id=e.id
                 WHERE ".$where;
       $db->setQuery($query);
       $total= $db->loadResult();
       jimport('joomla.html.pagination');
       $pageNav = new JPagination($total, $limitstart, $limit);      


        $query = "SELECT e.id,e.event_name,e.start_date,e.end_date,e.category_id,e.published,e.image_name,
                 l.location_name,l.location_id,
                 con.contact_name
                 From #__em_events as e
                 INNER JOIN #__em_locations as l
                 ON l.event_id=e.id
                 INNER JOIN #__em_contacts as con
                 ON con.event_id=e.id
                 WHERE ".$where . "ORDER BY $query_order $query_limit ";
        
      $db->setQuery($query);
      $result = $db->loadObjectList();
      echo $db->getErrorMsg();
      return array('pageNav' => $pageNav,'eventsList'=>$result);
     }

     function pubEvents($arrayIDs) {
        $db =  JFactory::getDBO();
        if ($arrayIDs['task'] == "publish") {
            $publish = 1;
        } else {
            $publish = 0;
        }
        $n = count($arrayIDs['cid']);

        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__em_events set published=" . $publish . " WHERE id=" . $arrayIDs['cid'][$i];
            $db->setQuery($query);
            $db->query();

        }
    }
    //sorting
     public function populateState() {
        $filter_order = JRequest::getCmd('filter_order');
        $filter_order_Dir = JRequest::getCmd('filter_order_Dir');

        $this->setState('filter_order', $filter_order);
        $this->setState('filter_order_Dir', $filter_order_Dir);



         parent::populateState();
    }
    function deleteEvent($arrayIDs)
    {
         $db =  JFactory::getDBO();
            $n = count($arrayIDs);

            for ($i = 0; $i < $n; $i++) {
          $query = "UPDATE #__em_events set published = '-2' WHERE id=" . $arrayIDs[$i];
            $db->setQuery($query);
            $db->query();

        }
    }

    public function AllCategory(){
        $db=JFactory::getDbo();

        $db->setQuery(
			'SELECT a.id AS value, a.name AS text, COUNT(DISTINCT b.id) AS level' .
			' FROM #__em_categories AS a' .
			' LEFT JOIN `#__em_categories` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
			' WHERE a.published = 1' .
			' GROUP BY a.id, a.name, a.lft, a.rgt' .
			' ORDER BY a.lft ASC'
		);
		$options = $db->loadObjectList();

		foreach ($options as &$option)
		{
			$option->text = str_repeat('- ', $option->level).$option->text;
		}


        return $options;
   }
 public function showCategory(){
        $db=JFactory::getDbo();
         $db->setQuery(	'SELECT a.id as cat_id, a.name' .
			' FROM #__em_categories AS a'.
			' WHERE a.published = 1' );
		$options = $db->loadAssocList('cat_id');
                
        return $options;
   }
 function exportCal($arrayIDs) {
        $db = JFactory::getDBO();
        $n = count($arrayIDs);
        if($n > 1){
            return 0;
        }else{
            $id = $arrayIDs[0];
            $query = "SELECT a.id,a.event_name,a.start_date,a.end_date,a.description,b.location_name,b.address FROM #__em_events a ".
                     " INNER JOIN #__em_locations b on b.event_id = a.id ".
                     " WHERE a.id = '$id' ";
            $db->setQuery($query);
            $objIcal = $db->loadObject();
            //Export as .ics
            $site = JURI::root();
$importCal = 'BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//'.$site.'//NONSGML v1.0//EN
METHOD:PUBLISH
BEGIN:VEVENT
UID:"'.md5(uniqid(mt_rand(), true)).'@'.$site.'"
SUMMARY:'. $objIcal->event_name .'
DESCRIPTION:'. addslashes(strip_tags($objIcal->description)) .'
DTSTAMP:'. date('Ymd\THis\Z') .'
DTSTART:'. date('Ymd\THis\Z',strtotime($objIcal->start_date) ) .'
DTEND:'. date('Ymd\THis\Z',strtotime($objIcal->end_date) ) .'
LOCATION:'. $objIcal->location_name.'('.$objIcal->address.')'.'
END:VEVENT
END:VCALENDAR';
            header('Content-type: text/calendar');
            header('Content-Disposition: attachment; filename="events.ics"');
            header('Pragma: no-cache');
            header('Expires: 0');
            echo $importCal;
            exit;
        }
    }

     function exportCsvfmt($arrayIDs) {
        $db = JFactory::getDBO();

        if(count($arrayIDs)){
            $cids = implode( ',', $arrayIDs );
            $query = "SELECT a.id,a.event_name,a.start_date,a.end_date,a.description,b.location_name,b.address,c.web,c.email,c.phone FROM #__em_events a ".
                     " INNER JOIN #__em_locations b on b.event_id = a.id ".
                     " INNER JOIN #__em_contacts c on c.event_id = a.id ".
                     " WHERE a.id IN ($cids) ";
            $db->setQuery($query);
            $objIcal = $db->loadObjectList();

            $filename = 'events.csv';
            header('Content-type: application/ms-excel');
            header('Content-Disposition: attachment; filename='.$filename);
            header("Pragma: no-cache");
            header("Expires: 0");

            $result .= '"Event Name","Event Start date","Event End date","Event Description","Event URL","Event Email","Event Phone","Location name","Location address"';
            $result .= "\n";
            foreach($objIcal as $row):
                $desc = strip_tags($row->description);
                $result .= '"'.$row->event_name.'",'.'"'.$row->start_date.'",'.'"'.$row->end_date.'",'.'"'.$desc.'",'.'"'.$row->web.'",'.'"'.$row->email.'",'.'"'.$row->phone.'",'.'"'.$row->location_name.'",'.'"'.$row->address.'"';
                $result .= "\n";
            endforeach;
            echo $result;
            exit;
        }

    }

}