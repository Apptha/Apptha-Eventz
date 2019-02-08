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

class appthaeventzModelinvite extends JModel {
public function gmailclient(){
  $db = JFactory::getDBO();
  $query="SELECT `googleclient` FROM  `#__em_eventsettings`";
  $db->setQuery($query);
  $result = $db->loadResult();
  
  return $result;
}


public function gmail(){
  $event_id = JRequest::getvar('event_id',null,'POST');
  $emailaddress = JRequest::getvar('emailaddress',null,'POST','array' );    
  $db = JFactory::getDBO();
  $query="SELECT `event_name`,`start_date` FROM  `#__em_events` WHERE id=$event_id";
  $db->setQuery($query);
  $result = $db->loadObject();
  $event_name = $result->event_name;
  $start_date = $result->start_date;
  if($emailaddress){  
   $db = JFactory::getDBO();
    $query="SELECT  `fromemail`,`fromname` FROM  `#__em_emailsettings`";
   $db->setQuery($query);
   $contentList = $db->loadObject();
   $query="SELECT  `invite` FROM  `#__em_emailsettings`";
   $db->setQuery($query);
   $invitation_email = $db->loadResult();
   $invitation_email = json_decode($invitation_email);
   $invitation_subject = $invitation_email[0];
   $invitation_body = $invitation_email[1];
   $subject = str_replace('{EventName}',$event_name,$invitation_subject);
   $messag1 = str_replace('{EventName}',$event_name,$invitation_body);
   $message = str_replace('{EventStartDate}',$start_date,$messag1);
   $mailer = JFactory::getMailer();
   for($i=0;$i<500;$i++){
   $recipient = $emailaddress[$i];
   $mailer->addRecipient($recipient);
   $body   = "$message";
   if(isset($contentList->fromemail)){
   $mailer->setSender($contentList->fromemail);
   }
   $mailer->isHTML(true);
   $mailer->Encoding = 'base64';
   $mailer->setSubject($subject);
   $mailer->setBody($body);
  }

   session_destroy();
  $send = $mailer->Send();
   

  }
  }
 public function manualinvite(){
  $event_id = JRequest::getvar('event_id',null,'POST');
  $db = JFactory::getDBO();
  $query="SELECT `event_name`,`start_date` FROM  `#__em_events` WHERE id=$event_id";
  $db->setQuery($query);
  $result = $db->loadObject();
  $event_name = $result->event_name;
  $start_date = $result->start_date;
 
  $mailer = JFactory::getMailer();
  $invitefriend1 = JRequest::getvar('invitefriend1',null,'POST');
  $invitefriend2 = JRequest::getvar('invitefriend2',null,'POST');
  $invitefriend3 = JRequest::getvar('invitefriend3',null,'POST');
  $invitefriend4 = JRequest::getvar('invitefriend4',null,'POST');
  if(isset($invitefriend1)||isset($invitefriend2)||isset($invitefriend3)||isset($invitefriend4)){
  $recipient = array($invitefriend1, $invitefriend2, $invitefriend3, $invitefriend4);
  
   $db = JFactory::getDBO();
   $query="SELECT  `fromemail`,`fromname` FROM  `#__em_emailsettings`";
   $db->setQuery($query);
   $contentList = $db->loadObject();
   $query="SELECT  `invite` FROM  `#__em_emailsettings`";
   $db->setQuery($query);
   $invitation_email = $db->loadResult();
   $invitation_email = json_decode($invitation_email);
   $invitation_subject = $invitation_email[0];
   $invitation_body = $invitation_email[1];
   $subject = str_replace('{EventName}',$event_name,$invitation_subject);   
   $messag1 = str_replace('{EventName}',$event_name,$invitation_body);
   $message = str_replace('{EventStartDate}',$start_date,$messag1);
   $recipients = $mailer->addRecipient($recipient);
   $body  = "$message";
   if(isset($contentList->fromemail)){
   $mailer->setSender($contentList->fromemail);
   }
   $mailer->isHTML(true);
   $mailer->Encoding = 'base64';
   $mailer->setSubject($subject);
   $mailer->setBody($body);
   
   $send = $mailer->Send();
   if ( $send !== true ) {
    echo 'Error sending email: ' . $send->message;
    } else {
    echo 'Mail sent';
   }
   

  }
 }
}



?>