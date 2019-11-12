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
 * Add to palette
 */
$arrDefaultPalletEntries = explode(";", $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']);
foreach ($arrDefaultPalletEntries as $index=>$entry)
{
  if (strpos($entry, "{monitoring_legend}") !== FALSE)
  {
    $entry .= ",monitoringAutoCompressionActive";
    $arrDefaultPalletEntries[$index] = $entry;
  }
}
$GLOBALS['TL_DCA']['tl_settings']['palettes']['__selector__'][] = 'monitoringAutoCompressionActive';
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = implode(";", $arrDefaultPalletEntries);
$GLOBALS['TL_DCA']['tl_settings']['subpalettes']['monitoringAutoCompressionActive'] = 'monitoringCompressionResponseTimeCombination'; 


/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['monitoringAutoCompressionActive'] = array
(
  'label'     => &$GLOBALS['TL_LANG']['tl_settings']['monitoringAutoCompressionActive'],
  'inputType' => 'checkbox',
  'eval'      => array('submitOnChange'=>true, 'tl_class'=>'clr w50 m12')
);
$GLOBALS['TL_DCA']['tl_settings']['fields']['monitoringCompressionResponseTimeCombination'] = array
(
  'label'     => &$GLOBALS['TL_LANG']['tl_settings']['monitoringCompressionResponseTimeCombination'],
  'inputType' => 'select',
  'options'   => array(MonitoringCompressor::RESPONSE_TIME_COMBINATION_AVERAGE, MonitoringCompressor::RESPONSE_TIME_COMBINATION_LOWEST, MonitoringCompressor::RESPONSE_TIME_COMBINATION_HIGHEST),
  'reference' => &$GLOBALS['TL_LANG']['MSC']['monitoringCompressionResponseTimeCombinationOptions'],
  'eval'      => array('mandatory'=>true, 'tl_class'=>'w50', 'includeBlankOption'=>true)
);

?>