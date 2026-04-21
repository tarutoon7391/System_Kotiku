<?php

// ==============================
// ゴミ箱から復元（restore.php）
// 役割: deleted_at を NULL に戻して通常一覧へ復帰させる
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

// 復元対象のidをGETから取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // deleted_at=NULL にすることで「論理削除解除」になる
    $sql = 'UPDATE SYS1_2409009_tasks SET deleted_at=NULL WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// 復元後はゴミ箱画面へ戻す
header('Location: trash.php');
exit;
