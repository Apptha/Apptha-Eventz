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


class AppthaeventzModelsubscriptions extends JModel {

   function getSubscriptions() {

        global $option, $mainframe;
         $mainframe = JFactory::getApplication();
        $total = 0;
        $option = 'com_subscriptions';
        $filter_order = $mainframe->getUserStateFromRequest($option . 'filter_order', 'filter_order', 'a.id', 'cmd');
        $filter_order_Dir = $mainframe->getUserStateFromRequest($option . 'filter_order_Dir', 'filter_order_Dir', 'asc', 'word');
        $filter_id = $mainframe->getUserStateFromRequest($option . 'filter_id', 'filter_id', '', 'int');
        $search_filter = JRequest::getVar('filter_search');
        $status_filter = JRequest::getVar('filter_status');
        $events_filter = $mainframe->getUserStateFromRequest($option . 'filter_events', 'filter_events', '', 'string');

        // page navigation
        $limit = $mainframe->getUserStateFromRequest($option . '.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        //search and status filter
        $where = '';
        $arrWhere = array();
        $arrWhere[] = " a.active_status != '0' ";
        if($search_filter != '')
            $arrWhere[] = "( a.name like '%$search_filter%' or a.email like '%$search_filter%' or c.event_name like '%$search_filter%') ";


        if($status_filter != '' )
            $arrWhere[] = " a.status = '$status_filter' ";
        if($events_filter != '' )
            $arrWhere[] = " c.id = '$events_filter' ";

        if(!empty($arrWhere) )
            $where = " WHERE ".implode('AND ',$arrWhere);

        $db =  JFactory::getDBO();
     
       
        $query = "SELECT count(a.id) FROM #__em_subscriptions a ".
                 " LEFT JOIN #__em_events c on c.id = a.event_id $where";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $total = $db->loadResult();
        }
        


        jimport('joomla.html.pagination');
        $pageNav = new JPagination($total, $limitstart, $limit);

        $lists['order_Dir'] = $filter_order_Dir;
        $lists['order'] = $filter_order;
        $lists['search'] = $search_filter;


       $query = "SELECT a.*,c.id as eventid,c.event_name,c.start_date,c.end_date FROM #__em_subscriptions a ".
                " LEFT JOIN #__em_events c on c.id = a.event_id $where ORDER BY $filter_order $filter_order_Dir  LIMIT $pageNav->limitstart,$pageNav->limit";
        $db->setQuery($query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $subscriptionsList = $db->loadObjectList();
        }
        

        $query = "SELECT id, event_name FROM #__em_events WHERE active_status != '0' AND published = '1' ";
        $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
           $rowsEvents = $db->loadObjectList();
        }
        

        array_unshift($rowsEvents, JHTML::_('select.option', '', ' '.JText::_('Select event').' ', 'id', 'event_name'));
        $lists['events'] = JHTML::_('select.genericlist',$rowsEvents, 'filter_events', 'class="inputbox" onchange="this.form.submit()"', 'id', 'event_name',$events_filter);
    
