# linux查看端口占用情况

## 1. netstat或ss命令

__netstat -antelp | grep 80__

## 2. lsof命令(查看进程占用哪些文件)

__lsof -i:80__

## 3. fuser命令

fuser命令和lsof正好相反，是查看某个文件被哪个进程占用的。Linux中，万物皆文件，所以可以查看普通文件、套接字文件、文件系统。而套接字文件就包含了端口号。比如查看22端口。

__fuser 22/tcp -v__

USER PID ACCESS COMMAND
22/tcp: root 1329 F.... sshd
root 1606 f.... sshd

## 4. nmap工具(扫描端口)

__nmap localhost__

Starting Nmap 5.51 ( http://nmap.org ) at 2018-03-03 18:00 CST
Nmap scan report for localhost (127.0.0.1)
Host is up (0.0000020s latency).
Other addresses for localhost (not scanned): 127.0.0.1
Not shown: 998 closed ports
PORT STATE SERVICE
22/tcp open ssh
25/tcp open smtp
Nmap done: 1 IP address (1 host up) scanned in 0.06 seconds