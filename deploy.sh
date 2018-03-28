#!/bin/bash
cd /usr/local/nginx/html/Blog
git pull origin master:master
hexo clean
hexo generate
hexo deploy
