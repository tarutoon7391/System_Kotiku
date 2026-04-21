{* タスク追加画面テンプレート *}
{include file='header.tpl'}

{* 画面ヘッダー *}
<div class="header">
    <h1>タスク追加</h1>
    <a href="index.php" class="act-btn">キャンセル</a>
</div>

{* バリデーションエラーがあるときだけ表示 *}
{if $errors}
    <div class="task-card">
        <div class="task-main">
            <p class="task-title">入力エラーがあります</p>
            <ul class="task-meta">
                {* エラー配列をループして1件ずつ表示 *}
                {foreach $errors as $error}
                    <li>{$error|escape}</li>
                {/foreach}
            </ul>
        </div>
    </div>
{/if}

{* method="post" で入力内容をサーバーへ送信 *}
<form method="post" action="add.php" class="task-card">
    <div class="task-main">
        <p class="task-title">新規タスク情報</p>
        {* style="display:block" は縦並び表示をしやすくするため *}
        <div class="task-meta" style="display:block;">
            <p>
                <label for="title">タイトル（必須）</label><br>
                {* default:'' は未定義時の表示崩れ防止 *}
                <input id="title" type="text" name="title" maxlength="100" value="{$post.title|default:''|escape}" style="width:100%;">
            </p>

            <p>
                <label for="detail">詳細（任意）</label><br>
                <textarea id="detail" name="detail" rows="5" style="width:100%;">{$post.detail|default:''|escape}</textarea>
            </p>

            <p>
                <label for="priority">優先度</label><br>
                <select id="priority" name="priority">
                    {* selectedで送信後の選択状態を保持 *}
                    <option value="1" {if $post.priority|default:2 == 1}selected{/if}>高</option>
                    <option value="2" {if $post.priority|default:2 == 2}selected{/if}>中</option>
                    <option value="3" {if $post.priority|default:2 == 3}selected{/if}>低</option>
                </select>
            </p>

            <p>
                <label for="due_date">期限日（任意）</label><br>
                <input id="due_date" type="date" name="due_date" value="{$post.due_date|default:''|escape}">
            </p>

            <p>
                <label for="estimate_min">見積時間（分・任意）</label><br>
                <input id="estimate_min" type="number" name="estimate_min" min="1" value="{$post.estimate_min|default:''|escape}">
            </p>

            <p>
                <label for="repeat_type">繰り返し設定</label><br>
                <select id="repeat_type" name="repeat_type">
                    {* 空文字は「繰り返しなし」扱い *}
                    <option value="" {if $post.repeat_type|default:'' == ''}selected{/if}>繰り返しなし</option>
                    <option value="daily" {if $post.repeat_type|default:'' == 'daily'}selected{/if}>毎日</option>
                    <option value="weekly" {if $post.repeat_type|default:'' == 'weekly'}selected{/if}>毎週</option>
                    <option value="monthly" {if $post.repeat_type|default:'' == 'monthly'}selected{/if}>毎月</option>
                </select>
            </p>
        </div>
    </div>

    {* 送信ボタンとキャンセル導線 *}
    <div class="task-actions">
        <button type="submit" class="add-btn">登録</button>
        <a href="index.php" class="act-btn">キャンセル</a>
    </div>
</form>

{include file='footer.tpl'}
