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

class appthaeventzModelgmailinvite extends JModel {
    
 public function gmail(){
  $emailaddress = JRequest::getvar('emailaddress',null,'POST','array' );
  


  }
public function gmailclient(){
  $db = JFactory::getDBO();
  $query="SELECT `googleclient`,`googlesecret` FROM  `#__em_eventsettings`";
  $db->setQuery($query);
  $result = $db->loadObject();
  return $result;
}  


    

}

?>    


    

