@extends('layouts.app')

@section('content')
<h2>ログイン</h2>
<form action="/accounts/login" method="POST">
	{{ csrf_field() }}
	メールアドレス: <input name="email">
	@if ($errors->first('email'))
		{{$errors->first('email')}}
	@endif
	<br>
	パスワード: <input type="password" name="password">
	<br>
	<button class="btn">ログイン</button>
</form>
@endsection