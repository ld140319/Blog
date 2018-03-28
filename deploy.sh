#!/bin/bash
cd /usr/local/nginx/html/Blog
chmod 755 deploy.exp
count=`ls themes/next|wc -w`
if [ "$count" < "0" ];
then
 git clone https://github.com/iissnan/hexo-theme-next themes/next
 fi
 git pull origin master:master
 hexo clean
 hexo generate
 hexo deploy
