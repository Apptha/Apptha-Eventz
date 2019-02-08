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

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
// import Joomla view library
jimport('joomla.application.component.view');


class AppthaeventzViewaddevent extends JView
{

	public function display($tpl=NULL)
	{
                $model = $this->getModel('addevent');
                $oneData=$model->oneEventData();
          	$this->assignRef('onedata', $oneData);
                $category=$model->AllCategory();
                $this->assignRef('category', $category);
                $oneImage=$model->oneImage();
                $this->assignRef('oneimage', $oneImage);

                $eventTicket=$model->eventTickets();
                $this->assignRef('eventticket', $eventTicket);
                $eventLocation=$model->oneLocation();
                $this->assignRef('eventlocation', $eventLocation);
                $alltags=$model->getAllTag();
                $this->assignRef('alltags', $alltags);
                $setting=$model->getSetting();
                $this->assignRef('setting', $setting);

                parent::display($tpl=NULL);
            
	}


}
