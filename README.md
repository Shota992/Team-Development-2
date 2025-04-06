# Kompass

## アプリ概要
組織の改善が見通せない人向けに、「組織の健康を保持するメンター型プロダクト」としてサポートします。

## 環境構築方法

1.`docker compose build --no-cache`

2.`docker compose up -d`

3.`docker compose exec app sh`

4.`composer install`

5.env exampleファイルから複製し、名前を「.env」に変更

6.`docker compose exec app sh`

7.`php artisan key:generate`

8.src内のenvの情報を以下の通りに修正する。
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=website
DB_USERNAME=posse
DB_PASSWORD=password
```

9.`php artisan migrate:fresh --seed`

10.`php artisan queue:work`

11.`exit`

12.`docker compose exec node sh`

13.`npm install`

14.`npm run dev`

※OPENAI_API_KEYをenvファイルに埋め込む必要あり

## ログイン方法
デフォルトでは以下の通りに入力することでシステムに入ることができます。

ログイン画面：localhost/login

メールアドレス：suzuki.takuma@example.com

パスワード：Password123

（初期Seederもこちらのアカウント中心で作成しています)

## 利用の流れ

①**アンケート作成**

自部署内でアンケートを作成します。

※アンケート作成時に、設問を追加したい場合は「設定 → 項目設定」から編集が可能です。

②**アンケート実施**

（今回の実装ではMailHogに）メールが送信されるので、受信メールを開いてアンケートに回答します。

③**アンケート確認**

アンケートの回答率が60％を超えると、ダッシュボードに結果が表示されます。

さらに詳細な分析が必要な場合は、「項目別詳細比較」や「部署別比較」機能を活用してください。

④**施策作成**

施策を考える際には、「AIメンター」や「マインドマップ」機能を活用できます。

ユーザーはそこで考案した施策を施策作成画面にて登録することができます。その際に、タスクの追加も行えます。

⑤**施策の振り返り**

施策を実行したあとは、設定した評価周期に応じて振り返りを行います。

期限が過ぎたタスクや、振り返りのタイミングが到来した施策は、サイドバーの通知バッジに表示されます。そこから対象施策を確認してください。

施策の振り返りでは、タスクごとの進捗確認に加えて、「KEEP」「PROBLEM」「TRY」の3項目を入力します。

振り返りは、施策の評価周期に達したとき、またはすべてのタスクが完了したときに行います。

---

このように、施策を着実に実行し、継続的にアンケートを実施していくことで、組織改善を実現していきます。
