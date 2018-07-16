<?php
/**
 * Created by PhpStorm.
 * User: lzm
 * Date: 2018/1/25
 * Time: 22:54
 */
include './ILWordServiceInterface.php';
include '../LWordDBTask.php';
include '../extension/MyExtensionFactory.php';
//中间服务层
class ILWordService implements ILWordServiceInterface
{
    // 添加留言
    public function append($newLWord) {

        $ext = MyExtensionFactory::createLWordExtension();

        $ext->beforeAppend($newLWord);

        // 调用数据访问层
        $dbTask = new LWordDBTask();
        $dbTask->append($newLWord);

        $ext->behindAppend($newLWord);
    }
}