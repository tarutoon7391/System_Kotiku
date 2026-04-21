<?php

// ==============================
// ゴミ箱から完全削除（trash_delete.php）
// 役割: 論理削除済みタスクをDBから物理削除する
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

// 完全削除対象idを取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // deleted_at IS NOT NULL 条件をつけることで、
    // 通常タスクを誤って消さないようにしています。
    $sql = 'DELETE FROM SYS1_2409009_tasks WHERE id=:id AND deleted_at IS NOT NULL';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// 処理後はゴミ箱へ戻す
header('Location: trash.php');
exit;