        return array('pageNav' => $pageNav, 'limitstart' => $limitstart, 'limit' => $limit, 'lists' => $lists, 'subscriptionsList' => $subscriptionsList,'order_Dir'=>$filter_order_Dir,'order'=>$filter_order);
    }

    function newSubscriptions(){
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $query = " SELECT a.id as eventid,a.event_name,a.start_date,b.id,b.ticket_name,b.price FROM `#__em_events` a ".
                 " LEFT JOIN `#__em_tickets` b on b.event_id=a.id where a.published='1' order by a.id ";
        $db->setQuery($query);
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
          $eventsList = $db->loadObjectList();
        }
        //Splitting to array to create optgroup
        $groups = array();
        foreach ($eventsList as $event) {
            $groups[$event->event_name][$event->start_date][$event->price][$event->eventid][$event->id] = $event->ticket_name;
        }

        return $groups;
   }

   function saveSubscriptions(){        //Save the subscriptions
      
        $db=JFactory::getDbo();

        $name = $db->getEscaped(JRequest::getVar('name'));
        $email = $db->getEscaped(JRequest::getVar('email'));
        $status = $db->getEscaped(JRequest::getVar('status'));
        $no_tickets = $db->getEscaped(JRequest::getVar('no_tickets'));
        $ticket = $db->getEscaped(JRequest::getVar('tickets_id'));
        $subscribed_date = date("Y-m-d H:i:s");

        if( is_numeric($ticket) ){
        //To get the events_id
        $query = "SELECT event_id,price FROM #__em_tickets where id = ".$ticket;
        $db->setQuery( $query );
        $events_id = $db->loadObject();
        $evID = $events_id->event_id;
        $evPrice = $events_id->price;
        }else{
         $res = explode('-',$ticket);
         $ticket = '0';
         $evID = $res[1];
         $evPrice = '0';

        }
        //Insert the subscription
        $query = " INSERT INTO #__em_subscriptions (name,email,status,no_tickets,tickets_id,event_id,subscribed_on) ".
                 " VALUES ('$name','$email','$status','$no_tickets','$ticket','$evID','$subscribed_date') ";
        $db->setQuery($query);
        $db->query();

        $row_id = $db->insertid();

        
        //To get event details
        $query = "SELECT event_name,start_date FROM #__em_events where id = ".$evID;
        $db->setQuery( $query );
        $eventDetails = $db->loadObject();

        $totalPrice = $evPrice*$no_tickets;

        if($status == '1' ){     //Incomplete mail
            $eventName = $eventDetails->event_name;
            $startDate = date('F d,Y H:i:s',strtotime($eventDetails->start_date) );

            $query = "SELECT registration FROM #__em_emailsettings ";
            $db->setQuery( $query );
            $content = $db->loadResult();
            $content = json_decode($content);
            //Subject of the mail
            $emailsubject = $content[0];
            $subject = str_replace('{EventName}',$eventName,$emailsubject);
            //Content of the mail
            $emailContent = $content[1];
            $messag = str_replace('{user}',$name,$emailContent);
            $messag1 = str_replace('{EventName}',$eventName,$messag);
            $message = str_replace('{EventStartDate}',$startDate,$messag1);
            $link = JURI::root().'index.php?option=com_appthaeventz&view=join&tmpl=component&event_id='. $evID.'&ticket_id='.$ticket.'&sub_id='. $row_id;
            if($totalPrice > 0 ){
                $message .= '<p>Your Payment amount : '.$totalPrice.'</p>';
            }
            $message .= '<p> Please <a href="'.$link.'">click this link</a> to proceed for payment.</p>';
            
        }else if($status == '2' ){   //Complete mail
            $eventName = $eventDetails->event_name;
            $startDate = date('F d,Y H:i:s',strtotime($eventDetails->start_date) );

            $query = "SELECT registration FROM #__em_emailsettings ";
            $db->setQuery( $query );
            $content = $db->loadResult();
            $content = json_decode($content);
            //Subject of the mail
            $emailsubject = $content[0];
            $subject = str_replace('{EventName}',$eventName,$emailsubject);
            //Content of the mail
            $emailContent = $content[1];
            $messag = str_replace('{user}',$name,$emailContent);
            $messag1 = str_replace('{EventName}',$eventName,$messag);
            $message = str_replace('{EventStartDate}',$startDate,$messag1);
        }

        $mailer =JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailer->setSender($sender);
        //$mailer->addBCC($email);
        $mailer->addRecipient($email);
        $mailer->setSubject($subject);
        $mailer->setBody($message);
        $mailer->isHTML();

        $send = $mailer->Send();
        if ( $send != true ) {
            echo 'Error sending email: ' . $send->message;
        }

        return $row_id;
       
   }

   function applySubscriptions(){       //Update the subscriptions

    $db=JFactory::getDbo();

    $id = JRequest::getInt('id');
  
    $name = $db->getEscaped(JRequest::getVar('name'));
    $email = $db->getEscaped(JRequest::getVar('email'));
    $status = $db->getEscaped(JRequest::getVar('status'));
    $subscribed_date = date("Y-m-d H:i:s");
    $denied_date = ($status == '3')?date("Y-m-d H:i:s"):'';
    $subscribed_date = ($denied_date == '')?date("Y-m-d H:i:s"):'';


    //To get event_id
    $query = "SELECT id,event_id,status,no_tickets,tickets_id FROM #__em_subscriptions where id = ".$id;
    $db->setQuery( $query );
    $eventId = $db->loadObject();

    //To get event details
    $query = "SELECT event_name,start_date FROM #__em_events where id = ".$eventId->event_id;
    $db->setQuery( $query );
    $eventDetails = $db->loadObject();


    //To get the events_id
    $query = "SELECT price FROM #__em_tickets where id = ".$eventId->tickets_id;
    $db->setQuery( $query );
    $price = $db->loadResult();

    $totalPrice = $eventId->no_tickets*$price;

    if($status == '1' && $eventId->status != $status ){     //Incomplete mail
        $eventName = $eventDetails->event_name;
        $startDate = date('F d,Y H:i:s',strtotime($eventDetails->start_date) );

        $query = "SELECT registration FROM #__em_emailsettings ";
        $db->setQuery( $query );
        $content = $db->loadResult();
        $content = json_decode($content);

        //Subject of the mail
        $emailsubject = $content['0'];
        $subject = str_replace('{EventName}',$eventName,$emailsubject);
        //Content of the mail
        $emailContent = $content['1'];
        $messag = str_replace('{user}',$name,$emailContent);
        $messag1 = str_replace('{EventName}',$eventName,$messag);
        $message = str_replace('{EventStartDate}',$startDate,$messag1);
        $link = JURI::root().'index.php?option=com_appthaeventz&view=join&tmpl=component&event_id='. $eventId->event_id.'&ticket_id='.$eventId->tickets_id.'&sub_id='. $id;
        if($totalPrice > 0 ){
                $message .= '<p>Your Payment amount : '.$totalPrice.'</p>';
        }
        $message .= '<p> Please <a href="'.$link.'">click this link</a> to proceed for payment.</p>';

        $mailer =JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailer->setSender($sender);
        //$mailer->addBCC($email);
        $mailer->addRecipient($email);
        $mailer->setSubject($subject);
        $mailer->setBody($message);
        $mailer->isHTML();

        $send = $mailer->Send();
        if ( $send != true ) {
            echo 'Error sending email: ' . $send->message;
            return false;
        } else {
            echo 'Mail sent';
        }

    }else if($status == '2' && $eventId->status != $status){    //Complete mail
        $eventName = $eventDetails->event_name;
        $startDate = date('F d,Y H:i:s',strtotime($eventDetails->start_date) );

        $query = "SELECT registration FROM #__em_emailsettings ";
        $db->setQuery( $query );
        $content = $db->loadResult();
        $content = json_decode($content);

        //Subject of the mail
        $emailsubject = $content['0'];
        $subject = str_replace('{EventName}',$eventName,$emailsubject);
        //Content of the mail
        $emailContent = $content['1'];
        $messag = str_replace('{user}',$name,$emailContent);
        $messag1 = str_replace('{EventName}',$eventName,$messag);
        $message = str_replace('{EventStartDate}',$startDate,$messag1);

        $mailer =JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailer->setSender($sender);
        //$mailer->addBCC($email);
        $mailer->addRecipient($email);
        $mailer->setSubject($subject);
        $mailer->setBody($message);
        $mailer->isHTML();

        $send = $mailer->Send();
        if ( $send != true ) {
            echo 'Error sending email: ' . $send->message;
            return false;
        } else {
            echo 'Mail sent';
        }

    }else if($status == '3' && $eventId->status != $status){    //Denied mail
        $eventName = $eventDetails->event_name;
        $startDate = date('F d,Y H:i:s',strtotime($eventDetails->start_date) );

        $query = "SELECT denied FROM #__em_emailsettings ";
        $db->setQuery( $query );
        $content = $db->loadResult();
        $content = json_decode($content);

        //Subject of the mail
        $emailsubject = $content['0'];
        $subject = str_replace('{EventName}',$eventName,$emailsubject);
        //Content of the mail
        $emailContent = $content['1'];
        $messag = str_replace('{user}',$name,$emailContent);
        $message = str_replace('{EventName}',$eventName,$messag);

        $mailer =JFactory::getMailer();
        $config = JFactory::getConfig();
        $sender = array(
        $config->getValue( 'config.mailfrom' ),
        $config->getValue( 'config.fromname' ) );
        $mailer->setSender($sender);
        //$mailer->addBCC($email);
        $mailer->addRecipient($email);
        $mailer->setSubject($subject);
        $mailer->setBody($message);
        $mailer->isHTML();

        $send = $mailer->Send();
        if ( $send != true ) {
            echo 'Error sending email: ' . $send->message;
            return false;
        } else {
            echo 'Mail sent';
        }
    }

    $query = " UPDATE #__em_subscriptions SET name = '$name',email = '$email',status = '$status',subscribed_on = '$subscribed_date',denied_on = '$denied_date' WHERE id = '$id' ";
    $db->setQuery($query);
    $db->query();


    return $id;
   }

   function resendMailActivation(){     //Resend activation mail
   
    $db=JFactory::getDbo();

    $id = JRequest::getInt('sid');

    $name = JRequest::getVar('user');
    $eventname = JRequest::getVar('eventname');
    $email = $db->getEscaped(JRequest::getVar('mail'));
    

    $query = "SELECT activation FROM #__em_emailsettings ";
    $db->setQuery( $query );
    $content = $db->loadResult();
    $content = json_decode($content);
    
    //Subject of the mail
    $emailsubject = $content['0'];
    $subject = str_replace('{EventName}',$eventname,$emailsubject);
    //Content of the mail
    $emailContent = $content['1'];
    $messag = str_replace('{user}',$name,$emailContent);
    $message = str_replace('{EventName}',$eventname,$messag);


    $mailer =JFactory::getMailer();
    $config = JFactory::getConfig();
    $sender = array(
    $config->getValue( 'config.mailfrom' ),
    $config->getValue( 'config.fromname' ) );
    $mailer->setSender($sender);
    //$mailer->addBCC($email);
    $mailer->addRecipient($email);
    $mailer->setSubject($subject);
    $mailer->setBody($message);
    $mailer->isHTML();

    $send = $mailer->Send();
    if ( $send != true ) {
        echo 'Error sending email: ' . $send->message;
        return false;
    } else {
        echo 'Mail sent';
        return $id;
    }


   }

   function editSubscriptions($id){     //Fetching the Edit details
       $db=JFactory::getDbo();
        global $option, $mainframe;
        $mainframe = JFactory::getApplication();

        $arrayIDs = JRequest::getVar('cid', null, 'default', 'array'); //Reads cid as an array

        $query = "SELECT a.id,a.name,a.email,a.status,a.no_tickets,a.tickets_id,a.event_id as mainid,a.subscribed_on,a.denied_on,a.active_status,b.event_id,b.ticket_name,b.price,c.id as eventid,c.event_name,c.start_date,c.end_date FROM #__em_subscriptions a ".
                 " LEFT JOIN #__em_tickets b on a.tickets_id = b.id ".
                 " LEFT JOIN #__em_events c on c.id = b.event_id where a.id = ".$id;
        $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
          $subscriptions = $db->loadObject();
        }

        $subscriptionList = array();
        if($subscriptions->event_name == ''){

            $query = " SELECT id,event_name,start_date,end_date FROM `#__em_events` where id = '$subscriptions->mainid' and published = 1 and active_status != 0 order by id ";
            $db->setQuery($query);
            //check whether any error in query
            if (!$db->query()) {
                JError::raiseError(500, $db->getErrorMsg());
                    return false;
            } else {
              $subscriptionList = $db->loadObject();
            }
        }

       
        return array($subscriptions, $subscriptionList);

   }

   public function getSubscriberCount($catName){      //ajax function to find category already exists
       $db = JFactory::getDBO();
       $query = "SELECT id FROM `#__em_subscriptions` WHERE `name` = '$catName' and active_status != '0' ";
       $db->setQuery( $query );
       //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
             $objResult = $db->loadResult();
        }

       return $objResult;
   }

   public function getEditSubscriberCount($catName,$catId){      //ajax function to find category already exists in Edit
       $db = JFactory::getDBO();
       $query = "SELECT id FROM `#__em_subscriptions` WHERE `name` = '$catName' and `id` != '$catId' and active_status != '0' ";
       $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
                return false;
        } else {
             $objResult = $db->loadResult();
        }
       return $objResult;
   }
   
   public function getCount($tktId){      //ajax function to find category already exists in Edit
       $db = JFactory::getDBO();
       $query = "SELECT * FROM `#__em_tickets` WHERE `id` = '$tktId' ";
       $db->setQuery( $query );
        //check whether any error in query
        if (!$db->query()) {
            JError::raiseError(500, $db->getErrorMsg());
            return false;
        } else {
             $objResult = $db->loadObject();
        }
        
        if( $objResult->booked_count == NULL && $objResult->balance_count == NULL )
            $count = $objResult->seats;
        else if( $objResult->booked_count != NULL && ( $objResult->balance_count == 2 || $objResult->balance_count == 1 ) )
            $count = $objResult->booked_count;
        
       return $count;
   }

   function appSubscriptions($arrayIDs) {   //Approve the subscriptions

        $n = count($arrayIDs['cid']);

        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__em_subscriptions set status='2' WHERE id=" . $arrayIDs['cid'][$i];
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();

        }
    }

    function pendSubscriptions($arrayIDs) {     //Pend the subscriptions

        $n = count($arrayIDs['cid']);

        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__em_subscriptions set status='1' WHERE id=" . $arrayIDs['cid'][$i];
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();

        }
    }

    function denySubscriptions($arrayIDs) {     //Deny the subscriptions

        $n = count($arrayIDs['cid']);

        for ($i = 0; $i < $n; $i++) {
            $query = "UPDATE #__em_subscriptions set status='3' WHERE id=" . $arrayIDs['cid'][$i];
            $db = $this->getDBO();
            $db->setQuery($query);
            $db->query();

        }
    }

    function deleteSubscriptions($arrayIDs) {   //Delete the subscriptions

        $n = count($arrayIDs);

        for ($i = 0; $i < $n; $i++) {
            $db = $this->getDBO();
            $query = "UPDATE #__em_subscriptions SET active_status = '0' WHERE id=" . $arrayIDs[$i];
            $db->setQuery($query);
            $db->query();
        }

    }
 
}

?>
