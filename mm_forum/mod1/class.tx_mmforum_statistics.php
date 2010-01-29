<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2007 Mittwald CM Service
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 *
 *
 *   60: class tx_mmforum_statistics
 *   80:     function main($content)
 *  111:     function getStartTime()
 *  128:     function displayMenu()
 *  224:     function getDateTitleLink($start, $stop, $colspan=3,$padding=2)
 *  241:     function stat_forum()
 *  321:     function stat_dayOfMonth()
 *  387:     function stat_dayOfYear()
 *  480:     function stat_monthOfYear()
 *  546:     function stat_timeOfDay()
 *  617:     function additionalStats()
 *  684:     function defaultData()
 *  739:     function getLL($key)
 *  750:     function init()
 *
 * TOTAL FUNCTIONS: 13
 * (This index is automatically created/updated by the extension "extdeveval")
 *
 */

/**
 * This class contains the mm_forum statistics module. It gives information
 * on how much data records were created in which period of time and so
 * on.
 *
 * @author     Martin Helmich <m.helmich@mittwald.de>
 * @version    2007-05-31
 * @copyright  2007 Mittwald CM Service
 * @package    mm_forum
 * @subpackage Backend
 */
class tx_mmforum_statistics {

    /**
     * Defines in which database field the creation date is stored.
     */
    var $timeFields = array(
        'tx_mmforum_posts'      => 'post_time',
        'tx_mmforum_topics'     => 'topic_time',
        'tx_mmforum_pminbox'    => 'sendtime',
        'fe_users'              => 'crdate'
    );

    /**
     * The module's main function.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     * @param   string $content The content
     * @return  string          The content
     */
    function main($content) {

        $this->init();

        $this->defaultData();

        $content = $this->displayMenu();

        switch($this->param['groupBy']) {
            case 'tod':     $content .= $this->stat_timeOfDay(); break;
            case 'dom':     $content .= $this->stat_dayOfMonth(); break;
            case 'doy':     $content .= $this->stat_dayOfYear(); break;
            case 'moy':     $content .= $this->stat_monthOfYear(); break;
            case 'frm':     $content .= $this->stat_forum(); break;
        }

        $content .= $this->additionalStats();

        return $content;

    }

