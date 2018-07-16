<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:06
 */
include './ILWordExtension.php';
// 扩展家族,将一系列扩展组合到一起组成扩展集
class LWordExtensionFamily implements ILWordExtension {
    // 扩展数组
    private $_extensionArray = array();

    // 添加扩展
    public function addExtension(ILWordExtension $extension) {
        $this->_extensionArray []= $extension;
    }

    // 添加留言前
    public function beforeAppend($newLWord) {
        foreach ($this->_extensionArray as $extension) {
            $extension->beforeAppend($newLWord);
        }
    }

    // 添加留言后
    public function behindAppend($newLWord) {
        foreach ($this->_extensionArray as $extension) {
            $extension->behindAppend($newLWord);
        }
    }
}