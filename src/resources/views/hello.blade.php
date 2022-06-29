@extends('layouts.app')

@section('content')
<p>
	Hello, 
	@if ($name)
		{{$name}}
	@else
		world
	@endif
	!
</p>
@endsection