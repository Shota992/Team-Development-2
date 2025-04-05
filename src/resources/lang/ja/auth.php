<?php

return [
    'required' => ':attribute は必須です。',
    'email' => '有効なメールアドレスを入力してください。',
    'confirmed' => ':attribute の確認が一致しません。',
    'min' => [
        'string' => ':attribute は :min 文字以上で入力してください。',
    ],
    'max' => [
        'string' => ':attribute は :max 文字以下で入力してください。',
    ],
    'between' => [
        'string' => ':attribute は :min 文字以上、:max 文字以下で入力してください。',
    ],
    'regex' => ':attribute は英字と数字を含む8〜16文字で、空白を含めないでください。',

    'attributes' => [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'password_confirmation' => 'パスワード（確認）',
    ],
];

