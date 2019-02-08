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
$app = JFactory::getApplication();
$searchResult_events=array();
$searchResults = $this->searchResult;
$searchResult_events=$searchResults['event'];
echo 'events';
foreach ($searchResult_events as $searchResult) {
    echo $searchResult->event_name;
    echo '<br/>';
    
}
echo '<br/>';
$searchResult_location=$searchResults['location'];
echo 'location';
foreach ($searchResult_location as $searchResult) {
    echo $searchResult->location_name;
    echo '<br/>';
    
}
echo '<br/>';
$searchResult_category=$searchResults['categories'];
echo 'categories';
foreach ($searchResult_category as $searchResult) {
    echo $searchResult->name;
    echo '<br/>';
    
}
?>

