@extends('layouts.master')
@section('css')
<!-- Internal Data table css -->
<link href="{{URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" />
<link href="{{URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css')}}" rel="stylesheet">
<link href="{{URL::asset('assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet">
@endsection

@section('title')
   {{ __('home.MainPage15') }}
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">{{ __('home.PurchaseInvoice') }}</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.AddPurchaseInvoice') }}</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@foreach (['Add', 'Update', 'Delete'] as $msg)
    @if(session($msg))
        <div class="alert alert-{{ 
            $msg == 'Add' ? 'info' : 
            ($msg == 'Update' ? 'warning' : 'danger') 
        }} alert-dismissible fade show" role="alert">
            <strong>{{ session($msg) }}</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach
<script>
    // إخفاء الرسائل بعد 10 ثواني
    setTimeout(function() {
        let alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            alert.style.display = 'none';
        });
    }, 10000);
</script>

<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                <a href="{{ route('purchases.create') }}" class="btn btn-success">{{ __('home.AddNewPurchaseInvoice') }}</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table text-md-nowrap display nowrap" id="example1" data-page-length='50'>
                        <thead>
                            <tr>
                            <th class="wd-5p border-bottom-0 text-center">{{ __('home.No.') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.PurchaseNo') }}</th>
                                <th class="wd-15p border-bottom-0 text-center">{{ __('home.Date') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Supplier') }}</th>
                                <th class="wd-10p border-bottom-0 text-center">{{ __('home.Vat') }}</th>
                                <th class="wd-20p border-bottom-0 text-center">{{ __('home.Total') }}</th>
                                <th class="wd-25p border-bottom-0 text-center">{{ __('home.Events') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)  
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center"><a href="{{ route('purchases.show', $purchase->id) }}">{{ $purchase->pur_number }}</a></td> 
                                    <td class="text-center">{{ $purchase->pur_date }}</td>
                                    <td class="text-center">{{ $purchase->supplier->sup_name ?? 'غير موجود' }}</td>  <!-- تأكد من أن المورد موجود -->
                                    <td class="text-center">{{ number_format ($purchase->vat_value,2) }}</td>
                                    <td class="text-center">{{ number_format ($purchase->total_deu,2) }}</td> <!-- التأكد من العرض الصحيح للمجموع الفرعي -->
                                    <td class="text-center">
                                    <a href="{{ route('purchases.edit',$purchase->id) }}" class="btn btn-primary btn-sm" ><i class="fa  fa-edit" ></i></a>
                                        <a href="{{ route('purchases.print', $purchase->id) }}" class="btn btn-success btn-sm"><i class="fa fa-print"></i></a>
                                        <a href="javascript:void(0)" onclick="if (confirm('{{ __('home.DeleteInvoice') }}')) { document.getElementById('delete-{{ $purchase->id }}').submit(); } else { return false; }" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </a>

                                        <form action="{{ route('purchases.destroy', $purchase->id) }}" method="POST" id="delete-{{ $purchase->id }}" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <td colspan="4" class="text-center" style="font-size: 20px; font-weight: bold;">
                                <strong>{{ __('home.TotalAllInvoices') }}</strong>
                            </td>
                            <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-vat">{{ __('home.S.R') }}</td>
                            <td class="text-center" style="font-size: 17px; font-weight: bold;" id="total-sum">{{ __('home.S.R') }}</td>
                            <td class="text-center"></td>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .modal-dialog {
        max-width: 90%; /* يمكنك تعديل هذه القيمة حسب الحاجة */
    }
</style>
@endsection

@section('js')
<!-- Internal Data tables -->
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.dataTables.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jquery.dataTables.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.bootstrap4.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.buttons.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.bootstrap4.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/jszip.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/pdfmake.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/vfs_fonts.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.html5.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.print.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/buttons.colVis.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/dataTables.responsive.min.js')}}"></script>
<script src="{{URL::asset('assets/plugins/datatable/js/responsive.bootstrap4.min.js')}}"></script>

<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<!-- المجاميع -->
<script>
    $(document).ready(function() {
        var table = $('#example1').DataTable({
            scrollX: true,             // تمكين السحب الأفقي
            scrollY: '400px',          // تمكين السحب العمودي بارتفاع معين
            scrollCollapse: true,      // تقليص الجدول عند الحاجة
            paging: true,              // تفعيل الترقيم
            responsive: true,
            language: {
                "url": "//cdn.datatables.net/plug-ins/1.13.4/i18n/ar.json"
            }
        });

        // Function to update the totals
        function updateTotals() {
            var totalVat = 0;
            var totalSum = 0;

            table.rows().every(function() {
                var data = this.data();
                totalVat += parseFloat(data[4] || 0); // vat_value column index
                totalSum += parseFloat(data[5] || 0); // total_deu column index
            });

            // تعيين النص واللون للعناصر وجعلها بالخط العريض (بولد)
            $('#total-vat').text(totalVat.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ريال').css('color', 'green').css('font-weight', 'bold');  // الضريبة باللون الأخضر وبخط عريض
            $('#total-sum').text(totalSum.toLocaleString('en-US', { minimumFractionDigits: 2 }) + ' ريال').css('color', 'green').css('font-weight', 'bold');  // المجموع باللون الأخضر وبخط عريض
        }

        // Update totals on page load and whenever the table is drawn
        table.on('draw', function() {
            updateTotals();
        });

        // Initial calculation
        updateTotals();
    });
</script>

<script>
    $(document).ready(function() {
        // تحديث المجموعات مثل الخصم، الضريبة، الإجمالي، والمبلغ المتبقي
        function updateTotals() {
            let subTotal = 0;
            let discountValue = parseFloat($('#discount_value').val()) || 0;
            let discountType = $('#discount_type').val();
            let vatRate = parseFloat($('#vat_value').val()) || 0;  // قيمة الضريبة (نسبة)
            let totalPaid = parseFloat($('#total_paid').val()) || 0;

            // حساب المجموع الفرعي
            $('#invoice_details tbody tr').each(function() {
                let quantity = parseFloat($(this).find('td:eq(5)').find('input').val()) || 0;  // الكمية
                let price = parseFloat($(this).find('td:eq(6)').find('input').val()) || 0;  // السعر
                let discount = parseFloat($(this).find('td:eq(7)').find('input').val()) || 0;  // الخصم
                let total = (quantity * price) - discount;  // المجموع لكل صف

                subTotal += total;  // جمع المجموع الفرعي لجميع الصفوف
            });

            // حساب الخصم الإجمالي إذا كان باستخدام النسبة المئوية أو السعر الثابت
            if (discountType === "نســبة") {
                subTotal -= (subTotal * (discountValue / 100));
            } else {
                subTotal -= discountValue;
            }

            // حساب قيمة الضريبة
            let vatValue = (subTotal * (vatRate / 100));

            // حساب الإجمالي
            let totalDue = subTotal + vatValue;

            // تحديث الحقول
            $('#sub_total').val(subTotal.toFixed(2));  // المجموع الفرعي
            $('#vat_value').val(vatValue.toFixed(2));  // قيمة الضريبة
            $('#total_deu').val(totalDue.toFixed(2));  // الإجمالي
            $('#total_unpaid').val((totalDue - totalPaid).toFixed(2));  // المبلغ المتبقي
        }

        // تحديث الإجماليات عندما يتغير أحد الحقول
        $('#invoice_details').on('input', 'input', function() {
            updateTotals();  // تحديث الإجماليات عند تغيير أي قيمة
        });

        // تحديث الإجماليات عند تغيير قيمة الدفع
        $('#total_paid').on('input', function() {
            updateTotals();  // تحديث الإجماليات عندما يتم إدخال المبلغ المدفوع
        });

        // إضافة صف إلى الجدول
        $('.btn_add').click(function() {
            let newRow = `<tr>
                <td style="text-align: center;">#</td>
                <td><input type="text" class="form-control" name="group[]" style="text-align: center;"></td>
                <td><input type="text" class="form-control" name="item[]" style="text-align: center;"></td>
                <td><input type="text" class="form-control" name="unit[]" style="text-align: center;"></td>
                <td><input type="text" class="form-control" name="barcode[]" style="text-align: center;"></td>
                <td><input type="number" class="form-control" name="quantity[]" style="text-align: center;"></td>
                <td><input type="number" class="form-control" name="price[]" style="text-align: center;"></td>
                <td><input type="number" class="form-control" name="discount[]" style="text-align: center;"></td>
                <td><input type="number" class="form-control" name="total[]" style="text-align: center;" readonly></td>
                <td><button type="button" class="btn btn-danger btn-sm btn_remove">حذف</button></td>
            </tr>`;

            $('#invoice_details tbody').append(newRow);  // إضافة الصف الجديد
        });

        // إزالة الصف من الجدول
        $('#invoice_details').on('click', '.btn_remove', function() {
            $(this).closest('tr').remove();  // إزالة الصف
            updateTotals();  // تحديث الإجماليات بعد إزالة الصف
        });

        // تحديث الإجماليات عند تحميل الصفحة
        updateTotals();
    });
</script>

@endsection