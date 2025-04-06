@extends('layouts.plain')

@section('content')
@php $admin = session('sign_up_admin'); @endphp

<div class="flex items-center justify-center min-h-screen bg-[#E0F4FF]">
    <div class="w-full max-w-xl bg-white p-6 rounded-md shadow mt-8 mb-8">
        <div class="text-center mb-6">
            <!-- 画像サイズ調整 -->
            <img src="{{ asset('images/Kompasslogo.jpeg') }}" alt="sign up" class="mx-auto mb-6 w-30">
        </div>

        <h2 class="text-center text-2xl font-bold mb-6">管理者情報入力画面</h2>

        <form method="POST" action="{{ route('sign-up.admin.store') }}">
            @csrf

            {{-- 氏名 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">氏名 <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ $admin['name'] ?? old('name') }}" class="w-full p-2 border rounded" placeholder="氏名を入力してください" required>
                @error('name')
                    <p class="text-red-500 text-sm">
                        {{ $message === 'The name field is required.' ? '氏名を入力してください。' : $message }}
                    </p>
                @enderror
            </div>

            {{-- 性別 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">性別 <span class="text-red-500">*</span></label>
                <select name="gender" class="w-full p-2 border rounded" required>
                    <option value="">選択してください</option>
                    <option value="1" {{ ($admin['gender'] ?? old('gender')) == 1 ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ ($admin['gender'] ?? old('gender')) == 2 ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ ($admin['gender'] ?? old('gender')) == 3 ? 'selected' : '' }}>その他</option>
                </select>
                @error('gender')
                    <p class="text-red-500 text-sm">性別を選択してください。</p>
                @enderror
            </div>

{{-- 生年月日 --}}
<div class="mb-4">
    <label class="block text-gray-700 font-semibold mb-1">生年月日 <span class="text-red-500">*</span></label>
    <input type="date" 
           name="birthday" 
           value="{{ $admin['birthday'] ?? old('birthday') }}" 
           class="w-full p-2 border rounded" 
           required 
           onfocus="this.showPicker && this.showPicker()">
    @error('birthday')
        <p class="text-red-500 text-sm">生年月日を選択してください。</p>
    @enderror
</div>

            {{-- メール --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">メールアドレス <span class="text-red-500">*</span></label>
                <input type="email" name="email" value="{{ $admin['email'] ?? old('email') }}" class="w-full p-2 border rounded" placeholder="example@example.com" required>
                @error('email')
                    <p class="text-red-500 text-sm">
                        @if ($message === 'The email field is required.')
                            メールアドレスを入力してください。
                        @elseif ($message === 'The email has already been taken.')
                            このメールアドレスは使用されています。
                        @else
                            {{ $message }}
                        @endif
                    </p>
                @enderror
            </div>

            {{-- パスワード --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">パスワード <span class="text-red-500">*</span></label>
                <input type="password" name="password" class="w-full p-2 border rounded" placeholder="パスワードを入力してください" required>
                <p class="text-sm text-gray-500 mt-1">（注）半角英数字記号8文字以上16文字以内（英数字混在）で入力してください。空白は使用できません。</p>
                @error('password')
                    <p class="text-red-500 text-sm">
                        @if (str_contains($message, 'required'))
                            パスワードを入力してください。
                        @elseif (str_contains($message, 'regex'))
                            指定の形式でパスワードを入力してください。
                        @else
                            {{ $message }}
                        @endif
                    </p>
                @enderror
            </div>

            {{-- パスワード確認 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">パスワード再入力 <span class="text-red-500">*</span></label>
                <input type="password" name="password_confirmation" class="w-full p-2 border rounded" placeholder="もう一度パスワードを入力してください" required>
                @error('password_confirmation')
                    <p class="text-red-500 text-sm">
                        @if (str_contains($message, 'required'))
                            パスワードを入力してください。
                        @elseif (str_contains($message, 'same'))
                            同じ値でパスワードを入力してください。
                        @else
                            {{ $message }}
                        @endif
                    </p>
                @enderror
            </div>

            {{-- 管理者の会社名 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">所属している会社名 <span class="text-red-500">*</span></label>
                <input type="text" name="company" value="{{ $admin['company'] ?? old('company') }}" class="w-full p-2 border rounded" placeholder="会社名を入力してください" required>
                @error('company')
                    <p class="text-red-500 text-sm">所属している会社名を入力してください。</p>
                @enderror
            </div>

            {{-- 管理者の部署名 --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-1">所属している部署名 <span class="text-red-500">*</span></label>
                <input type="text" name="department" value="{{ $admin['department'] ?? old('department') }}" class="w-full p-2 border rounded" placeholder="部署名を入力してください" required>
                <p class="text-sm text-gray-500 mt-1">（注）所属している部署がない方は、「社内」と入力してください。</p>
                @error('department')
                    <p class="text-red-500 text-sm">所属している部署名を入力してください。</p>
                @enderror
            </div>

            {{-- 管理者の役職名 --}}
            <div class="mb-6">
                <label class="block text-gray-700 font-semibold mb-1">就いている役職名 <span class="text-red-500">*</span></label>
                <input type="text" name="position" value="{{ $admin['position'] ?? old('position') }}" class="w-full p-2 border rounded" placeholder="役職名を入力してください" required>
                @error('position')
                    <p class="text-red-500 text-sm">就いている役職名を入力してください。</p>
                @enderror
            </div>

            {{-- ボタン --}}
            <div class="mt-10 text-center space-y-4">
                <button type="submit"
                        id="submit-next"
                        class="inline-block w-64 py-3 bg-[#86D4FE] text-white font-bold rounded-full shadow-lg hover:bg-[#69C2FD] transition duration-300 text-center">
                    次へ
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('submit-next')?.addEventListener('click', function () {
        const btn = this;
        btn.classList.add('pointer-events-none', 'opacity-70');
        btn.textContent = '保存中...';
    });
</script>
@endsection
