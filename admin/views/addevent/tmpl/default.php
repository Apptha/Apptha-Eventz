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
defined('_JEXEC') or die('Restricted access');

$layout=JRequest::getVar('layout',null,'GET');

        switch($layout)
        {

        case 'multiupload':
            echo $this->loadTemplate('multiupload');
            break;
        case 'upload':
            echo $this->loadTemplate('upload');
            break;
        case 'location':
            echo $this->loadTemplate('location');
            break;
        case 'category':
            echo $this->loadTemplate('category');
            break;
        default:
            echo $this->loadTemplate('addevent');
            break;

        }
?>