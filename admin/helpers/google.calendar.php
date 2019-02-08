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

defined( '_JEXEC' ) or die( 'Restricted access' );

class EMGoogleCalendar
{
	
	var $_username;
	var $_password;
	var $_errors = array();
	
	function EMGoogleCalendar($user,$pass)
	{
		$this->_username = $user;
		$this->_password = $pass;
	}
	
	
	/*
	*	Insert events in database
	*/
	
	function parse()
	{
		$db		= JFactory::getDBO();
		$events	= $this->getEvents();

		$category_id = JRequest::getVar('g_category_id');
                $location_id = JRequest::getVar('g_location_id');
		
		$i = 0;
            if (!empty($events)){
		foreach ($events as $event)
		{
                    //check if the current event was already added
                    $db->setQuery("SELECT COUNT(id) FROM #__em_importevents WHERE id = '".$event->id."' AND `event_from` = 'googlecal' ");
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
                            $query = " SELECT location_name FROM #__em_locations WHERE id = '$location_id' ";
                            $db->setQuery($query);
                            $locationName = $db->loadResult();
                        }
                        $location = !empty($event->EventLocation) ? $event->EventLocation : $locationName;
                        $db->setQuery("INSERT INTO #__em_locations SET location_name = '".$db->getEscaped($location)."' , address = '".$db->getEscaped($location)."' , event_id = '$eventID' ");
                        $db->query();

                        $db->setQuery("INSERT INTO #__em_importevents SET id = '".$event->id."' , event_id = '".$eventID."' , `event_from` = 'googlecal' ");
                        $db->query();
                    }
			$i++;
		}
            }
		
		return $i;
	}
	
	
	
	/*
	*	Get and parse events
	*/
	
	function getEvents()
	{
		$returns = array();
		$events = array();
		
		$login_url = "https://www.google.com/accounts/ClientLogin";
		$this->_username = stristr($this->_username,'@') ? $this->_username : $this->_username.'@gmail.com';
		
		$fields = array(
		'Email'       => $this->_username,
		'Passwd'      => $this->_password,
		'service'     => 'cl', // cl = Google calendar
		'source'      => 'rsevents-google-calendar-grabber',
		'accountType' => 'HOSTED_OR_GOOGLE',
		);

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL,$login_url);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS,$fields);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 
		$result = curl_exec($curl);
		
		
		if (empty($result))
			$this->addError('Could not connect to Google! server.');
		
		$lines = explode("\n",$result);
		if (!empty($lines))
			foreach ($lines as $line) 
			{
				$line = trim($line);
				if(!$line) continue;
				list($k,$v) = explode('=',$line,2);
				$returns[$k] = $v;
			}
		curl_close($curl);

		if (empty($returns['Auth']))
		{
			if (isset($returns['Error'])) 
				$this->addError($returns['Error']);
			else 
				$this->addError('Authentication failed');
			return;
		}
		
		$header = array( 'Authorization: GoogleLogin auth=' . $returns['Auth'] );
		$url = "https://www.google.com/calendar/feeds/$this->_username/private/full?alt=jsonc&max-results=250";
		$result = $this->getData($header,$url,1);
		
		if (!function_exists('json_decode'))
		{
			//get the json class
			require_once(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_appthaeventz'.DS.'helpers'.DS.'JSON.php');
			
			$json = new Services_JSON;
			$data = $json->decode($result);
		} else $data = json_decode($result);
		
		if (empty($data))
		{
			$this->addError('No data available.');
			return;
		}
		
		if (!empty($data->data->items))
		foreach ($data->data->items as $item)
		{
			$event = new stdClass();
			$event->id = $item->id;
			$event->EventName = $item->title;
			$event->EventDescription = $item->details;
			$event->EventLocation = $item->location;
			$event->Host = $item->creator->displayName;
			
			if (isset($item->when))
			{
				$dates = $item->when[0];
				$event->EventStartDate = strtotime($dates->start);
				$event->EventEndDate = strtotime($dates->end);
			}
			
			if (isset($item->recurrence))
			{
				$event->EventStartDate = time();
				$event->EventEndDate = time() + 7200;
				
				$lines = explode("\n",$item->recurrence);
				if (!empty($lines[0]))
				{
					$line = explode(':',$lines[0]);
					if (!empty($line[1]))
						$event->EventStartDate = strtotime($line[1]);
				}
				if (!empty($lines[1]))
				{
					$line = explode(':',$lines[1]);
					if (!empty($line[1]))
						$event->EventEndDate = strtotime($line[1]);
				}
			}
			$events[] = $event;
		}
		
		return $events;
	}
	
	/*
	*	Get data from server
	*/
	
	function getData($header,$url,$type='1')
	{		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_HEADER, $type);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); 

		$result = curl_exec($curl);
		curl_close($curl);	
		
		$objs = explode("\n",$result);
		foreach($objs as $obj)
			if(strpos($obj,'Location:') !== false)
			{
				$new_url = trim(str_replace('Location: ','',$obj));
				$result = $this->getData($header,$new_url,0);
			}
		
		return $result;
	}
	
	/*
	*	Add errors
	*/
	
	function addError($error)
	{
		if (isset($this->_errors))
			if (in_array($error,$this->_errors)) 
				return $this->_errors;
		
		$this->_errors[] = $error;
	}
	
	/*
	*	Get errors
	*/
	
	function getErrors()
	{
		return $this->_errors;
	}
	
}