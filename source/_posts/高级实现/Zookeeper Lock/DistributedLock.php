<?php
interface DistributedLock
{

    //获取锁，如果没有得到就等待
    //public  function acquire();
    public  function acquire ($time, $unit);

    //释放锁
    public  function release();
}