<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MindMapController extends Controller
{
    /**
     * コンストラクタで認証ミドルウェアを設定
     * ログインしていないユーザーは、このコントローラのアクションにアクセスできません。
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * マインドマップ画面を表示する
     *
     * ここではダミーデータとして、中央ノードから枝分かれしたサンプルデータを作成しています。
     * 実際には、データベースから取得したノード情報や親子関係に基づいて JSON を構築するなど、
     * 要件に合わせて処理を実装してください。
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // サンプルの階層構造データ（マインドマップのデータ）
        $mindMapData = [
            'name' => '中央ノード',
            'children' => [
                [
                    'name' => 'ブランチ A',
                    'children' => [
                        ['name' => 'リーフ A1'],
                        ['name' => 'リーフ A2']
                    ]
                ],
                [
                    'name' => 'ブランチ B',
                    'children' => [
                        ['name' => 'リーフ B1'],
                        ['name' => 'リーフ B2']
                    ]
                ],
                [
                    'name' => 'ブランチ C',
                    'children' => [
                        ['name' => 'リーフ C1'],
                        ['name' => 'リーフ C2'],
                        ['name' => 'リーフ C3']
                    ]
                ]
            ]
        ];

        // 上記データを JSON 文字列に変換してビューに渡す
        $mindMapJson = json_encode($mindMapData);

        return view('mindmap.index', compact('mindMapJson'));
    }
}
