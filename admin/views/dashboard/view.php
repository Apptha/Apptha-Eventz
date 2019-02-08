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

class AppthaeventzViewdashboard extends JView {

    public function display($tpl=NULL) {
        if (JRequest::getVar('task') == '') {
            JToolBarHelper::title('Apptha Eventz','dashboard.png');
        }
        $model = $this->getModel();
        $totalrevenues = $model->totalrevenues();
        $this->assignRef('totalrevenues', $totalrevenues);


        $highratedevents = $model->highratedevents();
        $this->assignRef('highratedevents', $highratedevents);

        $lastpayments = $model->lastpayments();
        $this->assignRef('lastpayments', $lastpayments);

        $upcomingevents = $model->upcomingevents();
        $this->assignRef('upcomingevents', $upcomingevents);

        $newsubscribers = $model->newsubscribers();
        $this->assignRef('newsubscribers', $newsubscribers);

        $currentevents = $model->currentevents();
        $this->assignRef('currentevents', $currentevents);


        parent::display();
    }

}
