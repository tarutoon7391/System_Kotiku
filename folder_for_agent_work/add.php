<?php

// ==============================
// タスク追加画面（add.php）
// GET: 入力フォームを表示
// POST: 入力値を検証してDBへ登録
// ==============================

// 共通ファイルの読み込み準備
$basePath = dirname(__DIR__);
// DB接続（$pdo）
require_once $basePath . '/inc_dbinfo.php';
// Smarty初期化（$Smarty_obj）
$Smarty_obj = require $basePath . '/inc_smarty.php';

// バリデーションエラーメッセージを入れる配列
$errors = [];

// 画面再表示時に入力値を保持するための配列
// 初期値を入れておくとテンプレート側が扱いやすくなります。
$post = [
    'title' => '',
    'detail' => '',
    'priority' => 2,
    'due_date' => '',
    'estimate_min' => '',
    'repeat_type' => '',
];

// フォーム送信時（POST）のみ登録処理を実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trim() で前後の空白を取り除きます。
    // (string) キャストは「想定外の型」を文字列として安全に扱うためです。
    $post['title'] = isset($_POST['title']) ? trim((string)$_POST['title']) : '';
    $post['detail'] = isset($_POST['detail']) ? trim((string)$_POST['detail']) : '';
    // 優先度は未入力なら 2（中）を採用
    $post['priority'] = isset($_POST['priority']) && $_POST['priority'] !== '' ? (int)$_POST['priority'] : 2;
    $post['due_date'] = isset($_POST['due_date']) ? trim((string)$_POST['due_date']) : '';
    $post['estimate_min'] = isset($_POST['estimate_min']) ? trim((string)$_POST['estimate_min']) : '';
    $post['repeat_type'] = isset($_POST['repeat_type']) ? trim((string)$_POST['repeat_type']) : '';

    // -------- 入力チェック（バリデーション） --------
    // タイトルは必須
    if ($post['title'] === '') {
        $errors[] = 'タイトルは必須です。';
    // 文字数制限（DB定義に合わせる）
    } elseif (mb_strlen($post['title']) > 100) {
        $errors[] = 'タイトルは100文字以内で入力してください。';
    }

    // in_array(..., true) の第3引数 true は「厳密比較」です。
    // 文字列"1"と数値1を区別できるため、意図しない通過を防げます。
    if (!in_array($post['priority'], [1, 2, 3], true)) {
        $errors[] = '優先度が不正です。';
    }

    // 繰り返し設定は許可した値だけ受け付ける
    if (!in_array($post['repeat_type'], ['', 'daily', 'weekly', 'monthly'], true)) {
        $errors[] = '繰り返し設定が不正です。';
    }

    // 期限日は YYYY-MM-DD 形式のみ許可
    if ($post['due_date'] !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $post['due_date'])) {
        $errors[] = '期限日の形式が不正です。';
    }

    // 見積時間は「未入力」または「正の整数」のみ
    if ($post['estimate_min'] !== '') {
        if (!ctype_digit($post['estimate_min']) || (int)$post['estimate_min'] <= 0) {
            $errors[] = '見積時間は正の整数で入力してください。';
        }
    }

    // エラーが1件もない場合のみINSERT実行
    if (!$errors) {
        // プレースホルダ付きSQLで安全に登録
        $sql = 'INSERT INTO SYS1_2409009_tasks (title, detail, priority, due_date, repeat_type, estimate_min) VALUES (:title, :detail, :priority, :due_date, :repeat_type, :estimate_min)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':title' => $post['title'],
            // 空文字はDBにNULLで保存（未入力として扱う）
            ':detail' => $post['detail'] !== '' ? $post['detail'] : null,
            ':priority' => $post['priority'],
            ':due_date' => $post['due_date'] !== '' ? $post['due_date'] : null,
            ':repeat_type' => $post['repeat_type'] !== '' ? $post['repeat_type'] : null,
            ':estimate_min' => $post['estimate_min'] !== '' ? (int)$post['estimate_min'] : null,
        ]);

        // 二重送信防止のため、処理後はリダイレクトします（PRGパターン）
        header('Location: index.php');
        exit;
    }
}

// テンプレートへ値を渡して表示
$Smarty_obj->assign('errors', $errors);
$Smarty_obj->assign('post', $post);
$Smarty_obj->display('add.tpl');
