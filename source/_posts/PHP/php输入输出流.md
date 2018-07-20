## php://input

__php://input 是个可以访问请求的原始数据的只读流。__ POST 请求的情况下，最好使用 php://input 来代替 $HTTP_RAW_POST_DATA（原生的post数据），因为它不依赖于特定的 php.ini 指令,内存消耗更少

“php://input allows you to read raw POST data. It is a less memory intensive alternative to $HTTP_RAW_POST_DATA and does not need any special php.ini directives. php://input is not available with enctype=”multipart/form-data”. 
翻译过来，是这样： 

“php://input可以读取没有处理过的POST数据。相较于$HTTP_RAW_POST_DATA而言，它给内存带来的压力较小，并且不需要特殊的php.ini设置。

__php://input不能用于enctype=multipart/form-data”__


仅当Content-Type为application/x-www-form-urlencoded且提交方法是POST方法时，$_POST数据与php://input数据才是”一致”（打上引号，表示它们格式不一致，内容一致）的。其它情况，它们都不一致

POST提交时，Content- Type取值为application/x-www-form-urlencoded时，也指明了Content-Length的值，php会将http请求body相应数据会填入到数 组$_POST，填入到$_POST数组中的数据是进行urldecode()解析的结果


__当Content-Type为application/x- www-form-urlencoded时，php://input和$_POST数据是“一致”的，为其它Content-Type的时候，php: //input和$_POST数据数据是不一致的。因为只有在Content-Type为application/x-www-form- urlencoded或者为multipart/form-data的时候，PHP才会将http请求数据包中的body相应部分数据填入$_POST全 局变量中,其它情况PHP都忽略。__

$http_raw_post_data是PHP内置的一个全局变量。它用于，PHP在无法识别的 Content-Type的情况下，将POST过来的数据原样地填入变量$http_raw_post_data。它同样无法读取Content- Type为multipart/form-data的POST数据。需要设置php.ini中的 always_populate_raw_post_data值为On，PHP才会总把POST数据填入变量$http_raw_post_data。

 __json_decode(file_get_contents("php://input"), true);__

## php://output

__php://output 是一个只写的数据流， 允许你以 print 和 echo 一样的方式 写入到输出缓冲区。__

应用举例:

```
 /**
     * 下载excel文件
     * @param array $title 标题行名称
     * @param array $data 导出数据
     * @param string $fileName 文件名
     */
    private function downloadExcel($title = array(), $data = array(), $fileName = '')
    {
        $obj = new \PHPExcel();

        //横向单元格标识
        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

        //设置sheet名称
        $obj->getActiveSheet(0)->setTitle('review汇总');

        //第一列为标题列
        $_row = 1;
        if ($title) {
            $i = 0;
            //设置列标题
            foreach ($title AS $v) {
                $obj->setActiveSheetIndex(0)->setCellValue($cellName[$i] . $_row, $v);
                $i++;
            }
            $_row++;
        }

        //填写数据
        if ($data) {
            $current_row = 0; //行数
            foreach ($data AS $_v) {
                $current_col = 0; //列数
                foreach ($_v AS $_cell) {
                    $obj->getActiveSheet(0)->setCellValue($cellName[$current_col] . ($current_row + $_row), $_cell);
                    $current_col++;
                }
                $current_row++;
            }
        }

        //文件名处理
        if (!$fileName) {
            $fileName = "评论导出报表-" . date("Ymd");
            //$fileName = uniqid(time(),true);
        }

        $objWrite = \PHPExcel_IOFactory::createWriter($obj, 'Excel2007');

        //网页下载

        header('pragma:public');
        //禁止缓存
        header('Cache-Control: max-age=0');
        header("Content-Disposition:attachment;filename=$fileName.xlsx");
        $objWrite->save('php://output');
        exit;
    }
}
```