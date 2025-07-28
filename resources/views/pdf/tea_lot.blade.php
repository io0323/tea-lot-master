{{--
  茶ロット検査レポートPDFテンプレート
  @var \App\Models\TeaLot $lot
--}}
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>茶ロット検査レポート</title>
  <style>
    body { font-family: ipag, 'Noto Sans JP', sans-serif; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #888; padding: 8px; }
    th { background: #eee; }
    h1 { font-size: 1.5em; }
  </style>
</head>
<body>
  <h1>茶ロット検査レポート</h1>
  <table>
    <tr><th>ロット番号</th><td>{{ $lot->batch_code }}</td></tr>
    <tr><th>茶種</th><td>{{ $lot->tea_type }}</td></tr>
    <tr><th>産地</th><td>{{ $lot->origin }}</td></tr>
    <tr><th>含水率（%）</th><td>{{ $lot->moisture }}</td></tr>
    <tr><th>香りスコア</th><td>{{ $lot->aroma_score }}</td></tr>
    <tr><th>色スコア</th><td>{{ $lot->color_score }}</td></tr>
    <tr><th>検査日</th><td>{{ $lot->inspected_at }}</td></tr>
    <tr><th>サプライヤー</th><td>{{ optional($lot->supplier)->name }}</td></tr>
    <tr><th>検査員</th><td>{{ optional($lot->inspector)->name }}</td></tr>
  </table>
  <p style="margin-top:40px; font-size:0.9em; color:#888;">Generated: {{ now()->format('Y-m-d H:i') }}</p>
</body>
</html> 