@extends('layouts.master')

@section('css')
<!-- Internal Data table css -->
<link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
<link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
   {{ __('home.MainPage63') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.accounts') }}</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ {{ __('home.account_types') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <h2>{{ __('home.account_types') }}</h2>

    <!-- زر البحث -->
    <div class="mb-3">
        <input type="text" id="searchInput" class="form-control" placeholder="{{ __('home.search_account_type') }}">
    </div>

    <!-- زر إضافة نوع جديد -->
    <a href="{{ route('account_types.create') }}" class="btn btn-primary mb-3">{{ __('home.add_new_account_type') }}</a>

    <!-- جدول أنواع الحسابات -->
    <table id="accountTable" class="table table-striped table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th>{{ __('home.serial_number') }}</th> <!-- عمود الرقم التسلسلي -->
                <th>{{ __('home.name') }}</th>
                <th>{{ __('home.code') }}</th>
                <th>{{ __('home.status') }}</th>
                <th>{{ __('home.description') }}</th>
                <th>{{ __('home.actions') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($types as $index => $type)
                <tr>
                    <td>{{ $index + 1 }}</td> <!-- الرقم التسلسلي -->
                    <td>{{ $type->name }}</td>
                    <td>{{ $type->code }}</td>
                    <td>{{ $type->is_active ? __('home.active') : __('home.inactive') }}</td> <!-- عرض الحالة بشكل مناسب -->
                    <td>{{ $type->description }}</td>
                    <td>
                        <!-- زر تعديل -->
                        <a href="{{ route('account_types.edit', $type->id) }}" class="btn btn-warning btn-sm">{{ __('home.edit') }}</a>

                        <!-- زر حذف -->
                        <form action="{{ route('account_types.destroy', $type->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
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
        $('#accountTable').DataTable({
            responsive: true,
            language: {
                search: "{{ __('home.search') }}:",
                lengthMenu: "{{ __('home.show_menu') }} _MENU_ {{ __('home.records') }}",
                info: "{{ __('home.show_info') }} _START_ {{ __('home.to') }} _END_ {{ __('home.of') }} _TOTAL_ {{ __('home.records') }}",
                infoEmpty: "{{ __('home.no_records') }}",
                infoFiltered: "(تم تصفيته من _MAX_ {{ __('home.total') }} {{ __('home.records') }})"
            }
        });

        // إضافة وظيفة بحث
        $('#searchInput').on('keyup', function() {
            $('#accountTable').DataTable().search(this.value).draw();
        });
    });
</script>
@endsection
@endsection
