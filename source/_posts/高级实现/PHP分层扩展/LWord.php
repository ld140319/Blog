<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:16
 */
//DTO:数据传输对象 留言
class LWord {
    private $title;
    private $content;
    public function __construct($title, $content)
    {
        $this->title = $title;
        $this->content = $content;
    }
}