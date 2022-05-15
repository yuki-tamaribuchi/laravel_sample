@extends('layouts.app')

@section('content')
<h2>{{ $user->name }}(id:{{ $user->id }})</h2>

<p>最終ログイン: {{ $user->last_login_at }}</p>

@if ($user->biograph)
{{ $user->biograph }}
@else
<p>自己紹介はありません。</p>
@endif

@if (Auth::user()->id == $user->id)
<a href="{{ route('account_update', ['id' => $user->id]) }}">プロフィール変更</a>
<a href="{{ route('account_delete', ['id' => $user->id]) }}">退会</a>
@endif
@endsection