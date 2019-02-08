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
$document->addStyleSheet('components/com_appthaeventz/css/style.css');

?>

        <div class="wraper">
            <h3><?php echo JText::_('EVENTS_LIST_EVENTS'); ?></h3>
            <?php
            
              $category_events=$this->events['event_list'];
              if(!empty($category_events)){
                foreach($category_events as $category_event)
                {
            ?>
            <div class="list_events clear">
                <?php
                    $alias_name= JFilterOutput::stringURLSafe($category_event->event_name);
                    $path = JRoute::_('index.php?option=com_appthaeventz&view=events&events_id=' . $category_event->id.':'.$alias_name);
                    $img = ($category_event->image_name != '')?JURI::base().'images/appthaeventz/thumb_'.$category_event->image_name:'';
                    if($category_event->image_name != ''){
                ?>
                <a href="<?php echo $path; ?>" class="events_pic">  <img src="<?php echo $img; ?>" border="0" alt="" title="" /></a>
                <?php }
                    $alias_locname= JFilterOutput::stringURLSafe($category_event->location_name);
                    $locPath = JRoute::_('index.php?option=com_appthaeventz&view=locationmap&location='.$category_event->location_id.':'.$alias_locname);
                    
                ?>
                <div class="event_details">
                    <a href="<?php echo $path; ?>" class="event_title"> <?php echo $category_event->event_name; ?> </a>
                    <pre class="date_grid"><i class="calender_pic"></i> <span><?php echo JText::_('EVENTS_FROM'); ?>&nbsp;<b><?php $getFormat = json_decode($this->events['dateSet']->json_data_general); if(!empty($getFormat)) $format = $getFormat[1]." ".$getFormat[2]; echo date($format,strtotime($category_event->start_date)); ?></b>&nbsp;<?php echo JText::_('EVENTS_TO'); ?>&nbsp;<b><?php echo date($format,strtotime($category_event->end_date) ); ?></b></span></pre>
                    <span class="clearblock"><?php echo JText::_('EVENTS_AT'); ?> :<a href="<?php echo $locPath; ?>"><?php echo $category_event->location_name; ?></a></span>
                    <span class="clearblock"><?php echo JText::_('EVENTS_CATEGORIES'); ?>: 
                        
                            <?php
                                
                                $a = array();
                                $listCat = $category_event->category_id;
                                $cat = explode(',',$listCat);
                                if(count($cat) > 0){
                                foreach($cat as $key=>$c):
                                    $r = $this->categories[$c]->name;
                                    $alias_name = JFilterOutput::stringURLSafe($r);
                                    $catPath = JRoute::_('index.php?option=com_appthaeventz&view=eventlist&category_id=' . $c.':'.$alias_name);
                                    
                            ?>

                                <a href="<?php echo $catPath; ?>">
                                    <?php echo $r; ?>
                                </a>
                            <?php
                                    if(count($cat) != $key+1 ){echo ",";}
                                endforeach;
                                }
                            ?>
                             
                    </span>
                    <?php 
                        $getTags = Jview::loadHelper('appthaeventz'); //got an error without this
                        $getTags =  appthaeventzHelper::tagsList($category_event->id);
                            
                        if(count($getTags) > 0){ ?>
                    <span class="clearblock"><?php echo JText::_('EVENTS_TAGS'); ?>:
                        <?php
                            
                            foreach($getTags as $key=>$tag):
                                $tagPath = JRoute::_('index.php?option=com_appthaeventz&view=eventlist&tag_name=' . $tag->tag_name);
                        ?>
                        <a href="<?php echo $tagPath; ?>">
                            <?php echo $tag->tag_name; ?>
                        </a>
                        <?php if(count($getTags) != $key+1 ){echo ",";} endforeach; ?>
                    </span>
                    <?php } ?>
                </div>
                <div class="clear"></div>
            </div>
            <?php } ?>


            <?php echo $this->events['pageNav']; ?>

             <div class="clear"></div>
          <?php } ?>
        </div>