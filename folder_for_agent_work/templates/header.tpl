{* =============================================== *}
{* 共通ヘッダー（header.tpl） *}
{* すべての画面の上部HTMLをここにまとめることで、 *}
{* デザイン変更を1か所で管理できるようにしています。 *}
{* =============================================== *}
<!DOCTYPE html>
<html lang="ja">
<head>
    {* 文字コード。日本語の文字化け防止に重要です。 *}
    <meta charset="UTF-8">
    {* スマホ表示で拡大されすぎないようにする定番設定です。 *}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {* ブラウザタブに表示されるタイトル *}
    <title>タスク管理システム</title>
    {* Webフォント読み込み（見た目を統一） *}
    <link href="https://fonts.googleapis.com/css2?family=Klee+One&display=swap" rel="stylesheet">
    {* 画面共通スタイル（ヘッダー） *}
    <link rel="stylesheet" href="css/header.css">
    {* 画面本体スタイル（リスト/フォームなど） *}
    <link rel="stylesheet" href="css/index.css">
    {* フッター側調整（今回は最小） *}
    <link rel="stylesheet" href="css/footer.css">
</head>
<body>
{* bodyタグ開始。各画面の本体コンテンツはここから下に入ります。 *}
