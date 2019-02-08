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

class appthaeventzModelcalender extends JModel {

     //get event data
    function getEventData()
    {
        $db = JFactory::getDBO();
      /************build query***********/
        $query="SELECT e.id as id ,e.event_name as title ,e.start_date as start,e.end_date as end
                FROM #__em_events as e
                WHERE e.published=1 and parent_id=0 ";
            $db->setQuery($query);
            $result=$db->loadAssocList();
            foreach($result as $val):
                $alias_name= JFilterOutput::stringURLSafe($val['title']);
              $url=JRoute::_('index.php?option=com_appthaeventz&view=events&events_id='.$val['id'].':'.$alias_name,true);
              $title=explode(" ",$val['title']);
              $total[] = array('title'=>$title[0],'start'=>$val['start'],'end'=>$val['end'],'url'=>$url);
            endforeach;
            //print_r($total);
           $json=json_encode($total);
           return $json;

  }


}

?>