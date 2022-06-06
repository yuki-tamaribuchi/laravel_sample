@extends('layouts.app')

@section('content')
<h2>{{ $post->title }}</h2>


{{ $post->content }}

<br>
<br>
カテゴリ:
@forelse ($post->categories as $category)
<a href="#">{{ $category->name }}</a>&nbsp;
@empty
カテゴリは登録されていません。
@endforelse
<br>
<br>
投稿日時: {{ $post->created_at }} | 更新日時: {{ $post->updated_at }}
<br>
@if (Auth::check())
	@if (Auth::user()->id == $post->user_id)
	<a href="{{ route('posts.edit', ['post'=>$post->id]) }}">更新</a>
	<a href="{{ route('posts.destroy-confirm', ['post'=>$post->id]) }}">削除</a>
	@endif
@endif
<br>
<br>
<h3>投稿者</h3>
<h4><a href="{{ route('account_detail', ['id'=>$post->user->id]) }}">{{$post->user->name}}</a></h4>
@if ($post->user->biograph)
	{{$psot->user->biograph}}
@else
	自己紹介はありません。
@endif
@endsection