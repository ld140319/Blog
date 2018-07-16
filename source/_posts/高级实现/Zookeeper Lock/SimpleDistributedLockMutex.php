<?php
include "DistributedLock.php";
include "BaseDistributedLock.php";

class  SimpleDistributedLockMutex extends BaseDistributedLock implements DistributedLock
{
    private $basePath = "/locker";
    private $ourLockPath;
    const LOCK_NAME ="lock-";
    public function __construct($client, $basePath, $lockName)
    {
        parent::__construct($client, $basePath, $lockName);
    }

    public function acquire($time, $unit)
    {
        return $this->internalLock();
    }

    public function release()
    {
        return parent::releaseLock($this->ourLockPath);
    }
    public function internalLock()
    {
        $this->ourLockPath = parent::attemptLock();
        return  $this->ourLockPath;
    }
}