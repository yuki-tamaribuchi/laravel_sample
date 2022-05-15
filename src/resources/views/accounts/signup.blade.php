@extends('layouts.app')

@section('content')
<h2>新規登録フォーム</h2>
<form action="{{ route('signup') }}" method="POST">
	{{ csrf_field() }}
	名前: <input name="name">
	@if ($errors->first('name'))
		{{$errors->first('name')}}
	@endif
	<br>
	メールアドレス: <input name="email">
	@if ($errors->first('email'))
		{{$errors->first('email')}}
	@endif
	<br>
	パスワード: <input name="password">
	<br>
	パスワード(確認): <input name="password_confirmation">
	@if ($errors->first('password'))
		{{$errors->first('password')}}
	@endif
	<br>
	<button class="btn">登録</button>
</form>
@endsection