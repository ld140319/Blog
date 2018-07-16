<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:04
 */
include './ILWordExtension.php';
//用户积分
class AddScoreExtension implements ILWordExtension {
    // 添加留言前
    public function beforeAppend($newLWord) {
    }

    // 添加留言后
    public function behindAppend($newLWord) {
        // 在这里给用户积分
    }
}