<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2016 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2016-2016
 * @author     Cliff Parnitzky
 * @package    MonitoringCompression
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
 * @copyright  Cliff Parnitzky 2016-2016
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class MonitoringCompressor extends \Backend
{
    const COMPRESSION_NONE       = '';
    const COMPRESSION_DAY        = 'DAY';
    const COMPRESSION_MONTH      = 'MONTH';
    const COMPRESSION_IMPOSSIBLE = 'IMPOSSIBLE';

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
      $this->compressMonitoringEntry(\MonitoringModel::findByPk(\Input::get('id')));

      \Message::addConfirmation(sprintf($GLOBALS['TL_LANG']['MSC']['monitoringCompressedOne'], \Input::get('id')));
      $this->logDebugMsg("Finished compression of the test results of monitoring entry with ID " . \Input::get('id'), __METHOD__);

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
      $arrMonitoringEntryIds = \MonitoringModel::findAll()->fetchEach("id");
      foreach ($arrMonitoringEntryIds as $intMonitoringEntryId)
      {
        $objMonitoringEntry = \MonitoringModel::findByPk($intMonitoringEntryId);
        $this->compressMonitoringEntry($objMonitoringEntry);
      }

      \Message::addConfirmation($GLOBALS['TL_LANG']['MSC']['monitoringCompressedAll']);
      $this->logDebugMsg("Compressed the test results of all monitoring entries.", __METHOD__);

      $this->returnToList(\Input::get('do'));
  }

  /**
   * Automatically (CRON triggered) compresses the test results of the last day.
   */
  public function autoCompressLastDay()
  {
    if ($GLOBALS['TL_CONFIG']['monitoringAutoCompressionActive'] === TRUE)
    {
      $this->compressDay($this->getLastDay());
      $this->log('Automatically compressed the test results of the last day.', __METHOD__, TL_CRON);
    }
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
          // TODO add function
          //$this->compressMonth($lastMonth);
          //$this->log('Automatically compressed the test results of the last month.', __METHOD__, TL_CRON);
      }
  }

  /**
   * Compress the test results of the given monitoring entry.
   *
   * @param $objMonitoringEntry The monitoring entry (if null, the test results of all entries will be compressed).
   */
  private function compressMonitoringEntry($objMonitoringEntry)
  {
    if ($objMonitoringEntry != null)
    {
      $lastDay = $this->getLastDay();

      $objTest = \Database::getInstance()->prepare("SELECT MIN(date) AS date FROM tl_monitoring_test WHERE compression_type = ? AND pid = ?")
                                               ->execute(MonitoringCompressor::COMPRESSION_NONE, $objMonitoringEntry->id);
      $earliesDay = $lastDay;
      if ($objTest->next() && $objTest->date != null)
      {
        $earliesDay = mktime(0, 0, 0, date("m", $objTest->date), date("d", $objTest->date), date("Y", $objTest->date));
      }

      while ($lastDay >= $earliesDay)
      {
        $this->compressDay($lastDay, $objMonitoringEntry, false);
        $lastDay = $this->subOneDay($lastDay);
      }
    }
  }

  /**
   * Compresses the test results of the day at given timestamp.
   *
   * @param $tstampStartOfDay The timestamp of the day.
   * @param $objMonitoringEntry The monitoring entry (if null, the test results of all entries will be compressed).
   * @param $blnIsAutoExecuted Specifies whether the method is triggered by an automatic process (e.g. CRON).
   */
  private function compressDay($tstampStartOfDay, $objMonitoringEntry=null, $blnIsAutoExecuted=true)
  {
    $objMonitoringEntries = null;

    if ($objMonitoringEntry != null)
    {
      $objMonitoringEntries = new \Model\Collection(array($objMonitoringEntry), 'tl_monitoring');
    }
    else
    {
        $objMonitoringEntries = \MonitoringModel::findAll();
    }

    if ($objMonitoringEntries !== null)
    {
      while ($objMonitoringEntries->next())
      {
        if (!$objMonitoringEntries->disable_auto_compression || !$blnIsAutoExecuted)
        {
          $objTest = \Database::getInstance()->prepare("SELECT * FROM tl_monitoring_test WHERE date >= ? AND date < ? AND compression_type = ? AND pid = ? ORDER BY date")
                                             ->execute($tstampStartOfDay, $this->addOneDay($tstampStartOfDay), MonitoringCompressor::COMPRESSION_NONE, $objMonitoringEntries->id);

          $this->logDebugMsg('Found ' . $objTest->numRows . ' test results for day ' . date("d.m.Y", $tstampStartOfDay) . ' at entry with ID ' . $objMonitoringEntries->id, __METHOD__);
          // TODO remove after testing
          //log_message('Found ' . $objTest->numRows . ' test results for day ' . date("d.m.Y", $tstampStartOfDay) . ' at entry with ID ' . $objMonitoringEntries->id, 'monitoring_compression.log'); 

          if ($objTest->numRows > 0)
          {
            $arrDeleteIds = array();
            $arrComments = array();
            $blnEachStatusEqual = true;

            // get the first entry
            $objTest->next();

            // remember data of the first entry
            $intFirstId = $objTest->id;
            $strFirstStatus = $objTest->status;
            $arrComments[] = $objTest->comment;
            
            while ($objTest->next())
            {
              if ($objTest->status != $strFirstStatus)
              {
                $blnEachStatusEqual = false;
              }
              $arrDeleteIds[] = $objTest->id;
              $arrComments[] = $objTest->comment;
            }

            if ($blnEachStatusEqual)
            {
              \Database::getInstance()->prepare("UPDATE tl_monitoring_test SET compression_type = ?, comment = ? WHERE id = ?")
                                      ->execute(MonitoringCompressor::COMPRESSION_DAY, implode("\n\n", $arrComments), $intFirstId);

              if (!empty($arrDeleteIds))
              {
                \Database::getInstance()->prepare("DELETE FROM tl_monitoring_test WHERE id IN (" . implode(",", $arrDeleteIds) . ")")
                                        ->execute();
              }
            }
            else
            {
              $this->logDebugMsg('The test results of monitoring entry with ID ' . $objMonitoringEntries->id . ' for the day: ' . date("d.m.Y", $tstampStartOfDay) . ' could not be compressed, because they don\'t have same status.', __METHOD__);
              $arrDeleteIds[] = $intFirstId;
              \Database::getInstance()->prepare("UPDATE tl_monitoring_test SET compression_type = ? WHERE id IN (" . implode(",", $arrDeleteIds) . ")")
                                        ->execute(MonitoringCompressor::COMPRESSION_IMPOSSIBLE);
            }
          }
        }
        else
        {
           $this->logDebugMsg('Monitoring entry with ID ' . $objMonitoringEntries->id . ' not found or auto compression disabled.', __METHOD__);
        }
      }
    }

    $this->logDebugMsg('Finished compression of test results for the day: ' . date("d.m.Y", $tstampStartOfDay), __METHOD__);
  }

  /**
   * Compresses the test results of the month starting at given timestamp.
   *
   * @param $tstampStartOfMonth The timestamp of the start of the month.
   * @param $intEntryId The id of a monitoring entry (if null, the test results of all entries will be compressed).
   * @param $blnIsAutoExecuted Specifies whether the method is triggered by an automatic process (e.g. CRON).
   */
  private function compressMonth($tstampStartOfMonth, $intEntryId=null, $blnIsAutoExecuted=true)
  {
      // TODO add function
      $dayCountOfMonth = date("t", $tstampStartOfMonth);

      $this->logDebugMsg('Compressing ' . $dayCountOfMonth . ' days', __METHOD__);

      $arrEntryIds = array();

      if (is_int($intEntryId))
      {
          $arrEntryIds[] = $intEntryId;
      }
      else
      {
          $arrEntryIds = \MonitoringModel::findAll()->fetchEach("id");
      }

      foreach ($arrEntryIds as $entryId)
      {
          $objMonitoringEntry = \MonitoringModel::findByPk($entryId);

          if ($objMonitoringEntry !== null && (!$objMonitoringEntry->disable_auto_compression || !$blnIsAutoExecuted))
          {

              //TODO: execute compression here

              // at first compress the days

              $tstampStartOfDay = $tstampStartOfMonth;
              for ($i = 0; $i < $dayCountOfMonth; $i++)
              {
                 $this->compressDay($tstampStartOfDay, $entryId, $blnIsAutoExecuted);
                 $tstampStartOfDay = $this->addOneDay($tstampStartOfDay);
              }

              // now compress the month
              $this->logDebugMsg('Compressed ' . $dayCountOfMonth . ' days at entry with ID ' . $entryId, __METHOD__);
            }
            else
            {
                $this->logDebugMsg('Monitoring entry with ID ' . $entryId . ' not found or auto compression disabled.', __METHOD__);
            }
      }

      $this->logDebugMsg('Compressed the test results of the month: ' . date("M Y", $tstampStartOfMonth), __METHOD__);
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
   * Get the timestamp for the last day.
   *
   * @return int The timestamp for the last day.
   */
  private function getLastDay()
  {
    $time = time();
    return mktime(0, 0, 0, date("m", $time), date("d", $time) - 1, date("Y", $time));
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
   * Decrease the given timestamp by one day (24 hours).
   *
   * @param int $tstamp The timestamp which should be decreased.
   * @return int The decreased timestamp.
   */
  private function subOneDay($tstamp)
  {
    return mktime(0, 0, 0, date("m", $tstamp), date("d", $tstamp) - 1, date("Y", $tstamp));
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