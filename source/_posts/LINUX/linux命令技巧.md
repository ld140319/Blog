# linux命令技巧

## 删除一个大文件

```
echo "">filename   >filename    :>filename

rm -f filename
```

## 记录终端输出

使用script命令行工具来为你的终端输出创建输出记录

```
script my.terminal.session
```

输入命令：

```
ls
date
sudo service foo stop
```

要退出（结束script会话），输入 exit 或者 logout 或者按下 control-D。

exit

要浏览输入：

```
more my.terminal.session
less my.terminal.session
cat my.terminal.session
```

## 在vim中用密码保护文件

害怕root用户或者其他人偷窥你的个人文件么？尝试在vim中用密码保护，输入：

__vim +X filename__

或者，__在退出vim之前使用:X 命令来加密你的文件，vim会提示你输入一个密码。__

## 清除屏幕上的乱码

只要输入：
```
reset

clear

crtl+l
```

## 在Linux系统中显示已知的用户信息

只要输入：

__lslogins__

## 删除意外在当前文件夹下解压的文件

```
cd /var/www/html/
/bin/rm -f "$(tar ztf /path/to/file.tar.gz)"
```

## 创建目录树

mkdir加上-p选项一次创建一颗目录树：

mkdir -p /jail/{dev,bin,sbin,etc,usr,lib,lib64}

ls -l /jail/

mkdir -p dir1 dir2

mkdir -p dir1 dir{3..10}

## 将文件复制到多个目录中

cp /path/to/file /usr/dir1
cp /path/to/file /var/dir2
cp /path/to/file /nas/dir3

运行下面的命令来复制文件到多个目录中：

```
echo /usr/dir1 /var/dir2 /nas/dir3 | xargs -n 1 cp -v /path/to/file
```

## 快速找出两个目录的不同

diff命令会按行比较文件。但是它也可以比较两个目录：

ls -l /tmp/r
ls -l /tmp/s

__diff /tmp/r/ /tmp/s/__

## 可以看见输出并将其写入到一个文件中

使用tee命令在屏幕上看见输出并同样写入到日志文件my.log中：

echo 111 && echo 222 | tee my.log

tee可以保证你同时在屏幕上看到mycoolapp的输出并写入文件 my.log

## 想要再次运行相同的命令

只需要输入!!。比如：

```

/myhome/dir/script/name arg1 arg2

要再次运行相同的命令
!!
=以root用户运行最后运行的命令
sudo !!
!!会运行最近使用的命令。
要运行最近运行的以“foo”开头命令：
!foo

以root用户运行上一次以“service”开头的命令
sudo !service

!$用于运行带上最后一个参数的命令：

# 编辑 nginx.conf
sudo vi /etc/nginx/nginx.conf
# 测试 nginx.conf
/sbin/nginx -t -c /etc/nginx/nginx.conf
# 测试完 "/sbin/nginx -t -c /etc/nginx/nginx.conf"你可以用vi再次编辑这个文件了
sudo vi !$
```

## 易读格式

```
ls -lh
# 以易读的格式 (比如： 1K 234M 2G)
df -h
df -k
# 以字节、KB、MB 或 GB 输出：
free -b
free -k
free -m
free -g
# 以易读的格式输出 (比如 1K 234M 2G)
du -h
# 以易读的格式显示文件系统权限
stat -c %A /boot
# 比较易读的数字
sort -h -a file
# 在Linux上以易读的形式显示cpu信息
lscpu
lscpu -e
lscpu -e=cpu,node
# 以易读的形式显示每个文件的大小
tree -h
tree -h /boot
```

## 切换目录

想要进入刚才进入的地方？运行：

__cd -__

需要快速地回到你的家目录？输入：

__cd  或者 cd ~__

变量CDPATH定义了目录的搜索路径：

__export CDPATH=/var/www:/nas10__

现在，不用输入cd */var/www/html/ 这样长了，我可以直接输入下面的命令进入 /var/www/html：

cd html

## 在less浏览时编辑文件

要编辑一个正在用less浏览的文件，可以按下v。你就可以用变量$EDITOR所指定的编辑器来编辑了：

less *.c
less foo.html

__按下v键来编辑文件, 退出编辑器后，你可以继续用less浏览了__

## 列出你系统中的所有文件和目录

要看到你系统中的所有目录，运行：

find / -type d | less

列出$HOME 所有目录
find $HOME -type d -ls | less
要看到所有的文件，运行：

find / -type f | less
列出 $HOME 中所有的文件
find $HOME -type f -ls | less