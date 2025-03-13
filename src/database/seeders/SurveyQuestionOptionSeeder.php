<?php

namespace Database\Seeders;

use App\Models\SurveyQuestionOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SurveyQuestionOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $texts1 = [
            [ 'text'  => '顧客基盤が脆弱で、新規顧客獲得が難しい'],
            [ 'text'  => '既存顧客の離脱が増えている'],
            [ 'text'  => '主要顧客の依存度が高く、リスクがある'],
            [ 'text'  => '競合他社に顧客を奪われている'],
            [ 'text'  => '顧客満足度が低下している'],
            [ 'text'  => '口コミや紹介による新規顧客獲得が少ない'],
            [ 'text'  => '市場の変化に適応できていない'],
        ];

        foreach ($texts1 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 1,
            ], $text));
        }

        $texts2 = [
            [ 'text'  => '企業のビジョンや戦略が曖昧で分かりにくい'],
            [ 'text'  => '理念や戦略が現場の業務と結びついていない'],
            [ 'text'  => '経営層からの説明が不足している'],
            [ 'text'  => '経営層と従業員の認識にギャップがある'],
            [ 'text'  => '戦略が現実的ではない、もしくは実行力に欠けている'],
            [ 'text'  => '理念や戦略が頻繁に変わり、一貫性がない'],
            [ 'text'  => '自分の業務に対する影響が不透明で不安を感じる'],
        ];

        foreach ($texts2 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 2,
            ], $text));
        }

        $texts3 = [
            [ 'text'  => '企業の社会貢献活動がほとんど行われていない'],
            [ 'text'  => '社会的貢献に関する情報発信が不足している'],
            [ 'text'  => '環境・社会問題に対する取り組みが不十分'],
            [ 'text'  => '利益追求が優先され、社会的責任が軽視されていると感じる'],
            [ 'text'  => '地域社会との関係が希薄で、貢献している実感がない'],
            [ 'text'  => '社会貢献活動が形だけで、実質的な影響が見えにくい'],
            [ 'text'  => '従業員が社会貢献活動に参加する機会がない'],
        ];

        foreach ($texts3 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 3,
            ], $text));
        }

        $texts4 = [
            [ 'text'  => '顧客への約束や品質基準が十分に守られていない'],
            [ 'text'  => '顧客対応が不十分で、クレームや不満が多い'],
            [ 'text'  => '社会貢献よりも利益追求が優先されていると感じる'],
            [ 'text'  => 'コンプライアンス意識が低く、不正リスクがある'],
            [ 'text'  => '環境や社会問題への取り組みが不十分'],
            [ 'text'  => 'ステークホルダー（顧客・従業員・社会）への説明責任が果たされていない'],
            [ 'text'  => '企業の行動が理念と一致しておらず、信用に欠ける'],
        ];

        foreach ($texts4 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 4,
            ], $text));
        }

        $texts5 = [
            [ 'text'  => '部門間やチーム内での協力が不足している'],
            [ 'text'  => '個人主義が強く、チームワークが感じられない'],
            [ 'text'  => '意見や価値観の違いが尊重されない'],
            [ 'text'  => 'コミュニケーションが不足している'],
            [ 'text'  => '対立や派閥があり、職場の雰囲気が良くない'],
            [ 'text'  => '上司・同僚からのリスペクトが感じられない'],
            [ 'text'  => 'ハラスメントや差別的な発言・態度が見受けられる'],
        ];

        foreach ($texts5 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 5,
            ], $text));
        }

        $texts6 = [
            [ 'text'  => '上司がリーダーシップを発揮できていない'],
            [ 'text'  => '上司や同僚に尊敬できる人物が少ない'],
            [ 'text'  => '指導やサポートが不足している'],
            [ 'text'  => '部下や後輩を育成する文化が弱い'],
            [ 'text'  => '上司が公正な評価をしていない'],
            [ 'text'  => 'チームワークより個人プレーが重視される'],
            [ 'text'  => '上司や同僚との信頼関係が築けていない'],
        ];

        foreach ($texts6 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 6,
            ], $text));
        }

        $texts7 = [
            [ 'text'  => '勤務地が通勤しにくくアクセスが悪い'],
            [ 'text'  => 'オフィスや設備が老朽化している'],
            [ 'text'  => 'デスクや作業スペースが狭く、快適性に欠ける'],
            [ 'text'  => '休憩スペースやリフレッシュできる環境が整っていない'],
            [ 'text'  => '空調や照明などの環境が快適ではない'],
            [ 'text'  => '設備やIT環境が整っておらず、業務効率が悪い'],
            [ 'text'  => '社内の清掃や衛生管理が不十分'],
        ];

        forEach ($texts7 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 7,
            ], $text));
        }

        $texts8 = [
            [ 'text'  => '給与が業務内容や成果に見合っていない'],
            [ 'text'  => '評価制度が不透明で、公平性に欠ける'],
            [ 'text'  => '昇給や昇進の機会が少ない'],
            [ 'text'  => '成果よりも年功序列が重視されている'],
            [ 'text'  => '柔軟な働き方が推奨されていない'],
            [ 'text'  => 'ワークライフバランスが考慮されていない'],
            [ 'text'  => '福利厚生や手当が充実していない'],
        ];

        forEach ($texts8 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 8,
            ], $text));
        }

        $texts9 = [
            [ 'text'  => '顧客の要望やフィードバックが現場に共有されていない'],
            [ 'text'  => '企業の事業戦略が明確に伝えられていない'],
            [ 'text'  => '情報伝達の仕組みが整っていない'],
            [ 'text'  => '経営層と現場の意識にギャップがある'],
            [ 'text'  => '部署間の連携が不足しており、情報が行き届かない'],
            [ 'text'  => '市場の変化に対する情報更新が遅い'],
            [ 'text'  => '顧客ニーズを意識した戦略が感じられない'],
        ];

        forEach ($texts9 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 9,
            ], $text));
        }

        $texts10 = [
            [ 'text'  => '従業員の意見が経営層や上司に十分に届いていない'],
            [ 'text'  => '上司が部下の業務負担や状況を把握していない'],
            [ 'text'  => '会社の方針や意思決定が一方的で、現場の声が反映されていない'],
            [ 'text'  => '働き方に対する柔軟性がなく、個々の事情が考慮されていない'],
            [ 'text'  => 'キャリアや成長に関する支援が不十分'],
            [ 'text'  => '問題が発生しても相談しにくい雰囲気がある'],
            [ 'text'  => '従業員の貢献が正しく評価・認識されていない'],
        ];

        forEach ($texts10 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 10,
            ], $text));
        }

        $texts11 = [
            [ 'text'  => '評価基準が不明確で、何が重視されているのか分からない'],
            [ 'text'  => '上司の主観や好みによる評価が多い'],
            [ 'text'  => '実績や努力が正当に評価されていない'],
            [ 'text'  => '昇進や昇給の基準が不透明で、一部の人に偏りがある'],
            [ 'text'  => '年功序列が強く、実力のある人が正当に評価されない'],
            [ 'text'  => 'フィードバックが不足しており、評価の理由が説明されない'],
            [ 'text'  => 'チームや組織全体の貢献が正しく評価されていない'],
        ];

        forEach ($texts11 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 11,
            ], $text));
        }

        $texts12 = [
            [ 'text'  => '上司からの指導やフィードバックが不足している'],
            [ 'text'  => '必要なスキルや知識を学ぶ機会が少ない'],
            [ 'text'  => '部下の成長に関心が低く、育成に力を入れていない'],
            [ 'text'  => '部下の適性やキャリア志向が考慮されていない'],
            [ 'text'  => 'ミスや失敗に対して支援よりも責任追及が優先される'],
            [ 'text'  => '相談しやすい環境が整っていない'],
            [ 'text'  => '部下に対する期待や評価の基準が曖昧で、方向性が示されない'],
        ];

        forEach ($texts12 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 12,
            ], $text));
        }

        $texts13 = [
            [ 'text'  => '顧客のニーズや課題を十分に理解できていない'],
            [ 'text'  => '提案のための時間やリソースが不足している'],
            [ 'text'  => '標準的なサービス・商品提供にとどまり、付加価値を生み出せていない'],
            [ 'text'  => '顧客とのコミュニケーションが不足している'],
            [ 'text'  => '提案に対する社内のサポート体制が整っていない'],
            [ 'text'  => '競合他社と比較して提案の魅力が弱い'],
            [ 'text'  => 'イノベーションを生み出す社内文化や仕組みが不足している'],
        ];

        forEach ($texts13 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 13,
            ], $text));
        }

        $texts14 = [
            [ 'text'  => '会社の目標が明確に示されていない'],
            [ 'text'  => '目標が具体性に欠け、従業員が実行しにくい'],
            [ 'text'  => '目標が部門や個人レベルに落とし込まれていない'],
            [ 'text'  => '定期的な進捗確認やフィードバックが不足している'],
            [ 'text'  => '目標が現実的でなく、達成可能性が低い'],
            [ 'text'  => '目標が頻繁に変更され、一貫性がない'],
            [ 'text'  => '目標の重要性が従業員に十分に伝わっていない'],
        ];

        forEach ($texts14 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 14,
            ], $text));
        }

        $texts15 = [
            [ 'text'  => '長期的なビジョンや戦略が明確に示されていない'],
            [ 'text'  => '市場や業界の変化に対する対応が遅い'],
            [ 'text'  => '新規事業やイノベーションの取り組みが不足している'],
            [ 'text'  => '研究開発や技術投資が不十分で、成長の可能性が低い'],
            [ 'text'  => '従業員のスキルアップや育成が後回しにされている'],
            [ 'text'  => '経営層の意思決定が短期的で、長期視点が欠けている'],
            [ 'text'  => '他社や異業種との連携・協業の機会が少ない'],
        ];

        forEach ($texts15 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 15,
            ], $text));
        }

        $texts16 = [
            [ 'text'  => '業務マニュアルや手順書が整備されていない'],
            [ 'text'  => '過去の成功事例やノウハウが共有されていない'],
            [ 'text'  => '情報共有の仕組みが不十分'],
            [ 'text'  => '新しい知識や技術の習得・活用が進んでいない'],
            [ 'text'  => '部署間での情報共有が不足している'],
            [ 'text'  => '属人的な業務が多く、標準化が進んでいない'],
            [ 'text'  => 'ナレッジ共有に対するインセンティブや仕組みがない'],
        ];

        forEach ($texts16 as $text) {
            SurveyQuestionOption::create(array_merge([
                'question_id'     => 16,
            ], $text));
        }
    }
}
