# CURL常见用法

## 1. 获取页面内容

当我们不加任何选项使用 curl 时，默认会发送 GET 请求来获取链接内容到标准输出

```
	curl http://www.codebelief.com
```

## 2. 显示 HTTP 头

如果我们只想要显示 HTTP 头，而不显示文件内容，可以使用 -I 选项

```
	curl -I http://www.codebelief.com
```

## 同时显示 HTTP 头和文件内容，使用 -i 选项

```
	curl -i http://www.codebelief.com
```

## 3. 将链接保存到文件

```
(1)使用 > 符号将输出重定向到本地文件中

	curl http://www.codebelief.com > index.html

(2)-o（小写的 o）：结果会被保存到命令行中提供的文件名

	curl -o index.html http://www.codebelief.com

(3)-O（大写的 O）：URL 中的文件名会被用作保存输出的文件名

	curl -O http://www.codebelief.com/page/2/test.html


	使用 -O 选项时，必须确保链接末尾包含文件名，否则 curl 无法正确保存文件。如果遇到链接中无文件名的情况，应该使

用 -o 选项手动指定文件名，或使用重定向符号。

```

## 4. 同时下载多个文件, 使用 -o 或 -O 选项来同时指定多个链接

```
	curl -o page1.html http://www.codebelief.com/page/1/ -o page2.html http://www.codebelief.com/page/2/
```

## 5. 使用 -L 跟随链接重定向(获取重定向后的网页内容)

```
    默认情况下CURL不会发送HTTP Location headers(重定向).当一个被请求页面移动到另一个站点时，会发送一个HTTP Loaction header作为请求，然后将请求重定向到新的地址上。
	例如：访问google.com时，会自动将地址重定向到google.com.hk上。

	curl -L http://codebelief.com
```

## 6. 使用 -A 自定义 User-Agent

```
	curl -A "Mozilla/5.0 (Android; Mobile; rv:35.0) Gecko/35.0 Firefox/35.0" http://www.baidu.com
```

## 7. 使用 -H 自定义 header

```
	curl -H "Referer: www.example.com" -H "User-Agent: Custom-User-Agent" http://www.baidu.com
```

## 8. 使用 -c 保存 Cookie(保存cookie到文件)

```
	当我们使用 cURL 访问页面的时候，默认是不会保存 Cookie 的。有些情况下我们希望保存 Cookie 以便下次访问时使用。例如登陆了某个网站，我们希望再次访问该网站时保持登陆的状态，这时就可以现将登陆时的 Cookie 保存起来，下次访问时再读取。
    -c 后面跟上要保存的文件名。

    curl -c "cookie-example" http://www.example.com
```

## 9. 使用 -b 读取 Cookie(-b 后面既可以是 Cookie 字符串，也可以是保存了 Cookie 的文件名。)

```
	从文件中读取 Cookie
		curl -b "cookie-example" http://www.example.com

	发送cookies文本
		curl -b "key1=val1;key2=val2;" http://www.baidu.com
```

## 10. 使用 -d -X 发送 POST 请求(-d 用于指定发送的数据，-X 用于指定发送数据的方式)

```
	curl -d "userName=tom&passwd=123456" -X POST http://www.example.com/login

	在使用 -d 的情况下，如果省略 -X，则默认为 POST 方式：
		
		curl -d "userName=tom&passwd=123456" http://www.example.com/login

	curl -d "inviteeIds[0]=425048" -X POST http://useropsp.services.tff.com/invitation/invitePeople
```

## 11. 以表单的方式上传文件(-F/--form <name=content>)

```
	curl -F file=@/tmp/me.txt http://www.aiezu.com
	
	相当于设置form表单的method="POST"和enctype='multipart/form-data'两个属性以及file input的key为file。

	curl --form "fileupload=@aaa.txt" reviewsvcs.com/review/test

	curl -F "file=@localfile;filename=nameinpost" url.com    filename相当于对文件重命名
```
## 12. 同时传递数据和文件

```
	curl -X POST -F 'user[0][name]=liuzeming' -F 'user[0][age]=22' -F 'user[1][name]=liuyan' -F 'user[1][age]=20' -F file=@upload.sh http://reviewsvcs.com/review/test
```
## 13. 指定一个文件，将该文件中的内容当作数据传递给服务器端（文件内容以字符串形式传递）

```
	 curl --data @filename  url
```

## 14. 401认证

```
	curl -u 用户名:密码 url

	curl -u liuzeming:123456 http://test.com/hello_lzm
```

## 带cookie登录

```
	可以用之前提到的方法保存 Cookie，在每次访问网站时都带上该 Cookie 以保持登录状态。

		curl -c "cookie-login" -d "userName=tom&passwd=123456" http://www.example.com/login
	再次访问该网站时，使用以下命令：

		curl -b "cookie-login" http://www.example.com/login
```