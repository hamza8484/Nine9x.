@extends('layouts.master')

@section('css')

@endsection

@section('title')
   {{ __('home.MainPage61') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.account_types') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.add_account_type') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <h2>{{ __('home.add_new_account_type') }}</h2>

   <form action="{{ route('account_types.store') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-group">
        <label>{{ __('home.name') }}</label>
        <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
    </div>

    <div class="form-group">
        <label>{{ __('home.code') }}</label>
        <input type="text" name="code" class="form-control" required value="{{ old('code') }}">
    </div>

    <div class="form-group">
        <label for="is_active">{{ __('home.is_active') }}:</label><br>
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
    </div>

    <div class="form-group">
        <label>{{ __('home.description_optional') }}</label>
        <textarea name="description" class="form-control">{{ old('description') }}</textarea>
    </div>

    <button type="submit" class="btn btn-success">{{ __('home.save') }}</button>
</form>

</div>
@endsection
