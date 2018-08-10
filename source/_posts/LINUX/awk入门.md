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
<li><a href="#controll">控制语句</a></li>
<li><a href="#function">自定义函数</a></li>
<li><a href="#arg">命令行参数</a></li>
<li><a href="#var">使用内置变量</a></li>
<li><a href="#env">使用环境变量</a></li>
<li><a href="#redirect">输出重定向</a></li>
<li><a href="#pipeline">管道</a></li>
<li><a href="#test">例子</a></li>
<li><a href="#example">示例</a></li>
<li><a href="#link">参考地址</a></li>
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

<em style="color:#2B91D5;">
awk 程序并不一定要处理数据文件
BEGIN 和 END 同为awk中的一种 Pattern. 以 BEGIN 为 Pattern的Actions ,只有在awk开始执行程序,尚未开启任何输入文件前, 被执行一次.(注意: 只被执行一次)
</em>

<h2 id="controll">控制语句(流程控制)</h2>

    (1)if
        if (condition)
           action
         
        if (condition) {
           action-1
           action-1
           .
           .
           action-n
        }
         
        if (condition)
           action-1
        else if (condition2)
           action-2
        else
           action-3
           
    (2)for
    
        for (initialisation; condition; increment/decrement) {
           action-1
               action-1
               .
               .
               action-n
        }
   
   (3)while
    
        while (condition) {
           action-1
               action-1
               .
               .
               action-n
        }
        
   (4)do while
    
        do {
           action-1
               action-1
               .
               .
               action-n
        } while (condition)
    
<h2 id="var">常用内置变量</h2>

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
|IGNORECASE|正则匹配时,忽略大小写 1忽略 0不忽略(<span style="color:red">一般用于BEGIN中</span>)
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

    (4)正则匹配忽略大小写
                
                awk 'BEGIN{INGORECASE=1}/a/ {print}' marks.txt
                awk 'BEGIN{INGORECASE=0}/a/ {print $0}' marks.txt
    
![匹配时,不区分大小写](awk入门/匹配时,不区分大小写.png)

<h2 id="arg">命令行参数</h2>

<em style="color:red;">
    ARGC:参数个数
    ARGV:参数数组
</em>
    
    (1)命令行运行
    
    awk 'BEGIN { 
       for (i = 0; i < ARGC - 1; ++i) { 
          printf "ARGV[%d] = %s\n", i, ARGV[i] 
       } 
    }' one two three four
    
    (2)脚本运行  
    
    vim command.awk
    
    #!/bin/awk
    BEGIN {
      for (i = 0; i < ARGC - 1; ++i) {
         printf "ARGV[%d] = %s\n", i, ARGV[i]
      }
    }
    
    awk -f command.awk one two three four
    
![命令行参数](awk入门/命令行参数.png)

<h2 id="env">使用环境变量</h2>

<em style="color:red;">
查看所有环境变量: env
    ENVIRON[KEY]
</em>
    
    (1)命令行运行
    
    awk 'BEGIN { 
       print "user:"ENVIRON["USER"]
       print "shell:"ENVIRON["SHELL"]
    }' 
    
    (2)脚本运行  
    
    vim command.awk
    
    #!/bin/awk
    BEGIN {
      print "user:"ENVIRON["USER"]
      print "shell:"ENVIRON["SHELL"]
    }
    
    awk -f command.awk 
    
![使用环境变量](awk入门/使用环境变量.png)

