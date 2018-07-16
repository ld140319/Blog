<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:01
 */
include './ILWordExtension.php';
//检查权限
class CheckPowerExtension implements ILWordExtension {
    // 添加留言前
    public function beforeAppend($newLWord) {
        // 在这里判断用户权限
    }

    // 添加留言后
    public function behindAppend($newLWord) {
    }
}