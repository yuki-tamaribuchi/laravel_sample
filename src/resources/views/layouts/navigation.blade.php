<nav class="navigation">
	<div class="brand">
		<a href="/">
			<div class="logo">
				{{ config('app.name')}}
			</div>
		</a>
	</div>
	<div class="auth-info">
		@if (Auth::check())
			ログイン中: <a href="{{ route('account_detail', ['id'=>Auth::user()->id]) }}">{{ Auth::user()->email }}({{Auth::user()->name}})</a>
			<br>
			<a href="{{ route('logout') }}" onclick="document.logout-form.submit();">ログアウト</a>
		@else
			<a href="{{ route('signup') }}">新規登録</a>
			<br>
			<a href="{{ route('login') }}">ログイン</a>
		@endif
		<br>
		<a href="{{ route('posts.create') }}">投稿</a>
	</div>
</nav>