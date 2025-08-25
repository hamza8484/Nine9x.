@extends('layouts.master')

@section('css')
@endsection

@section('title')

    {{ __('home.MainPage27') }}

@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Users') }}</h4>
                <span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.Add User') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
<!-- row -->
<div class="row">


    <div class="col-lg-12 col-md-12">

        @if (count($errors) > 0)
        <div class="alert alert-danger">
            <button aria-label="Close" class="close" data-dismiss="alert" type="button">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong>خطا</strong>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-right">
                     
                    </div>
                </div><br>
                <form class="parsley-style-1" id="selectForm2" autocomplete="off" name="selectForm2"
                    action="{{route('users.store','test')}}" method="post">
                    {{csrf_field()}}

                    <div class="">

                        <div class="row">
                            <div class="parsley-input col-md-6 mb-3" id="fnWrapper">
                                <label>{{ __('home.User Name') }}<span class="tx-danger">*</span></label>
                                <input class="form-control form-control"
                                    data-parsley-class-handler="#lnWrapper" name="name" required="" type="text">
                            </div>
                            
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">{{ __('home.User Status') }}</label>
                                    <select name="Status" id="select-beast" class="form-control  nice-select  custom-select">
                                        <option value="مفعل">{{ __('home.Active') }}</option>
                                        <option value="غير مفعل">{{ __('home.Inactive') }}</option>
                                    </select>
                                </div>
                        </div>
                            <div class="row">
                            <div class="parsley-input col-md-6 mb-3" id="lnWrapper">
                                <label>{{ __('home.User Email') }} <span class="tx-danger">*</span></label>
                                <input class="form-control form-control"
                                    data-parsley-class-handler="#lnWrapper" name="email" required="" type="email">
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="parsley-input col-md-6 mb-3" id="lnWrapper">
                            <label>{{ __('home.Password') }} <span class="tx-danger">*</span></label>
                            <input class="form-control form-control" data-parsley-class-handler="#lnWrapper"
                                name="password" required="" type="password">
                        </div>
                        
                        <div class="parsley-input col-md-6 mb-3" id="lnWrapper">
                            <label> {{ __('home.ConfirmPassword') }} <span class="tx-danger">*</span></label>
                            <input class="form-control form-control" data-parsley-class-handler="#lnWrapper"
                                name="confirm-password" required="" type="password">
                        </div>
                    </div>
                   
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label"> {{ __('home.UserPermission') }}</label>
                                {!! Form::select('roles_name[]', $roles,[], array('class' => 'form-control','multiple')) !!}
                            </div>
                        </div>
                    </div>
                    <div class=" col-md-12 text-center">
                        <a class="btn btn-primary btn-lg" href="{{ route('users.index') }}">{{ __('home.BackToUsersList') }}</a>
                        <button class="btn btn-main-primary btn-lg" type="submit">{{ __('home.Confirm') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- row closed -->
</div>
<!-- Container closed -->
</div>
<!-- main-content closed -->
@endsection

@section('js')
@endsection
