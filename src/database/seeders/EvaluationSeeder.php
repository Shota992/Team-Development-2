<?php

namespace Database\Seeders;

use App\Models\Evaluation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Evaluation::create(
            [
                'measure_id' => 1,
                'keep' => '退職者アンケートを継続的に実施できており、退職理由の傾向が把握できている点は良かった。',
                'problem' => 'アンケート内容が形式的であり、具体的な改善策に繋がる深い情報が十分に取れていない。',
                'try' => '今後は1on1やインタビューを組み合わせ、定性的なデータも収集して施策に反映していく。',
                'created_at' => now()->subWeeks(5),
                'updated_at' => now()->subweeks(5),
            ]);
        Evaluation::create([
                'measure_id' => 1,
                'keep' => '1on1ミーティング制度を試験的に導入し、従業員からポジティブな反応を得られている。',
                'problem' => '上司側のスキルに差があり、1on1の質にばらつきが出ている。',
                'try' => '上司向けの1on1トレーニングを企画・実施し、全体の質の向上を目指す。',
                'created_at' => now()->subWeeks(4),
                'updated_at' => now()->subWeeks(4),
        ]);
        Evaluation::create([
                'measure_id' => 1,
                'keep' => '従業員満足度サーベイにより、定量的な指標で課題を可視化できた。',
                'problem' => '課題が見えてきたものの、改善アクションのスピードが遅く従業員の不信感につながっている。',
                'try' => '小さな改善でも素早く共有・実行し、変化を感じられるようなアクション設計を行う。',
                'created_at' => now()->subWeeks(3),
                'updated_at' => now()->subWeeks(3),
        ]);
        Evaluation::create([
            'measure_id' => 2,
            'keep' => '退職者アンケートを継続的に実施できており、退職理由の傾向が把握できている。',
            'problem' => 'アンケート内容が形式的であり、具体的な改善策に繋がる深い情報が十分に取れていない。',
            'try' => '今後は1on1やインタビューを組み合わせ、定性的なデータも収集して施策に反映していく。',
            'created_at' => now()->subWeeks(3),
            'updated_at' => now()->subWeeks(3),
        ]);
        Evaluation::create([
            'measure_id' => 3,
            'keep' => '新卒採用プロセスの現状分析が完了し、課題が明確化された。',
            'problem' => '課題の優先順位付けが不十分で、改善案の具体性が不足している。',
            'try' => '課題の優先順位を明確化し、具体的な改善案を策定する。',
            'created_at' => now()->subWeeks(2),
            'updated_at' => now()->subWeeks(2),
        ]);

        Evaluation::create([
            'measure_id' => 4,
            'keep' => '中途採用プロセスの改善案が現場で受け入れられた。',
            'problem' => '一部の候補者体験において、フィードバックが遅れる問題が発生している。',
            'try' => 'フィードバックの迅速化を図り、候補者体験を向上させる。',
            'created_at' => now()->subWeeks(1),
            'updated_at' => now()->subWeeks(1),
        ]);

        Evaluation::create([
            'measure_id' => 5,
            'keep' => '研修プログラムの現状調査が完了し、スキルギャップが明確化された。',
            'problem' => '研修内容が一部の従業員にとって実用的でない。',
            'try' => '研修内容を見直し、実用性を高める。',
            'created_at' => now()->subWeeks(1),
            'updated_at' => now()->subWeeks(1),
        ]);
    }
}
