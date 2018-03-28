#!/bin/bash
cd /usr/local/nginx/html/Blog
chmod 755 ls /usr/local/nginx/html/Blog/deploy.exp
count=`ls /usr/local/nginx/html/Blog/themes/next/|wc -w`
if [ $count -eq 0 ];
then
 git clone https://github.com/iissnan/hexo-theme-next  /usr/local/nginx/html/Blog/themes/next
 fi
# git reset --hard HEAD
 git pull origin master:master -f
 hexo clean
 hexo generate
 hexo deploy
