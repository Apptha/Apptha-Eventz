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


class AppthaeventzViewevents extends JView
{

	public function display($tpl = null)
	{

                 $model = $this->getModel('events');
                 $eventData = $model->getEvents();
          	 $this->assignRef('eventdata', $eventData);
                //sorting
                 $items = $this->get('Items');
                 $state = $this->get('State');
                 $this->sortDirection = $state->get('filter_order_Dir');
                 $this->sortColumn = $state->get('filter_order');
                 $filter['filter_name']=$state->get('filter_name');
                 $filter['filter_category']=$state->get('filter_category');
                 $filter['filter_status']=$state->get('filter_status');
                 $this->assignRef('filter', $filter);
                 $this->assignRef('pagination', $pagination);
                 $category=$model->AllCategory();
                $this->assignRef('category', $category);
                $showcategory=$model->showCategory();
                $this->assignRef('showcategory', $showcategory);

          	parent::display();
            
	}


}
