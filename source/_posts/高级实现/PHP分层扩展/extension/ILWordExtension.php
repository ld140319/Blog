<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 22:59
 */
// 扩展接口
interface ILWordExtension{
    // 添加留言前
    public function beforeAppend($newLWord);
    // 添加留言后
    public function behindAppend($newLWord);
}