# 那些情况下order by不会使用索引?

1. 在排序时使用多个不同的索引

	SELECT * FROM t1 ORDER BY key1, key2;

2. 使用一个组合索引不连续的部分进行排序  （key1_part1,key2_part1 key1_part3）

	SELECT * FROM t1 WHERE key2=constant ORDER BY key1_part1, key1_part3;

3. 混用ASC和DESC 

	SELECT * FROM t1 ORDER BY key_part1 DESC, key_part2 ASC;

4. 用于读取行的索引不同于ORDER BY中使用的索引

	SELECT * FROM t1 WHERE key2=constant ORDER BY key1;

5. 查询使用ORDER BY的表达式，其中包含索引列名以外的其他术语

	SELECT * FROM t1 ORDER BY ABS(key);
	SELECT * FROM t1 ORDER BY -key;

6. 查询连接多个表，并且ORDER BY中的列不是全部来自用于检索行的第一个非常数表。 （这是EXPLAIN输出中没有常量联接类型的第一个表。）如:排序非驱动表不会走索引（当使用left join，使用右边的表字段排序）

7. 查询具有不同的ORDER BY和GROUP BY表达式(不同指的是不同列，相同列时MySQL会优化使用索引)

8. order by中的索引为前缀索引时，在这种情况下，索引不能用于完全解决排序顺序

	例如，如果仅对CHAR（20）列的前10个字节进行索引，则索引无法区分超过第10个字节的值，并需要使用filesort

9. 索引不能按照顺序存储行 如:hash索引

	The index does not store rows in order. For example, this is true for a HASH index in a MEMORY table.

10. 排序列为别名列

	SELECT ABS(a) AS a FROM t1 ORDER BY a;

	在以下语句中，ORDER BY引用的名称不是选择列表中列的名称。但是在t1中有一列名为a，所以ORDER BY引用t1.a并且可以使用t1.a上的索引。 （当然，得到的排序顺序可能与ABS（a）的顺序完全不同。）

	SELECT ABS(a) AS b FROM t1 ORDER BY a; 可以使用索引进行排序


11. GROUP BY col1, col2, ... 包含了一个默认排序 ORDER BY col1, col2，可以通过ORDER BY NULL禁止