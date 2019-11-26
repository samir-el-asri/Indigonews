@extends('layouts.app')

@section('content')
<div class="register-photo">
    <div class="form-container">
        <div class="image-holder"></div>
		
        <form method="post" action="{{ route('register') }}">
			@csrf
            <h2 class="text-center"><strong>Create</strong> an account.</h2>
            <div class="form-group">
				<label for="username">{{ __('Username:') }}</label>
				<input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" autofocus placeholder="username">

				@error('username')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="email">{{ __('Email:') }}</label>
				<input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="email">

				@error('email')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="password">{{ __('Password:') }}</label>
				<input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="password">

				@error('password')
					<span class="invalid-feedback" role="alert">
						<strong>{{ $message }}</strong>
					</span>
				@enderror
			</div>
            <div class="form-group">
				<label for="password-confirmation">{{ __('Password (repeat):') }}</label>
				<input id="password-confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="password (repeat)">
			</div>
            <div class="form-group">
                <div class="form-check">
					<label class="form-check-label">
					<input class="form-check-input" type="checkbox" />I agree to the license terms.</label>
				</div>
            </div>
            <div class="form-group">
				<button class="btn btn-primary btn-block" type="submit">{{ __('Sign Up') }}</button>
			</div>
			<a class="already" href="/login">I already have an account.</a>
		</form>
    </div>
</div>
@endsection
