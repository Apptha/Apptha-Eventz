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
// No direct access.
defined('_JEXEC') or die('Restricted access');
// import Joomla controller library
jimport('joomla.application.component.controller');

class AppthaeventzControllersettings extends JController {

    function display($cachable = false, $urlparams = false) {

        $viewName = JRequest::getVar('view', 'settings');
        $viewLayout = JRequest::getVar('layout', 'settings');
        $model = $this->getModel('settings');
        $model->email();
        $view = $this->getView($viewName);
        if ($model = $this->getModel('settings')) {
            $view->setModel($model, true);
        }
       
        $view->setLayout($viewLayout);
        $view->display();
    }

    function apply() {
        $tab = JRequest::getVar('tab');

        $model = $this->getModel('settings');
        $model->emailfields();

        if ($tab == 'general') {
            //redirect after applying the settings for general tab
            $this->setRedirect('index.php?option=com_appthaeventz&view=settings#tabs-1', 'General settings Saved!');
        } else if ($tab == 'events') {
            //redirect after applying the settings for events tab
            $this->setRedirect('index.php?option=com_appthaeventz&view=settings#tabs-2', 'Events settings Saved!');
        } else if ($tab == 'emails') {
            //redirect after applying the settings for emails tab
            $this->setRedirect('index.php?option=com_appthaeventz&view=settings#tabs-3', 'Email settings Saved!');
        } else if ($tab == 'payments') {
            //redirect after applying the settings for payments tab
            $this->setRedirect('index.php?option=com_appthaeventz&view=settings#tabs-4', 'Payment settings Saved!');
        }
    }

    function save() {
        $tab = JRequest::getVar('tab');

        $model = $this->getModel('settings');
        $model->emailfields();
        if ($tab == 'general') {
             //redirect after applying the settings for general tab
            $this->setRedirect('index.php?option=com_appthaeventz', 'General settings Saved!');
        } else if ($tab == 'events') {
            //redirect after applying the settings for events tab
            $this->setRedirect('index.php?option=com_appthaeventz', 'Events settings Saved!');
        } else if ($tab == 'emails') {
             //redirect after applying the settings for emails tab
            $this->setRedirect('index.php?option=com_appthaeventz', 'Email settings Saved!');
        } else if ($tab == 'payments') {
            //redirect after applying the settings for payments tab
            $this->setRedirect('index.php?option=com_appthaeventz', 'Payment settings Saved!');
        }
    }

}
