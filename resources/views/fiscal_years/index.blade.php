@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
   {{ __('home.MainPage67') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-19 mr-2 mb-0">/ {{ __('home.fiscal_years') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
    <!-- إظهار رسالة النجاح إذا كانت موجودة -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('fiscal_years.create') }}" class="btn btn-primary mb-3">{{ __('home.add_new_fiscal_year') }}</a>

    <table id="accountsTable" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('home.fiscal_year_name') }}</th>
                <th>{{ __('home.start_date') }}</th>
                <th>{{ __('home.end_date') }}</th>
                <th>{{ __('home.status') }}</th>
                <th>{{ __('home.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fiscalYears as $fiscalYear)
                <tr>
                    <td>{{ $fiscalYear->id }}</td>
                    <td>{{ $fiscalYear->name }}</td>
                    <td>{{ $fiscalYear->start_date }}</td>
                    <td>{{ $fiscalYear->end_date }}</td>
                    <td>{{ $fiscalYear->status }}</td>
                    <td>
                        <a href="{{ route('fiscal_years.edit', $fiscalYear->id) }}" class="btn btn-warning btn-sm">{{ __('home.edit') }}</a>
                        <form action="{{ route('fiscal_years.destroy', $fiscalYear->id) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ __('home.confirm_delete_fiscal_year') }}')">{{ __('home.delete') }}</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

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
                lengthMenu: "{{ __('home.show') }} _MENU_ {{ __('home.records') }}",
                info: "{{ __('home.showing') }} _START_ {{ __('home.to') }} _END_ {{ __('home.of') }} _TOTAL_ {{ __('home.records') }}",
                infoEmpty: "{{ __('home.no_records') }}",
                infoFiltered: "{{ __('home.filtered') }} (_MAX_ {{ __('home.total_records') }})"
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
                table.column(4).search('').draw(); // إعادة الجدول بدون تصفية حسب الحالة
            } else {
                table.column(4).search(statusValue).draw(); // تصفية حسب الحالة
            }
        });
    });
</script>
@endsection
