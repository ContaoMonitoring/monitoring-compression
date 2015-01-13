<?php

namespace Monitoring;

/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once '../../../initialize.php';

class TestRunner extends MonitoringCompressor
{

    public function testRun(){
        $this->autoCompressLastMonth();
        echo "executed 'autoCompressLastMonth(...)'<br>";
        $this->autoCompressLastDay();
        echo "executed 'autoCompressLastDay(...)'<br>";
    }
}


$t = new TestRunner();
$t->testRun();

?>