---
title: 20180330
date: 2018-03-30 09:10:56
tags:
- 文件操作
- linux
categories:
- linux
---
# linux文件压缩与接压缩

>引用地址
[文件压缩与解压缩](http://www.jb51.net/LINUXjishu/43356.html)
#tar打包(打包!=压缩)

&nbsp;&nbsp;&nbsp;&nbsp;Linux下最常用的打包程序就是tar了，使用tar程序打出来的包我们常称为tar包，tar包文件的命令通常都是以.tar结尾的。生成tar包后，就可以用其它的程序来进行压缩了

1.将所有.jpg的文件打成一个名为all.tar的包

    tar -cf all.tar *.jpg      (-c是表示产生新的包 ，-f指定包的文件名)

2.所有.gif的文件增加到all.tar的包里面去

    tar -rf all.tar *.gif     (-r是表示增加文件)
    
3.列出all.tar包中所有文件

    tar -tf all.tar           (-t是列出文件)
    
4.解出all.tar包中所有文件

    tar -xf all.tar           (-x是解开)
    
&nbsp;&nbsp;&nbsp;&nbsp;为了方便用户在打包解包的同时可以压缩或解压文件，tar提供了一种特殊的功能。这就是**tar可以在打包或解包的同时调用其它的压缩程序，比如调用gzip、bzip2等**

1) tar调用gzip

gzip是GNU组织开发的一个压缩程序，.gz结尾的文件就是gzip压缩的结果。与gzip 相对的解压程序是gunzip。**tar中使用-z这个参数来调用gzip**。下面来举例说明一下

    tar -czf all.tar.gz *.jpg
这条命令是将所有.jpg的文件打成一个tar包，并且将其用gzip压缩，生成一个gzip压缩过的包，包名为all.tar.gz

    tar -xzf all.tar.gz
这条命令是将上面产生的包解开。

2) tar调用bzip2

bzip2是一个压缩能力更强的压缩程序，.bz2结尾的文件就是bzip2压缩的结果。

与bzip2相对的解压程序是bunzip2。 **tar中使用-j这个参数来调用bzip2**。下面来举例说明一下：

    tar -cjf all.tar.bz2 *.jpg
这条命令是将所有.jpg的文件打成一个tar包，并且将其用bzip2压缩，生成一个bzip2压缩过的包，包名为all.tar.bz2

    tar -xjf all.tar.bz2
    
这条命令是将上面产生的包解开。

3)tar调用compress

compress也是一个压缩程序，但是好象使用compress的人不如gzip和bzip2的人多。。与 compress相对的解压程序是uncompress。**tar中使用-Z这个参数来调用compress。**下面来举例说明一下：


    tar -cZf all.tar.Z *.jpg
这条命令是将所有.jpg的文件打成一个tar包，并且将其用compress压缩，生成一个uncompress压缩过的包，包名为all.tar.Z

    tar -xZf all.tar.Z
这条命令是将上面产生的包解开

tar

**-c: 建立压缩档案 
-x：解压 
-t：查看内容 
-r：向压缩归档文件末尾追加文件 
-u：更新原压缩包中的文件
-v：显示所有过程**

这五个是独立的命令，压缩解压都要用到其中一个，可以和别的命令连用但只能用其中一个。下面的参数是根据需要在压缩或解压档案时可选的。

**-z：有gzip属性的 
-j：有bz2属性的 
-Z：有compress属性的**
-O：将文件解开到标准输出 
 

下面的参数-f是必须的

**-f: 使用档案名字，切记，这个参数是最后一个参数，后面只能接档案名。** 

4)处理xz文件

    xz -d xxx.tar.xz
    tar xf xxx.tar
    
#zip文件处理

    zip all.zip *.jpg
这条命令是将所有.jpg的文件压缩成一个zip包

    unzip all.zip
这条命令是将all.zip中的所有文件解压出来

gzip -d

语 法：gzip [-acdfhlLnNqrtvV][-S <压缩字尾字符串>][-<压缩效率>][--best/fast][文件...] 或 gzip [-acdfhlLnNqrtvV][-S <压缩字尾字符串>][-<压缩效率>][--best/fast][目录]

补充说明：gzip是个使用广泛的压缩程序，文件经它压缩过后，其名称后面会多出".gz"的扩展名。

参 数：
 

-a或--ascii 使用ASCII文字模式。 

-c或--stdout或--to-stdout 把压缩后的文件输出到标准输出设备，不去更动原始文件。 

-d或--decompress或----uncompress 解开压缩文件。 

-f或--force 强行压缩文件。不理会文件名称或硬连接是否存在以及该文件是否为符号连接。 

-h或--help 在线帮助。

-l或--list 列出压缩文件的相关信息。

-L或--license 显示版本与版权信息。

-n或--no-name 压缩文件时，不保存原来的文件名称及时间戳记。 

-N或--name 压缩文件时，保存原来的文件名称及时间戳记。 

-q或--quiet 不显示警告信息。 

-r或--recursive 递归处理，将指定目录下的所有文件及子目录一并处理。 

-S<压缩字尾字符串>或----suffix<压缩字尾字符串> 更改压缩字尾字符串。 

-t或--test 测试压缩文件是否正确无误。 

-v或--verbose 显示指令执行过程。 

-V或--version 显示版本信息。 

-<压缩效率> 压缩效率是一个介于1－9的数值，预设值为"6"，指定愈大的数值，压缩效率就会愈高。

--best 此参数的效果和指定"-9"参数相同。 

--fast 此参数的效果和指定"-1"参数相同。

#rar文件处理

官网:http://www.rarsoft.com/download.htm

    tar -xzpvf rarlinux-3.2.0.tar.gz 
    cd rar 
    make
    

    rar a all *.jpg
这条命令是将所有.jpg的文件压缩成一个rar包，名为all.rar，该程序会将.rar 扩展名将自动附加到包名后。

    unrar e all.rar
这条命令是将all.rar中的所有文件解压出来

压缩 

tar –cvf jpg.tar *.jpg //将目录里所有jpg文件打包成tar.jpg 

tar –czf jpg.tar.gz *.jpg //将目录里所有jpg文件打包成jpg.tar后，并且将其用gzip压缩，生成一个gzip压缩过的包，命名为jpg.tar.gz 

tar –cjf jpg.tar.bz2 *.jpg //将目录里所有jpg文件打包成jpg.tar后，并且将其用bzip2压缩，生成一个bzip2压缩过的包，命名为jpg.tar.bz2 

tar –cZf jpg.tar.Z *.jpg //将目录里所有jpg文件打包成jpg.tar后，并且将其用compress压缩，生成一个umcompress压缩过的包，命名为jpg.tar.Z 

rar a jpg.rar *.jpg //rar格式的压缩，需要先下载rar for linux 

zip jpg.zip *.jpg //zip格式的压缩，需要先下载zip for linux

解压

tar –xvf file.tar //解压 tar包 

tar -xzvf file.tar.gz //解压tar.gz 

tar -xjvf file.tar.bz2 //解压 tar.bz2 

tar –xZvf file.tar.Z //解压tar.Z 

unrar e file.rar //解压rar 

unzip file.zip //解压zip 

1、*.tar 用 tar –xvf 解压

2、*.gz 用 gzip -d或者gunzip 解压 

3、*.tar.gz和*.tgz 用 tar –xzf 解压 

4、*.bz2 用 bzip2 -d或者用bunzip2 解压 

5、*.tar.bz2用tar –xjf 解压 

6、*.Z 用 uncompress 解压 

7、*.tar.Z 用tar –xZf 解压 

8、*.rar 用 unrar e解压 

9、*.zip 用 unzip 解压
