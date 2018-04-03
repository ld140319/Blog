---
title: awk入门
date: 2018-034-03 22:08:11
tags: 
- linux
- shell
- 文件操作

categories: 
- linux
- shell
- 文件操作

#awk入门

---

<ul>
<li><a href="#Introduce">介绍</a></li>
<li><a href="#todo">典型用途</a></li>
<li><a href="#stream">工作流</a></li>
<li><a href="#ProgramStructure">程序结构</a></li>
</ul>

<h2 id="Introduce">介绍</h2>

<p>&nbsp;&nbsp;&nbsp;&nbsp;AWK是一门解释型的编程语言。在文本处理领域它是非常强大的，它的名字<span style="color:red;">来源于它的三位作者的姓氏：Alfred Aho， Peter Weinberger 和 Brian Kernighan。</span>
GNU/Linux发布的AWK目前由自由软件基金会（FSF）进行开发和维护，通常也称它为 GNU AWK。</p>

<h2 id="todo">典型用途</h2>

    文本处理
    输出格式化的文本报表(格式化数据处理)
    执行算数运算(统计:如txt文件总工资 搜索出来的文件的总大小)
    执行字符串操作等等

<h2 id="stream">工作流</h2>

![awk执行流程](awk入门/awk执行流程.jpg)

    读取=>执行=>重复前两步
    
    Read

    AWK从输入流（文件，管道或者标准输入）中读取一行，然后存储到内存中。
    
    Execute
    
    所有的AWK命令都依次在输入上执行。默认情况下，AWK会对每一行执行命令，我们可以通过提供模式限制这种行为。
    
    Repeat
    
    处理过程不断重复，直到到达文件结尾。
    
<h2 id="ProgramStructure">程序结构</h2>

&nbsp;&nbsp;BEGIN 语句块

        BEGIN {awk-commands}
    
&nbsp;&nbsp;&nbsp;&nbsp;BEGIN语句块<em    style="color:red;">在程序开始的使用执行，它只执行一次，在这里可以初始化变量。BEGIN是AWK的关键字，因此它必须为大写，注意，这个语句块是可选的。</em>

&nbsp;&nbsp;BODY 语句块

        /pattern/ {awk-commands}
        

&nbsp;&nbsp;&nbsp;&nbsp;<em    style="color:red;">BODY语句块中的命令会对输入的每一行执行</em>，我们也可以通过提供模式来控制这种行为。注意，BODY语句块没有关键字。

&nbsp;&nbsp;END 语句块

        END {awk-commands}
        
&nbsp;&nbsp;&nbsp;&nbsp;<em    style="color:red;">END语句块在程序的最后执行，仅仅执行一次END是AWK的关键字，因此必须为大写，它也是可选的。</em>

<h2 id="env">内置变量</h2>

|变量名|含义
|------|----
|FILENAME|当前输入文件的名字(<span style="color:red">一般用于BODY中</span>)
|$0|当前记录整个行的内容(<span style="color:red">一般用于BODY中</span>)
|\$1-\$n|当前记录的第n个字段，字段间由FS分隔(<span style="color:red">一般用于BODY中</span>)
|FS|输入字段分隔符 默认是空格或Tab(<span style="color:red">一般用于BEGIN中,重新指定</span>)
|OFS|输出字段分隔符， 默认也是空格(<span style="color:red">一般用于BEGIN中,重新指定</span>)
|NF|当前记录中的字段个数，就是有多少列(<span style="color:red">一般用于BODY中</span>)
|NR|已经读出的记录数，就是行号，从1开始，如果有多个文件话，这个值也是不断累加中(<span style="color:red">一般用于BODY中</span>)
|FNR|当前记录数，与NR不同的是，这个值会是各个文件自己的行号(<span style="color:red">一般用于BODY中</span>)
|RS|输入的记录分隔符， 默认为换行符(<span style="color:red">一般用于BEGIN中,重新指定</span>)
|ORS|输出的记录分隔符，默认为换行符(<span style="color:red">一般用于BEGIN中,重新指定</span>)

<h2 id="test">例子</h2>

    vim marks.txt
        1) Amit     Physics    80
        2) Rahul    Maths      90
        3) Shyam    Biology    87
        4) Kedar    English    85
        5) Hari     History    89
        
    (1)显示该文件的完整内容
        
        awk '{print}' marks.txt
        awk '{print $0}' marks.txt
    
![显示该文件的完整内容](awk入门/显示该文件的完整内容.png)

    (2)显示含a的行
            
            awk '/a/ {print}' marks.txt
            awk '/a/ {print $0}' marks.txt
    
![含a的行](awk入门/含a的行.png)

    (3)显示不含a的行
            
            awk '!/a/ {print}' marks.txt
            awk '!/a/ {print $0}' marks.txt
    
![不含a的行](awk入门/不含a的行.png)
        