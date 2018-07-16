<?php

class BaseDistributedLock
{
    private $client = null;    //zookeeper对象
    private $basePath = "";    //主目录，持久节点
    private $pathPrefix = "";  //子节点路径前缀(包括主目录)(完整路径=子节点路径前缀+随机数)
    private $lockName = "";    //子节点文件路径前缀(不包括主目录)
    private $has_lock = false;  //是否获取锁成功
    private $absolutePath = ""; //全路径
    const MAX_RETRY_COUNT  = 10; //失败重试次数

    public function __construct($client, $basePath, $lockName)
    {
        $this->client = $client;
        $this->basePath = $basePath;
        $this->lockName = $lockName;
        $this->pathPrefix = $basePath.DIRECTORY_SEPARATOR.$lockName;
    }
    public function attemptLock()
    {
        $ourPath = null;
        $hasTheLock = false;
        $isDone = false;
        $retryCount = 0;
        $aclArray = array(
            array(
                'perms'  => Zookeeper::PERM_ALL,
                'scheme' => 'world',
                'id'     => 'anyone',
            )
        );
        try {
            //网络闪断需要重试
            while (!$isDone) {
                $isDone = true;

                //创建主目录节点,持久节点
                //注意:节点路径不能够以/结尾
                if (!$this->client->exists($this->basePath)) {
                    $this->client->create($this->basePath, $this->basePath, $aclArray);
                }

                //在locker（basePath持久节点）下创建客户端要获取锁的[临时]顺序节点
                $ourPath = $this->client->create($this->pathPrefix, $this->pathPrefix, $aclArray, Zookeeper::EPHEMERAL | Zookeeper::SEQUENCE );
                $this->absolutePath = $ourPath;

                /**
                 * 该方法用于判断自己是否获取到了锁，即自己创建的顺序节点在locker的所有子节点中是否最小
                 * 如果没有获取到锁，则等待其它客户端锁的释放，并且稍后重试直到获取到锁或者超时
                 */
                $hasTheLock = $this->waitToLock($ourPath);

            }
        } catch (\Exception $e) {
            ++$retryCount;
            if ($retryCount < self::MAX_RETRY_COUNT ){
                $isDone = false;
            }else{
                throw $e;
            }
        }
        if ($hasTheLock) {
            return $ourPath;
        }
        return null;
    }
    protected function waitToLock($ourPath)
    {
        $haveTheLock = false;
        while (!$haveTheLock) {

            $children = $this->getSortedChildren();
            $sequenceNodeName = substr($ourPath, strlen($this->basePath.DIRECTORY_SEPARATOR));

            //计算刚才客户端创建的顺序节点在locker的所有子节点中排序位置，如果是排序为0，则表示获取到了锁
            $ourIndex = array_search($sequenceNodeName, $children);
            if ($ourIndex === false) {
                throw new \Exception("节点没有找到: ".$sequenceNodeName);
            }

            //如果当前客户端创建的节点在locker子节点列表中位置大于0，表示其它客户端已经获取了锁
            //此时当前客户端需要等待其它客户端释放锁，
            $isGetTheLock = ($ourIndex == 0);

            //如何判断其它客户端是否已经释放了锁？从子节点列表中获取到比自己次小的哪个节点，并对其建立监听
            $pathToWatch = $isGetTheLock ? null : $children[$ourIndex - 1];

            if ($isGetTheLock) {
                $haveTheLock = true;
                $this->setHasLock(true);
                return $haveTheLock;
            } else {
                $this->watchPrevious();
                sleep(1); //等待一秒
                return $this->hasClock();
            }
        }
        return $haveTheLock;
    }
    protected  function watchPrevious()
    {
        $children = $this->getSortedChildren();
        $size = count($children);
        for ($i = 0; $i < $size; $i++) {
            $randomNumber = substr($this->absolutePath, strlen($this->basePath.DIRECTORY_SEPARATOR));
            if ($randomNumber == $children[$i]) {
                if ($i > 0) {
                    $pathToWatch = $children[$i - 1];
                    $previousSequencePath = $this->basePath.DIRECTORY_SEPARATOR.$pathToWatch;
                    $this->client->get($previousSequencePath, array($this, 'watchNode'));
                    return $previousSequencePath;
                }
                $previousSequencePath = $this->basePath.DIRECTORY_SEPARATOR.$children[$i];
                return $previousSequencePath;
            }
        }
        throw new Exception(
            sprintf(
            "Something went very wrong! I can't find myself: %s/%s",
                    $this->basePath,
                    $this->absolutePath
            )
        );
    }
    public function hasClock()
    {
        return $this->has_lock;
    }
    protected function setHasLock($flag)
    {
        $this->has_lock = $flag;
    }
    protected function releaseLock($lockPath) {
        return $this->deleteOurPath($lockPath);
    }
    protected function deleteOurPath($version = -1)
    {
        return $this->client->delete($this->absolutePath, $version);
    }
    protected function getSortedChildren()
    {
        $children = $this->client->getChildren($this->basePath);
        if (!empty($children)) {
            sort($children);
        }
        return $children;
    }
    public function watchNode($i, $type, $name)
    {
        $watching = $this->watchPrevious();
        if ($watching == $this->absolutePath) {
            $this->setHasLock(true);
        }
    }
    protected function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }
}