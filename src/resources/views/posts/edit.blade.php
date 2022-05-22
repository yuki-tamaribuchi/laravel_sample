@extends('layouts.app')

@section('content')
<h2>投稿編集</h2>
<form action="{{ route('posts.update', ['post' => $post->id]) }}" method="PUT">
	{{ csrf_field() }}
	タイトル: <input name="title" value="{{ $post->title }}">
	@if ($errors->first('title'))
		{{$errors->first('title')}}
	@endif
	<br>
	内容: <input name="content" type="text" value="{{ $post->content }}">
	<br>
	<button type="submit">更新</button>
</form>

@endsection