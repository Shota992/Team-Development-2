<?php

namespace Database\Seeders;

use App\Models\EvaluationTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EvaluationTaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EvaluationTask::create(
            [
                'evaluation_id' => 1,
                'task_id' => 1,
                'score' => 4,
                'comment' => '退職理由の傾向が見えており、分析としては一定の成果を出せている。',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        EvaluationTask::create(
            [
                'evaluation_id' => 2,
                'task_id' => 1,
                'score' => 3,
                'comment' => 'アンケート内容の深掘りが足りず、具体的な改善策に結びついていない。',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        EvaluationTask::create(
            [
                'evaluation_id' => 3,
                'task_id' => 1,
                'score' => 4,
                'comment' => '退職者インタビューを組み合わせたことで、より深いインサイトが得られた。',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
        EvaluationTask::create([
            'evaluation_id' => 1,
            'task_id' => 2,
            'score' => 5,
            'comment' => '回答率が高く、満足度の傾向を把握できたのは大きな成果。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 2,
            'task_id' => 2,
            'score' => 3,
            'comment' => '部署によって回答に差があり、全体把握には不十分だった。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 3,
            'task_id' => 2,
            'score' => 4,
            'comment' => 'フィードバック共有に時間がかかったが、分析の質は高かった。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 1,
            'task_id' => 3,
            'score' => 4,
            'comment' => '制度設計は順調で、現場からの期待も高まっている。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 2,
            'task_id' => 3,
            'score' => 2,
            'comment' => '導入後の運用面で課題があり、実施状況にムラが出てしまった。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 3,
            'task_id' => 3,
            'score' => 3,
            'comment' => '上司向け研修の効果が出始めたが、継続的なフォローが必要。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 4,
            'task_id' => 4,
            'score' => 4,
            'comment' => '退職理由の傾向が見えており、分析としては一定の成果を出せている。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        EvaluationTask::create([
            'evaluation_id' => 4,
            'task_id' => 5,
            'score' => 3,
            'comment' => '部署によって回答に差があり、全体把握には不十分だった。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        EvaluationTask::create([
            'evaluation_id' => 4,
            'task_id' => 6,
            'score' => 5,
            'comment' => '1on1制度の導入が順調で、現場からの期待も高まっている。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        EvaluationTask::create([
            'evaluation_id' => 5, // 新卒採用プロセスの現状分析
            'task_id' => 7,
            'score' => 4,
            'comment' => '課題の明確化が進み、次のステップに進む準備が整った。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 5, // 新卒採用の課題整理と改善案作成
            'task_id' => 8,
            'score' => 3,
            'comment' => '改善案の具体性が不足しており、さらなる検討が必要。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 5, // 新卒採用プロセスの改善案実施
            'task_id' => 9,
            'score' => 5,
            'comment' => '改善案の実施が順調に進み、採用プロセスが効率化された。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // measure_id=4 のタスク評価
        EvaluationTask::create([
            'evaluation_id' => 6, // 中途採用プロセスの現状分析
            'task_id' => 10,
            'score' => 4,
            'comment' => '現状分析が的確で、課題が明確化された。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 6, // 中途採用の課題整理と改善案作成
            'task_id' => 11,
            'score' => 3,
            'comment' => '改善案の一部が現場で受け入れられなかった。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 6, // 中途採用プロセスの改善案実施
            'task_id' => 12,
            'score' => 5,
            'comment' => '改善案の実施が成功し、候補者体験が向上した。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // measure_id=5 のタスク評価
        EvaluationTask::create([
            'evaluation_id' => 7, // 研修プログラムの現状調査
            'task_id' => 13,
            'score' => 4,
            'comment' => '現状調査が完了し、スキルギャップが明確化された。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 7, // 研修プログラムの課題整理と改善案作成
            'task_id' => 14,
            'score' => 3,
            'comment' => '改善案の一部が従業員にとって実用的でない。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        EvaluationTask::create([
            'evaluation_id' => 7, // 研修プログラムの改善案実施
            'task_id' => 15,
            'score' => 5,
            'comment' => '改善案の実施が成功し、従業員の満足度が向上した。',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
