@extends('layouts.master')

@section('css')
<!-- إضافة التنسيقات الخاصة بك إذا لزم الأمر -->
@endsection

@section('title')
    {{ __('home.MainPage72') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.account_types') }}</h4>
            <span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.edit_account_type') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4>{{ __('home.edit_account_type') }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('account_types.update', $type->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- اسم الحساب -->
                <div class="form-group">
                    <label for="name">{{ __('home.name') }}</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $type->name) }}" required>
                </div>

                <!-- كود الحساب -->
                <div class="form-group">
                    <label for="code">{{ __('home.code') }}</label>
                    <input type="text" name="code" id="code" class="form-control" value="{{ old('code', $type->code) }}" required>
                </div>

                <!-- وصف الحساب -->
                <div class="form-group">
                    <label for="description">{{ __('home.description_optional') }}</label>
                    <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $type->description) }}</textarea>
                </div>

                <!-- حالة الحساب (is_active) -->
                <div class="form-group">
                    <label for="is_active">
                        <input type="checkbox" name="is_active" id="is_active" {{ old('is_active', $type->is_active) ? 'checked' : '' }}> {{ __('home.active_type') }}
                    </label>
                </div>

                <!-- زر الحفظ -->
                <button type="submit" class="btn btn-success btn-block">{{ __('home.save_changes') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
