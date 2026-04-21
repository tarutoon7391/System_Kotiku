<?php

// =====================================
// DB接続設定ファイル
// このファイルを読み込むと $pdo が使えるようになります。
// $pdo は PHP の PDO クラスのインスタンスで、
// MySQLなどのDBへ安全に接続・操作するためのオブジェクトです。
// =====================================

// 接続先ホスト
$host = 'mysql:host=localhost';
// 使用するデータベース名
$dbname = 'dbname=se2_2025';
// 文字コード（日本語の文字化け防止に重要）
$charset = 'charset=utf8';
// DBユーザー名
$username = 'se2_2025';
// DBパスワード
$password = 'IshidaT';

// DSN = 接続情報をまとめた文字列
// 「どのDBに・どんな設定でつなぐか」をPDOへ渡します。
$dsn = $host . ";" . $dbname . ";" . $charset;

// try-catch は「失敗する可能性がある処理」を安全に包む構文です。
try
{
    // new PDO(...) で実際に接続します。
    // 成功すると $pdo が使えるようになります。
    $pdo = new PDO($dsn, $username, $password);
}
catch(PDOException $e)
{
    // 接続失敗時の処理
    // 本番では詳細エラーを出しすぎないほうが安全ですが、
    // 今回は学習用としてメッセージだけ表示しています。
    echo "データベースの接続失敗しました" . PHP_EOL;
    exit;
}

?>