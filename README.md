[![Latest Version on Packagist](http://img.shields.io/packagist/v/contao-monitoring/monitoring-compression.svg?style=flat)](https://packagist.org/packages/contao-monitoring/monitoring-compression)
[![Installations via composer per month](http://img.shields.io/packagist/dm/contao-monitoring/monitoring-compression.svg?style=flat)](https://packagist.org/packages/contao-monitoring/monitoring-compression)
[![Installations via composer total](http://img.shields.io/packagist/dt/contao-monitoring/monitoring-compression.svg?style=flat)](https://packagist.org/packages/contao-monitoring/monitoring-compression)

Contao Extension: MonitoringCompression
=======================================

Provides components to compress the test results for the [[Contao Monitoring]](https://github.com/ContaoMonitoring/monitoring) system.

Features:

- compress all hourly test results of a day to one (if the status is the same)
- enable automatic compression in system settings (triggered via external CRON)
- disable automatic compression for individual monitoring entries
- compress all or individual monitoring entries via backend manually
- display compression type for test entries in backend


Installation
------------

Install the extension via composer: [contao-monitoring/monitoring-compression](https://packagist.org/packages/contao-monitoring/monitoring-compression).

If you prefer to install it manually, download the latest release here: https://github.com/ContaoMonitoring/monitoring-compression/releases

After installation update the database and define Monitoring specific values in the system settings.


Tracker
-------

https://github.com/ContaoMonitoring/monitoring-compression/issues


Compatibility
-------------

- Contao version >= 3.2.0 ... <  3.6.0
- Contao version >= 4.4.0


Dependency
----------

This extension is dependent on the following extensions:

- [[contao-monitoring/monitoring]](https://packagist.org/packages/contao-monitoring/monitoring)
- [[menatwork/contao-multicolumnwizard]](https://packagist.org/packages/menatwork/contao-multicolumnwizard)
