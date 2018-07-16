package Id;
import java.util.Iterator;
import java.util.SortedMap;
import java.util.TreeMap;
import java.util.UUID;
import java.util.zip.CRC32;
public class ConsistHashing {
	/**
     * 服务器信息
     */
    private static final String[] SERVERS = new String[]{"node1", "node2", "node3"};

    /**
     * 一致性哈希环
     */
    private static SortedMap<Long, String> hashRing = new TreeMap<>();

    /**
     * 虚拟节点数目
     */
    private static final int VIRTUAL_NODE_COUNT = 32;

    /**
     * hash环的最大位置2^32
     */
    private static final long MAX_HASH_LOCATION = 1L << 32;

    static {
        //initHashRingWithoutVirtualNode();
        initHashRingWithVirtualNode();
    }

    /**
     * 初始化哈希环，没有虚拟节点
     */
    public static void initHashRingWithoutVirtualNode() {
        for (String server : SERVERS) {
            hashRing.put(hash(server), server);
        }
    }

    /**
     * 初始化哈希环，带虚拟节点
     */
    public static void initHashRingWithVirtualNode() {
        for (String server : SERVERS) {
            for (int i = 0; i < VIRTUAL_NODE_COUNT; i++) {
                String virtualServer = server + "#virtual" + i;
                hashRing.put(hash(virtualServer), virtualServer);
            }
        }
    }

    /**
     * 为key分配server
     *
     * @param key key
     * @return 分配的server
     */
    public static String allocateServer(String key) {
        long hashValue = hash(key);
        /*
        * TreeMap保证key的有序排列，可以当作是一个首尾相连的环,
        * 所以为新的key分配server地址时，按照相同的hash算法得到hashValue，
        * 在TreeMap的环状结构中，查找第一个比该hashValue大的key，其对应的server即为分配的server
        * */
        SortedMap<Long, String> tailHashRing = hashRing.tailMap(hashValue);
        String virtualNode;
        if (tailHashRing.isEmpty()) {
            virtualNode = hashRing.get(hashRing.firstKey());
        } else {
            virtualNode = hashRing.get(tailHashRing.firstKey());
        }
        return getRealNode(virtualNode);
    }


    private static long hash(String key) {
        CRC32 crc32 = new CRC32();
        crc32.update(key.getBytes());
        System.out.println(crc32.getValue());
        return crc32.getValue() % MAX_HASH_LOCATION;
    }

    private static String getRealNode(String virtualNode) {
        if (!virtualNode.contains("#")) {
            return virtualNode;
        }
        return virtualNode.split("#")[0];
    }

	public static void main(String[] args) {
		Iterator<Long> iterator = hashRing.keySet().iterator();
        while (iterator.hasNext()) {
            Long key = iterator.next();
            System.out.println(key + "," + hashRing.get(key));
        }
        int node1Count = 0;
        int node2Count = 0;
        int node3Count = 0;

        for (int i = 0; i < 1000; i++) {
            String key = UUID.randomUUID().toString();
            System.out.println(key);
            String server = allocateServer(key);
            if (server.equals("node1")) node1Count++;
            if (server.equals("node2")) node2Count++;
            if (server.equals("node3")) node3Count++;
        }
        System.out.println("node1Count=" + node1Count);
        System.out.println("node2Count=" + node2Count);
        System.out.println("node3Count=" + node3Count);

	}

}
