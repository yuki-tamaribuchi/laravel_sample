@extends('layouts.app')

@section('content')
<h2>{{ $post->title }}を削除しますか？</h2>
<form action="{{ route('posts.destroy', ['post'=>$post->id]) }}" method="POST">
	<input type="hidden" name="_method" value="DELETE" />
	{{ csrf_field() }}
	<button type="submit">削除</button>
</form>

@endsection