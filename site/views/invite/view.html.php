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

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * @package		Joomla.Site
 * @subpackage	photogallery
 */
class appthaeventzViewinvite extends JView {

    public function display($tpl = null) {
       global $mainframe;
        $user=Jfactory::getUser();
        $model = $this->getModel();
        $gmailclient = $model->gmailclient();
        $this->assignRef('gmailclient', $gmailclient);
        $gmail = $model->gmail();
        $this->assignRef('gmail', $gmail);
         $manualinvite = $model->manualinvite();
        $this->assignRef('manualinvite', $manualinvite);
     parent::display($tpl);   
    }







}
