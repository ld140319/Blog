# MySQL索引

## 索引设计原则

__1. where clause / join clause / order by clause / group by clause中使用到的字段需要建索引__

__2. 索引列字段值得区分度应该足够高(重复数据较多的列)__ ，否则索引的效果就不明显，白白浪费存储空间

__3. 在 varchar 字段上建立索引时，必须指定索引长度__，没必要对全字段建立索引，根据 实际文本区分度决定索引长度即可。

        使用 count(distinct left( 列名, 索引长度 )) / count( * ) 的区分度 来确定。

__4. 索引字段要尽量的小。__

             一方面能够减少查询次数(h降低)， 另一方面能够减少磁盘IO次数(数据项变小，单个磁盘快的数据项数量就增多了，一页的数据就变多了)
        
            Mysql b+树真实的数据存在于叶子节点这个事实可知，IO次数取决于b+数的高度h。  假设当前数据表的数据量为N，每个磁盘块的数据项的数量是m，则树高h=㏒(m+1)N，当数据量N一定的情况下，m越大，h越小； 而m = 磁盘块的大小/数据项的大小，磁盘块的大小也就是一个数据页的大小，是固定的；如果数据项占的空间越小，数据项的数量m越多，树的高度h越低。这就是为什么每个数据项，即索引字段要尽量的小，比如int占4字节，要比bigint8字节少一半。
            
__5. mysql  join 条件列使用索引前提条件：创建表时， 字符集、字段类型大小一致__

__6. 在创建多列索引时，where子句中使用最频繁的一列放在最左边，满足最左前缀匹配原则__

__7. 业务上具有唯一特性的字段，即使是多个字段的组合，也必须建成唯一索引__
            
8. 尽量建组合索引，尽量修改索引，而不是创建索引

        eg: 表中已经有a的索引，现在要加(a,b)的索引，那么只需要修改原来的索引即可

__9. 索引列必须设置为 not null, 原来可能存在的null可以通过设置default value来标识__

            为什么索引列不能存Null值？
    
        将索引列值进行建树，其中必然涉及到诸多的比较操作。Null值的特殊性就在于参与的运算大多取值为null。这样的话，null值实际上是不能参与进建索引的过程。也就是说，null值不会像其他取值一样出现在索引树的叶子节点上。

10. 一个表最好不要超过6个索引， 一个索引最多可包含16列

## 索引失效的情况

__1. 前导模糊查询不能利用索引(like '%XX'或者like '%XX%')__

        like %liu%  like other_col 不能够使用索引
        
                假如有这样一列code的值为'AAA','AAB','BAA','BAB' ,如果where code like '%AB'条件，由于前面是
        
        模糊的，所以不能利用索引的顺序，必须一个个去找，看是否满足条件。这样会导致全索引扫描或者全表扫
        
        描。如果是这样的条件where code like 'A % '，就可以查找CODE中A开头的CODE的位置，当碰到B开头的
        
        数据时，就可以停止查找了，因为后面的数据一定不满足要求。这样就可以利用索引了。

__2. 不满足最左前缀原则 或者 满足了最左前缀原则但是使用了or__

        index: (col1, col2, col3) =》 (col1), (col1, col2), (col1, col3)，(col1, col2, col3) 能够使用索引
        
        (col2,col3) (col2) (col3) 不能够使用索引
        
        col1 = xxx or col2 = xxx  不能够使用索引
        
__3. 数据类型隐式转换__

        如果列类型是字符串，那一定要在条件中将数据使用引号引用起来,否则不使用索引
        
__4. 在 where 子句中对字段进行表达式操作或者函数操作__

        如：select id from t where num/2=100应改为:select id from t where num=100*2
        如：select id from t where substring(name,1,3)=’abc’ ，name以abc开头的id应改为:
            select id from t where name like ‘abc%’
        
__5. 索引列与非常量值做比较__

        eg: select * from score where english_score>math_score;

