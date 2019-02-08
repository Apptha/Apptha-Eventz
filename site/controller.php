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

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');

require_once( JPATH_BASE . DS . 'components'. DS . 'com_appthaeventz'. DS . 'helpers' . DS . 'paypal.class.php' );

class appthaeventzController extends JController
{
    /**
     * Method to display the view
     */
    function display()
    {
        parent::display();
    }
    
}
?>