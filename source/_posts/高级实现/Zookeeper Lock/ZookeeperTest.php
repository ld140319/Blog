<?php
include "SimpleDistributedLockMutex.php";
$zookeeper = new Zookeeper('localhost:2182', null, 10000);
$simpleDistributedLockMutex = new SimpleDistributedLockMutex($zookeeper, '/locker', 'lock-');
$simpleDistributedLockMutex->acquire(time(), 2);
while (true) {
    sleep(2);
    if ($simpleDistributedLockMutex->hasClock()) {
        echo "有锁".PHP_EOL;
    } else {
        echo "无锁".PHP_EOL;
    }
}