<h2 id="operation">运算</h2>

    (1)算数操作符
    awk 'BEGIN { a = 50; b = 20; print "(a + b) = ", (a + b) }'
    
    awk 'BEGIN { a = 50; b = 20; print "(a - b) = ", (a - b) }'
    
    awk 'BEGIN { a = 50; b = 20; print "(a * b) = ", (a * b) }'
    
    awk 'BEGIN { a = 50; b = 20; print "(a / b) = ", (a / b) }'
    
    awk 'BEGIN { a = 50; b = 20; print "(a % b) = ", (a % b) }'
    
    (2)增减运算符
    
    awk 'BEGIN { a = 10; b = ++a; printf "a = %d, b = %d\n", a, b }'
    
    awk 'BEGIN { a = 10; b = --a; printf "a = %d, b = %d\n", a, b }'
    
    awk 'BEGIN { a = 10; b = a++; printf "a = %d, b = %d\n", a, b }'
    
    awk 'BEGIN { a = 10; b = a--; printf "a = %d, b = %d\n", a, b }'
    
    (3)赋值操作符
    
    awk 'BEGIN { name = "Jerry"; print "My name is", name }'
    
    awk 'BEGIN { cnt = 10; cnt += 10; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 100; cnt -= 10; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 10; cnt *= 10; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 100; cnt /= 5; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 100; cnt %= 8; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 2; cnt ^= 4; print "Counter =", cnt }'
    
    awk 'BEGIN { cnt = 2; cnt **= 4; print "Counter =", cnt }'
    
    (4)关系操作符
    
    awk 'BEGIN { a = 10; b = 10; if (a == b) print "a == b" }'
    awk 'BEGIN { a = 10; b = 20; if (a != b) print "a != b" }'
    awk 'BEGIN { a = 10; b = 20; if (a<b) print "b > a" }'
    
    (5)逻辑操作符
    
    awk 'BEGIN {
       num = 5; if (num >= 0 && num <= 7) printf "%d is in octal format\n", num
    }'
    
    awk 'BEGIN {
       ch = "\n"; if (ch == " " || ch == "\t" || ch == "\n")
       print "Current character is whitespace."
    }'
    
    awk 'BEGIN { name = ""; if (! length(name)) print "name is empty string." }'
    
    (6)三元操作符
    
    awk 'BEGIN { a = 10; b = 20; (a > b) ? max = a : max = b; print "Max =", max}'
    
    awk 'BEGIN { a = 10; b = 20; max=(a > b) ?  a : b; print "Max =", max}'
    
    (7)一元操作符(取反)
    
    awk 'BEGIN { a = -10; a = +a; print "a =", a }'
    
    awk 'BEGIN { a = -10; a = -a; print "a =", a }'
    
    (8)指数操作符(a的多少次方)
   
    awk 'BEGIN { a = 10; a = a ^ 2; print "a =", a }'
    
    awk 'BEGIN { a = 10; a ^= 2; print "a =", a }'
    
    (9)字符串连接操作符
    
    awk 'BEGIN { str1 = "Hello, "; str2 = "World"; str3 = str1 str2; print str3 }'
    
    (10)数组成员操作符
    
    awk 'BEGIN { 
       arr[0] = 1; arr[1] = 2; arr[2] = 3; for (i in arr) printf "arr[%d] = %d\n", i, arr[i]
    }'
    
    awk 'BEGIN {
       fruits["mango"] = "yellow";
       fruits["orange"] = "orange"
       print fruits["orange"] "\n" fruits["mango"]
    }'

    awk 'BEGIN {
       array["0,0"] = 100;
       array["0,1"] = 200;
       array["0,2"] = 300;
       array["1,0"] = 400;
       array["1,1"] = 500;
       array["1,2"] = 600;
     
       # print array elements
       print "array[0,0] = " array["0,0"];
       print "array[0,1] = " array["0,1"];
       print "array[0,2] = " array["0,2"];
       print "array[1,0] = " array["1,0"];
       print "array[1,1] = " array["1,1"];
       print "array[1,2] = " array["1,2"];
    }'

    (11)正则表达式操作符
    
    正则表达式操作符使用 ~ 和 !~ 分别代表匹配和不匹配
    匹配正则表达式需要在表达式前后添加反斜线
        
        awk '{if($0 ~ /9/) {print $0}}' marks.txt
        
        awk '{if($0 !~ /9/) {print $0}}' marks.txt
        
<h2 id="function">自定义函数</h2>

    # Returns minimum number
    function find_min(num1, num2){
       if (num1<num2)
            return num1
       return num2
    }
    
    # Returns maximum number
    function find_max(num1, num2){
       if (num1>num2)
            return num1
       return num2
    }
    
    # Main function
    function main(num1, num2){
       
       # Find minimum number
       result = find_min(10, 20)
       print "Minimum =", result
     
       # Find maximum number
       result = find_max(10, 20)
       print "Maximum =", result
    }
    
    # Script execution starts here
    BEGIN {
       main(10, 20)
    }
    
<h2 id="redirect">输出重定向</h2>

 <em style="color:red;">
  print DATA > output-file
  print DATA >> output-file
 </em>
   
    echo "Hello, World !!!" > /tmp/message.txt
    awk 'BEGIN { print "Hello, World !!!" > "/tmp/message.txt" }'
    
    awk 'BEGIN { print "Hello, World !!!" >> "/tmp/message.txt" }'
    cat /tmp/message.txt
    
