{* =============================================== *}
{* 共通フッター + カウントダウン用JavaScript *}
{* js-countdown クラスの要素を探して、残り時間を毎秒更新します。 *}
{* =============================================== *}
<script>
(function () {
	// DBから来る日時文字列を JavaScript Date に変換する関数
	function parseDateTime(value) {
		// 値がない場合は null を返して呼び出し側で処理
		if (!value) {
			return null;
		}
		// "YYYY-MM-DD HH:MM:SS" を "YYYY-MM-DDTHH:MM:SS" に変換
		// Dateコンストラクタで解釈しやすくするためです。
		var normalized = value.replace(' ', 'T');
		var date = new Date(normalized);
		// 不正な日時は null を返す
		if (isNaN(date.getTime())) {
			return null;
		}
		return date;
	}

	// 残り時間（正の値）を画面表示用の文字列にする関数
	function formatRemaining(diffMs) {
		// ミリ秒を秒へ
		var totalSec = Math.floor(diffMs / 1000);
		var hours = Math.floor(totalSec / 3600);
		var minutes = Math.floor((totalSec % 3600) / 60);
		var seconds = totalSec % 60;

		// 1時間超の場合は「時間+分+秒」で表示
		if (hours > 1) {
			return 'あと' + hours + '時間' + minutes + '分' + seconds + '秒';
		}
		// 1時間以内は「分+秒」で表示
		return 'あと' + (hours * 60 + minutes) + '分' + seconds + '秒';
	}

	// 超過（負の差分）を表示用に整形する関数
	function formatOver(diffMs) {
		var overSec = Math.floor(Math.abs(diffMs) / 1000);
		var hours = Math.floor(overSec / 3600);
		var minutes = Math.floor((overSec % 3600) / 60);
		return hours + '時間' + minutes + '分 超過';
	}

	// 画面中のすべてのカウントダウン要素を更新
	function updateCountdown() {
		// class="js-countdown" を持つ要素を全取得
		var elements = document.querySelectorAll('.js-countdown');
		// 現在時刻を1回だけ作る（ループ内で毎回作らない）
		var now = new Date();

		elements.forEach(function (el) {
			// data-* 属性から値を取得
			var createdAt = parseDateTime(el.getAttribute('data-created-at'));
			var estimateMin = parseInt(el.getAttribute('data-estimate-min'), 10);

			// 入力値が不正なら表示を空にして終了
			if (!createdAt || !estimateMin || estimateMin <= 0) {
				el.textContent = '';
				return;
			}

			// 終了時刻 = 作成時刻 + 見積時間(分)
			var endAt = new Date(createdAt.getTime() + estimateMin * 60 * 1000);
			// まだ残っているか、超過しているかを判定する差分
			var diffMs = endAt.getTime() - now.getTime();

			// 超過表示（赤）
			if (diffMs < 0) {
				el.textContent = formatOver(diffMs);
				el.style.color = '#d93025';
				return;
			}

			// 1時間超なら緑
			if (diffMs > 3600 * 1000) {
				el.textContent = formatRemaining(diffMs);
				el.style.color = '#188038';
			} else {
				// 1時間以内は黄
				el.textContent = formatRemaining(diffMs);
				el.style.color = '#f9ab00';
			}
		});
	}

	// 初回表示直後に1回実行
	updateCountdown();
	// 1秒ごとに再計算して表示更新
	setInterval(updateCountdown, 1000);
})();
</script>
{* body/htmlをここで閉じる（header.tplで開いたタグの対応） *}
</body>
</html>
