<?php
require __DIR__ . '/../lib/setup.php';
use Gaia\DB;
use Gaia\Test\Tap;

if( ! class_exists('\PDO') ){
    Tap::plan('skip_all', 'php-pdo not installed');
}

if( ! in_array( 'sqlite', PDO::getAvailableDrivers()) ){
    Tap::plan('skip_all', 'this version of PDO does not support sqlite');
}

DB\Connection::load(array('test'=>function () {
        return new DB\Driver\PDO( 'sqlite:/tmp/stockpile.db');
    }
));