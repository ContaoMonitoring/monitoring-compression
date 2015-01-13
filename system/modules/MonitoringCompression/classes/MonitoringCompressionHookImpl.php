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
 * @package    MonitoringCompression
 * @license    LGPL
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
namespace Monitoring;

/**
 * Class MonitoringCompressionImpl
 *
 * Implementation of hooks.
 * @copyright  Cliff Parnitzky 2014
 * @author     Cliff Parnitzky
 * @package    Controller
 */
class MonitoringCompressionHookImpl extends \Backend
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->loadLanguageFile('tl_monitoring_test');
	}

	/**
	 * Modify the output for a test result ... add the compression type
	 * @param  $arrRow The array of data for each row.
	 * @param  $arrOutputTable The output array containing rows with columns.
	 * @return Array The modified output array.
	 */
	public function addCompressionTypeToTestResultOutput($arrRow, $arrOutputTable)
	{
		$arrOutputTable[] = array
		(
			'col_0' => $GLOBALS['TL_LANG']['tl_monitoring_test']['compression_type'][0],
			'col_1' => $GLOBALS['TL_LANG']['tl_monitoring_test']['compressionTypes'][$arrRow['compression_type']][0],
		);

		return $arrOutputTable;
	}
}

?>