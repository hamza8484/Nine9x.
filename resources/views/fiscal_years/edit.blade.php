@extends('layouts.master')

@section('css')
<!-- إضافة التنسيقات الخاصة بك إذا لزم الأمر -->
@endsection

@section('title')
    {{ __('home.MainPage69') }} 
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.edit_fiscal_year') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <form action="{{ route('fiscal_years.update', $fiscalYear->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- إظهار الأخطاء -->
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="name">{{ __('home.fiscal_year_name') }}</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $fiscalYear->name) }}" required>
        </div>

        <div class="form-group">
            <label for="start_date">{{ __('home.start_date') }}</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="{{ old('start_date', $fiscalYear->start_date) }}" required>
        </div>

        <div class="form-group">
            <label for="end_date">{{ __('home.end_date') }}</label>
            <input type="date" name="end_date" id="end_date" class="form-control" value="{{ old('end_date', $fiscalYear->end_date) }}" required>
        </div>

        <div class="form-group">
            <label for="status">{{ __('home.status') }}</label>
            <select name="status" id="status" class="form-control" required>
                <option value="نشطة" {{ old('status', $fiscalYear->status) == 'نشطة' ? 'selected' : '' }}>{{ __('home.active') }}</option>
                <option value="غير نشطة" {{ old('status', $fiscalYear->status) == 'غير نشطة' ? 'selected' : '' }}>{{ __('home.inactive') }}</option>
                <option value="مؤرشفة" {{ old('status', $fiscalYear->status) == 'مؤرشفة' ? 'selected' : '' }}>{{ __('home.archived') }}</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success mt-3">{{ __('home.update') }}</button>
    </form>
@endsection
