---
title: git常用操作
date: 2018-04-03 13:35:33
tags:
- linux
- git
categories:
- linux
- git

#git常用操作

---

<ul>
<li><a href="#account">账号配置</a></li>
<li><a href="#font">linux git中文文件名乱码</a></li>
<li><a href="#branch">分支管理</a></li>
<li><a href="#change">修改处理</a></li>
<li><a href="#diff_content">差异比较</a></li>
<li><a href="#commit_content">提交内容查看</a></li>
<li><a href="#log">提交历史</a></li>
<li><a href="#remote">远程仓库管理</a></li>
<li><a href="#tag">标签管理</a></li>
<li><a href="#store">暂存区管理</a></li>
</ul>

<h2 id="account">账号配置</h2>

    git config --global user.name "用户名"
    git config --global user.email 邮箱
    
    git config --list
    
    如果你用git从远程pull拉取代码，每次都要输入密码，那么执行下面命令即可

    git config --global credential.helper store
    
<h2 id="font">linux git中文文件名乱码</h2>
    
    (1)方案一
        git config --global core.quotepath false
    (1)方案二
        vim ~/.gitconfig
        添加:
            [core]
                 quotepath = false
    
<h2 id="branch">分支管理</h2>

查看所有分支

    git branch -a
    
查看本地分支

    git branch

查看远程分支

    git branch -r
    
切换分支

    git checkout <branchName>
    
    git checkout - 切换到之前的分支
    
创建分支

    git branch <branchName>
    
    git checkout -b <branchName>

重命名分支

    git branch -m <old> <new>

删除本地分支

    git branch -D <branchName>
    
    git branch|grep hotfix|xargs git branch -D

删除远程分支

    git push origin --delete <branchName>
    
    git push origin :<branchName>

git查看本地分支关联（跟踪）的远程分支之间的对应关系，本地分支对应哪个远程分支

    git branch -vv
    
查看每个分支最后一次提交的信息:

    git branch -v
    
正则表达式处理分支

        git branch -r |awk -F '/' '/origin/ {printf "%s", $3}' |xargs -I {} echo {}
        
        git branch -r |awk -F '[/]' '/origin/ {printf "%s\n", $3}' |xargs -I {} git push origin :{}
    
        git branch -r |awk -F '[/_]' '/origin/ {printf "%s\n", $3}' |xargs -I {} git push origin :{}
    
        git branch -r |awk -F '[/]' '/origin/ {printf "%s\t", $3}' |xargs -I {} git push origin :{}
    
        git branch -r |awk -F '[/_]' '/origin/ {printf "%s\t", $3}' |xargs -I {} git push origin :{}
    

awk -F支持多分隔符     

    http://blog.csdn.net/tianxuhong/article/details/46456423  
    
    http://blog.csdn.net/love_android_2011/article/details/43986621

远程拉取推送

    git push <远程主机名> <本地分支名>:<远程分支名>
    
    git pull <远程主机名> <远程分支名>:<本地分支名>
    
    git branch --set-upstream   <远程主机名>origin <远程分支名>test
    建立追踪关系，在现有分支与指定的远程分支之间
    
<h2 id="#change">修改处理</h2>

丢弃修改

    git checkout -- <filename>
    
添加修改到暂存区
    
    git add <filename>
    
提交修改

    git commit -m "信息"  一次性提交所有修改过的文件
    
    git commit -a -m "信息"  一次性提交所有修改过的文件(不需要git add，注意新加的文件不会被提交)
  
  添加所有修改到暂存区

    git add --all

提交暂存区的指定文件到仓库区
   
    git commit [file1] [file2] ... -m [message]

提交工作区自上次commit之后的变化，直接到仓库区
    
    git commit -a

提交时显示所有diff信息
    
    git commit -v

使用一次新的commit，替代上一次提交
   
    如果代码没有任何新变化，则用来改写上一次commit的提交信息
    
    git commit --amend -m [message]

重做上一次commit，并包括指定文件的新变化
    
    git commit --amend [file1] [file2] ...  
    
合并分支

    git merge命令用于将两个或两个以上的开发历史加入(合并)一起
    
    git merge 分支名称
    
<h2 id="diff_content">差异比较</h2>

比较暂存区和版本库差异

    git diff --staged

比较暂存区和版本库具体差异

    git diff --cached

在两个分支之间比较

    git diff <branch1> <branch2>
    
比较两个分支的整体差异情况

    git diff --stat master 
    
    git diff --stat master   filename

比较两个分支的的某个文件差异情况
    
    git diff branchname -- filename
    
比较两次提交之间的差异

    git diff <id1> <id2>
    
<h2 id="commit_content">提交内容查看</h2>

查看某次提交具体修改的内容
    
    在推送(push)操作之前，如想要检查文件代码变化，可使用git show命令指定提交ID来查看具体的变化。
    
    git show commit_id

显示某次提交发生变化的文件名称

    git show --name-only [commitid]

显示某次提交时，某个文件的全部内容

    git show [commitid]:[filename]

<h2 id="remote">远程仓库管理</h2>

添加远程仓库

    git remote add [shortname] [url]

不带参数，列出已经存在的远程仓库

    git remote

列出详细信息，在每一个名字后面列出其远程url

    git remote -v

查看某个远程仓库具体信息

    git remote show <name>
    
重命名某个远程仓库
    
    git remote rename <old> <new>
    
删除某个远程仓库

    git remote remove <name>


<h2 id="tag">标签管理</h2>

删除本地标签

    git tag -d <tagname>

删除远程标签

    git push origin --delete tag <tagname>
    
    git push origin :refs/tags/<tagname>

<h2 id="log">提交历史</h2>

命令用于汇总每个人commit记录输出(将每个人的提交记录汇总到一起输出)

    git shortlog

搜索提交历史，根据关键词

    git log -S [keyword]

查看提交统计信息(每一次提交修改了那些文件)
    
    git log --stat 

只在一行中查看版本号以及commit信息

    git log --oneline --decorate

查看文件历史版本信息(关于这个文件的提交记录)
    
    git log --follow filename
    
文件内容修改历史(可以跟踪到每一行是哪个添加的)
   
    git blame filename 

显示指定文件相关的每一次diff(每一次提交的改动情况)

    git log -p filename

显示指定文件相关的commit记录
   
    git whatchanged filename

<h2 id="store">暂存区管理</h2>

要切换分支以进行客户升级，但不想提交一直在做的工作; 那么可以把当前工作的改变隐藏起来。 要将一个新的存根推到堆栈上，运行git stash命令
    
    git stash
    
现在，工作目录是干净的，所有更改都保存在堆栈中。现在使用git status命令来查看当前工作区状态。


命令来查看已存在更改的列表
   
    git stash list
    
已经解决了客户升级问题，想要重新开始新的功能的代码编写，查找上次没有写完成的代码，只需执行git stash pop命令即可从堆栈中删除更改并将其放置在当前工作目录中

    git stash pop

恢复暂存的内容

    git stash apply

删除第一个暂存区
    
    git stash drop
