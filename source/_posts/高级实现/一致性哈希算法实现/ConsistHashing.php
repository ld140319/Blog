<?php
class ConsistHashingHelper {
    //服务器信息
    private $SERVERS = array("node1", "node2", "node3","node4", "node5", "node6");
    //一致性哈希环
    private $hashRing = array();
    //虚拟节点数目
    const VIRTUAL_NODE_COUNT = 32;
    //hash环的最大位置2^32
    const MAX_HASH_LOCATION = 1<<32;
    public  $temp = '';
    public function __construct()
    {
        $this->initHashRingWithVirtualNode();
    }
    //初始化哈希环，没有虚拟节点
    public function initHashRingWithoutVirtualNode()
    {
        foreach($this->SERVERS as $server) {
            $this->hashRing[self::hash($server)] = $server;
        }
    }
    //初始化哈希环，没有虚拟节点
    public function initHashRingWithVirtualNode()
    {
        foreach($this->SERVERS as $server) {
            for ($i=0; $i<self::VIRTUAL_NODE_COUNT; $i++) {
                $virtualServer = $server ."#virtual" .$i;
                $this->temp .= ','.$virtualServer;
                $this->hashRing[self::hash($virtualServer)] = $virtualServer;
            }
        }
    }
    //计算hash值,常用crc32算法
    public static function hash($key) {
        $crc32Int = crc32($key);
        $crc32Int = abs($crc32Int);
        return $crc32Int % self::MAX_HASH_LOCATION;
    }
    //根据虚节点获取对应物理节点
    public function getRealNode($virtualNode) {
        if (strpos($virtualNode, '#') === false) {
            return $virtualNode;
        }
        return explode('#', $virtualNode)[0];
    }
    //为key分配server(真实物理节点)
    public  function allocateServer($key) {
        $hashValue = self::hash($key);
        $tailHashRing = $this->getNextAll($hashValue);
        var_dump(count($tailHashRing));
        if (empty($tailHashRing)) {
            reset($this->hashRing);
            $virtualNode = current($this->hashRing);
        } else {
            $virtualNode = current($tailHashRing);
        }
        return $this->getRealNode($virtualNode);
    }
    //获取对应的虚节点
    public  function  getNextAll($findKey){
        $flag = false; // 标识是否找到了键相等的元素即环上的节点
        $tailHashRing = array();//找到的节点及其后面的所有节点
        foreach ($this->hashRing as $key => $val) {
            if ($findKey == $key) {
                $flag = true;
                $tailHashRing[$key] = $val;
            } else {
                if ($flag == true) {
                    $tailHashRing[$key] = $val;
                }
            }
        }
        return $tailHashRing;
    }
    public function getHashRing() {
        return $this->hashRing;
    }
}
$consistHashingHelper = new ConsistHashingHelper();
/*foreach (array_keys($consistHashingHelper->getHashRing()) as $key) {
    echo $key.PHP_EOL;
}*/
$node1Count = 0;
$node2Count = 0;
$node3Count = 0;
$node4Count = 0;
$node5Count = 0;
$node6Count = 0;
$str = $consistHashingHelper->temp;
var_dump($str);
$data = explode(',', $str);
$data = array_filter($data, function ($val) {
    if (empty($val)){
        return false;
    } else {
        return true;
    }
});
$data = array_values($data);
for ($i=0; $i<count($data);$i++) {
    $key = $data[$i];
    $server = $consistHashingHelper->allocateServer($key);
    if (strpos($server, 'node1') !== false ) {
        $node1Count++;
    } else if (strpos($server, 'node2') !== false ) {
        $node2Count++;
    } else if (strpos($server, 'node3') !== false ) {
        $node3Count++;
    } else if (strpos($server, 'node4') !== false ) {
        $node4Count++;
    }else if (strpos($server, 'node5') !== false ) {
        $node5Count++;
    }else if (strpos($server, 'node6') !== false ) {
        $node6Count++;
    }else {
        echo '没有找到匹配项'.PHP_EOL;
    }
}
echo "node1Count=" . $node1Count.PHP_EOL;
echo "node2Count=" . $node2Count.PHP_EOL;
echo "node3Count=" . $node3Count.PHP_EOL;
echo "node4Count=" . $node4Count.PHP_EOL;
echo "node5Count=" . $node5Count.PHP_EOL;
echo "node6Count=" . $node6Count.PHP_EOL;


