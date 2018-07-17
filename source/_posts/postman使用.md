## postman使用

post 传递list的两种方式:

|key    |value|
|:--------------:|:-------------:|
|list[] |1|
|list[] |2|

|key    |value|
|:--------------:|:-------------:|
|list[0]| 0|
|list[1] |1|

```
array(1) {
  ["list"]=>
  array(2) {
    [0]=>
    string(1) "1"
    [1]=>
    string(1) "2"
  }
}
```

post 传递关联数组:
|key        |value|
|:--------------:|:-------------:|
|user[name] |liuzeming|
|user[age]  |22|

```
array(1) {
  ["user"]=>
  array(2) {
    ["name"]=>
    string(9) "liuzeming"
    ["age"]=>
    string(2) "22"
  }
}
```

post 传递二维数组:

|key|value|
|:--------------:|:-------------:|
|userId[0][name]|liuzeming|
|userId[0][age]|22|
|userId[1][name]|liuyan|
|userId[1][age]|20|

```
array(1) {
  ["user"]=>
  array(2) {
    [0]=>
    array(2) {
      ["name"]=>
      string(9) "liuzeming"
      ["age"]=>
      string(2) "22"
    }
    [1]=>
    array(2) {
      ["name"]=>
      string(6) "liuyan"
      ["age"]=>
      string(2) "20"
    }
  }
```
post 传递json数据:

header
Content-Type:application/json

json_decode(file_get_contents("php://input"), true)

example:
```
	{"name":"lzm", "age":22}
	json_decode(file_get_contents("php://input"), true)

	array(2) {
	  ["name"]=>
	  string(3) "lzm"
	  ["age"]=>
	  int(22)
	}

```