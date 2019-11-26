@extends('layouts.app')

@section('content')
<div class="login-clean">
    <form method="post" action="{{ route('login') }}">
		@csrf
        <h2 class="sr-only">Login Form</h2>
        <div class="illustration">
			<i class="icon ion-log-in"></i>
		</div>
        <div class="form-group">
			<label for="email">{{ __('E-Mail Address:') }}</label>
			<input id="email" placeholder="E-Mail" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
			@error('email')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>
        <div class="form-group">
			<label for="password">{{ __('Password:') }}</label>
			<input id="password" placeholder="Password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
			@error('password')
				<span class="invalid-feedback" role="alert">
					<strong>{{ $message }}</strong>
				</span>
			@enderror
		</div>
        <div class="form-group">
			<button type="submit" class="btn btn-primary btn-block">
				{{ __('Login') }}
			</button>
		</div>
		@if (Route::has('password.request'))
			<a class="forgot" href="{{ route('password.request') }}">
				{{ __('Forgot Your Password?') }}
			</a>
		@endif
	</form>
</div>
@endsection
