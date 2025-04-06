@extends('layouts.plain')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="text-center">
        <!-- Kompassのロゴ表示 -->
        <img src="{{ asset('images/Kompasslogo.jpeg') }}" alt="Kompass Logo" class="mx-auto mb-8" style="max-width: 300px;">
        
        <!-- 新規登録を始めるボタン -->
        <a href="{{ route('sign-up.admin') }}" class="inline-block w-60 py-3 bg-[#4880FF] text-white font-bold rounded-md shadow text-center">
            新規登録を始める
        </a>
    </div>
</div>
@endsection
