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
defined('_JEXEC') or die("Restricted Access");

class Tablesitesettings extends JTable
{
    //To create the tables
    public $id=null;
    public $fromemail=null;
    public $fromname=null;
    public $replyto=null;
    public $replytoname=null;
    public $cc=null;
    public $bcc=null;
    
    function Tablesitesettings(&$db)
    {
        parent::__construct('#__em_emailsettings','id',$db);
    }
}
?>