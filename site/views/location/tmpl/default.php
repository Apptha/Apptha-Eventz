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
// No direct Access
defined('_JEXEC') or die('Restricted access');
$document = JFactory::getDocument();
$document->addStyleSheet('components/com_appthaeventz/css/location.css');
$location_lists = $this->locationlist['location_list'];


?>

        
        <div class="wraper">
            <h2><?php echo JText::_('EVENTS_LOCATION_LIST');?></h2>
           <?php   foreach ($location_lists as $location_list){
                  $alias_name= JFilterOutput::stringURLSafe($location_list->location_name);
               ?>
            
            <div class="list_location clear">
               

                 <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=eventlist&location_id=' . $location_list->location_id); ?>" class="list_heading"><?php echo $location_list->location_name; ?> <small>(<?php echo $location_list->id ?> events)</small></a>
                <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=locationmap&location=' . $location_list->location_id.':'.$alias_name); ?>" class="view_grid"></a>
                <span class="para"> <?php echo "" ?></span>


            </div>
            <?php } ?>
              <?php echo $this->locationlist['pageNav']; ?>
             <div class="clear"></div>
        </div>
   
