<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
        ログアウト
    </button>
</form>

