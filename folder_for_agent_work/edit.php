<?php

// ==============================
// タスク編集画面（edit.php）
// GET : 指定idの既存データを表示
// POST: 入力値を検証して更新
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

// POST時は hidden の id、GET時はクエリの id を使います。
// これにより同じファイルで「表示」と「更新」を両方扱えます。
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
} else {
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
}

// 不正idなら安全に一覧へ戻します。
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// 編集対象の現在データを取得します。
$selectSql = 'SELECT * FROM SYS1_2409009_tasks WHERE id = :id';
$selectStmt = $pdo->prepare($selectSql);
$selectStmt->execute([':id' => $id]);
$task = $selectStmt->fetch(PDO::FETCH_ASSOC);

// 存在しないidなら一覧へ戻します。
if (!$task) {
    header('Location: index.php');
    exit;
}

// エラーメッセージをためる配列
$errors = [];

// POST時のみ更新処理を行う
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 入力値を取得（空白除去）
    $title = isset($_POST['title']) ? trim((string)$_POST['title']) : '';
    $detail = isset($_POST['detail']) ? trim((string)$_POST['detail']) : '';
    $priority = isset($_POST['priority']) && $_POST['priority'] !== '' ? (int)$_POST['priority'] : 2;
    $dueDate = isset($_POST['due_date']) ? trim((string)$_POST['due_date']) : '';
    $estimateMin = isset($_POST['estimate_min']) ? trim((string)$_POST['estimate_min']) : '';
    $repeatType = isset($_POST['repeat_type']) ? trim((string)$_POST['repeat_type']) : '';

    // -------- バリデーション --------
    if ($title === '') {
        $errors[] = 'タイトルは必須です。';
    } elseif (mb_strlen($title) > 100) {
        $errors[] = 'タイトルは100文字以内で入力してください。';
    }

    if (!in_array($priority, [1, 2, 3], true)) {
        $errors[] = '優先度が不正です。';
    }

    if (!in_array($repeatType, ['', 'daily', 'weekly', 'monthly'], true)) {
        $errors[] = '繰り返し設定が不正です。';
    }

    if ($dueDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dueDate)) {
        $errors[] = '期限日の形式が不正です。';
    }

    if ($estimateMin !== '') {
        if (!ctype_digit($estimateMin) || (int)$estimateMin <= 0) {
            $errors[] = '見積時間は正の整数で入力してください。';
        }
    }

    // エラーがない場合のみUPDATE
    if (!$errors) {
        // 更新対象は id で1件に限定
        $updateSql = 'UPDATE SYS1_2409009_tasks SET title=:title, detail=:detail, priority=:priority, due_date=:due_date, repeat_type=:repeat_type, estimate_min=:estimate_min WHERE id=:id';
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([
            ':title' => $title,
            ':detail' => $detail !== '' ? $detail : null,
            ':priority' => $priority,
            ':due_date' => $dueDate !== '' ? $dueDate : null,
            ':repeat_type' => $repeatType !== '' ? $repeatType : null,
            ':estimate_min' => $estimateMin !== '' ? (int)$estimateMin : null,
            ':id' => $id,
        ]);

        // 更新後は一覧へ戻す（更新リロード対策）
        header('Location: index.php');
        exit;
    }

    // エラー時は入力値を$taskへ戻し、フォームに再表示します。
    // これにより「入力し直し」を減らせます。
    $task['title'] = $title;
    $task['detail'] = $detail;
    $task['priority'] = $priority;
    $task['due_date'] = $dueDate;
    $task['estimate_min'] = $estimateMin;
    $task['repeat_type'] = $repeatType;
}

// テンプレートへ値を渡して表示
$Smarty_obj->assign('errors', $errors);
$Smarty_obj->assign('task', $task);
$Smarty_obj->display('edit.tpl');
