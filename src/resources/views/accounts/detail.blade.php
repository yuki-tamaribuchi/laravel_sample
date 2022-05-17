@extends('layouts.app')

@section('content')
<h2>{{ $user->name }}(id:{{ $user->id }})</h2>

<p>最終ログイン: {{ $user->last_login_at }}</p>

@if ($user->biograph)
{{ $user->biograph }}
@else
<p>自己紹介はありません。</p>
@endif

<h3>投稿</h3>
@if ($posts)
	@foreach($posts as $post)
	<a href="{{ route('posts.show', ['post'=>$post->id])}}">{{ $post->title }}</a>
	@endforeach
@else
投稿はありません。
@endif

<br>
<br>

@if (Auth::user()->id == $user->id)
<a href="{{ route('account_update', ['id' => $user->id]) }}">プロフィール変更</a>
<a href="{{ route('account_delete', ['id' => $user->id]) }}">退会</a>
@endif
@endsection