<h2 id="pipeline">管道</h2>

    (1)单向管道
        
   awk程序中可接受下列两种语法:
   
        a: awk output 指令 | "Shell 接受的命令"
        (如 : print $1,$2 | "sort -k 1")
        
        b: "Shell 接受的命令" | awk input 指令
        (如 : "ls " | getline)

 

注 : 
<em style="color:red;">
awk input 指令只有 getline 一个.
awk output 指令有 print, printf() 二个.
</em>

在a 语法中, awk所输出的数据将转送往 Shell , 由 Shell 的命令进行处理.以上例而言, print 所输出的数据将经由 Shell 命令 "sort -k 1" 排序后再送往屏幕(stdout).

上列awk程序中, <em style="color:red;">"print $1, $2" 可能反复执行很多次, 其输出的结果将先暂存于 pipe 中,等到该程序结束时, 才会一并进行 "sort -k 1".</em>

须注意二点 :
<em style="color:#43CD80;">
    
1.不论 print $1, $2 被执行几次, "sort -k 1" 的执行时间是 "awk程序结束时",

2."sort -k 1" 的执行次数是 "一次".
</em>
 

在 b 语法中, awk将先调用 Shell 命令. 其执行结果将通过 pipe 送入awk程序,以上例而言, <em style="color:red;">awk先让 Shell 执行 "ls",Shell 执行后将结果存于 pipe, awk指令 getline再从 pipe 中读取数据.</em>

使用本语法时应留心: 

<em style="color:#43CD80;">
1.awk "立刻"调用 Shell 来执行 "ls", 执行次数是一次.
    
2.getline 则可能执行多次(若pipe中存在多行数据).
</em>

<em style="color:red;">除上列 a, b 二中语法外, awk程序中其它地方如出现像 "date", "cls", "ls"... 这样的字符串, awk只把它当成一般字符串处理.</em>

&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;">输出重定向需用到getline函数。</span>getline从标准输入、管道或者当前正在处理的文件之外的其他输入文件获得输入。<span style="color:red;">它负责从输入获得下一行的内容，并给NF,NR和FNR等内建变量赋值</span>。如果得到一条记录，getline函数返回1，如果到达文件的末尾就返回0，如果出现错误，例如打开文件失败，就返回-1。


|语法|位置|由何处读取数据|数据读入后置于|
|----|--------------|--------------|--------------|
|getline var< file|body|所指定的 file|变量 var(var省略时,表示置于$0)|
|getline var|body|pipe 变量|变量 var(var省略时,表示置于\$0)|
|getline var|begin/end|见 注一|变量 var(var省略时,表示置于\$0)|
注一 : 当 Pattern 为 BEGIN 或 END 时, getline 将由 stdin 读取数据, 否则由awk正处理的数据文件上读取数据.

&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;">awk 'BEGIN{ "date" | getline d; print d}'</span>
    
        执行linux的date命令，并通过管道输出给getline，然后再把输出赋值给自定义变量d，并打印它。
        
---

&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;">awk 'BEGIN{"date" | getline d; split(d,mon); print mon[2]}'</span>  
    
        split (string, array, field separator)
        split (string, array)           -->如果第三个参数没有提供，awk就默认使用当前FS值
   
        执行shell的date命令，并通过管道输出给getline，然后getline从管道中读取并将输入赋值给d，split函数把变量d转化成数组mon，然后打印数组mon的第二个元素。

---

&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;">awk 'BEGIN{while( "ls" | getline) print}'
&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:red;">awk 'BEGIN{while( "ls" | getline d) print d}'</span>
    
        命令ls的输出传递给geline作为输入，循环使getline从ls的输出中读取一行，并把它打印到屏幕。这里没有输入文件，因为BEGIN块在打开输入文件前执行，所以可以忽略输入文件。

