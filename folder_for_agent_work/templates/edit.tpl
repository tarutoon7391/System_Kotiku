{* タスク編集画面テンプレート *}
{include file='header.tpl'}

<div class="header">
    <h1>タスク編集</h1>
    <a href="index.php" class="act-btn">キャンセル</a>
</div>

{* エラー表示ブロック（入力不備時） *}
{if $errors}
    <div class="task-card">
        <div class="task-main">
            <p class="task-title">入力エラーがあります</p>
            <ul class="task-meta">
                {foreach $errors as $error}
                    <li>{$error|escape}</li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}

{* hidden id で「どのタスクを更新するか」をサーバーへ渡す *}
<form method="post" action="edit.php" class="task-card">
    <input type="hidden" name="id" value="{$task.id}">

    <div class="task-main">
        <p class="task-title">タスク情報</p>
        <div class="task-meta" style="display:block;">
            <p>
                <label for="title">タイトル（必須）</label><br>
                <input id="title" type="text" name="title" maxlength="100" value="{$task.title|default:''|escape}" style="width:100%;">
            </p>

            <p>
                <label for="detail">詳細（任意）</label><br>
                <textarea id="detail" name="detail" rows="5" style="width:100%;">{$task.detail|default:''|escape}</textarea>
            </p>

            <p>
                <label for="priority">優先度</label><br>
                <select id="priority" name="priority">
                    {* 元データの値に応じて selected を切り替える *}
                    <option value="1" {if $task.priority == 1}selected{/if}>高</option>
                    <option value="2" {if $task.priority == 2}selected{/if}>中</option>
                    <option value="3" {if $task.priority == 3}selected{/if}>低</option>
                </select>
            </p>

            <p>
                <label for="due_date">期限日（任意）</label><br>
                <input id="due_date" type="date" name="due_date" value="{$task.due_date|default:''|escape}">
            </p>

            <p>
                <label for="estimate_min">見積時間（分・任意）</label><br>
                <input id="estimate_min" type="number" name="estimate_min" min="1" value="{$task.estimate_min|default:''|escape}">
            </p>

            <p>
                <label for="repeat_type">繰り返し設定</label><br>
                <select id="repeat_type" name="repeat_type">
                    <option value="" {if $task.repeat_type|default:'' == ''}selected{/if}>繰り返しなし</option>
                    <option value="daily" {if $task.repeat_type|default:'' == 'daily'}selected{/if}>毎日</option>
                    <option value="weekly" {if $task.repeat_type|default:'' == 'weekly'}selected{/if}>毎週</option>
                    <option value="monthly" {if $task.repeat_type|default:'' == 'monthly'}selected{/if}>毎月</option>
                </select>
            </p>
        </div>
    </div>

    {* 更新実行ボタン *}
    <div class="task-actions">
        <button type="submit" class="add-btn">更新</button>
        <a href="index.php" class="act-btn">キャンセル</a>
    </div>
</form>

{include file='footer.tpl'}
