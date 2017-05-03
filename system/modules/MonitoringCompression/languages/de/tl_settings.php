<?php

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2017 Leo Feyer
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
 * @copyright  Cliff Parnitzky 2016-2017
 * @author     Cliff Parnitzky
 * @package    MonitoringCompression
 * @license    LGPL
 */

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_settings']['monitoringAutoCompressionActive']              = array('Automatische Komprimierung aktivieren', 'Wählen Sie ob die automatische Komprimierung aktiviert sein soll.');
$GLOBALS['TL_LANG']['tl_settings']['monitoringCompressionResponseTimeCombination'] = array('Antwortzeit Zusammenfassung', 'Wählen Sie welcher Wert der Antwortzeiten in das komprimierte Testergebnis übernommen werden soll.');

/**
 * Options
 */
$GLOBALS['TL_LANG']['tl_settings']['monitoringCompressionResponseTimeCombinationOptions']['average'] = 'Durchschnittswert';
$GLOBALS['TL_LANG']['tl_settings']['monitoringCompressionResponseTimeCombinationOptions']['lowest']  = 'Kleinster Wert';
$GLOBALS['TL_LANG']['tl_settings']['monitoringCompressionResponseTimeCombinationOptions']['highest'] = 'Höchster Wert';

?>