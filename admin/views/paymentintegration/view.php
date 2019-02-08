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

class AppthaeventzViewpaymentintegration extends JView {

    public function display($tpl=NULL) {

        if (JRequest::getVar('task') == '') {
            JToolBarHelper::title('Payment Integration','payment.png');
            JToolBarHelper::publishList();
            JToolBarHelper::unpublishList();
            
        }
        $cid = JRequest::getVar('cid');
        $cid = $cid[0];
        if ((JRequest::getVar('edit') == '') && ($cid == '1')) {
            JToolBarHelper::title('Wire Transfer');
            JToolBarHelper::save();
            JToolBarHelper::apply();
            JToolBarHelper::cancel();
        }
        if ((JRequest::getVar('edit') == '') && ($cid == '2')) {
            JToolBarHelper::title('PayPal');
            JToolBarHelper::save();
            JToolBarHelper::apply();
            JToolBarHelper::cancel();
        }
        if ((JRequest::getVar('edit') == '') && ($cid == '3')) {
            JToolBarHelper::title('Authorized .Net');
            JToolBarHelper::save();
            JToolBarHelper::apply();
            JToolBarHelper::cancel();
        }
        $model = $this->getModel();
        $paymentmethods = $model->paymentmethods();
        $this->assignRef('paymentmethods', $paymentmethods);

        $getwiretransfer = $model->getwiretransfer();
        $this->assignRef('getwiretransfer', $getwiretransfer);

        $getpaypal = $model->getpaypal();
        $this->assignRef('getpaypal', $getpaypal);

        $getauthorized = $model->getauthorized();
        $this->assignRef('getauthorized', $getauthorized);

        $getstatus = $model->getstatus();
        $this->assignRef('getstatus', $getstatus);
        parent::display();
    }

}