5. 可能失效的几种情况

        1. 在 where 子句中对字段进行 null 值判断  
            
            eg： num  is null
                可以在num上设置默认值0，确保表中num列没有null值，然后这样查询：select id from t where num=0
        2. 在 where 子句中使用!=或<>操作符
        
        3. 在 where 子句中使用in操作符，并且为嵌套查询(存在子查询) ， 可以使用exists代替
        
            eg :
            
            select * from a where num in(select num from b)
            用下面的语句替换： 
            select * from a where exists(select 1 from b where num=a.num
        
        4. 在 where 子句中使用or 来连接条件， 可以使用union all来代替
        
        5. 在 where 子句中使用参数，也会导致全表扫描。
        
            因为SQL只有在运行时才会解析局部变量，但优化程序不能将访问计划的选择推迟到运行时；它必须在编译时进行选择。然 而，如果在编译时建立访问计划，变量的值还是未知的，因而无法作为索引选择的输入项。如下面语句将进行全表扫描：select id from t where num=@num可以改为强制查询使用索引：select id from t with(index(索引名)) where num=@num
        
        5. 全表扫描比索引更快
        
## 其它优化

(1)范围查询条件为一个Constant value是可以使用索引

     btree、hash： =, <=>, IN(), IS NULL, or IS NOT NULL operators
     btree：>，<，> =，<=，BETWEEN，！=，<>，like %xxx operators
    
     “Constant value” in the preceding descriptions means one of the following:
    
    A constant from the query string  常量字符串
    
    A column of a const or system table from the same join 来自同一连接的const或system表的列。(驱动表只有一列或者使用了唯一索引)
    
    The result of an uncorrelated subquery  不相关子查询的结果


(2) Mysql无法使用范围查询列之后的其他索引列（以及5.6版本的ICP）

    mysql5.6版本之前没有加入index condition pushdown，所以索引逻辑还是这样的：
    
    即便对于复合索引，从第一列开始先确定第一列索引范围，如果范围带=号，则对于=号情况，确定第二列索引范围加入索引结果集里，每列的处理方式都是一样的。
    
    确定完索引范围后，则回表查询数据，再用剩下的where条件进行过滤判断。
    
    mysql5.6后加入了ICP，对于确定完了索引范围后，会用剩下的where条件对索引范围再进行一次过滤，然后再回表，再用剩下的where条件进行过滤判断。（减少回表记录数量）。
    
(3) 提高GROUP BY 语句的效率, 可以通过将不需要的记录在GROUP BY 之前过滤掉  having是在取出数据以后再来判断，采用where先过滤数据

(4) 在输出每一行之前，将跳过与HAVING子句不匹配的行

(5) 避免select *

    1.解析成本  2.网络数据传输成本

(6) order by 语句优化
    
    任何在Order by语句的非索引项或者有计算表达式都将降低查询速度。
    
    方法：1.重写order by语句以使用索引；
    
      2.为所使用的列建立另外一个索引
    
      3.绝对避免在order by子句中使用表达式。
      
(7) 能用DISTINCT的就不用GROUP BY
    
    SELECT OrderID FROM Details WHERE UnitPrice > 10 GROUP BY OrderID
    
    可改为：
    
    SELECT DISTINCT OrderID FROM Details WHERE UnitPrice > 10

(8) 能用UNION ALL就不要用UNION

    UNION ALL不执行SELECT DISTINCT函数，这样就会减少很多不必要的资源。

(9) 在Join表的时候使用相当类型的例，并将其索引

    如果应用程序有很多JOIN 查询，这些被用来Join的字段，应该是相同的类型的(字符集也要一致)。
    
(10) 尽量使用数字型字段
    
    若只含数值信息的字段尽量不要设计为字符型，这会降低查询和连接的性能，并会增加存储开销。这是因为引擎在处理查询和连接时会逐个比较字符串中每一个字符，而对于数字型而言只需要比较一次就够了。
    
(11) 尽可能的使用 varchar/nvarchar 代替 char/nchar

    因为首先变长字段存储空间小，可以节省存储空间，其次对于查询来说，在一个相对较小的字段内搜索效率显然要高些。
    
(12) 尽量避免大事务操作，提高系统并发能力。

(13) 尽量避免向客户端返回大数据量，若数据量过大，应该考虑相应需求是否合理。


## 索引的不足

索引的额外开销：

    (1) 空间：索引需要占用空间；
    
    (2) 时间：查询索引需要时间；
    
    (3) 维护：索引须要维护（数据变更时）；

不建议使用索引的情况：

    (1) 数据量很小的表
    
    (2) 频繁变更的表
    
    (3) 空间紧张


