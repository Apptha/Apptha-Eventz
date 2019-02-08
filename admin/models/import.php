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
defined('_JEXEC') or die();

jimport('joomla.application.component.model');
jimport('joomla.filesystem.file');


class AppthaeventzModelimport extends JModel {

   function getCategory(){          //To get the category for import csv,google calendar,facebook
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

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

   function getLocation(){      //To get the location for import csv
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $query = " SELECT a.location_id as id ,a.location_name as name FROM `#__em_locations` a ".
                 " LEFT JOIN `#__em_events` b ON a.event_id = b.id".
                 " WHERE b.published = '1' and a.location_name != '' group by a.location_name";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $locations = $db->loadObjectList();
        }
        

        array_unshift($locations, JHTML::_('select.option', '', ' '.JText::_('Create new location based on events').' ', 'id', 'name'));
        $locationSelect = JHTML::_('select.genericlist',$locations, 'location_id', 'class="inputbox" style="width:200px;"', 'id', 'name','');

        return $locationSelect;
   }

   function getGLocation(){         //To get the location for google calendar
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $query = " SELECT google_location FROM #__em_eventsettings WHERE id = '1' ";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $googLoc = $db->loadResult();
        }
        

        $query = " SELECT a.location_id as id ,a.location_name as name FROM `#__em_locations` a ".
                 " LEFT JOIN `#__em_events` b ON a.event_id = b.id".
                 " WHERE b.published = '1' and a.location_name != '' group by a.location_name";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $locations = $db->loadObjectList();
        }


        array_unshift($locations, JHTML::_('select.option', '', ' '.JText::_('Create new location based on events').' ', 'id', 'name'));
        $locationSelect = JHTML::_('select.genericlist',$locations, 'g_location_id', 'class="inputbox" style="width:200px;"', 'id', 'name',$googLoc);

        return $locationSelect;
   }

   function getFbLocation(){         //To get the location for Facebook events
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $query = " SELECT fb_location FROM #__em_eventsettings WHERE id = '1' ";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $fbLoc = $db->loadResult();
        }


        $g_location = $db->getEscaped(JRequest::getVar('g_location_id'));
        $query = " SELECT a.location_id as id ,a.location_name as name FROM `#__em_locations` a ".
                 " LEFT JOIN `#__em_events` b ON a.event_id = b.id".
                 " WHERE b.published = '1' and a.location_name != '' group by a.location_name";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $locations = $db->loadObjectList();
        }


        array_unshift($locations, JHTML::_('select.option', '', ' '.JText::_('Create new location based on events').' ', 'id', 'name'));
        $locationSelect = JHTML::_('select.genericlist',$locations, 'fb_location_id', 'class="inputbox" style="width:200px;"', 'id', 'name',$fbLoc);

        return $locationSelect;
   }


  function importnew(){ //function to import from csv
     $db=JFactory::getDbo();
     global $option, $mainframe;
     $mainframe = JFactory::getApplication();
     $category_id= JRequest::getInt('category_id','0', 'default');
     $location_id= JRequest::getInt('location_id','0', 'default');
            //get the csv file
            $file = $_FILES['csvimport']['tmp_name'];
            $handle = fopen($file,"r");

            //loop through the csv file and insert into database
            while(($row = fgetcsv($handle, 1000, ",",'"')) !== FALSE) {
                $fulldata[] = $row;
            }
 
            $fieldLog = array();
            $rowCnt = count($fulldata);
            for($k=1;$k<$rowCnt;$k++){
                if(count($fulldata[$k]) < 9 ){
                    $row = $k;
                    $fieldLog[] = $row;
                }
            }
            if(!empty($fieldLog)){
                $objErr = implode(",",$fieldLog);
                $errOut = 'Columns count mismatch in following rows : '.$objErr."<br/> Please upload valid file";
                $mainframe->redirect('index.php?option=com_appthaeventz&view=import&task=add',$errOut,'error');
            }

            if(filesize($file) <= 0){
                 $val = '1';
            }else{

                $errorLog = array();
                $errDate = array();
                $errStCount = array();
                $errEndCount = array();
                $errStartEndCount = array();

                $rowCnt = count($fulldata);
                for($k=1;$k<$rowCnt;$k++){

                    if( $fulldata[$k][0] != '' && $fulldata[$k][1] != '' && $fulldata[$k][2] != '' && ( $fulldata[$k][8] != '' || $location_id != '0' ) ){

                        if(!preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $fulldata[$k][1], $parts) ){
                           
                            //$errDate[] = 'Event Start date format is incorrect';
                            $errStCount[] = $k;
                        }
                        if(!preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2}) ([0-9]{2}):([0-9]{2}):([0-9]{2})$/", $fulldata[$k][2], $parts) ){
                           
                            //$errDate[] = 'Event End date format is incorrect';
                            $errEndCount[] = $k;
                        }
                        if( strtotime($fulldata[$k][1]) > strtotime($fulldata[$k][2]) ){
                            //$errDate[] = 'Event Start Date greater than Event End date';
                            $errStartEndCount[] = $k;
                        }

                    }else{
                        $errorLog[] = $k;
                    }


                    //Throwing errors
                    $objErrlog = '';
                    $objErrStart = '';
                    $objErrEnd = '';
                    $objErrBoth = '';

                    if(!empty($errorLog)){
                        $objErrlog = implode(",",$errorLog);//mandatory fields empty
                        $objError = 'Some mandatory fields are empty for following row(s) : '.$objErrlog;
                    }
                    if(!empty($errStCount)){
                        $objErrStart = implode(",",$errStCount);//Start date incorrect
                        $objSError = 'Event Start date format is incorrect for following row(s) : '.$objErrStart;
                    }
                    if(!empty($errEndCount)){
                        $objErrEnd = implode(",",$errEndCount);//End date incorrect
                        $objEndError = 'Event End date format is incorrect for following row(s) : '.$objErrEnd;
                    }
                    if(!empty($errStartEndCount)){
                        $objErrBoth = implode(",",$errStartEndCount);//Start date greater than End date
                        $objSEndError = 'Event Start Date greater than Event End date for following row(s) : '.$objErrBoth;
                    }
                //Checking and inserting in database
                if( $objErrlog != '' || $objErrStart != '' || $objErrEnd != '' || $objErrBoth != '' ){
                    $errorMessage = '';
                    if($objError != '')
                        $errorMessage .= $objError.'<br/>';
                    if($objSError != '')
                        $errorMessage .= $objSError.'<br/>';
                    if($objEndError != '')
                        $errorMessage .= $objEndError.'<br/>';
                    if($objSEndError != '')
                        $errorMessage .= $objSEndError.'<br/>';
                    $mainframe->redirect('index.php?option=com_appthaeventz&view=import&task=add',$errorMessage.'<br/>Please import the rows once again.','error');
                }else{
                    $db->setQuery("INSERT INTO `#__em_events` (event_name, start_date,end_date,description,category_id,published) VALUES
                        (
                            '".addslashes($fulldata[$k][0])."',
                            '".addslashes($fulldata[$k][1])."',
                            '".addslashes($fulldata[$k][2])."',
                            '".addslashes($fulldata[$k][3])."',
                            '".$category_id."',
                            '1'
                        )
                        ");
                        $db->query();
                        $eventId = $db->insertid();

                        $db->setQuery("INSERT INTO `#__em_contacts` (event_id, web,email,phone) VALUES
                            (
                                '".$eventId."',
                                '".addslashes($fulldata[$k][4])."',
                                '".addslashes($fulldata[$k][5])."',
                                '".addslashes($fulldata[$k][6])."'
                            )
                        ");
                        $db->query();

                        if($fulldata[$k][7] == ''){

                            if($location_id != '0'){
                                $query = " SELECT location_name FROM `#__em_locations`WHERE location_id = '$location_id'";
                                $db->setQuery($query);
                                $locationName = $db->loadResult();
                            }
                            $location = ($location_id != '0')?$locationName:'';
                        }else{
                            $location = addslashes($fulldata[$k][7]);
                        }

                        if($fulldata[$k][8] == ''){
                            $location_id= JRequest::getInt('location_id','0', 'default');
                            if($location_id != '0'){
                                $query = " SELECT address,latitude,longitude FROM `#__em_locations`WHERE location_id = '$location_id'";
                                $db->setQuery($query);
                                $locationAddress = $db->loadObject();
                            }
                            $locationAdd = ($location_id != '0')?$locationAddress->address:'';

                        }else{
                            $locationAdd = addslashes($fulldata[$k][8]);
                        }
                        //Getting lattitude and longitude
                       /* if($fulldata[$k][8] != ''){
                            $address = $fulldata[$k][8];

                            $url = "http://maps.google.com/maps/api/geocode/json?address=$address&sensor=true";
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    // return values as a string - not to std out
                            $get_google_content = curl_exec($ch);
                            curl_close($ch);

                            $get_google_content1 = json_decode($get_google_content);
                            $lat = $get_google_content1->results[0]->geometry->location->lat;
                            $long = $get_google_content1->results[0]->geometry->location->lng;
                        }else{
                            $lat = $locationAddress->latitude;
                            $long = $locationAddress->longitude;
                        }*/

                        $db->setQuery("INSERT INTO `#__em_locations` (event_id, location_name, address) VALUES
                            (
                                '".$eventId."',
                                '".$location."',
                                '".$locationAdd."'
                            )
                        ");
                        $db->query();
                }
                }
                    


            }
          
             return $val;
   
  }

  function saveGcalendar(){     //To save the google calendar credentials

    $db=JFactory::getDbo();

    $g_username = $db->getEscaped(JRequest::getVar('g_username'));
    $g_password = $db->getEscaped(JRequest::getVar('g_password'));
    $g_category = $db->getEscaped(JRequest::getVar('g_category_id'));
    $g_location = $db->getEscaped(JRequest::getVar('g_location_id'));

    //Insert the credentials
    $query = " UPDATE #__em_eventsettings SET google_username = '$g_username',google_password = '$g_password',google_category = '$g_category',google_location = '$g_location' WHERE id = '1' ";
    $db->setQuery($query);
    $db->query();
  }

  function getGcalendar(){      //To retrieve the google calendar credentials

    $db=JFactory::getDbo();

    //Get the credentials
    $query = " SELECT google_username,google_password,google_category,google_location FROM #__em_eventsettings WHERE id = '1' ";
    $db->setQuery($query);
    $googdetails = $db->loadObject();
    return $googdetails;
  }

  function saveFbevents(){      //To save the Facebook credentials

    $db=JFactory::getDbo();

    $fb_category = $db->getEscaped(JRequest::getVar('fb_category_id'));
    $fb_location = $db->getEscaped(JRequest::getVar('fb_location_id'));

    //Insert the credentials
    $query = " UPDATE #__em_eventsettings SET fb_category = '$fb_category',fb_location = '$fb_location' WHERE id = '1' ";
    $db->setQuery($query);
    $db->query();
  }

  function getFbevents(){       //To retrieve the Facebook credentials

    $db=JFactory::getDbo();

    //Get the credentials
    $query = " SELECT fb_appid,fb_secret,fb_category,fb_location FROM #__em_eventsettings WHERE id = '1' ";
    $db->setQuery($query);
    $fbdetails = $db->loadObject();
    return $fbdetails;
  }
  

}

?>
