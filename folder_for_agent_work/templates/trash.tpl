{* ゴミ箱画面テンプレート *}
{include file='header.tpl'}

{* 画面ヘッダーと一覧へ戻るリンク *}
<div class="header">
    <h1>ゴミ箱</h1>
    <a href="index.php" class="act-btn">一覧へ戻る</a>
</div>

{* ゴミ箱データがあるときだけリスト表示 *}
{if $trashTasks|@count > 0}
    <ul class="task-list">
        {* 論理削除済みデータを1件ずつ描画 *}
        {foreach $trashTasks as $task}
            <li class="task-card done">
                <div class="task-main">
                    <p class="task-title">{$task.title|escape}</p>
                    <div class="task-meta">
                        {* いつ削除したかを表示 *}
                        <span>削除日時: {$task.deleted_at|escape}</span>
                    </div>
                </div>
                <div class="task-actions">
                    {* 復元: deleted_atをNULLへ戻す *}
                    <a href="restore.php?id={$task.id}" class="act-btn">復元</a>
                    {* 完全削除: DBから物理削除 *}
                    <a href="trash_delete.php?id={$task.id}" class="act-btn">完全削除</a>
                </div>
            </li>
        {/foreach}
    </ul>
{else}
    {* データが0件の場合の案内 *}
    <p class="empty">ゴミ箱は空です</p>
{/if}

{include file='footer.tpl'}
