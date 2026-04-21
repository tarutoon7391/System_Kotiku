<?php

// ==============================
// タスク完了処理（complete.php）
// GET : 完了確認画面を表示
// POST: statusを完了に変更し、完了メモ履歴へ記録
// ==============================

$basePath = dirname(__DIR__, 2);
require_once $basePath . '/php_include/inc_dbinfo.php';
$Smarty_obj = require $basePath . '/php_include/inc_smarty.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // hiddenで送られてくる対象タスクID
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    // 任意の完了メモ
    $memo = isset($_POST['complete_memo']) ? trim((string)$_POST['complete_memo']) : '';

    // 長すぎるメモはDB定義に合わせて200文字に丸める
    if (mb_strlen($memo) > 200) {
        $memo = mb_substr($memo, 0, 200);
    }

    // 不正idは処理せず一覧へ戻す
    if ($id <= 0) {
        header('Location: index.php');
        exit;
    }

    // 完了対象のタスクを確認（deleted_at IS NULLでゴミ箱除外）
    $selectSql = 'SELECT * FROM SYS1_2409009_tasks WHERE id = :id AND deleted_at IS NULL';
    $selectStmt = $pdo->prepare($selectSql);
    $selectStmt->execute([':id' => $id]);
    $task = $selectStmt->fetch(PDO::FETCH_ASSOC);

    // タスクが見つからない場合は安全に戻る
    if (!$task) {
        header('Location: index.php');
        exit;
    }

    try {
        // 複数テーブル更新のためトランザクション開始
        // どちらか失敗したら全体を取り消し、整合性を守ります。
        $pdo->beginTransaction();

        // メインタスクを完了状態に更新
        $updateSql = 'UPDATE SYS1_2409009_tasks SET status=1 WHERE id=:id';
        $updateStmt = $pdo->prepare($updateSql);
        $updateStmt->execute([':id' => $id]);

        // 完了履歴テーブルへメモを記録
        // メモ未入力ならNULLを保存
        $insertCompletionSql = 'INSERT INTO SYS1_2409009_task_completions (task_id, complete_memo) VALUES (:task_id, :complete_memo)';
        $insertCompletionStmt = $pdo->prepare($insertCompletionSql);
        $insertCompletionStmt->execute([
            ':task_id' => $id,
            ':complete_memo' => $memo !== '' ? $memo : null,
        ]);

        // 完了時は完了記録のみを行い、次回タスクの自動生成は行わない

        // ここまで成功したら確定
        $pdo->commit();
    } catch (Throwable $e) {
        // 例外発生時はDB状態を元に戻す
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        // ユーザーには一覧へ戻して再操作を促す
        header('Location: index.php');
        exit;
    }

    // 正常終了後は一覧へ戻る
    header('Location: index.php');
    exit;
}

// ここから先はGETアクセス時（確認画面表示）
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit;
}

$sql = 'SELECT * FROM SYS1_2409009_tasks WHERE id = :id AND deleted_at IS NULL';
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    header('Location: index.php');
    exit;
}

// 確認画面へ対象タスクを渡して表示
$Smarty_obj->assign('task', $task);
$Smarty_obj->display('complete_confirm.tpl');
