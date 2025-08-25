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
    {{ __('home.MainPage70') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.entries') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.journal_entries') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <h1>{{ __('home.journal_entries') }}</h1>
    
    <!-- زر إضافة قيد يومي جديد -->
    <a href="{{ route('journal_entries.create') }}" class="btn btn-success mb-3">{{ __('home.add_new_entry') }}</a>

    <!-- جدول القيود اليومية -->
    <table id="journalEntriesTable" class="table table-striped table-bordered text-center">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>{{ __('home.transaction_date') }}</th>
                <th>{{ __('home.description') }}</th>
                <th>{{ __('home.debit_account') }}</th>
                <th>{{ __('home.credit_account') }}</th>
                <th>{{ __('home.amount') }}</th>
                <th>{{ __('home.reference_number') }}</th>
                <th>{{ __('home.actions') }}</th> 
            </tr>
        </thead>
        <tbody>
            @foreach($journalEntries as $index => $entry)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- إضافة تسلسل للأرقام -->
                    <td>{{ $entry->transaction_date }}</td>
                    <td>{{ $entry->description }}</td>
                    <td>
                        @isset($entry->debitAccount)
                            {{ $entry->debitAccount->name }}
                        @else
                            {{ __('home.no_debit_account') }}
                        @endisset
                    </td>
                    <td>
                        @isset($entry->creditAccount)
                            {{ $entry->creditAccount->name }}
                        @else
                            {{ __('home.no_credit_account') }}
                        @endisset
                    </td>
                    <td>{{ number_format($entry->amount, 2) }}</td>
                    <td>{{ $entry->reference_number }}</td>
                    <td>
                        <!-- زر تعديل -->
                        <a href="{{ route('journal_entries.edit', $entry->id) }}" class="btn btn-warning btn-sm">{{ __('home.edit') }}</a>
                        <!-- زر حذف -->
                        <form action="{{ route('journal_entries.destroy', $entry->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('{{ __('home.confirm_delete_entry') }}');">
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
@endsection

@section('js')
<!-- DataTables js -->
<script src="{{ URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js') }}"></script>

<script>
    $(document).ready(function() {
        // تفعيل DataTable مع البحث والتصفية التلقائية
        $('#journalEntriesTable').DataTable({
            responsive: true, // لجعل الجدول يستجيب للأحجام المختلفة للشاشات
            language: {
                url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json' // لتحديد اللغة العربية
            },
            dom: 'Bfrtip', // لتحديد مكان الأزرار
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print' // أزرار لتصدير البيانات
            ],
        });
    });
</script>
@endsection
