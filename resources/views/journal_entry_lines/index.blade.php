@extends('layouts.master')

@section('title')
    {{ __('home.MainPage73') }} 
@stop

@section('page-header')
<!-- breadcrumb -->
<div class="breadcrumb-header justify-content-between">
    <div class="my-auto">
        <div class="d-flex">
            <h4 class="content-title mb-0 my-auto">{{ __('home.journal_entries_list') }}</h4>
            <span class="text-muted mt-1 tx-13 mr-2 mb-0">/ {{ __('home.view_journal_entries') }}</span>
        </div>
    </div>
</div>
<!-- breadcrumb -->
@endsection

@section('content')
<div class="container">
    <h3 class="mb-4">{{ __('home.journal_entries_list') }} ({{ __('home.journal_entry_line') }})</h3>

    <!-- حقل البحث -->
    <div class="row mb-4">
        <div class="col-md-6">
            <input type="text" id="search" class="form-control" placeholder="{{ __('home.search_by_reference_or_account_or_description') }}">
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('journal_entry_lines.create') }}" class="btn btn-success">{{ __('home.add_new_entry_line') }}</a>
        </div>
    </div>

    <!-- عرض جدول الحركات داخل كارت -->
    <div class="card">
        <div class="card-header">
            <h5>{{ __('home.transaction_details') }}</h5>
        </div>
        <div class="card-body">
            <table id="journalTable" class="table table-bordered">
                <thead>
                    <tr>
                        <th class="wd-5p border-bottom-0 text-center">{{ __('home.sequence') }}</th> <!-- عمود التسلسل -->
                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.reference_number') }}</th>
                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.date') }}</th>
                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.account') }}</th>
                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.entry_type') }}</th>
                        <th class="wd-12p border-bottom-0 text-center">{{ __('home.amount') }}</th>
                        <th class="wd-20p border-bottom-0 text-center">{{ __('home.description') }}</th>
                        <th class="wd-15p border-bottom-0 text-center">{{ __('home.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($journalEntryLines as $index => $line)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td> <!-- تسلسل تلقائي -->
                            <td class="text-center">{{ $line->journalEntry->reference_number }}</td>
                            <td class="text-center">{{ $line->journalEntry->transaction_date }}</td>
                            <td class="text-center">{{ $line->account->name }}</td>
                           <td class="text-center">
                                <span class="{{ $line->entry_type == 'debit' ? 'text-success font-weight-bold' : 'text-danger font-weight-bold' }}">
                                    {{ $line->entry_type == 'debit' ? __('home.debit') : __('home.credit') }}
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="{{ $line->entry_type == 'debit' ? 'text-success font-weight-bold' : 'text-danger font-weight-bold' }}">
                                    {{ number_format($line->amount, 2) }}
                                </span>
                            </td>

                            <td class="text-center">{{ $line->description }}</td>
                            <td class="text-center">
                                <!-- أزرار التعديل والحذف -->
                                <a href="{{ route('journal_entry_lines.edit', $line->id) }}" class="btn btn-primary btn-sm">{{ __('home.edit') }}</a>
                                <form action="{{ route('journal_entry_lines.destroy', $line->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('home.confirm_delete') }}');">
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
    </div>
</div>

<!-- إضافة سكربت DataTables لتحسين الجدول -->
@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // تفعيل DataTables مع البحث والتصفية التلقائية
            $('#journalTable').DataTable({
                responsive: true, // لجعل الجدول يستجيب للأحجام المختلفة للشاشات
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.10.25/i18n/Arabic.json' // لتحديد اللغة العربية
                }
            });

            // إضافة وظيفة البحث داخل الصفحة
            $('#search').on('keyup', function() {
                $('#journalTable').DataTable().search(this.value).draw();
            });
        });
    </script>
@endsection
@endsection
