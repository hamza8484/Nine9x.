@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

<style>
    .select2-container {
        width: 100% !important;
    }
</style>
@endsection

@section('title')
{{ __('home.MainPage4') }}
@stop

@section('page-header')
    <!-- breadcrumb -->
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">{{ __('home.Program Setting') }} </h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.Cashboxes') }}</span>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
@endsection

@section('content')
    <!-- row -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between">
                        <a class="modal-effect btn btn-outline-primary btn-block" 
                        data-effect="effect-scale" data-toggle="modal" 
                        href="#modaldemo8" style="font-size: 15px; width: 300px; height: 40px;" >{{ __('home.AddCashbox') }} </a>
                    </div>

                </div>

                <div class="card-body">
                    <!-- Display Success/Failure Messages -->
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @elseif(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 8%;">{{ __('home.No.') }}</th>
                                    <th class="text-center" style="width: 15%;">{{ __('home.CashboxName') }}</th>
                                    <th class="text-center" style="width: 20%;">{{ __('home.Accounts') }}</th>
                                    <th class="text-center" style="width: 10%;">{{ __('home.Palance') }}</th>
                                    <th class="text-center" style="width: 10%;">{{ __('home.CashboxType') }}</th>
                                    <th class="text-center" style="width: 10%;">{{ __('home.Status') }}</th>
                                    <th class="text-center" style="width: 10%;">{{ __('home.CreatDate') }}</th>
                                    <th class="text-center" style="width: 20%;">{{ __('home.Notes') }}</th>
                                    <th class="text-center" style="width: 15%;">{{ __('home.Events') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cashboxes as $cashbox)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td class="text-center">{{ $cashbox->cash_name }}</td>
                                        <td class="text-center">
                                            @foreach($cashbox->accounts as $account)
                                                <span class="badge badge-primary">{{ $account->name }}</span>
                                            @endforeach
                                        </td>

                                        <td class="text-center">{{ number_format($cashbox->cash_balance, 2) }} </td>
                                        <td class="text-center">{{ $cashbox->cashbox_type }}</td>
                                        <td class="text-center">{{ $cashbox->status }}</td>
                                        <td class="text-center">{{ $cashbox->created_at->format('Y/m/d') }}</td>
                                        <td class="text-center">{{ $cashbox->notes }}</td>
                                        <td class="text-center">
                                            <!-- زر عرض رصيد الخزنة -->
                                            <a href="#" class="btn btn-info btn-sm">
                                            {{ __('home.Palance') }} 
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Basic modal -->
        <div class="modal" id="modaldemo8">
            <div class="modal-dialog modal-dialog-centered modal-lg " role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">{{ __('home.AddCashbox') }}</h6>
                            <button aria-label="Close" class="close" data-dismiss="modal" type="button">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <div class="modal-body">
                            <form action="{{ route('cashboxes.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">

                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="cash_name">{{ __('home.CashboxName') }}</label>
                                        <input type="text" name="cash_name" class="form-control" required>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cashbox_type">{{ __('home.CashboxType') }}</label>
                                        <select name="cashbox_type" class="form-control" required>
                                            <option value="main">{{ __('home.Main') }}</option>
                                            <option value="sub">{{ __('home.Sub') }}</option>
                                            <option value="temporary">{{ __('home.Temporary') }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="cash_balance">{{ __('home.Palance') }}</label>
                                        <input type="number" name="cash_balance" class="form-control" step="0.01" required>
                                    </div>
                                </div>

                                <!-- إضافة الحقول الجديدة -->
                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="start_date">{{ __('home.StartDate') }}</label>
                                        <input type="date" name="start_date" class="form-control">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="last_opening_balance">{{ __('home.LastOpeningBalance') }}</label>
                                        <input type="number" name="last_opening_balance" class="form-control" step="0.01">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="usable_balance">{{ __('home.UsableBalance') }}</label>
                                        <input type="number" name="usable_balance" class="form-control" step="0.01">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-4">
                                        <label for="reconciliation_status">{{ __('home.ReconciliationStatus') }}</label>
                                        <select name="reconciliation_status" class="form-control">
                                            <option value="pending">{{ __('home.Pending') }}</option>
                                            <option value="completed">{{ __('home.Completed') }}</option>
                                            <option value="reconciled">{{ __('home.Reconciled') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="limit_effective_date">{{ __('home.LimitEffectiveDate') }}</label>
                                        <input type="date" name="limit_effective_date" class="form-control">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="cash_limit">{{ __('home.CashLimit') }}</label>
                                        <input type="number" name="cash_limit" class="form-control" step="0.01">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-5">
                                        <label for="account_ids">اختر</label>
                                        <select name="account_ids[]" multiple style="height: 100px;">
                                            @foreach($accounts as $account)
                                                <option value="{{ $account->id }}" {{ in_array($account->id, old('account_ids', $selectedAccounts ?? [])) ? 'selected' : '' }}>
                                                    {{ $account->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="currency_code">{{ __('home.CurrencyCode') }}</label>
                                        <select name="currency_code" class="form-control" required>
                                            <option value="SAR" selected>{{ __('home.SAR') }}</option>
                                            <option value="USD">{{ __('home.USD') }}</option>
                                            <option value="KWD">{{ __('home.KWD') }}</option>
                                            <option value="AED">{{ __('home.AED') }}</option>
                                            <option value="EGP">{{ __('home.EGP') }}</option>
                                            <option value="EUR">{{ __('home.EUR') }}</option>
                                            <option value="JOD">{{ __('home.JOD') }}</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <label for="status">{{ __('home.Status') }}</label>
                                        <select name="status" class="form-control" required>
                                            <option value="active">{{ __('home.Active') }}</option>
                                            <option value="inactive">{{ __('home.Inactive') }}</option>
                                            <option value="closed">{{ __('home.Closed') }}</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="notes">{{ __('home.Notes') }}</label>
                                        <textarea name="notes" class="form-control">{{ __('home.NoNotes') }}</textarea>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">{{ __('home.Save') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- row closed -->
@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/select2/js/select2.min.js')}}"></script>
<script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تفعيل select2 لجميع الحقول التي تحتوي على class "select2"
        $('.select2').select2({
            placeholder: "اختر الحسابات",  // رسالة العرض داخل الـ select عند عدم اختيار أي عنصر
            allowClear: true,  // يسمح بإلغاء الاختيار
        });
    });
</script>
@endsection
