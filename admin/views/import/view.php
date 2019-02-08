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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
class AppthaeventzViewimport extends JView {

    function display($tpl=NULL) {
        $task = JRequest::getVar('task');
        if ($task == 'add') {
                JToolBarHelper::title('Import', 'import.png');
                JToolBarHelper::apply();
                JToolBarHelper::cancel();
               
                $model = $this->getModel('import');
                $categories = $model->getCategory();
                $this->assignRef('categoriesList', $categories);
                //locations
                $locations = $model->getLocation();
                $this->assignRef('locationsList', $locations);
                $glocations = $model->getGLocation();
                $this->assignRef('glocationsList', $glocations);
                $fblocations = $model->getFbLocation();
                $this->assignRef('fblocationsList', $fblocations);
                //Google calendar
                $googdetails = $model->getGcalendar();
                $this->assignRef('googDetails', $googdetails);
                //Facebook events
                $fbdetails = $model->getFbevents();
                $this->assignRef('fbdetails', $fbdetails);


                parent::display();
        }

    }

}

?>