---
    #!/bin/awk
    BEGIN {
        FS=":"
        printf "What is your name?"; getline name < "/dev/tty"
    }
    {
        if ($1 ~ name) print "Found name on line ", NR
    }
    END{print "See you," name}
    
    awk -f command.awk /etc/passwd

        在屏幕上打印”What is your name?",并等待用户应答。当一行输入完毕后，getline函数从终端接收该行输入，并把它储存在自定义变量name中。如果第一个域匹配变量name的值，print函数就被执行，END块打印See you和name的值。
        
 ---       
    awk 'BEGIN {
            while ((getline one < "/etc/passwd")> 0){
                lc++; 
                print one
            }
            print lc
          }'

    wc -l "/etc/passwd"
    
        awk将逐行读取文件/etc/passwd的内容，在到达文件末尾前，计数器lc一直增加，当到末尾时，打印lc的值。注意，如果文件不存在，getline返回-1，如果到达文件的末尾就返回0，如果读到一行，就返回1，所以命令 while (getline < "/etc/passwd")在文件不存在的情况下将陷入无限循环，因为返回-1表示逻辑真。

---
        可以在awk中打开一个管道，且同一时刻只能有一个管道存在。通过close()可关闭管道。
    
    如：$ awk 'BEGIN{FS=":"}{print $1, $2 | "sort" }END{close("sort")}' /etc/passwd
    
    awk把print语句的输出通过管道作为linux命令sort的输入,END块执行关闭管道操作。

    BEGIN {
        "date" | getline current_time
        close("date")
        print "Report printed on " current_time
    }
        
    awk '{print \$1, \$2 | "sort" }END {close("sort")}' mark.txt
    awk '{print \$1, \$2 | "sort -r" }END {close("sort -r")}' mark.txt
    awk '{print \$1, \$2 | "sort"}END {close("sort")}' mark.txt >>sort.txt

---

    (2)双向连接(协同进程)

    awk 'BEGIN {
        cmd = "tr [a-z] [A-Z]"
        print "hello, world !!!" |& cmd
        close(cmd, "to")
        cmd |& getline out
        print out;
        close(cmd);
    }'
        
---        
    (3)调用shell命令
        
        system该函数用于执行指定的命令并且返回它的退出状态，返回状态码0表示命令成功执行

        BEGIN {
            date_cmd="date -d '-3 days' +'%Y/%m/%d'";
            ret = system(date_cmd); 
            print "Return value = " ret 
         }
        awk 'BEGIN{system("clear")'
        
<h2 id="example">示例</h2>
    
    (1)文件拆分
    
        netstat -antep|awk '{if(NR!=1)print $4;}'
    
    （2)查看客户端连接
    
        netstat -ntu | awk '{print $5}' | cut -d: -f1 | sort | uniq -c | sort -nr
        
    (3)每个用户的进程的占了多少内存
    
        ps aux | awk 'NR!=1{a[$1]+=$6;} END { for(i in a) print i ", " a[i]"KB";}'
    
    (4)线上人数
        
        BEGIN {

            while ( "who" | getline ) n++
            
            print n
        
        }
        
    (4)统计学生成绩
    
    cat score.txt
        Marry   2143 78 84 77
        Jack    2321 66 78 45
        Tom     2122 48 77 71
        Mike    2537 87 97 95
        Bob     2415 40 57 62
        
    #!/bin/awk -f
    #运行前
    BEGIN {
        math = 0
        english = 0
        computer = 0
     
        printf "NAME    NO.   MATH  ENGLISH  COMPUTER   TOTAL\n"
        printf "---------------------------------------------\n"
    }
    #运行中
    {
        math+=$3
        english+=$4
        computer+=$5
        printf "%-6s %-6s %4d %8d %8d %8d\n", $1, $2, $3,$4,$5, $3+$4+$5
    }
    #运行后
    END {
        printf "---------------------------------------------\n"
        printf "  TOTAL:%10d %8d %8d \n", math, english, computer
        printf "AVERAGE:%10.2f %8.2f %8.2f\n", math/NR, english/NR, computer/NR
    }

<h2 id="link">参考链接</h2>
    
[三十分钟学会AWK](http://blog.jobbole.com/109089/ "三十分钟学会AWK")
[awk实战与总结](http://www.fzb.me/2016-9-27-awk-in-action.html "awk实战与总结")
[见过最好的AWK手册](https://blog.csdn.net/aqi2014/article/details/41218403 "见过最好的AWK手册")
[awk 用法（使用入门）](https://www.cnblogs.com/emanlee/p/3327576.html#id2808971 "awk 用法（使用入门）") 
[AWK入门指南](http://awk.readthedocs.io/en/latest/chapter-one.html "AWK入门指南") 
[linux下awk内置函数的使用(split/substr/length)](https://www.cnblogs.com/sunada2005/p/3493941.html "linux下awk内置函数的使用(split/substr/length)")   
    
