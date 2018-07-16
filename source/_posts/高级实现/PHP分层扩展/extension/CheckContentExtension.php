<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:02
 */
include './ILWordExtension.php';
// 检查留言文本
class CheckContentExtension implements ILWordExtension {
    // 添加留言前
    public function beforeAppend($newLWord) {
        if (stristr($newLWord, "SB")) {
            throw new Exception();
        }
    }

    // 添加留言后
    public function behindAppend($newLWord) {
    }
}