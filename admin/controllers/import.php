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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');


class AppthaeventzControllerimport extends JController
{
   
    function add($cachable = false, $urlparams = false) // Function to add a new category
    {
        $view =  $this->getView('import');
        if ($model =  $this->getModel('import'))
        {
            $view->setModel($model, true);
        }
        $view->setLayout('import');
        $view->display();
    }

    function apply($cachable = false, $urlparams = false) // Function to store and stay on the same page till we click on save button[Apply]
    {

        $model =  $this->getModel('import');
        $model->saveGcalendar();
        $model->saveFbevents();
        
        $link='index.php?option=com_appthaeventz&view=import&task=add';
        $this->setRedirect($link, 'Settings saved!');
       
    }

    function cancel($cachable = false, $urlparams = false) // Function to cancel some operation
    {
        $this->setRedirect('index.php?option=com_appthaeventz&view=dashboard');
    }

    function csvimport($cachable = false, $urlparams = false) // Function to import from csv
    {
        $model = $this->getModel('import');
        $objRes = $model->importnew();
        //echo $objRes;die;
        if($objRes == '1'){
            $this->setRedirect('index.php?option=com_appthaeventz&view=import&task=add','Uploaded file is empty','error');
        }else{
            $this->setRedirect('index.php?option=com_appthaeventz&view=import&task=add','File imported successfully');
        }
     }

    function googlecal()            //Function to save the google calendar events
    {
        $app =& JFactory::getApplication();
        $db=JFactory::getDbo();
        $query = " SELECT google_username,google_password FROM #__em_eventsettings WHERE id = '1' ";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $googLoc = $db->loadObject();
        }

        $username = $googLoc->google_username;
        $password = $googLoc->google_password;

        if (empty($username) || empty($password)) $app->redirect('index.php?option=com_appthaeventz&view=import&task=add','Credentials are empty');

        require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_appthaeventz'.DS.'helpers'.DS.'google.calendar.php');
        $googleCalendar = new EMGoogleCalendar($username,$password);
        $response = $googleCalendar->parse();
        $errors = $googleCalendar->getErrors();

        if (!empty($errors)) $app->redirect('index.php?option=com_appthaeventz&view=import&task=add',implode("<br/>",$errors),'error');

