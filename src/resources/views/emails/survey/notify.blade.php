@component('mail::message')
# {{ $user->name }} さん

新しいアンケート「{{ $survey->name }}」が配信されました。

@component('mail::button', ['url' => url('/survey/employee')])
アンケートに回答する
@endcomponent

このメールは自動配信です。
@endcomponent