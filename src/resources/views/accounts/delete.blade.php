@extends('layouts.app')

@section('content')
<form method="POST" action="{{ route('account_delete', ['id'=>Auth::user()->id]) }}">
	{{ csrf_field() }}
	<button type="submit">退会</button>
</form>
@endsection