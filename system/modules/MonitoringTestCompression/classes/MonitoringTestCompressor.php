<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    MonitoringTestCompression
 * @license    LGPL
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Monitoring;

use Contao\Database;
/**
 * Class Monitoring
 *
 * Read the text from the given url and compare with test string.
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class MonitoringTestCompressor extends \Backend
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Executes a check
	 */
	public function compressOne()
	{
	    // ToDo: execute cempression here
	    $time = time();
	    $lastDay = mktime(0, 0, 0, date("m", $time), date("d", $time) - 1, date("Y", $time));
	    $this->compressDay($lastDay, \Input::get('id'));

	    \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['monitoringCompressedOne']);
	    $this->logDebugMsg("Compressed the test results of monitoring entry with ID " . \Input::get('id'), __METHOD__);

	    $urlParam = \Input::get('do');

	    if (\Input::get('table') == "tl_monitoring_test" && \Input::get('id'))
	    {
	        $urlParam .= "&table=tl_monitoring_test&id=" . \Input::get('id');
	    }

	    $this->returnToList($urlParam);
	}

	/**
	 * Check all monitoring entries
	 */
	public function compressAll()
	{
	    // ToDo: execute cempression here
	    \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['monitoringCompressedAll']);
	    $this->logDebugMsg("Compressed the test results of all monitoring entries.", __METHOD__);
	    $this->returnToList(\Input::get('do'));
	}


	/**
	 * Automatically (CRON triggered) compresses the test results of the last month.
	 */
	public function autoCompressLastMonth()
	{
	    if ($GLOBALS['TL_CONFIG']['monitoringAutoCompressionActive'] === TRUE)
	    {
	        $time = time();
	        $lastMonth = mktime(0, 0, 0, date("m", $time) - 1, 1, date("Y", $time));
	        $this->compressMonth($lastMonth);
	        $this->log('Automatically compressed the test results of the last month.', __METHOD__, TL_CRON);
	    }
	}

	/**
	 * Automatically (CRON triggered) compresses the test results of the last day.
	 */
	public function autoCompressLastDay()
	{
	    if ($GLOBALS['TL_CONFIG']['monitoringAutoCompressionActive'] === TRUE)
	    {
	        $time = time();
	        $lastDay = mktime(0, 0, 0, date("m", $time), date("d", $time) - 1, date("Y", $time));
	        $this->compressDay($lastDay);
	        $this->log('Automatically compressed the test results of the last day.', __METHOD__, TL_CRON);
	    }
	}

	/**
	 * Compresses the test results of the month starting at given timestamp.
	 *
	 * @param $tstampStartOfMonth The timestamp of the start of the month.
	 * @param $intEntryId The id of a monitoring entry (if null, the test results of all entries will be compressed).
	 */
	private function compressMonth($tstampStartOfMonth, $intEntryId=null)
	{
	    $dayCountOfMonth = date("t", $tstampStartOfMonth);

	    $this->logDebugMsg('Compressing ' . $dayCountOfMonth . ' days', __METHOD__);

	    $arrEntryIds = array();

	    if (is_int($intEntryId))
	    {
	        $arrEntryIds[] = $intEntryId;
	    }
	    else
	    {
	        $arrEntryIds = $this->getAllActiveMonitoringEntries();
	    }

	    foreach ($arrEntryIds as $entryId)
	    {
	        // at first compress the days

	        $tstampStartOfDay = $tstampStartOfMonth;
	        for ($i = 0; $i < $dayCountOfMonth; $i++)
	        {
	           $this->compressDay($tstampStartOfDay, $entryId);
	           $tstampStartOfDay = $this->addOneDay($tstampStartOfDay);
	        }

	        // now compress the month
	        $this->logDebugMsg('Compressed ' . $dayCountOfMonth . ' days at entry with ID ' . $entryId, __METHOD__);
	    }

	    $this->logDebugMsg('Compressed the test results of the month: ' . date("M Y", $tstampStartOfMonth), __METHOD__);
	}

	/**
	 * Compresses the test results of the day at given timestamp.
	 *
	 * @param $tstampStartOfDay The timestamp of the day.
	 * @param $intEntryId The id of a monitoring entry (if null, the test results of all entries will be compressed).
	 */
	private function compressDay($tstampStartOfDay, $intEntryId=null)
	{
	    $arrEntryIds = array();

	    if (is_int($intEntryId))
	    {
	        $arrEntryIds[] = $intEntryId;
	    }
	    else
	    {
	        $arrEntryIds = $this->getAllActiveMonitoringEntries();
	    }

	    foreach ($arrEntryIds as $entryId)
	    {
	        $objTest = Database::getInstance()->prepare("SELECT * FROM tl_monitoring_test WHERE date >= ? AND date < ? AND type = ? AND pid = ? ORDER BY date")
	        ->execute($tstampStartOfDay, $this->addOneDay($tstampStartOfDay), Monitoring::CHECK_TYPE_AUTOMATIC, $entryId);

	        // do the compression here

	        //$this->logDebugMsg('Found ' . $objTest->numRows. ' automatic test results for day ' . date("d.m.Y", $tstampStartOfDay) . ' at entry with ID ' . $entryId, __METHOD__);
	    }

	    $this->logDebugMsg('Compressed the test results of the day: ' . date("d.m.Y", $tstampStartOfDay), __METHOD__);
	}

	/**
	 * Redirect to the list.
	 */
	private function returnToList($act)
	{
	    $path = \Environment::get('base') . 'contao/main.php?do=' . $act;
	    $this->redirect($path, 301);
	}

	/**
	 * Logs the given message if the debug mode is anabled.
	 */
	private function logDebugMsg($msg, $origin)
	{
	    if ($GLOBALS['TL_CONFIG']['monitoringDebugMode'] === TRUE)
	    {
	        $this->log($msg, $origin, TL_INFO);
	    }
	}

	/**
	 * Returns the ids of all active monitoring entries.
	 *
	 * @return array An assoc array of the ids.
	 */
	private function getAllActiveMonitoringEntries()
	{
	    $strSelect = "SELECT id FROM tl_monitoring";

	    if ($GLOBALS['TL_CONFIG']['monitoringDebugMode'] === FALSE)
	    {
	        $strSelect .= " WHERE disable = ''";
	    }

	    $objId = Database::getInstance()->prepare($strSelect)
	                                    ->execute();

	    return $objId->fetchEach("id");
	}

	/**
	 * Increases the given timestamp by one day (24 hours).
	 *
	 * @param int $tstamp The timestamp which should be increased.
	 * @return int The increased timestamp.
	 */
	private function addOneDay($tstamp)
	{
	    return mktime(0, 0, 0, date("m", $tstamp), date("d", $tstamp) + 1, date("Y", $tstamp));
	}

	/**
	 * Increases the given timestamp by one month.
	 *
	 * @param int $tstamp The timestamp which should be increased.
	 * @return int The increased timestamp.
	 */
	private function addOneMonth($tstamp)
	{
	    return mktime(0, 0, 0, date("m", $tstamp) + 1, date("d", $tstamp), date("Y", $tstamp));
	}
}

?>