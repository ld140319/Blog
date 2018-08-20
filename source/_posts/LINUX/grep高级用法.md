# grep高级用法

## 匹配单词

test文件内容如下:

```
abchello world
abc helloabc abc
abc abchelloabc abc
abc hello lzm
```

1. 包含以hello开头的单词的行

```
grep --color "\<hello" test.txt 

abc helloabc abc
abc hello lzm
```

2. 包含以hello结尾的单词的行

```
grep --color "hello\>" test.txt 

abchello world
abc hello lzm
```
3. 包含单词hello的行

```
grep --color "\<hello\>" test.txt 

abc hello lzm
```

## {}

test文件内容如下:

```
Hello world Hello
Hiiii world Hiiii
```

```
grep --color "\(H.\{4\}\) world \1" test.txt 

Hello world Hello
Hiiii world Hiiii

grep --color "H.\{4\} world H.\{4\}" test.txt 

Hello world Hello
Hiiii world Hiiii

grep --color "H.{4} world H.{4}" test.txt //匹配不到内容 不支持扩展的正则表达式 需要像上面一样转义

grep -E --color "H.{4} world H.{4}" test.txt 

Hello world Hello
Hiiii world Hiiii
```


## 正则表达式分组

test文件内容如下:

```
abefef
abefefabefef
```

```
grep --color "\(ab\\(ef\)\)" test.txt 

abefef
abefefabefef


grep --color "\(ab\\(ef\)\)ef\1" test.txt 

abefefabefef

grep --color "\(ab\\(ef\)\)\2" test.txt 

abefef
abefefabefef
```

分组与引用: http://www.zsythink.net/archives/1952








