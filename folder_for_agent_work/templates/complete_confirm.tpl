{* タスク完了確認画面テンプレート *}
{include file='header.tpl'}

{* タイトルと戻る導線 *}
<div class="header">
    <h1>完了確認</h1>
    <a href="index.php" class="act-btn">キャンセル</a>
</div>

{* 完了対象タスクの確認表示 *}
<div class="task-card">
    <div class="task-main">
        <p class="task-title">{$task.title|escape}</p>
        <p class="task-meta">このタスクを完了にしますか？</p>
    </div>
</div>

{* POST送信で本当に完了処理を実行します *}
<form method="post" action="complete.php" class="task-card">
    {* hidden id がないと、どのタスクか判定できません *}
    <input type="hidden" name="id" value="{$task.id}">

    <div class="task-main">
        <div class="task-meta" style="display:block;">
            <p>
                {* 任意メモ。完了履歴テーブルに保存されます。 *}
                <label for="complete_memo">完了時のひとことメモ（任意・200文字まで）</label><br>
                <textarea id="complete_memo" name="complete_memo" rows="4" maxlength="200" style="width:100%;"></textarea>
            </p>
        </div>
    </div>

    <div class="task-actions">
        <button type="submit" class="add-btn">完了にする</button>
        <a href="index.php" class="act-btn">キャンセル</a>
    </div>
</form>

{include file='footer.tpl'}
