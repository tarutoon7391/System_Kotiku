<?php

// =====================================
// Smarty 初期化ファイル
// このファイルはテンプレートエンジン Smarty を使う準備をします。
// 読み込んだ側では return された $Smarty_obj を受け取って使います。
// =====================================

// Smartyクラスの読み込み（環境依存パスを避けて探索）
if (!class_exists('Smarty', false)) {
	$basePath = __DIR__; // リポジトリルート
	$envSmartyDir = getenv('SMARTY_DIR');

	$candidateDirs = [
		$envSmartyDir ?: '',
		$basePath . '/vendor/smarty/smarty/libs',
		'/usr/local/lib/smarty4/libs',
		'C:/xampp/php/pear/smarty/libs',
		'C:/xampp/php/PEAR/smarty/libs',
		'C:/xampp/php/pear/Smarty/libs',
		'C:/xampp/php/PEAR/Smarty/libs',
	];

	$loaded = false;
	foreach ($candidateDirs as $dir) {
		if ($dir === '') {
			continue;
		}
		$classFile = rtrim($dir, '/\\') . '/Smarty.class.php';
		if (is_file($classFile)) {
			require_once $classFile;
			$loaded = true;
			break;
		}
	}

	if (!$loaded) {
		@include_once 'Smarty.class.php';
	}

	if (!class_exists('Smarty', false)) {
		throw new RuntimeException('Smarty.class.php が見つかりません。SMARTY_DIR の設定または Smarty のインストールを確認してください。');
	}
}

// Smartyオブジェクトを作成
$Smarty_obj = new Smarty();

$appPath = __DIR__ . '/folder_for_agent_work';
$templateDir = $appPath . '/templates';
$compileDir = $appPath . '/templates_c';

if (!is_dir($compileDir)) {
	mkdir($compileDir, 0777, true);
}

// template_dir は .tpl ファイルの保存場所
$Smarty_obj->template_dir = $templateDir;
// compile_dir は Smarty が変換後ファイルを置く場所
$Smarty_obj->compile_dir = $compileDir;

// requireした側で受け取れるように return する
return $Smarty_obj;

?>