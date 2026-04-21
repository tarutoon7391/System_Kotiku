<?php

// ==============================
// 論理削除処理（delete.php）
// 役割: タスクを物理削除せず deleted_at に時刻を入れて非表示化
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

// この処理はリンク遷移（GET）想定
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Location: index.php');
    exit;
}

// クエリパラメータから対象idを取得
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    // deleted_at に現在時刻を保存 = ゴミ箱へ移動
    $sql = 'UPDATE SYS1_2409009_tasks SET deleted_at = NOW() WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}

// 処理後は一覧へ戻す
header('Location: index.php');
exit;
