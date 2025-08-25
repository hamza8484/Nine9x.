@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
{{ __('home.MainPage66') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.accounts_guide') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <h2>{{ __('home.accounts_guide') }}</h2>

     <!-- زر إضافة حساب جديد -->
    <a href="{{ route('accounts.create') }}" class="btn btn-primary mb-3">{{ __('home.add_new_account') }}</a>
   
    <!-- عرض زر ميزان المراجعة إذا كانت السنة المالية موجودة -->
    @if(isset($fiscalYear))
        <a href="{{ url('/balance-sheet/' . $fiscalYear->id) }}" class="btn btn-success mb-3">{{ __('home.view_trial_balance') }} {{ $fiscalYear->name }}</a>
    @else
        <p>{{ __('home.no_active_fiscal_year') }}</p>
    @endif

    <!-- زر البحث -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="{{ __('home.search_for_account') }}">
        </div>

        <!-- تصفية حسب النوع -->
        <div class="col-md-4 mb-3">
            <select id="accountTypeFilter" class="form-control">
                <option value="">{{ __('home.filter_by_type') }}</option>
                @foreach($accountTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- تصفية حسب الحالة -->
        <div class="col-md-4 mb-3">
            <select id="accountStatusFilter" class="form-control">
                <option value="">{{ __('home.filter_by_status') }}</option>
                <option value="1">{{ __('home.active') }}</option>
                <option value="0">{{ __('home.inactive') }}</option>
            </select>
        </div>
    </div>

    <!-- جدول عرض الحسابات -->
    <table id="accountsTable" class="table table-striped table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th>{{ __('home.serial') }}</th>
                <th>{{ __('home.name') }}</th>
                <th>{{ __('home.code') }}</th>
                <th>{{ __('home.type') }}</th>
                <th>{{ __('home.parent_account') }}</th>
                <th>{{ __('home.balance') }}</th>
                <th>{{ __('home.status') }}</th>
                <th>{{ __('home.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($accounts as $account)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $account->name }}</td>
                    <td>{{ $account->code }}</td>
                    <td>{{ $account->type->name ?? '-' }}</td>
                    <td>{{ $account->parent->name ?? '-' }}</td>
                    <td>{{ $account->balance }}</td>
                    <td>{{ $account->is_active ? __('home.active') : __('home.inactive') }}</td>
                    <td>
                            <!-- زر دفتر الأستاذ -->
                            <a href="{{ route('accounts.ledger', $account->id) }}" class="btn btn-success btn-sm">{{ __('home.ledger') }}</a>
                            <!-- زر تعديل -->
                            <a href="{{ route('accounts.edit', $account->id) }}" class="btn btn-warning btn-sm">{{ __('home.edit') }}</a>
                            
                            <!-- زر حذف -->
                            <form action="{{ route('accounts.destroy', $account->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">{{ __('home.delete') }}</button>
                            </form>
                        </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@section('js')
<!-- DataTables js -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تفعيل DataTables
        var table = $('#accountsTable').DataTable({
            responsive: true,
            language: {
                search: "{{ __('home.search') }}:",
                lengthMenu: "{{ __('home.show_menu') }} _MENU_ {{ __('home.records') }}",
                info: "{{ __('home.showing') }} _START_ {{ __('home.to') }} _END_ {{ __('home.of') }} _TOTAL_ {{ __('home.records') }}",
                infoEmpty: "{{ __('home.no_records') }}",
                infoFiltered: "{{ __('home.filtered_from') }} _MAX_ {{ __('home.total_records') }}"
            }
        });

        // إضافة وظيفة البحث
        $('#searchInput').on('keyup', function() {
            table.search(this.value).draw();
        });

        // إضافة وظيفة التصفية حسب النوع
        $('#accountTypeFilter').on('change', function() {
            table.column(2).search(this.value).draw(); // تصفية حسب العمود الذي يحتوي على نوع الحساب
        });

        // إضافة وظيفة التصفية حسب الحالة
        $('#accountStatusFilter').on('change', function() {
            var statusValue = this.value;

            if (statusValue === "") {
                table.column(6).search('').draw(); // إعادة الجدول بدون تصفية
            } else {
                if (statusValue === '1') {
                    table.column(6).search('{{ __('home.active') }}').draw();
                } else {
                    table.column(6).search('{{ __('home.inactive') }}').draw();
                }
            }
        });
    });
</script>
@endsection
@endsection
