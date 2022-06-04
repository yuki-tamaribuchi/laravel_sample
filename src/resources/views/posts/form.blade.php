<form action="@if ($post->id==null){{ route('posts.store') }} @else{{ route('posts.update', ['post' => $post->id]) }}@endif" method="POST">
	@if (!$post->id==null)<input type="hidden" name="_method" value="PUT">@endif
	{{ csrf_field() }}
ル: <input name="title" value="{{ $post->title }}">
	@if ($errors->first('title'))
		{{$errors->first('title')}}
	@endif
	<br>
	内容: <input name="content" type="text" value="{{ $post->content }}">
	<br>
	カテゴリ:
	<select name="categories[]" id="categories" multiple>
		@foreach ($categories as $category)
		<option value="{{ $category->id }}" @if ($post->categories->contains('id', $category->id)) selected @endif>{{ $category->name }}</option>
		@endforeach
	</select>
	<br>
	<button type="submit">@if ($post->id==null) 投稿 @else 更新 @endif</button>
</form>