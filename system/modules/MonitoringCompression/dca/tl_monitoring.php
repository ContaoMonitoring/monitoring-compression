<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2019 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2016-2019
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
$GLOBALS['TL_DCA']['tl_monitoring']['config']['onload_callback'][] = array('tl_monitoring_MonitoringCompression', 'initPalettes');

/**
 * Add global operations
 */
array_insert($GLOBALS['TL_DCA']['tl_monitoring']['list']['global_operations'], count($GLOBALS['TL_DCA']['tl_monitoring']['list']['global_operations']) - 1, array
(
    'compressAll' => array
    (
        'label' => &$GLOBALS['TL_LANG']['tl_monitoring']['compressAll'],
        'href'  => 'key=compressAll',
        'class' => 'header_icon tl_monitoring_compress'
    )
));

/**
 * Add operations
 */
$GLOBALS['TL_DCA']['tl_monitoring']['list']['operations']['compressOne'] = array
(
  'label' => &$GLOBALS['TL_LANG']['tl_monitoring']['compressOne'],
  'href'  => 'key=compressOne',
  'icon'  => 'system/modules/MonitoringCompression/assets/icon_compress.png'
);

/**
 * Add to palette
 */
$GLOBALS['TL_DCA']['tl_monitoring']['palettes']['default'] .= ";{compression_legend},disable_auto_compression,response_time_combination";

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_monitoring']['fields']['disable_auto_compression'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_monitoring']['disable_auto_compression'],
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('submitOnChange'=>true, 'tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);
$GLOBALS['TL_DCA']['tl_monitoring']['fields']['response_time_combination'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_monitoring']['response_time_combination'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => array(MonitoringCompressor::RESPONSE_TIME_COMBINATION_AVERAGE, MonitoringCompressor::RESPONSE_TIME_COMBINATION_LOWEST, MonitoringCompressor::RESPONSE_TIME_COMBINATION_HIGHEST),
    'reference'               => &$GLOBALS['TL_LANG']['MSC']['monitoringCompressionResponseTimeCombinationOptions'],
    'eval'                    => array('tl_class'=>'w50', 'includeBlankOption'=>true),
    'sql'                     => "varchar(16) NOT NULL default ''"
);

/**
 * Class tl_monitoring_MonitoringCompression
 *
 * Provide miscellaneous methods that are used by the data configuration array.
 * PHP version 5
 * @copyright  Cliff Parnitzky 2019-2019
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class tl_monitoring_MonitoringCompression extends Backend
{
  /**
   * Default constructor
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
      $objMonitoringEntry = \MonitoringModel::findByPk(\Input::get('id'));
      if ($objMonitoringEntry != null && $objMonitoringEntry->disable_auto_compression)
      {
        $GLOBALS['TL_DCA']['tl_monitoring']['palettes']['default'] = str_replace(',response_time_combination', '', $GLOBALS['TL_DCA']['tl_monitoring']['palettes']['default']);
      }
    }
  }
}

?>