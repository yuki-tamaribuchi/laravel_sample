@extends('layouts.app')

@section('content')
<h2>新規投稿</h2>
<form action="{{ route('posts.store') }}" method="POST">
	{{ csrf_field() }}
	タイトル: <input name="title">
	@if ($errors->first('title'))
		{{$errors->first('title')}}
	@endif
	<br>
	内容: <input name="content" type="text">
	<br>
	<button type="submit">投稿</button>
</form>

@endsection