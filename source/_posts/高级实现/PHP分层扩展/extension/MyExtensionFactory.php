<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 23:08
 */
include "./LWordExtensionFamily.php";
// 自定义扩展工厂,实现根据不同需求返回不同的扩展集
class MyExtensionFactory {
    // 创建留言扩展
    public static function createLWordExtension() {
        $lwef = new LWordExtensionFamily();
        // 添加扩展
        $lwef->addExtension(new CheckPowerExtension());
        $lwef->addExtension(new CheckContentExtension());
        $lwef->addExtension(new AddScoreExtension());
        return $lwef;
        // 注意这里返回的是扩展家族类对象,
        // 扩展家族 LWordExtensionFamily 恰好也实现了接口 ILWordExtension,
        // 所以这是符合业务逻辑的要求.
        // 从此, 业务逻辑可以不关心具体的扩展对象, 只要知道扩展家族即可
    }
}