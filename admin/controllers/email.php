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

class AppthaeventzControlleremail extends JController {

    function display($cachable = false, $urlparams = false) {

        $viewName = JRequest::getVar('view', 'email');
        $model = $this->getModel('settings');
        $model->email();
        $view = $this->getView($viewName);
        if ($model = $this->getModel('settings')) {
            $view->setModel($model, true);
        }
        $view->display();
    }

}
