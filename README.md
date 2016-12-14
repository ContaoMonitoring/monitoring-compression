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

The extension is not published in contao extension repository.

Install it manually or via composer: [contao-monitoring/monitoring-compression](https://packagist.org/packages/contao-monitoring/monitoring-compression).


Tracker
-------

https://github.com/ContaoMonitoring/monitoring-compression/issues


Compatibility
-------------

- min. Contao version: >= 3.2.0
- max. Contao version: <  3.6.0


Dependency
----------

This extension is dependent on the following extensions:
- [[contao-monitoring/monitoring]](https://packagist.org/packages/contao-monitoring/monitoring)