    /**
     * Determines the time that is to be concerned as "forum starting time".
     * For this time, it is determined when the first post was created,
     * and this post's creation date is used as "forum starting time".
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     * @return  int The forum starting time as UNIX-timestamp
     */
    function getStartTime() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'MIN(post_time)',
            'tx_mmforum_posts',
            'deleted=0'
        );
        list($minPostTime) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        return $minPostTime;
    }

    /**
     * Displays the statistic module configuration module.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     * @return  string The menu content
     */
    function displayMenu() {
        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'MIN(post_time)',
            'tx_mmforum_posts',
            'deleted=0'
        );
        list($minPostTime) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $first_Year     = intval(date("Y",$minPostTime));

        $sOption_Years = '<option value="all">'.$this->getLL('menu.all').'</option>';
        for($i=date("Y"); $i >= $first_Year; $i --) {
            $sel = ($this->param['year']==$i)?'selected="selected"':'';
            $sOption_Years .= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
        }

        $sOption_Months = '<option value="all">'.$this->getLL('menu.all').'</option>';
        for($i=1; $i <= 12; $i ++) {
            $sel = ($this->param['month']==$i)?'selected="selected"':'';
            $sOption_Months .= '<option value="'.$i.'" '.$sel.'>'.$this->getLL('months.'.$i).'</option>';
        }

        $sOption_Days = '<option value="all">'.$this->getLL('menu.all').'</option>';
        for($i=1; $i <= 31; $i ++) {
            $sel = ($this->param['day']==$i)?'selected="selected"':'';
            $sOption_Days .= '<option value="'.$i.'" '.$sel.'>'.$i.'</option>';
        }

        $content .= '
    <table cellspacing="0" cellpadding="2">
        <tr>
            <td>'.$this->getLL('menu.table').'</td>
            <td>
                <select name="tx_mmforum_stats[table]">
                    <option value="tx_mmforum_posts" '.(($this->param['table']=='tx_mmforum_posts')?'selected="selected"':'').'>'.$this->getLL('menu.table.posts').'</option>
                    <option value="tx_mmforum_topics" '.(($this->param['table']=='tx_mmforum_topics')?'selected="selected"':'').'>'.$this->getLL('menu.table.topics').'</option>
                    <option value="tx_mmforum_pminbox" '.(($this->param['table']=='tx_mmforum_pminbox')?'selected="selected"':'').'>'.$this->getLL('menu.table.pms').'</option>
                    <option value="fe_users" '.(($this->param['table']=='fe_users')?'selected="selected"':'').'>'.$this->getLL('menu.table.users').'</option>
                </select>
            </td>
            <td>'.$this->getLL('menu.group').'</td>
            <td>
                <select name="tx_mmforum_stats[groupBy]">
                    <option value="tod" '.(($this->param['groupBy']=='tod')?'selected="selected"':'').'>'.$this->getLL('menu.group.tod').'</option>
                    <option value="dom" '.(($this->param['groupBy']=='dom')?'selected="selected"':'').'>'.$this->getLL('menu.group.dom').'</option>
                    <option value="doy" '.(($this->param['groupBy']=='doy')?'selected="selected"':'').'>'.$this->getLL('menu.group.doy').'</option>
                    <option value="moy" '.(($this->param['groupBy']=='moy')?'selected="selected"':'').'>'.$this->getLL('menu.group.month').'</option>
                    <option value="frm" '.(($this->param['groupBy']=='frm')?'selected="selected"':'').'>'.$this->getLL('menu.group.forum').'</option>
                </select>
            </td>
            <td>'.$this->getLL('menu.day').'</td>
            <td>
                <select name="tx_mmforum_stats[day]">
                    '.$sOption_Days.'
                </select>
            </td>
            <td>'.$this->getLL('menu.month').'</td>
            <td>
                <select name="tx_mmforum_stats[month]">
                    '.$sOption_Months.'
                </select>
            </td>
            <td>'.$this->getLL('menu.year').'</td>
            <td>
                <select name="tx_mmforum_stats[year]">
                    '.$sOption_Years.'
                </select>
            </td>
        </tr>
        <tr>
            <td>Display mode</td>
            <td colspan="9">
                <input type="radio" name="tx_mmforum_stats[mode]" '.(($this->param['mode']=='total')?'checked="checked"':'').' value="total" style="vertical-align:middle;" /> Total
                <input type="radio" name="tx_mmforum_stats[mode]" '.(($this->param['mode']=='grad')?'checked="checked"':'').' value="grad" style="vertical-align:middle;" /> Gradient
            </td>
        </tr>
    </table>
    <input type="submit" value="'.$this->getLL('menu.update').'" />
