{* 一覧画面テンプレート。まず共通ヘッダーを読み込みます。 *}
{include file='header.tpl'}

{* ページタイトルと新規追加ボタンのエリア *}
<div class="header">
    <h1>タスク一覧</h1>
    <a href="add.php" class="add-btn">＋ 追加</a>
</div>

{* 未完了 / 完了済み の表示モード切替 *}
{* PHP側で $view に active / completed を設定しています。 *}
<div class="bottom-links" style="margin-bottom: 12px;">
    <a href="index.php?view=active" class="act-btn">未完了</a>
    <a href="index.php?view=completed" class="act-btn">完了済み</a>
</div>

{* completedモードかどうかで表示ブロックを切り替え *}
{if $view == 'completed'}
    {* 完了済みデータが1件以上あるとき *}
    {if $completedTasks|@count > 0}
        <ul class="task-list">
            {* foreachで配列を1件ずつ表示 *}
            {foreach $completedTasks as $task}
                <li class="task-card done">
                    <div class="task-main">
                        {* escapeでHTML特殊文字を無害化（XSS対策） *}
                        <p class="task-title">{$task.title|escape}</p>
                        {if $task.detail}
                            {* nl2brで改行を<br>に変換し、入力時の改行を表示に反映 *}
                            <p class="task-detail">{$task.detail|escape|nl2br}</p>
                        {/if}
                        <div class="task-meta">
                            {* 完了メモがある場合とない場合で表示を分岐 *}
                            {if $task.complete_memo}
                                <span>完了メモ: {$task.complete_memo|escape}</span>
                            {else}
                                <span>完了メモ: なし</span>
                            {/if}
                            {* 完了時刻があれば表示 *}
                            {if $task.completed_at}
                                <span>完了日時: {$task.completed_at|escape}</span>
                            {/if}
                        </div>
                    </div>
                    <div class="task-actions">
                        <a href="delete.php?id={$task.id}" class="act-btn">削除</a>
                    </div>
                </li>
            {/foreach}
        </ul>
    {else}
        {* データがない場合の案内表示 *}
        <p class="empty">完了済みタスクはありません</p>
    {/if}
{else}
    {* ここから未完了モードの表示（今日やること + 通常タスク） *}
    <h2 class="task-title" style="margin: 10px 0 8px;">今日やること</h2>
    {if $todayTasks|@count > 0}
        <ul class="task-list">
            {foreach $todayTasks as $task}
                <li class="task-card">
                    {* 優先度に応じて色付きドットを切り替え *}
                    {if $task.priority == 1}
                        <span class="priority-dot dot-high"></span>
                    {elseif $task.priority == 2}
                        <span class="priority-dot dot-mid"></span>
                    {else}
                        <span class="priority-dot dot-low"></span>
                    {/if}

                    <div class="task-main">
                        <p class="task-title">{$task.title|escape}</p>
                        {if $task.detail}
                            <p class="task-detail">{$task.detail|escape|nl2br}</p>
                        {/if}
                        <div class="task-meta">
                            {* 優先度ラベル表示 *}
                            {if $task.priority == 1}
                                <span class="priority-badge badge-high">優先度: 高</span>
                            {elseif $task.priority == 2}
                                <span class="priority-badge badge-mid">優先度: 中</span>
                            {elseif $task.priority == 3}
                                <span class="priority-badge badge-low">優先度: 低</span>
                            {else}
                                <span>優先度: -</span>
                            {/if}
                            {* 期限日が設定されているときだけ表示 *}
                            {if $task.due_date}
                                <span>期限: {$task.due_date|escape}</span>
                            {/if}
                            {* 見積時間がある場合、JSがこの要素を書き換えてカウントダウン表示します *}
                            {if $task.estimate_min}
                                <span class="js-countdown" data-created-at="{$task.created_at|escape}" data-estimate-min="{$task.estimate_min|escape}">計算中...</span>
                            {/if}
                        </div>
                    </div>

                    {* タスク操作ボタン群 *}
                    <div class="task-actions">
                        <a href="toggle_today.php?id={$task.id}" class="act-btn">今日やる解除</a>
                        <a href="complete.php?id={$task.id}" class="act-btn">完了</a>
                        <a href="edit.php?id={$task.id}" class="act-btn">編集</a>
                        <a href="delete.php?id={$task.id}" class="act-btn">削除</a>
                    </div>
                </li>
            {/foreach}
        </ul>
    {else}
        <p class="empty">今日やるタスクはありません</p>
    {/if}

    {* 通常タスクセクション *}
    <h2 class="task-title" style="margin: 18px 0 8px;">通常タスク</h2>
    {if $normalTasks|@count > 0}
        <ul class="task-list">
            {foreach $normalTasks as $task}
                <li class="task-card">
                    {if $task.priority == 1}
                        <span class="priority-dot dot-high"></span>
                    {elseif $task.priority == 2}
                        <span class="priority-dot dot-mid"></span>
                    {else}
                        <span class="priority-dot dot-low"></span>
                    {/if}

                    <div class="task-main">
                        <p class="task-title">{$task.title|escape}</p>
                        {if $task.detail}
                            <p class="task-detail">{$task.detail|escape|nl2br}</p>
                        {/if}
                        <div class="task-meta">
                            {* 優先度バッジ *}
                            {if $task.priority == 1}
                                <span class="priority-badge badge-high">優先度: 高</span>
                            {elseif $task.priority == 2}
                                <span class="priority-badge badge-mid">優先度: 中</span>
                            {elseif $task.priority == 3}
                                <span class="priority-badge badge-low">優先度: 低</span>
                            {else}
                                <span>優先度: -</span>
                            {/if}
                            {if $task.due_date}
                                <span>期限: {$task.due_date|escape}</span>
                            {/if}
                            {* JS更新対象（残り時間表示） *}
                            {if $task.estimate_min}
                                <span class="js-countdown" data-created-at="{$task.created_at|escape}" data-estimate-min="{$task.estimate_min|escape}">計算中...</span>
                            {/if}
                        </div>
                    </div>

                    <div class="task-actions">
                        <a href="toggle_today.php?id={$task.id}" class="act-btn">今日やる</a>
                        <a href="complete.php?id={$task.id}" class="act-btn">完了</a>
                        <a href="edit.php?id={$task.id}" class="act-btn">編集</a>
                        <a href="delete.php?id={$task.id}" class="act-btn">削除</a>
                    </div>
                </li>
            {/foreach}
        </ul>
    {else}
        <p class="empty">通常タスクはありません</p>
    {/if}
{/if}

{* ゴミ箱画面への導線 *}
<div class="bottom-links">
    <a href="trash.php" class="act-btn">ゴミ箱を見る</a>
</div>

{* 共通フッター（JS含む）を読み込みます *}
{include file='footer.tpl'}
