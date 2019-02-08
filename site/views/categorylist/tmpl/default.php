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
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
        <script type="text/javascript">
    
    var no_cat=jQuery.noConflict();

    function showing(show_id)
    {
        var show_id;
        no_cat('#showme_'+show_id).toggleClass("hidebox");
        no_cat('#showing_'+show_id).slideToggle("slow");
          if(no_cat('#showme_'+show_id).attr('class') == 'showbox show_hide hidebox')
          {
          no_cat('#showme_'+show_id).text('Hide');
          }
          else
          {
          no_cat('#showme_'+show_id).text('More');
          }
        no_cat('#showing_'+show_id).show();
    }
        </script>


        <div class="wraper">
            <h3><?php echo JText::_('EVENTS_LIST_CATEGORIES'); ?></h3>
            <?php
            $categoryList=$this->categoryList['category_list'];
            foreach ($categoryList as $category):
            $alias_name= JFilterOutput::stringURLSafe($category->name);
            $getEventCount = Jview::loadHelper('appthaeventz'); //got an error without this
            $getEventCount =  appthaeventzHelper::getEventCount($category->id);

            
            ?>
            <div class="list_categories clear">
                <a href="<?php echo JRoute::_('index.php?option=com_appthaeventz&view=eventlist&category_id=' . $category->id.':'.$alias_name); ?>" class="list_heading"><?php echo $category->name; ?> <small><?php echo '('.$getEventCount.' events)'; ?></small></a>
                <?php if($category->description != ''){ ?>
                <span class="para">
                    <?php echo substr($category->description,0,255);?>
                </span>
                <?php if(strlen($category->description) >= 255){ ?>
                <span style="display:none;" class="slidingDiv para" id="showing_<?php echo $category->id; ?>">
                    <?php echo substr($category->description,256)  ?>
                </span>
                <?php } if(strlen($category->description) >= 255){ ?>

                <a href="javascript:void(0)" id="showme_<?php echo $category->id; ?>" onclick="showing(<?php echo $category->id; ?>)"  class="showbox show_hide" >More</a>
                <?php } } ?>
                
            </div>

            

            <div class="clear"></div>
            <?php endforeach; ?>

            <?php echo $this->categoryList['pageNav']; ?>
        </div>


        