---
title: 2018040201
date: 2018-04-02 23:50:33
tags:
- linux
- shell
- 字符串
categories:
- linux
- shell
- 字符串
---
# shell字符串操作

<ul>
<li><a href="#define">定义和输出</a></li>
<li><a href="#length">字符串长度</a></li>
<li><a href="#substr">字符串截取</a></li>
<li><a href="#delete">字符串删除</a></li>
<li><a href="#replace">字符串替换</a></li>
<li><a href="#index">字符串索引</a></li>
<li><a href="#split">split字符串</a></li>
<li><a href="#match">从字符串开始的位置匹配子串的长度</a></li>
</ul>
---

<h2 id="define">定义与输出</h2>

  <span style="color:red;">echo "${str}"</span>
   
  <span style="color:red;">echo "$str"</span>
  
  <span style="color:red;">echo "${str=DEFAULT}"</ 
  
    str="string"
    
    echo "$str"
    
    echo "${str}"
    
    #如果str没声明，则输出DEFAULT  
    echo "${str=DEFAULT}"  
    #DEFAULT 
    
 <h2 id="length">字符串长度</h2> 
   
   <span style="color:red;">${#str}</span>
   
   <span style="color:red;">expr length $str</span>
   
    str="string"
    
    echo ${#str}
    echo `expr length $str`
    
    str="abc,def,ghi,abcjkl"  
    echo ${#str}
    
  <h2 id="substr">字符串截取</h2>  
  
   <span style="color:red;">索引从0开始</span>
   
   <span style="color:red;">${str:start}</span>
   
   <span style="color:red;">${str:start:length}</span>
   
   <span style="color:red;">expr substr $str start length</span>(length不能够省略)
   
    #从第5个元素开始截取 
        echo ${str:5}
    #ef,ghi,abcjkl 
    
    #从第5个元素开始截取8个字符
        echo ${str:5:8}  
        echo `expr substr $str 5 8`
    #ef,ghi,a
    
<span style="color:red;">倒序截取</span>(最后一个字符为-1)

    stringZ=abcABC123ABCabc
    
    echo ${stringZ:(-4)}                         # Cabc
    
    echo ${stringZ: -4}                          # Cabc
    
  <h2 id="delete">字符串删除</h2>  
  
   **<span style="color:red;">要删除的子字符串必须是字符串的开头或结尾,否则删除无效</span>**
   
   <span style="color:red;">从开始处删除</span>
   
   <span style="color:red;">${str#substring}</span>(从str开头开始删除最短的substring匹配)
   
   <span style="color:red;">${str##substring}</span>(从str开头开始删除最长的substring匹配)
   
  <span style="color:red;">从结尾处删除</span>
   
   <span style="color:red;">${str%substring}</span>(从str结尾开始删除最短的substring匹配)
   
   <span style="color:red;">${str%%substring}</span>(从str结尾开始删除最长的substring匹配)
   
    str="abc,def,ghi,abcjkl"  
    
    #从str开头开始删除最短的a*c匹配  
         echo ${str#a*c}  
    #,def,ghi,abcjkl 

    ##从str开头开始删除最长的a*c匹配   
         echo ${str##a*c} 
    #jkl  

    #从str结尾开始删除最短的b*l匹配  
        echo ${str%b*l}  
    #abc,def,ghi,a
    
    #从str结尾开始删除最长的b*l匹配  
        echo ${str%%b*l}  
    #a

<h2 id="replace">字符串替换</h2>

  替换第一个目标子字符串:<span style="color:red;">${str/substring/replace}</span>
  
  替换所有目标子字符串:<span style="color:red;">${str//substring/replace}</span>
  
    str="abc,def,ghi,abcjkl" 
    
    #用TEST替换字符串中第一个abc
        echo ${str/abc/TEST}  
    #TEST,def,ghi,abcjkl
    
    #用TEST替换字符串中所有的abc  
        echo ${str//abc/TEST}  
    #TEST,def,ghi,TESTjkl
    
<span style="color:red;">开头匹配替换</span>

    #从str开头匹配，用TEST替换最长的a*c  
        echo ${str/#a*c/TEST}  
    #TESTjkl
    
<span style="color:red;">结尾匹配替换</span>

    #从str结尾匹配，用TEST替换最长的b*l  
        echo ${str/%b*l/TEST}  
    #aTEST 
    
<h2 id="index">字符串索引</h2>

    #求字符串中元素的下标索引，如果元素不存在输出0（因为此时索引从1开始）
    
    str="abc,def,ghi,abcjkl" 
    index=`expr index $str "2"`  
    echo $index  
    #0

    index=`expr index $str "a"`  
    echo $index  
    #1
    
    index=`expr index $str "ab"`  
    echo $index  
    #1
    
<h2 id="split">split字符串</h2>

    str='2016-01-05'
    
    echo $str | awk -F '-' '{print $1}' 
    
    echo '2016-01-05'|cut -d "-" -f 1
    
    echo $str|echo ${str:0:4}
    
    echo $str | awk -F '-' '{for( i=1;i<=NF; i++ ) print $i}'  
    
    #2016  
    #2016  
    #2016  
    #01  
    #05  
    
    arr=(${str//-/ })  
    echo ${arr[0]} 
    #2016

<h2 id="match">从字符串开始的位置匹配子串的长度</h2>

  expr match "$string" '$substring' $substring是一个正则表达式
  
  expr "$string" : '$substring'  $substring是一个正则表达式
   
      stringZ=abcABC123ABCabc
      
      echo `expr match "$stringZ" 'abc[A-Z]*.2'`  #8
      echo `expr "$stringZ" : 'abc[A-Z]*.2'`      #8
