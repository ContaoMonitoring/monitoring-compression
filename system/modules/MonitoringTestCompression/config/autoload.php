<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package MonitoringTestCompression
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'Monitoring',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'Monitoring\MonitoringTestCompressionImpl' => 'system/modules/MonitoringTestCompression/classes/MonitoringTestCompressionImpl.php',
	'Monitoring\MonitoringTestCompressor'      => 'system/modules/MonitoringTestCompression/classes/MonitoringTestCompressor.php',
));
