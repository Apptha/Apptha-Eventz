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
jimport('joomla.html.pane');

?>

<style type="text/css">
    div.cpanel-left {float: left;width:50% !important; }
    .text{padding:10px;margin-left:10px;}
</style>
<div class="contus-contropanel">

</div>
<div class="cpanel-left" >

    <div id="cpanel">
        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=events"); ?>" title="Events">
                <img src="components/com_appthaeventz/assets/images/event.png" title="Events" alt="Events">
                <span>Events</span></a>
        </div>

        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=categories"); ?>" title="Categories">
                <img src="components/com_appthaeventz/assets/images/categories.png" title="Categories" alt="Categories">
                <span>Categories</span></a>
        </div>

        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=subscriptions"); ?>" title="Subscriptions">
                <img src="components/com_appthaeventz/assets/images/subs.png" title="Subscriptions" alt="Subscriptions">
                <span>Subscriptions</span></a>
        </div>

        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=paymentintegration"); ?>" title="Payment Integration">
                <img src="components/com_appthaeventz/assets/images/payment.png" title="Payment Integration" alt="Payment Integration">
                <span>Payment Integration</span></a>
        </div>

        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=import&task=add"); ?>" title="Import">
                <img src="components/com_appthaeventz/assets/images/import.png" title="Import" alt="Import">
                <span>Import</span></a>
        </div>

        <div class="icon">
            <a href="<?php echo JRoute::_("index.php?option=com_appthaeventz&view=settings"); ?>" title="Settings">
                <img src="components/com_appthaeventz/assets/images/settings.png" title="Settings" alt="Settings">
                <span>Settings</span></a>
        </div>

  

    </div>
</div>
<div style="width:50%;float:right;">
    <?php
    $totalrevenues = $this->totalrevenues;
    $highratedevents = $this->highratedevents;
    $lastpayments = $this->lastpayments;
    $upcomingevents = $this->upcomingevents;
    $newsubscribers = $this->newsubscribers;
    $currentevents = $this->currentevents;
    $pane = JPane::getInstance('sliders');

    echo $pane->startPane('pane');
    echo $pane->startPanel('Total revenues', 'panel1'); ?>

    <div class="main-text">
        <div class="text">
            <?php if ($totalrevenues == "") {
                echo "<span style='margin-left:200px;'><img width='28px' height='20px' src='components/com_appthaeventz/assets/images/norevenues.gif' alt='No revenues' /><span style='position:absolute;margin-left:10px;margin-top:5px;'>No Revenues</span></span>";
            } else {
                echo "$" . $totalrevenues;
            } ?>
                </div>
            </div>
    <?php           echo $pane->endPanel();
            echo $pane->startPanel('Last payments done', 'panel3');
            if(count($lastpayments)){
?>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Event Name</th>
                        <th>Payment Method</th>
                        <th>Payment Status</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
<?php
            $i = 1;
            foreach ($lastpayments as $lastpayment) {
?>
                <tr>
                    <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $lastpayment->event_name; ?></td>
                <td class="center"><?php echo $lastpayment->payment_method; ?></td>
                <td class="center"><?php if(strtolower($lastpayment->payment_status) == "pending" || $lastpayment->payment_status == '1')echo "completed"; ?></td>
                <td class="center"><?php echo "$" . $lastpayment->payment_amount; ?></td>
    <?php $i++;
            }?>
                    </tr>
                </tbody>
            </table>
<?php }else {
    echo '<div class="main-text"><div class="text" align="center">No Results Found For Last Payments Done.</div></div>';
}?>
    <?php
            echo $pane->endPanel();
            echo $pane->startPanel('New subscribers', 'panel5');
            if(count($newsubscribers)){
?>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Event Name</th>
                        <th>Name</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
<?php $i = 1;
            foreach ($newsubscribers as $newsubscriber) { $subscribed_date = date('F d,Y H:i:s',strtotime($newsubscriber->subscribed_on) ); ?>
                <tr>
                    <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $newsubscriber->event_name; ?></td>
                <td class="center"><?php echo $newsubscriber->name; ?></td>
                <td class="center"><?php echo $subscribed_date; ?></td>

    <?php $i++;
            } ?>
                    </tr>
                </tbody>
            </table>
<?php }else {
    echo '<div class="main-text"><div class="text" align="center">No Subscribers Found.</div></div>';
}
     ?>
<?php
            echo $pane->endPanel();
            echo $pane->startPanel('High rated events', 'panel2');

            if(count($highratedevents)){
?>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Event Name</th>
                    </tr>
                </thead>
                <tbody>
<?php $i = 1;
            foreach ($highratedevents as $highratedevent) { ?>
                <tr>
                    <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $highratedevent->event_name; ?></td>
               
<?php $i++;
            } ?>
                    </tr>
                </tbody>
            </table>
<?php
            }else{
                echo '<div class="main-text"><div class="text" align="center">No High Rated Events Found.</div></div>';
            }
?>
 
 <?php
            echo $pane->endPanel();
            echo $pane->startPanel('Upcoming events', 'panel4');
               if(count($upcomingevents)){
           
?>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Event Name</th>
                        <th>Subscribers</th>
                        <th>Start Date</th>
                       
                    </tr>
                </thead>
                <tbody>
<?php $i = 1; 
            foreach ($upcomingevents as $upcomingevent) { $start_date = date('F d,Y H:i:s',strtotime($upcomingevent->start_date) );?>
                <tr>
                    <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $upcomingevent->event_name; ?></td>
                <td class="center"><?php echo $upcomingevent->subscriber_count; ?></td>
                <td class="center"><?php echo $start_date; ?></td>
                

    <?php $i++;
            } ?>
                    </tr>
                </tbody>
            </table>
<?php }else {
    echo '<div class="main-text"><div class="text" align="center">No Upcoming Events Found.</div></div>';
}
           ?>
 
 <?php       echo $pane->endPanel();
            echo $pane->startPanel('Current events', 'panel6');
            if(count($currentevents)){
?>
            <table class="adminlist">
                <thead>
                    <tr>
                        <th>Sno</th>
                        <th>Event Name</th>
                        <th>Subscribers</th>
                        <th>Start Date</th>
                        

                    </tr>
                </thead>
                <tbody>
<?php $i = 1;  
            foreach ($currentevents as $currentevent) { $start_date = date('F d,Y H:i:s',strtotime($currentevent->start_date) ); ?>
             <?php



?>
                    <tr>
                    <td class="center"><?php echo $i; ?></td>
                <td class="center"><?php echo $currentevent->event_name; ?></td>
                <td class="center"><?php echo $currentevent->subscriber_count; ?></td>
                <td class="center"><?php echo $start_date; ?></td>
               

    <?php $i++;
            } ?>
                    </tr>
                </tbody>
            </table>
<?php }else {
    echo '<div class="main-text"><div class="text" align="center">No Current Events Found.</div></div>';
}?>


    
 <?php
            echo $pane->endPanel();
            echo $pane->endPane();
?>

</div>






