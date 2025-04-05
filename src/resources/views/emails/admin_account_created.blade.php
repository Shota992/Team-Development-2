<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>管理者アカウント作成通知</title>
</head>
<body>
    <p>{{ $email }} 様</p>

    <p>管理者アカウントが作成されました。</p>

    <p>以下の情報でログインしてください：</p>

    <ul>
        <li><strong>メールアドレス：</strong> {{ $email }}</li>
        <li><strong>パスワード：</strong> {{ $password }}</li>
    </ul>

    <p>※ログイン後、パスワードの変更をおすすめします。</p>

    <p>よろしくお願いいたします。</p>
</body>
</html>
