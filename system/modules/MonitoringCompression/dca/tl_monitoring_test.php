<?php

use Monitoring\MonitoringCompressor;
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
 * Add css for styling global operations button
 */
$GLOBALS['TL_CSS'][] = 'system/modules/MonitoringCompression/assets/styles.css';

/**
 * Add callback
 */
$GLOBALS['TL_DCA']['tl_monitoring_test']['config']['onload_callback'][] = array('tl_monitoring_test_MonitoringCompression', 'initPalettes');

/**
 * Add global operations
 */
array_insert($GLOBALS['TL_DCA']['tl_monitoring_test']['list']['global_operations'], count($GLOBALS['TL_DCA']['tl_monitoring_test']['list']['global_operations']) - 1, array
(
    'compressOne' => array
    (
        'label' => &$GLOBALS['TL_LANG']['tl_monitoring_test']['compressOne'],
        'href'  => 'key=compressOne',
        'class' => 'header_icon tl_monitoring_test_compress'
    )
));

/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_monitoring_test']['palettes']['default'] .= ";{compression_legend},compression_type";

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_monitoring_test']['fields']['response_times'] = array
(
  'label'                   => &$GLOBALS['TL_LANG']['tl_monitoring_test']['response_times'],
  'exclude'                 => true,
  'inputType'               => 'multiColumnWizard',
  'eval'                    => array
  (
    'tl_class'     => 'clr',
    'buttons'      => array('up' => false, 'down' => false, 'copy' => false, 'delete' => false), 
    'columnFields' => array
    (
      'date' => array
      (
        'label'         => &$GLOBALS['TL_LANG']['tl_settings']['monitoringAdditionalInfoFieldsCategory'],
        'inputType'     => 'text',
        'eval'          => array('rgxp' => 'datim', 'readonly' => true)
      ),
      'responseTime' => array
      (
        'label'         => &$GLOBALS['TL_LANG']['tl_settings']['monitoringAdditionalInfoFieldsName'],
        'inputType'     => 'text',
        'eval'          => array('rgxp'=>'digit', 'readonly' => true)
      )
    ) 
  ),
  'sql'                     => "blob NULL"
);
$GLOBALS['TL_DCA']['tl_monitoring_test']['fields']['compression_type'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_monitoring_test']['compression_type'],
    'exclude'                 => true,
    'filter'                  => true,
    'sorting'                 => true,
    'inputType'               => 'select',
    'default'                 => MonitoringCompressor::COMPRESSION_NONE,
    'options'                 => array(MonitoringCompressor::COMPRESSION_NONE, MonitoringCompressor::COMPRESSION_DAY, MonitoringCompressor::COMPRESSION_IMPOSSIBLE),
    'reference'               => &$GLOBALS['TL_LANG']['tl_monitoring_test']['compressionTypes'],
    'eval'                    => array('tl_class'=>'w50', 'readonly'=>true, 'helpwizard'=>true),
    'sql'                     => "varchar(16) NOT NULL default '" . MonitoringCompressor::COMPRESSION_NONE . "'"
);

/**
 * Class tl_monitoring_test_MonitoringCompression
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * PHP version 5
 * @copyright  Cliff Parnitzky 2017-2017
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class tl_monitoring_test_MonitoringCompression extends Backend
{
  /**
   * Import the back end user object
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * Initialize the palettes when loading
   * @param \DataContainer
   */
  public function initPalettes()
  {
    if (\Input::get('act') == "edit")
    {
      $objMonitoringTest = \MonitoringTestModel::findByPk(\Input::get('id'));
      if ($objMonitoringTest != null && $objMonitoringTest->compression_type == MonitoringCompressor::COMPRESSION_DAY)
      {
        $GLOBALS['TL_DCA']['tl_monitoring_test']['palettes']['default'] = str_replace(",response_time,", ",response_time,response_times,", $GLOBALS['TL_DCA']['tl_monitoring_test']['palettes']['default']);
      }
    }
  }
}

?>