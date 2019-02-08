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

                                if (!isset($_GET["date"])) {
                                        if (!isset($_GET["x"])) {
                                            $x = date("n");
                                        } else {
                                            $x = $_GET["x"];
                                        }
                                        if ($x == "")
                                            $x = date("n");

                                        $year = date("Y");
                                        $date = strtotime("$year/$x/1");
                                        $day = date("D", $date);
                                        $m = date("F", $date);
                                    }
                                    else {
                                        $dateSplit = explode("__", $_GET["date"]);
                                        $x = $dateSplit[0];
                                        if ($x == "")
                                            $x = date("n");
                                        $year = $dateSplit[1];
                                        $date = strtotime("$year/$x/1");
                                        $day = date("D", $date);
                                        $m = date("F", $date);
                                    }

?>
<style type="text/css">
.calend {margin-top: 15px;}
table {border-collapse: separate;border-spacing: 0;}
.weekDays {background: white;}
.weekDays th {font-weight: bold;text-align: center;font-size: 9px;padding: 10px;}
body.webkit font {padding-top: 5px;}
.weekDays th {font-weight: bold;text-align: center;font-size: 9px;}
.calend tr td {font-weight: bold;border-bottom: 1px solid white!important;padding: 20px 34px;border-left: 1px solid white;background-color: #EEE;color: #999;}

</style>
<div id="calendarWrapper">
    <?php $totaldays = date("t", $date); //get the total day of specified date ?>
                                <table border='1' cellspacing='0' cellpadding='2' class="calend">
                                    <tr class='weekDays'>
                                        <th class="center"><font size = '1' face = 'tahoma'>Sun</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Mon</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Tue</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Wed</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Thu</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Fri</font></th>
                                        <th class="center"><font size = '1' face = 'tahoma'>Sat</font></th>
                                    </tr>
<?php
                                        if ($day == "Sun")
                                            $st = 1;
                                        if ($day == "Mon")
                                            $st = 2;
                                        if ($day == "Tue")
                                            $st = 3;
                                        if ($day == "Wed")
                                            $st = 4;
                                        if ($day == "Thu")
                                            $st = 5;
                                        if ($day == "Fri")
                                            $st = 6;
                                        if ($day == "Sat")
                                            $st = 7;

                                        if ($st >= 6 && $totaldays == 31) {
                                            $tl = 42;
                                        } elseif ($st == 7 && $totaldays == 30) {
                                            $tl = 42;
                                        } else {
                                            $tl = 35;
                                        }

                                        $ctr = 1;
                                        $d = 1;

                                        for ($i = 1; $i <= $tl; $i++) {

                                            if ($ctr == 1)
                                                echo "<tr>";

                                            if ($i >= $st && $d <= $totaldays) {


                                                if (strtotime("$year-$x-$d") < strtotime(date("Y-m-d"))) {
                                                    echo "<td align='center' class='previous days '><font size = '2' face = 'tahoma'>$d</font></td>";
                                                } else {
                                                    echo "<td class='normal days' align='center' ><font size = '2' face = 'tahoma'>$d</font></td>";
                                                }
                                                $d++;
                                            } else {
                                                echo "<td>&nbsp</td>";
                                            }

                                            $ctr++;

                                            if ($ctr > 7) {
                                                $ctr = 1;
                                                echo "</tr>";
                                            }
                                        }
?>
                                    </table>
                                    <div style="clear:both;margin-top:10px" >
                                        <span style="padding: 10px;background-color: #999;float:left;"></span><h2 style="padding:0 10px;font-weight:bold;float:left;font-size:15px;">Past</h2>
                                        <span style="padding: 10px;background-color: #ACDBA8;float:left;"></span><h2 style="padding:0 10px;font-weight:bold;float:left;font-size:15px;">Available</h2>
                                        <span style="padding: 10px;background-color: #E07272;float:left;"></span><h2 style="padding:0 10px;font-weight:bold;float:left;font-size:15px;">Booked</h2>
                                    </div>
                                </div>
