@extends('layouts.app')

@section('content')
<form action="{{ route('account_update', ['id' => Auth::user()->id])}}" method="POST">
	{{ csrf_field() }}
	名前: <input name="name" value="{{Auth::user()->name}}" />
	<br>
	自己紹介: <input type="text" name="biograph" value="{{ Auth::user()->biograph }}" />
	<br>
	<button type="submit">更新</button>
</form>
@endsection