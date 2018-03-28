<?php
echo exec("cd /usr/local/nginx/html/Blog && git pull origin master:master && hexo clean && hexo generate && git deploy");
?>
