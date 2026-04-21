<?php

// ==============================
// 今日やるフラグ切り替え（toggle_today.php）
// 役割: today_flag を 0⇔1 でトグルする
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

// GETパラメータのidを整数で受け取る
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // CASE WHEN で現在値に応じて反転させます。
    // today_flag=1 なら 0、それ以外なら 1 に更新します。
    // deleted_at IS NULL を付けることでゴミ箱内データは対象外にします。
    $sql = 'UPDATE SYS1_2409009_tasks SET today_flag = CASE WHEN today_flag=1 THEN 0 ELSE 1 END WHERE id=:id AND deleted_at IS NULL';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// 操作後は一覧へ戻す
header('Location: index.php');
exit;
