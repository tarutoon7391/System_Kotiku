<?php

// ==============================
// タスク一覧画面（index.php）
// このファイルの役割:
// 1) URLパラメータを見て表示モードを決める
// 2) DBから必要なタスクを取得する
// 3) Smartyテンプレートにデータを渡して表示する
// ==============================

// dirname(__DIR__, 2) は「このファイルから2階層上のフォルダ」を表します。
// 共通ファイルを絶対パスに近い形で安全に読み込むために使います。
$basePath = dirname(__DIR__);

// DB接続（$pdo）を使えるようにします。
require_once $basePath . '/inc_dbinfo.php';
// Smarty（テンプレート表示エンジン）を初期化します。
$Smarty_obj = require $basePath . '/inc_smarty.php';

// 一覧の表示モードを決めます。
// view=completed のときは完了済み、それ以外は未完了（active）を表示します。
// 三項演算子（条件 ? 真の値 : 偽の値）で短く書いています。
$view = isset($_GET['view']) && $_GET['view'] === 'completed' ? 'completed' : 'active';

// Smartyに渡す配列を最初に空で用意しておくと、
// テンプレート側で「未定義変数」エラーを避けられます。
$todayTasks = [];
$normalTasks = [];
$completedTasks = [];

if ($view === 'completed') {
	// 完了済み表示モードのSQLです。
	// 完了タスク本体(t)に、最新の完了メモ(c)をLEFT JOINで結合しています。
	// LEFT JOINなので、メモがない完了タスクも表示できます。
	$completedSql = 'SELECT t.*, c.complete_memo, c.completed_at
					 FROM SYS1_2409009_tasks t
					 LEFT JOIN (
						-- タスクごとに「一番新しい完了記録」を1件に絞り込む部分
						SELECT tc1.task_id, tc1.complete_memo, tc1.completed_at
						FROM SYS1_2409009_task_completions tc1
						INNER JOIN (
							-- 各task_idの最新completed_atを取得
							SELECT task_id, MAX(completed_at) AS latest_completed_at
							FROM SYS1_2409009_task_completions
							GROUP BY task_id
						) tc2
						-- task_idと時刻が一致した「最新1件」を取り出す
						ON tc1.task_id = tc2.task_id AND tc1.completed_at = tc2.latest_completed_at
					 ) c ON c.task_id = t.id
					 -- status=1（完了）かつ deleted_at IS NULL（ゴミ箱に入っていない）
					 WHERE t.status=1 AND t.deleted_at IS NULL
					 -- 新しく更新された順に並べます
					 ORDER BY t.updated_at DESC, t.id DESC';
	// prepare + execute はSQLインジェクション対策の基本です。
	// このクエリはプレースホルダがなくても、統一してこの書き方にしています。
	$completedStmt = $pdo->prepare($completedSql);
	$completedStmt->execute();
	// fetchAll(PDO::FETCH_ASSOC) は「連想配列の配列」で結果を受け取ります。
	$completedTasks = $completedStmt->fetchAll(PDO::FETCH_ASSOC);
} else {
	// 未完了表示モード:
	// today_flag=1 を「今日やること」セクションとして取得
	$todaySql = 'SELECT * FROM SYS1_2409009_tasks
				 WHERE status=0 AND today_flag=1 AND deleted_at IS NULL
				 ORDER BY priority ASC, due_date ASC, id DESC';
	$todayStmt = $pdo->prepare($todaySql);
	$todayStmt->execute();
	$todayTasks = $todayStmt->fetchAll(PDO::FETCH_ASSOC);

	// today_flag=0 を「通常タスク」セクションとして取得
	$normalSql = 'SELECT * FROM SYS1_2409009_tasks
				  WHERE status=0 AND today_flag=0 AND deleted_at IS NULL
				  ORDER BY priority ASC, due_date ASC, id DESC';
	$normalStmt = $pdo->prepare($normalSql);
	$normalStmt->execute();
	$normalTasks = $normalStmt->fetchAll(PDO::FETCH_ASSOC);
}

// assign()でテンプレートに変数を渡します。
// ここで渡した名前を、tpl側で {$view} のように参照できます。
$Smarty_obj->assign('view', $view);
$Smarty_obj->assign('todayTasks', $todayTasks);
$Smarty_obj->assign('normalTasks', $normalTasks);
$Smarty_obj->assign('completedTasks', $completedTasks);

// 最後にテンプレートを表示します。
$Smarty_obj->display('index.tpl');
