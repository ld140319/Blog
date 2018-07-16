package Id;
import java.util.Iterator;
import java.util.SortedMap;
import java.util.TreeMap;
import java.util.UUID;
import java.util.zip.CRC32;
public class ConsistHashing {
	/**
     * ��������Ϣ
     */
    private static final String[] SERVERS = new String[]{"node1", "node2", "node3"};

    /**
     * һ���Թ�ϣ��
     */
    private static SortedMap<Long, String> hashRing = new TreeMap<>();

    /**
     * ����ڵ���Ŀ
     */
    private static final int VIRTUAL_NODE_COUNT = 32;

    /**
     * hash�������λ��2^32
     */
    private static final long MAX_HASH_LOCATION = 1L << 32;

    static {
        //initHashRingWithoutVirtualNode();
        initHashRingWithVirtualNode();
    }

    /**
     * ��ʼ����ϣ����û������ڵ�
     */
    public static void initHashRingWithoutVirtualNode() {
        for (String server : SERVERS) {
            hashRing.put(hash(server), server);
        }
    }

    /**
     * ��ʼ����ϣ����������ڵ�
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
     * Ϊkey����server
     *
     * @param key key
     * @return �����server
     */
    public static String allocateServer(String key) {
        long hashValue = hash(key);
        /*
        * TreeMap��֤key���������У����Ե�����һ����β�����Ļ�,
        * ����Ϊ�µ�key����server��ַʱ��������ͬ��hash�㷨�õ�hashValue��
        * ��TreeMap�Ļ�״�ṹ�У����ҵ�һ���ȸ�hashValue���key�����Ӧ��server��Ϊ�����server
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