';
        return $content;
    }

    /**
     * Generates a table header.
     * This function generates a table header for the statistics output.
     * This table header contains the starting and the stop date of the
     * period that is displayed.
     *
     * @param   int    The starting date
     * @param   int    The stop date
     * @param   int    The colspan for the table header
     * @param   int    The cell padding for the whole table
     * @return  string The table header
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function getDateTitleLink($start, $stop, $colspan=3,$padding=2) {
        $sStop = ($stop==time())?$this->getLL('now'):date("d. m. Y, H:i:s",$stop);
        return '<br /><table cellspacing="0" cellpadding="'.$padding.'" style="width:100%" class="mm_forum-list">
    <tr>
        <td class="mm_forum-listrow_header" colspan="'.$colspan.'">'.date("d. m. Y, H:i:s",$start).' &mdash; '.$sStop.'</td>
    </tr>
';
    }

    /**
     * Displays a statistic grouped by the forum the records are located in.
     * This is only possible for posts and topics.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function stat_forum() {
        $tstamp_Start   = $this->param['start'];
        $tstamp_Stop    = $this->param['stop'];
        $table          = $this->param['table'];
        $timeField      = $this->timeFields[$table];

        if(!in_array($table,array('tx_mmforum_posts','tx_mmforum_topics'))) return "<br />The selected display mode is not supported.";

        $content .= $this->getDateTitleLink($tstamp_Start,$tstamp_Stop,3,0);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            $table,
            "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0"
        );
        list($total_amount) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            '*',
            'tx_mmforum_forums',
            'deleted=0 AND parentID=0',
            '',
            'sorting'
        );
        $i = 0;
        while($ctg = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {

            $ctgRecords = 0;
            $ctgContent = '';
            $res2 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                'tx_mmforum_forums',
                'deleted=0 AND parentID='.$ctg['uid'],
                '',
                'sorting'
            ); $ctgI = $i;
            $i ++;
            while($frm = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res2)) {

                $res3 = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'COUNT(*)',
                    $table,
                    "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0 AND forum_id=".$frm['uid']
                );
                list($records) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res3);
                $ctgRecords += $records;

                $width    = $total_amount?round($records / $total_amount * 100,2):0;
                $color = (($i%2)?'#e00000':'#ff0000');
                $ctgContent .= '<tr class="mm_forum-listrow'.(($i%2)?'2':'').'">
        <td style="width:1px; white-space:nowrap; padding: 2px; padding-left:16px;">'.$frm['forum_name'].'</td>
        <td style="width:1px; padding:2px;">'.$records.'</td>
        <td style="width:100%;"><div style="background-color: '.$color.'; height: 14px; width:'.$width.'%; border-right: 1px solid #660000; border-bottom: 1px solid #660000; border-top: 1px solid #ff6666;">&nbsp;</div></td>
        </tr>';
                $i ++;

            }

            $width    = $total_amount?round($ctgRecords / $total_amount * 100,2):0;
            $color = (($ctgI%2)?'#e00000':'#ff0000');
            $content .= '<tr class="mm_forum-listrow'.(($ctgI%2)?'2':'').'">
        <td style="width:1px; white-space:nowrap; padding:2px;">'.$ctg['forum_name'].'</td>
        <td style="width:1px; padding:2px;">'.$ctgRecords.'</td>
        <td><div style="background-color: '.$color.'; height: 14px; width:'.$width.'%; border-right: 1px solid #660000; border-bottom: 1px solid #660000; border-top: 1px solid #ff6666;">&nbsp;</div></td>
        </tr>';
            $content .= $ctgContent;

        }

        $content .= '</table>';

        return $content;
    }

    /**
     * Displays a statistic grouped by the day of month.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function stat_dayOfMonth() {
        $tstamp_Start   = $this->param['start'];
        $tstamp_Stop    = $this->param['stop'];
        $table          = $this->param['table'];
        $timeField      = $this->timeFields[$table];

        $dayCount = intval(date('t',$tstamp_Start));

        if($this->param['mode']=='total') {

            if($this->param['month']=='all') return '<br /><br />'.$this->getLL('error.noMonth').'<br /><br />';
            if($this->param['year']=='all') return '<br /><br />'.$this->getLL('error.noYear').'<br /><br />';

            $tTstamp_Stop = $tstamp_Start;
            $maxValue       = 0;
            for($i=1; $i <= $dayCount; $i ++) {
                $tTstamp_Stop += 86400;
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'COUNT(*)',
                    $table,
                    "$timeField <= $tTstamp_Stop AND deleted=0"
                );
                list($num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                $results[$i] = $num;
                if($num > $maxValue) $maxValue = $num;
            }
        }
        else {
            $results = array();
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                $table,
                "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0"
            );
            $maxValue       = 0;
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $day = intval(date("j",$arr[$timeField]));
                $results[$day] ++;

                if($results[$day] > $maxValue) $maxValue = $results[$day];
            }
        }

        $content .= $this->getDateTitleLink($tstamp_Start,$tstamp_Stop,3,0);

        for($i=1; $i <= $dayCount; $i ++) {
            $result = intval($results[$i]);
            $width    = $maxValue?round($result / $maxValue * 100,2):0;
            $color = (($i%2)?'#e00000':'#ff0000');
            $content .= '<tr class="mm_forum-listrow'.(($i%2)?'2':'').'">
    <td style="width:1px; white-space:nowrap; text-align:right; font-weight: bold; padding: 2px;">'.$i.'.</td>
    <td style="width:1px; padding: 2px;">'.$result.'</td>
    <td><div style="background-color: '.$color.'; width:'.$width.'%; border-right: 1px solid #660000; border-bottom: 1px solid #660000; border-top: 1px solid #ff6666; height:14px;">&nbsp;</div></td>
</tr>';
        }
        $content .= '</table>';

        return $content;
    }

    /**
     * Displays a statistic grouped by the day of year.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function stat_dayOfYear() {
        $tstamp_Start   = $this->param['start'];
        $tstamp_Stop    = $this->param['stop'];
        $table          = $this->param['table'];
        $timeField      = $this->timeFields[$table];

        $results = array();

        if($this->param['mode']=='total') {

            if($this->param['year']=='all') return '<br /><br />'.$this->getLL('error.noYear').'<br /><br />';

            $tTstamp_Stop = $tstamp_Start;
            $maxValue       = 0;
            for($i=1; $i <= 12; $i ++) {
                $dayCount = date('t',mktime(12,0,0,$i,1,$this->param['year']));
                for($d=1; $d <= $dayCount; $d ++) {
                    $tTstamp_Stop += 86400;
                    if($tTstamp_Stop > time()) { $results[$i][$d] = 0; continue; }
                    $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                        'COUNT(*)',
                        $table,
                        "$timeField <= $tTstamp_Stop AND deleted=0"
                    );
                    list($num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                    $results[$i][$d] = $num;
                    if($num > $maxValue) $maxValue = $num;
                }

            }
        }
        else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                $table,
                "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0"
            );
            $maxValue       = 0;
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $month = intval(date("m",$arr[$timeField]));
                $day   = intval(date("d",$arr[$timeField]));
                $results[$month][$day] ++;

                if($results[$month][$day] > $maxValue) $maxValue = $results[$month][$day];
            }
            ksort($results);
        }

        $content .= $this->getDateTitleLink($tstamp_Start,$tstamp_Stop,2,0);

        $i = 1;
        for($month = 1; $month <= 12; $month ++) {
            $days = $results[$month];
            $content .= '<tr class="mm_forum-listrow'.(($i%2)?'2':'').'">
    <td style="width:1px; white-space:nowrap; text-align:left; padding:0px 2px;">'.$this->getLL('months.'.$month).'</td>
    <td style="width:100%;">';

            $color = (($i%2)?'#e00000':'#ff0000');
            $dayCount = date("t",mktime(0,0,0,$month,1,$this->param['year']));
            for($day = 1; $day <= $dayCount; $day ++) {
                $result   = intval($results[$month][$day]);
                $width    = $maxValue?round($result / $maxValue * 100,2):0;

                $tColor = $color;
                $bWidth = 1;
                if($width >= 1) {
                    if($day == 1) $tColor = '#ff6666';
                    if($day == $dayCount) $tColor = '#660000';
                } else $bWidth = 0;

                $content  .= '<div style="background-color: '.$tColor.'; width:'.$width.'%; height:1px; border-right: '.$bWidth.'px solid #660000;"></div>'."\r\n";
                if($result == $maxValue && $this->param['mode']!='total') $content .= '<div style="float:right">'.$maxValue.'</div>';
            }

            $content .= '
    </td>
</tr>';
            $i ++;
        }

        if($this->param['mode']=='total') $content .= '<tr><td colspan="2"><div style="float:right">'.$maxValue.'</div></td></tr>';

        $content .= '</table>';

        return $content;
    }

    /**
     * Displays a statistic grouped by the month of year.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function stat_monthOfYear() {
        $tstamp_Start   = $this->param['start'];
        $tstamp_Stop    = $this->param['stop'];
        $table          = $this->param['table'];
        $timeField      = $this->timeFields[$table];

        $results = array();
        if($this->param['mode']=='total') {
            $tTstamp_Stop = $tstamp_Start;

            if($this->param['year']=='all') return '<br /><br />'.$this->getLL('error.noYear').'<br /><br />';

            $maxValue       = 0;
            for($i=0;$i<=12;$i++) {
                $tTstamp_Stop += 86400 * date("t",mktime(0,0,0,$i,1,$this->param['year']));
                if($tTstamp_Stop > time()) { $results[$i] = 0; continue; }
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'COUNT(*)',
                    $table,
                    "$timeField <= $tTstamp_Stop AND deleted=0"
                );
                list($num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                $results[$i] = $num;
                if($num > $maxValue) $maxValue = $num;
            }
        }
        else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                $table,
                "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0"
            );
            $maxValue       = 0;
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $month = intval(date("m",$arr[$timeField]));
                $results[$month] ++;

                if($results[$month] > $maxValue) $maxValue = $results[$month];
            }
        }

        $dayCount = intval(date('t',$tstamp_Start));

        $content .= $this->getDateTitleLink($tstamp_Start,$tstamp_Stop,3,0);

        for($i=1; $i <= 12; $i ++) {
            $result = intval($results[$i]);
            $width    = $maxValue?round($result / $maxValue * 100,2):0;
            $color = (($i%2)?'#e00000':'#ff0000');
            $content .= '<tr class="mm_forum-listrow'.(($i%2)?'2':'').'">
    <td style="width:1px; white-space:nowrap; text-align:right; padding:2px;">'.$this->getLL('months.'.$i).'</td>
    <td style="width:1px; padding: 2px;">'.$result.'</td>
    <td><div style="background-color: '.$color.'; width:'.$width.'%; border-right: 1px solid #660000; border-top: 1px solid #ff6666; border-bottom: 1px solid #660000; height:14px;">&nbsp;</div></td>
</tr>';
        }
        $content .= '</table>';

        return $content;
    }

    /**
     * Displays a statistic grouped by the time of day.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function stat_timeOfDay() {
        $tstamp_Start   = $this->param['start'];
        $tstamp_Stop    = $this->param['stop'];
        $table          = $this->param['table'];
        $timeField      = $this->timeFields[$table];

        $tstamp_Span    = $tstamp_Stop - $tstamp_Start;
        $hours          = $tstamp_Span / 60 / 60;

        $results = array();

        if($this->param['mode']=='total') {
            $tTstamp_Stop = $tstamp_Start;

            if($this->param['month']=='all') return '<br /><br />'.$this->getLL('error.noMonth').'<br /><br />';
            if($this->param['day']=='all') return '<br /><br />'.$this->getLL('error.noDay').'<br /><br />';
            if($this->param['year']=='all') return '<br /><br />'.$this->getLL('error.noYear').'<br /><br />';

            $maxValue       = 0;
            for($i=0;$i<24;$i++) {
                $tTstamp_Stop += 3600*$i;
                if($tTstamp_Stop > time()) { $results[$i] = 0; continue; }
                $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                    'COUNT(*)',
                    $table,
                    "$timeField <= $tTstamp_Stop AND deleted=0"
                );
                list($num) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
                $results[$i] = $num;
                if($num > $maxValue) $maxValue = $num;
            }
        }
        else {
            $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
                '*',
                $table,
                "$timeField >= $tstamp_Start AND $timeField <= $tstamp_Stop AND deleted=0"
            );
            $maxValue       = 0;
            while($arr = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
                $hour = intval(date("H",$arr[$timeField]));
                $results[$hour] ++;

                if($results[$hour] > $maxValue) $maxValue = $results[$hour];
            }
        }

        $content .= $this->getDateTitleLink($tstamp_Start,$tstamp_Stop,4,0);

        for($i=0; $i < 24; $i ++) {
            $result = intval($results[$i]);
            $avg    = round($result / $hours,2);
            $width    = $maxValue?round($result / $maxValue * 100,2):0;
            $color = (($i%2)?'#e00000':'#ff0000');
            $content .= '<tr class="mm_forum-listrow'.(($i%2)?'2':'').'">
    <td style="width:1px; white-space:nowrap; text-align:right; padding:2px;">'.$i.':00</td><td style="width:1px; white-space:nowrap;">&mdash; '.$i.':59</td>
    <td style="width:1px; padding:2px;">'.$result.'</td>
    <td><div style="background-color: '.$color.'; width:'.$width.'%; height:14px; border-right: 1px solid #660000; border-bottom: 1px solid #660000; border-top: 1px solid #ff6666;">&nbsp;</div></td>
</tr>';
        }
        $content .= '</table>';

        return $content;
    }

    /**
     * Displays additional statistics.
     *
     * @return  The statistic table
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-31
     */
    function additionalStats() {
        $startTime  = $this->getStartTime();
        $span       = time() - $startTime;
        $days       = round($span / 86400);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'tx_mmforum_posts',
            'deleted=0'
        );
        list($post_count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $post_average = round($post_count / $days,4);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'tx_mmforum_topics',
            'deleted=0'
        );
        list($topic_count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $topic_average = round($topic_count / $days,4);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'fe_users',
            'deleted=0 AND crdate >= '.$startTime
        );
        list($user_count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $user_average = round($user_count / $days,4);

        $res = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
            'COUNT(*)',
            'tx_mmforum_pminbox',
            'deleted=0 AND sendtime > '.$startTime
        );
        list($pm_count) = $GLOBALS['TYPO3_DB']->sql_fetch_row($res);
        $pm_count /= 2;
        $pm_average = round($pm_count / $days,4);

        $content .= '
    <table cellspacing="0" cellpadding="2">
        <tr>
            <td>'.$this->getLL('menu.table.posts').' ('.$this->getLL('additional.totavg').')</td>
            <td>'.$post_count.' / '.$post_average.'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('menu.table.topics').' ('.$this->getLL('additional.totavg').')</td>
            <td>'.$topic_count.' / '.$topic_average.'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('menu.table.users').' ('.$this->getLL('additional.totavg').')</td>
            <td>'.$user_count.' / '.$user_average.'</td>
        </tr>
        <tr>
            <td>'.$this->getLL('menu.table.pms').' ('.$this->getLL('additional.totavg').')</td>
            <td>'.$pm_count.' / '.$pm_average.'</td>
        </tr>
    </table>
