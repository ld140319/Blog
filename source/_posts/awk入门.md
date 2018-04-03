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

    ![awk执行流程][awk入门/awk执行流程.jpg]
