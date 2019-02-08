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


date_default_timezone_set('UTC');
// Require the helpers
require_once( JPATH_COMPONENT . DS . 'helpers' . DS . 'appthaeventz.php' );
// Require the base controller

jimport('joomla.application.component.controller');
// Require specific controller if requested
if ($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT . DS . 'controllers' . DS . $controller . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }

}


class AppthaeventzClass  {

    protected $_const;

     function get_domain($domain)
    {

        $code = $this->domainKey($domain);
        $domainKey = substr($code, 0, 25) . "CONTUS";
        return $domainKey;

    }
     function social_genenrate()
    {
        $strDomainName = JURI::base();
        preg_match("/^(http:\/\/)?([^\/]+)/i", $strDomainName, $subfolder);
        preg_match("/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i", $subfolder[2], $matches);

        $customerurl = $matches['domain'];
        $customerurl = str_replace("www.", "", $customerurl);
        $customerurl = str_replace(".", "D", $customerurl);
        $customerurl = strtoupper($customerurl);
        $response     = $this->get_domain($customerurl);
        return $response;
    }
     function getOffset($start, $end) {

        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }

        for ($i = count($chars_array) - 1; $i >= 0; $i--) {
            $lookupObj[ord($chars_array[$i])] = $i;
        }

        $sNum = $lookupObj[ord($start)];
        $eNum = $lookupObj[ord($end)];

        $offset = $eNum - $sNum;

        if ($offset < 0) {
            $offset = count($chars_array) + ($offset);
        }

        return $offset;
    }
    function domainKey($tkey) {


        $message = "EJ-EVENTMGMP0EFIL9XEV8YZAL7KCIUQ6NI5OREH4TSEB3TSRIF2SI1ROTAIDALG-JW";

        for ($i = 0; $i < strlen($tkey); $i++) {
            $key_array[] = $tkey[$i];
        }

        $enc_message = "";
        $kPos = 0;
        $chars_str = "WJ-GLADIATOR1IS2FIRST3BEST4HERO5IN6QUICK7LAZY8VEX9LIFEMP0";
        for ($i = 0; $i < strlen($chars_str); $i++) {
            $chars_array[] = $chars_str[$i];
        }
        for ($i = 0; $i < strlen($message); $i++) {
            $char = substr($message, $i, 1);

         $offset = $this->getOffset($key_array[$kPos], $char);
            $enc_message .= $chars_array[$offset];
            $kPos++;
            if ($kPos >= count($key_array)) {
                $kPos = 0;
            }
        }

        return $enc_message;
    }
}

$comClass = new appthaeventzClass();
$comClass->apiKey();

$controllerName = JRequest::getVar('view');

$controller_path=JPATH_COMPONENT . DS . 'controllers'.DS.$controllerName.'.php';


if (file_exists($controller_path)) {

        require_once $controller_path;

    }else {

            $controllerName='home';
            $controller_path=JPATH_COMPONENT . DS . 'controllers'.DS.$controllerName.'.php';
           require_once $controller_path;
    }

$controller_path=JPATH_COMPONENT . DS . 'controllers'.DS.$controllerName.'.php';
// Create the controller
$classname =  'appthaeventzController'.$controllerName;
$controller = new $classname( );
// Perform the Request task
$controller->execute(JRequest::getWord('task'));

// Redirect if set by the controller
$controller->redirect();