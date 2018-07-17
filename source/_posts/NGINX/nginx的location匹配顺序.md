# nginx location匹配

## location表达式类型

~    表示执行一个正则匹配，区分大小写
~*   表示执行一个正则匹配，不区分大小写
^~   表示普通字符匹配。使用前缀匹配。如果匹配成功，则不再匹配其他location。
=    进行普通字符精确匹配。也就是完全匹配。
@    它定义一个命名的 location，使用在内部定向时，例如 error_page, try_files


## location匹配顺序

在nginx的location和配置中location的顺序以及类型均有关系。

__正则表达式类型与定义顺序有关, __与匹配长短无关
__常规字符串匹配类型与匹配长短有关，__与定义顺序无关

优先级排列:

1. 等号类型（=）的优先级最高。一旦匹配成功，则不再查找其他匹配项。

2. ^~类型表达式。一旦匹配成功，则不再查找其他匹配项。

3. 正则表达式类型（~ ~*）的优先级次之。按照配置里的正则表达式的顺序进行解析，从上到下开始匹配，一旦匹配一个，立即返回结果，结束解析过程。

4. 常规字符串匹配类型。按前缀匹配。

```
		location /test {
	          echo "test";
        }
        location = /test1 {
	          echo "test1";
        }
        location ~ /test(1|2|3|4|5) {
            echo "$1";
        }
        location ^~ /test2 {
            echo "this is test2";
        }
        location /test666 {
         echo "test666";
        }

	http://test.com/test1 //1
	http://test.com/test2 //this is test2
	http://test.com/test3 //3
	http://test.com/test4 //4
	http://test.com/test5 //5
	http://test.com/test6 //test
	http://test.com/test10 //1
	# 相比/test，匹配更长
	http://test.com/test666 //test666
    http://test.com/test666666 //test666

		location /test {
         echo "test";
        }
        location = /test1 {
         echo "test1";
        }
        location ~ /test5/test* {
         echo "the prefix is test5";
        }
        location ~ /test(1|2|3|4|5) {
          echo "$1";
        }
        location ^~ /test2 {
         echo "this is test2";
        }
        location /test666 {
         echo "test666";
        }

    http://test.com/test1 //1
	http://test.com/test2 //this is test2
	http://test.com/test3 //3
	http://test.com/test4 //4
	http://test.com/test5 //5
	http://test.com/test6 //test
	http://test.com/test10 //1
	# 相比/test，匹配更长
	http://test.com/test666 //test666
	http://test.com/test666666 //test666

	# /test5/test*被定义在/test(1|2|3|4|5)前面，先匹配/test5/test*
	http://test.com/test5/test17 //the prefix is test5
	http://test.com/test5/test18 //the prefix is test5


	 	location /test {
         echo "test"; 
        }
        location = /test1 {
         echo "test1";
        }
        location ~ /test(1|2|3|4|5) {
          echo "$1";
        }
        location ^~ /test2 { 
         echo "this is test2";
        }
        location ~ /test5/test* {
         echo "the prefix is test5";
        }
        location /test666 {
         echo "test666";
        }
    
    http://test.com/test1 //1
	http://test.com/test2 //this is test2
	http://test.com/test3 //3
	http://test.com/test4 //4
	http://test.com/test5 //5
	http://test.com/test6 //test
	http://test.com/test10 //1
	# 相比/test，匹配更长
	http://test.com/test666 //test666
	http://test.com/test666666 //test666

	# /test5/test*被定义在/test(1|2|3|4|5)后面，先匹配test(1|2|3|4|5)
	http://test.com/test5/test17 //5
	http://test.com/test5/test18 //5

```

## 常用正则
. ： 匹配除换行符以外的任意字符
? ： 重复0次或1次
+ ： 重复1次或更多次
* ： 重复0次或更多次
\d ：匹配数字
^ ： 匹配字符串的开始
$ ： 匹配字符串的介绍
{n} ： 重复n次
{n,} ： 重复n次或更多次
[c] ： 匹配单个字符c
[a-z] ： 匹配a-z小写字母的任意一个
小括号()之间匹配的内容，可以在后面通过$1来引用，$2表示的是前面第二个()里的内容。 \ 转义特殊字符。