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

class AppthaeventzViewsettings extends JView {

    public function display($tpl=NULL) {
        JToolBarHelper::title('Settings','settings.png');
        JToolBarHelper::apply();
        JToolBarHelper::save();

        $model = $this->getModel();
        $general = $model->emailfields();
        $this->assignRef('general', $general);
        $email = $model->email();
        $this->assignRef('email', $email);
        $getgeneralsettings = $model->getgeneralsettings();
        $this->assignRef('getgeneralsettings', $getgeneralsettings);
        $geteventsettings = $model->geteventsettings();
        $this->assignRef('geteventsettings', $geteventsettings);
        $getpaymentsettings = $model->getpaymentsettings();
        $this->assignRef('getpaymentsettings', $getpaymentsettings);
        $getemailsettings = $model->getemailsettings();
        $this->assignRef('getemailsettings', $getemailsettings);
        $fbdetails = $model->fbdetails();
        $this->assignRef('fbdetails', $fbdetails);
        $googledetails = $model->googledetails();
        $this->assignRef('googledetails', $googledetails);
        $getcurrencydetails = $model->getcurrencydetails();
        $this->assignRef('getcurrencydetails', $getcurrencydetails);

        parent::display();
    }

}
