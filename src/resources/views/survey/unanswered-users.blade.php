@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto p-8 bg-white shadow-lg rounded-lg ml-64">
    <h2 class="text-2xl font-extrabold text-gray-800 mb-6 border-b pb-2">
        未回答者一覧（{{ $survey->name }}）
    </h2>

    @if (session('status'))
        <div class="mb-4 p-4 text-green-700 bg-green-100 border border-green-300 rounded">
            {{ session('status') }}
        </div>
    @endif

    @if ($unansweredTokens->isEmpty())
        <div class="text-center text-gray-600 text-sm py-8">
            未回答のユーザーはいません。
        </div>
    @else
        <div class="overflow-x-auto mb-6">
            <table class="w-full text-sm text-left text-gray-700 border border-gray-200">
                <thead class="bg-blue-50 text-gray-800 uppercase text-xs tracking-wider">
                    <tr>
                        <th class="px-4 py-3 border border-gray-200">氏名</th>
                        <th class="px-4 py-3 border border-gray-200">メールアドレス</th>
                        <th class="px-4 py-3 border border-gray-200">部署</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @foreach ($unansweredTokens as $token)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 border border-gray-200">{{ $token->user->name }}</td>
                            <td class="px-4 py-3 border border-gray-200">{{ $token->user->email }}</td>
                            <td class="px-4 py-3 border border-gray-200">{{ $token->user->department->name ?? '未設定' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- リマインド送信用ボタン -->
        <form action="{{ route('survey.remind-unanswered', ['survey' => $survey->id]) }}" method="POST">
            @csrf
            <div class="flex justify-center">
                <button type="submit"
                    class="bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-6 rounded transition">
                    未回答者にリマインドメールを送る
                </button>
            </div>
        </form>
    @endif
</div>
@endsection