';

        return $content;
    }

    /**
     * Generates the default parameters for displaying the statistics table correctly.
     */
    function defaultData() {
        if(!$this->param) {
            $this->param = array(
                'groupBy'       => 'doy',
                'table'         => 'tx_mmforum_posts',
                'year'          => date('Y'),
                'mode'          => 'total'
            );
        }

        if($this->param['groupBy'] == 'doy') {
            $this->param['month'] = 'all';
            $this->param['day']   = 'all';
        }
        if($this->param['groupBy'] == 'dom') {
            $this->param['day']   = 'all';
        }
        if($this->param['groupBy'] == 'moy') {
            $this->param['day']   = 'all';
        }

        if($this->param['year'] == 'all') {
            $start = $this->getStartTime();
            $stop  = time();
        }
        else {
            if($this->param['month'] == 'all') {
                $start      = mktime(0,0,0,1,1,$this->param['year']);
                $stop       = mktime(23,59,59,12,31,$this->param['year']);
            }
            else {
                if($this->param['day'] == 'all') {
                    $start      = mktime(0,0,0,$this->param['month'],1,$this->param['year']);
                    $stop       = mktime(0,0,0,$this->param['month']+1,1,$this->param['year'])-1;
                }
                else {
                    $start      = mktime(0,0,0,$this->param['month'],$this->param['day'],$this->param['year']);
                    $stop       = $start + 86399;
                }
            }
        }
        $this->param['start']   = $start;
        $this->param['stop']    = $stop;
    }

    /**
     * Gets a language variable from the locallang_forumadmin.xml file.
     * Wrapper function to simplify retrieval of language dependent
     * strings.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-29
     * @param   string $key The language string key
     * @return  string      The language string
     */
    function getLL($key) {
        return $GLOBALS['LANG']->getLL('statistics.'.$key);
    }

    /**
     * Initializes the forum administration tool.
     *
     * @author  Martin Helmich <m.helmich@mittwald.de>
     * @version 2007-05-29
     * @return  void
     */
    function init() {
        $this->param = t3lib_div::_GP('tx_mmforum_stats');
        $this->conf = $this->p->config['plugin.']['tx_mmforum.'];
        $this->pid  = intval($this->conf['storagePID']);

        $this->func = $this->p->MOD_SETTINGS['function'];

        $GLOBALS['LANG']->includeLLFile('EXT:mm_forum/mod1/locallang_statistics.xml');
    }
}

if (defined("TYPO3_MODE") && $TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/mod1/class.tx_mmforum_statistics.php"])    {
    include_once($TYPO3_CONF_VARS[TYPO3_MODE]["XCLASS"]["ext/mm_forum/mod1/class.tx_mmforum_statistics.php"]);
}
?>