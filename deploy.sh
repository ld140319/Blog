#!/bin/bash
cd /usr/local/nginx/html/Blog
chmod 755 ls /usr/local/nginx/html/Blog/deploy.exp
if [ ! -d /usr/local/nginx/html/Blog/themes/next/ ]
then
 mkdir -p /usr/local/nginx/html/Blog/themes/next/
fi
count=`ls /usr/local/nginx/html/Blog/themes/next/|wc -w`
if [ $count -eq 0 ]
then
 git clone https://github.com/iissnan/hexo-theme-next  /usr/local/nginx/html/Blog/themes/next
fi
# git reset --hard HEAD
git checkout -- /usr/local/nginx/html/Blog/public/*
git checkout -- /usr/local/nginx/html/Blog/deploy.*
git pull origin master:master -f
chmod 755  /usr/local/nginx/html/Blog/deploy.sh
chmod 755  /usr/local/nginx/html/Blog/deploy.exp
hexo clean
hexo generate
hexo deploy
