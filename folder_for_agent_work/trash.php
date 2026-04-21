<?php

// ==============================
// ゴミ箱一覧画面（trash.php）
// 役割: 論理削除済み（deleted_atあり）のタスクを表示
// ==============================

$basePath = dirname(__DIR__);
require_once $basePath . '/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/inc_smarty.php';

// deleted_at IS NOT NULL = ゴミ箱に入っているデータ
// 新しい削除順に並べるため deleted_at DESC で取得
$sql = 'SELECT * FROM SYS1_2409009_tasks WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC';
$stmt = $pdo->prepare($sql);
$stmt->execute();
$trashTasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// テンプレートに渡して表示
$Smarty_obj->assign('trashTasks', $trashTasks);
$Smarty_obj->display('trash.tpl');
