<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>アンケートのお願い</title>
</head>
<body>
    <p>{{ $user->name }}さん</p>

    <p>アンケート「{{ $survey->name }}」へのご回答がまだのようです。</p>

    <p>以下のリンクからご回答をお願いいたします。</p>

    <p>
        <a href="{{ $url }}">{{ $url }}</a>
    </p>

    <p>ご協力ありがとうございます。</p>
</body>
</html>
