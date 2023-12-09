@extends('layouts.app')

@section('content')

    
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <form action="{{ route('users.update', ['user' => $user->id]) }}">
                @csrf
                @method('PUT')
                <div class="row mb-3">
                    <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                    <div class="col-md-6">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ $user->name }}" required autocomplete="name" autofocus>
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                    <div class="col-md-6">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{$user->email }}" required autocomplete="email">
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="mobile_no" class="col-md-4 col-form-label text-md-end">{{ __('Mobile No') }}</label>

                    <div class="col-md-6">
                        <input id="mobile_no" type="mobile_no" class="form-control @error('mobile_no') is-invalid @enderror" name="mobile_no" value="{{ $user->mobile_no }}" autocomplete="mobile_no">
                    </div>
                </div>

        

                <div class="row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            {{ __('Update') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
