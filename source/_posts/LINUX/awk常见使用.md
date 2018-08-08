# awk常见使用

## 统计词频

(1) test.txt文件内容如下，统计数字出现次数:

    1.2.3.4 
    4.5.6.7
    2.3.4.5
    1.2.3.4

```
awk -F. '{
  for(i=1;i<=NF;i++)
  {
    sub(/[[:blank:]]*$/,"",$i);
    sub(/^[[:blank:]]*/,"",$i);
    data[$i]++;
  }
}
END{
  for (j in data) printf "%s,%d\n",j,data[j];
}' test.txt
```
**注意:**

**awk实现trim:**

```
sub(/^[[:blank:]]*/,"",变量)  是去掉变量左边的空白符
sub(/[[:blank:]]*$/,"",变量) 是去掉变量右边的空白符
gsub(/[[:blank:]]*/,"",变量) 是去掉变量中所有的空白符

示例：
echo ' 123 456 789  ' | awk '{
print "<" $0 ">";
sub(/^[[:blank:]]*/,"",$0);print "[" $0 "]";
sub(/[[:blank:]]*$/,"",$0);print "|" $0 "|";
gsub(/[[:blank:]]*/,"",$0);print "/" $0 "/";
}'


或者用 gsub
gsub返值是替换次数，而不是替换结果
```

## 字符串分割 split

```
awk 'BEGIN{
 delimiter = " ";
 str = "this is a test";
 len = split(str, arr, delimiter);
 for(i=1; i<=len; i++){
     print i,arr[i];
 }
}'
```

## 数组排序

**(1) 值排序
asort[src_arr, dest_arr] 默认返回值是：原数组长度，传入参数dscarr则将排序后数组赋值给dscarr**

```
awk 'BEGIN{
  src_arr[100] = 100;
  src_arr[2] = 224;
  src_arr[3] = 34;
  len = asort(src_arr, dest_arr);
  for(i=1; i<=len; i++)
  {
    print i,dest_arr[i];
  }
}'

1 34
2 100
3 224
```
**asort只对value进行了排序，因此丢掉原先键值key**

**(2) 键排序
asorti[src_arr, dest_arr]  默认返回值是：原数组长度，传入参数dscarr则将排序后数组赋值给dscarr**
```
awk 'BEGIN{
  src_arr["d"]=100;
  src_arr["a"]=224;
  src_arr["c"]=34;
  len=asorti(src_arr, dest_arr);
  for(i=1;i<=len;i++)
  {
    print i,dest_arr[i],src_arr[dest_arr[i]];
  }
}'
1 a 224
2 c 34
3 d 100
```
**asorti对key进行排序（字符串类型），将生成新的数组放入dest_arr**

**(3) 通过管道发送到sort排序**

```
awk 'BEGIN{
  data["a"]=100;
  data["b"]=224;
  data["c"]=34;
  for(i in data)
  {
    print i,data[i] | "sort -r -n -k2";
  }
}'


awk 'BEGIN{
  data["a"]=100;
  data["b"]=224;
  data["c"]=34;
  for(i in data)
  {
    print i,data[i];
  }
}' | "sort -r -n -k2"

b 224
a 100
c 34

通过管道，发送到外部程序“sort”排序，-r 从大到小，-n 按照数字排序，-k2 以第2列排序。通过将数据丢给第3方的sort命令，所有问题变得非常简单。如果以key值排序 –k2 变成 -k1即可。

awk 'BEGIN{
  a[100]=100;
  a[2]=224;
  a[3]=34;
  for(i in a)
  {
    print i,a[i] | "sort -r -n -k1";
  }
}'

awk 'BEGIN{
  a[100]=100;
  a[2]=224;
  a[3]=34;
  for(i in a)
  {
    print i,a[i] ;
  }
}'|sort -r -n -k1

100 100
3 34
2 224
```

## 多分割符:

```
将空格及:均作为分隔符

ifconfig eth1|awk 'BEGIN{FS=[ :]+}NR==2{print NR" "$4}'

ifconfig eth1|awk 'BEGIN{FS="[ :]+"}NR==2{print NR" "$4}'
```

## 统计apache日志单IP访问请求数排名

request_log.txt内容如下:

    10.0.0.3 -- [21/Mar/2015-07:50:17+0800]*GET/HTTP/1.1*200 19 *-*
    
    10.0.0.3 -- [21/Mar/2015-07:50:17+0800]*GET/HTTP/1.1*200 19 *-*
    
    10.0.0.5 -- [21/Mar/2015-07:50:17+0800]*GET/HTTP/1.1*200 19 *-*
    
    10.0.0.3 -- [21/Mar/2015-07:50:17+0800]*GET/HTTP/1.1*200 19 *-*
    
    10.0.0.6 -- [21/Mar/2015-07:50:17+0800]*GET/HTTP/1.1*200 19 *-*
    
```
方案一:

    awk '$0 != "" {print $1}' request_log.txt |sort|uniq -c
    
    awk '{print $1}' request_log.txt |sort|uniq -c
方案二:

    awk '
    $0!=""{array[$1]++}
    END {
        for(key in array) print key,array[key]
    }' request_log.txt
```

## 统计域名访问量

domain.txt内容如下: 

    http://www.baidu.com/index.html
    
    http://www.163.com/1.html
    
    http://www.cnblogs.com/index.html
    
    http://www.baidu.com/2.html
    
    http://www.163.com/index.html
    
    http://www.qq.com/index.html
    
    http://www.baidu.com/3.html
    
    http://www.163.com/2.html
    
    http://www.baidu.com/2.html
    
```
方案一:

awk '$0!=""{
  split($0,array,"/+");
  domain_name=array[2];
  count[domain_name]++
}
END{
  for(domain_name in count) print domain_name,count[domain_name]
}' domain.txt

方案二:

awk -F [/]+ '$0!=""{
  array[$2]++
} 
END {
  for(domain_name in array) print domain_name,array[domain_name]
}' domain.txt
```

## 计算每个人的总工资和平均工资

amount.txt的内容如下:

    001 wodi 12k

    002 yingsui 15k
    
    003 jeacen 10k
    
    004 yideng 10k
    
    005 kuqi 8k
    
    006 xiaofen 6k
    
    007 wodi 11k
    
    008 yingsui 12k
    
    009 jeacen 4k
    
    010 kuqi 12k
    
    011 yideng 11k
    
    012 xiaofen 10k
    
```
awk '$0!=""{
  total[$2]+=$3;
  count[$2]++
}
END{
  for(name in total) print name,total[name]"k",total[name]/count[name];
}' amount.txt
```