        $message = $response ? JText::sprintf('Added Google event(s) successfully ',$response) : 'No Google events';
        $app->redirect('index.php?option=com_appthaeventz&view=import&task=add',$message);

    }

    function fbevents()             //Function to save the facebook events
    {
            $db = JFactory::getDBO();
            $app = JFactory::getApplication();
            
            require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_appthaeventz'.DS.'helpers'.DS.'facebook'.DS.'facebook.php');

            $query = " SELECT fb_appid,fb_secret,fb_token,fb_category,fb_location FROM #__em_eventsettings WHERE id = '1' ";
            $db->setQuery($query);
            //check whether any error in query
            if (!$db->query()) {
                JError::raiseError(500, $db->getErrorMsg());
                    return false;
            } else {
               $fbDetails = $db->loadObject();
            }

            $appid = $fbDetails->fb_appid;
            $secret = $fbDetails->fb_secret;
            $token = $fbDetails->fb_token;

            if (empty($token))
                    $app->redirect('index.php?option=com_appthaeventz&view=import&task=add','No Facebook connection');

            $category_id = JRequest::getVar('fb_category_id');
            $location_id = JRequest::getVar('fb_location_id');

            //To get location name if created event dont have location
            $locationName = '';
            if($location_id != ''){
                $query = " SELECT name FROM #__em_location WHERE id = '$location_id' ";
                $db->setQuery($query);
                $locationName = $db->loadResult();
            }

            $facebook = new Facebook(array('appId'  => $appid, 'secret' => $secret, 'cookie' => true));
            $attachment =  array('access_token' => $token,'limit' => 100);

            $container = array();

            try
            {
                    $user	= $facebook->api('/me', 'GET', $attachment);
                    $events	= $facebook->api('/me/events', 'GET', $attachment);

                    if (!empty($events) && !empty($events['data']))
                    {
                            foreach ($events['data'] as $event)
                            {
                                    $objResult = $facebook->api($event['id'], 'GET', $attachment);
                                    if (!empty($objResult)){
                                        $fevent = new stdClass();
                                        $fevent->id = $objResult['id'];
                                        $fevent->EventName = $objResult['name'];
                                        $fevent->EventDescription = $objResult['description'];
                                        $fevent->EventStartDate = isset($objResult['start_time']) ? strtotime($objResult['start_time']) : time();
                                        $fevent->EventEndDate = isset($objResult['end_time']) ? strtotime($objResult['end_time']) : time() + 7200;
                                        $fevent->EventHost = isset($objResult['owner']['name']) ? $objResult['owner']['name'] : $user['name'];
                                        $fevent->Location = isset($objResult['location']) ? $objResult['location'] : $locationName;
                                        $fevent->LocationStreet = isset($objResult['venue']['street']) ? $objResult['venue']['street'] : '';
                                        $fevent->LocationCity = isset($objResult['venue']['city']) ? $objResult['venue']['city'] : '';
                                        $fevent->LocationState = isset($objResult['venue']['state']) ? $objResult['venue']['state'] : '';
                                        $fevent->LocationCountry = isset($objResult['venue']['country']) ? $objResult['venue']['country'] : '';
                                        $fevent->LocationLat = isset($objResult['venue']['latitude']) ? $objResult['venue']['latitude'] : '';
                                        $fevent->LocationLon = isset($objResult['venue']['longitude']) ? $objResult['venue']['longitude'] : '';

                                        $eventList[] = $fevent;
                                    }
                            }
                    }

            } catch (Exception $e)
            {
                    $app->redirect('index.php?option=com_appthaeventz&view=import&task=add',$e->getMessage());
            }

            $i = 0;
            if (!empty($eventList))
                    foreach ($eventList as $event)
                    {
                        //check if the current event was already added
                        $db->setQuery("SELECT COUNT(id) FROM #__em_importevents WHERE id = '".$event->id."' AND `event_from` = 'fbevents' ");
                        $objResult = $db->loadResult();

                        if($objResult <= 0){
                            $start_date = date('Y-m-d H:i:s',$event->EventStartDate);
                            $end_date = date('Y-m-d H:i:s',$event->EventEndDate);

                            $db->setQuery("INSERT INTO #__em_events SET event_name = '".$db->getEscaped($event->EventName)."' , description = '".$db->getEscaped($event->EventDescription)."' , start_date = '".$db->getEscaped($start_date)."' , end_date = '".$db->getEscaped($end_date)."' , category_id = '$category_id', published = 1 ");
                            $db->query();
                            $eventID = $db->insertid();
                            //To get location name if created event dont have location
                            $locationName = '';
                            if($location_id != ''){
                                $query = " SELECT name FROM #__em_locations WHERE location_id = '$location_id' ";
                                $db->setQuery($query);
                                $locationName = $db->loadResult();
                            }
                            $location = !empty($event->Location) ? ($event->Location.",".$event->LocationStreet.",".$event->LocationCity.",".$event->LocationState.",".$event->LocationCountry ): $locationName;
                            $db->setQuery("INSERT INTO #__em_locations SET location_name = '".$db->getEscaped($location)."' , address = '".$db->getEscaped($location)."' , event_id = '$eventID' ");
                            $db->query();

                            $db->setQuery("INSERT INTO #__em_importevents SET id = '".$event->id."' , event_id = '".$eventID."' , `event_from` = 'fbevents' ");
                            $db->query();
                        }
                
                            $i++;
                    }

            $message = $i ? JText::sprintf('Added facebook event(s) successfully',$i) : 'No Facebook events';
            $app->redirect('index.php?option=com_appthaeventz&view=import&task=add',$message);
    }

    function savetoken()        //Function to save the access token for facebook sync
    {
            $db		= JFactory::getDBO();
            $token	= JRequest::getVar('access_token');
            if (!empty($token))
            {
                    $db->setQuery("UPDATE `#__em_eventsettings` SET `fb_token` = '".$db->getEscaped($token)."' WHERE `id` = '1' ");
                    $db->query();
                    $this->setRedirect('index.php?option=com_appthaeventz&view=import&task=add','Facebook Connected');
            } else $this->setRedirect('index.php?option=com_appthaeventz&view=import&task=add','No Facebook Connection');
    }


    function fbredirect($cachable = false, $urlparams = false) // Function to get access token
    {
        $db		= JFactory::getDBO();
        
        require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_appthaeventz'.DS.'helpers'.DS.'facebook'.DS.'facebook.php');

        $query = " SELECT fb_appid,fb_secret,fb_token,fb_category,fb_location FROM #__em_eventsettings WHERE id = '1' ";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $fbDetails = $db->loadObject();
        }

        $app_id = $fbDetails->fb_appid;
        $app_secret = $fbDetails->fb_secret;
        $my_url = JURI::base()."index.php?option=com_appthaeventz&view=import&task=fbredirect";

        //You will get this from redirect page as a query parameter
        $code = JRequest::getVar('code');        
        $cert = JPATH_BASE.DS.'components'.DS.'com_appthaeventz'.DS.'helpers'.DS.'facebook'.DS.'fb_ca_chain_bundle.crt';
        $token_url = "https://graph.facebook.com/oauth/access_token?client_id=". $app_id . "&redirect_uri=" . urlencode($my_url) . "&client_secret=". $app_secret . "&code=" . $code."&scope=email,read_stream,publish_stream";
        //Getting access_token using curl method
        $c = curl_init($token_url);
        curl_setopt($c, CURLOPT_HTTPGET, true);
        curl_setopt($c, CURLOPT_FRESH_CONNECT, true);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($c, CURLOPT_SSL_VERIFYPEER, TRUE);
        curl_setopt ($c, CURLOPT_CAINFO, $cert);
        $output = curl_exec($c);
        if ($output === false) {
            curl_close($c);
            return false;
        }
        curl_close($c);
        
        $curltoken = explode('&',$output);
        $token = explode('=',$curltoken[0]);
        $access_token = $token[1];          //get access token

        $this->setRedirect('index.php?option=com_appthaeventz&view=import&task=savetoken&access_token='.$access_token);
    }


}
?>
