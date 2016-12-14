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
 * Extend backend module (register new functions)
 */
$GLOBALS['BE_MOD']['system']['monitoring']['compressOne'] = array('MonitoringCompressor', 'compressOne');
$GLOBALS['BE_MOD']['system']['monitoring']['compressAll'] = array('MonitoringCompressor', 'compressAll');

/**
 * Cron jobs for compression
 */
$GLOBALS['TL_CRON']['daily'][]   = array('MonitoringCompressor', 'autoCompressLastDay');

/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['monitoringExtendTestResultOutput'][] = array('MonitoringCompressionHookImpl', 'addCompressionTypeToTestResultOutput');

?>