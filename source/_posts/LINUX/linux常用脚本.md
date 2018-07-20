## 写一个shell命令 实现找出所有包含 spread的进程，杀死这些进程并记录日志，日志包含杀死进程名称和杀死进程的时间

```
ps -ef |grep spread |grep -v grep |awk '{print $2}'|xargs kill -9
kill -9 $(ps -ef | grep spread| grep -v grep | awk '{print $2}')
```
## 统计 ip出现的次数
有一个文本文件，内容为ip 每行一个ip 格式为
1.2.3.4 
4.5.6.7
2.3.4.5
1.2.3.4

写出 shell命令 统计 ip出现的次数 结果类似
1.2.3.4 2
4.5.6.7 1
2.3.4.5 1

```
awk '{arr[$1]++;}END{for(i in arr){print i , arr[i] }}' test.txt
```