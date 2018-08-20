# Linux特殊符号

## ~ 帐户的 home 目录

代表使用者的 home 目录 cd ~ 也可以直接在符号后加上某帐户的名称：cd ~user或者当成是路径的一部份：~/bin

cd ~用户名

## ~+ 当前的工作目录，这个符号代表当前的工作目录，她和内建指令 pwd的作用是相同的。

 echo ~+/var/log

## ~- 或者- 上次的工作目录，这个符号代表上次的工作目录。 

echo ~-/etc/httpd/logs


## {}

__mkdir {userA,userB,userC}-{home,bin,data}__

我们得到 userA-home, userA-bin, userA-data, userB-home, userB-bin,userB-data, userC-home, userC-bin,userC-data

__rf -rf  {userA,userB,userC}-{home,bin,data}__

__mkdir {userA,userB,userC}-{1..10}__

 userA-1  userA-10
 userB-1  userB-10
 userC-1  userC-10

__mkdir {1..2}dir__

__rf -rf {1..2}dir__

chown root /usr/{ucb/{ex,edit},lib/{ex?.?*,how_ex}}

mkdir {ucb/{ex,edit},lib/{ex?.?*,how_ex}}  好像不支持正则表达式 正则表达式会当做普通文本  ucb/edit ucb/ex lib/ex?.?*  lib/how_ex
rm -rf {ucb/{ex,edit},lib/{ex?.?*,how_ex}}
chown root {ucb/{ex,edit},lib/{ex?.?*,how_ex}}

## ：

:>nohup.out  清空文件内容

:0 第一行

：$ 最后一行




