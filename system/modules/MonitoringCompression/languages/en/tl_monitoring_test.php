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
 * Fields
 */
$GLOBALS['TL_LANG']['tl_monitoring_test']['compression_type'] = array('Compression type', 'The type of compression of this test result.');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_monitoring_test']['compression_legend'] = 'Compression';

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_monitoring_test']['compressionTypes'][MonitoringCompressor::COMPRESSION_NONE]  = array('None', 'The test result is not compressed, it applies only to the time of testing.The test result is not compressed, it applies only to the time of testing.');
$GLOBALS['TL_LANG']['tl_monitoring_test']['compressionTypes'][MonitoringCompressor::COMPRESSION_DAY]   = array('Day', 'The test result summarizes all tests of one day.');
$GLOBALS['TL_LANG']['tl_monitoring_test']['compressionTypes'][MonitoringCompressor::COMPRESSION_MONTH] = array('Month', 'The test result summarizes all tests of one month.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_monitoring_test']['compressOne'] = array('Compress tests', 'Compresses the test results of this monitoring entry.');

?>