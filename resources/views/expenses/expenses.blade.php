@extends('layouts.master')

@section('css')
    <link href="{{ URL::asset('assets/plugins/datatable/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/buttons.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.bootstrap4.min.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/datatable/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/datatable/css/responsive.dataTables.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet">
@endsection

@section('title')
 المصروفات - ناينوكس
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">المصروفات</h4><span class="text-muted mt-1 tx-15 mr-2 mb-0">/ إضافة مصروف</span>
            </div>
        </div>
    </div>
     
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


                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card">
                                <div class="card-header pb-0">
                                    <div class="d-flex justify-content-between w-100">
                                    <!-- زر إضافة مصروف جديد في الجهة اليسرى -->
                                    <a href="{{ route('expenses.create') }}" class="btn btn-success">إضافة مصروف جديد</a>


                                    <!-- زر الذهاب إلى فئات المصروفات في الجهة اليسرى -->
                                    <a href="{{ route('e_categories.index') }}" class="btn btn-primary" 
                                    style="font-size: 17px; width: 200px; height: 40px;">
                                    شاشــة الفئــات
                                    </a>

                                    <!-- زر لطباعة جميع المصروفات بنفس الشكل -->
                                    <button onclick="printAllExpenses()" class="btn btn-outline-primary" 
                                            style="font-size: 17px; width: 300px; height: 40px;">
                                        طباعة جميع المصروفات
                                    </button>
                                </div>



                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
                                <thead>
                                    <tr>
                                        <th class="text-center">تسلسل</th>
                                        <th class="text-center">الرقم</th>
                                        <th class="text-center">المصروف</th>
                                        <th class="text-center">المجموعة</th>
                                        <th class="text-center">المورد</th>
                                        <th class="text-center">تاريخ</th>
                                        <th class="text-center">الاجمالي</th>
                                        <th class="text-center">الاحداث</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php $i=0 ?>
                                @foreach ($expenses as $expense)
                                    <?php $i++ ?>
                                    <tr>
                                        <td class="text-center">{{ $i }}</td>
                                        <td class="text-center">{{ $expense->exp_inv_number }}</td>
                                        <td class="text-center">{{ $expense->exp_name }}</td>
                                        <td class="text-center">{{ $expense->ECategory->cat_name }}</td>
                                        <td class="text-center">{{ $expense->exp_inv_name }}</td>
                                        <td class="text-center">{{ $expense->exp_date }}</td>
                                        <td class="text-center">{{ $expense->final_amount }}</td>
                                        <td>
                                            <!-- تعديل -->
                                            <a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
                                            data-id="{{ $expense->id }}" 
                                            data-exp_name="{{ $expense->exp_name }}"
                                            data-exp_inv_name="{{ $expense->exp_inv_name }}"
                                            data-exp_inv_number="{{ $expense->exp_inv_number }}"
                                            data-exp_inv_date="{{ $expense->exp_inv_date }}"
                                            data-exp_amount="{{ $expense->exp_amount }}"
                                            data-Discount="{{ $expense->Discount }}"
                                            data-tax_amount="{{ $expense->tax_amount }}"
                                            data-final_amount="{{ $expense->final_amount }}"
                                            data-description="{{ $expense->description }}"
                                            data-toggle="modal" href="#modaldemo8" title="تعديل"><i class="las la-pen"></i>
                                            </a>

                                            <!-- حذف -->
                                            <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale"
                                            data-id="{{ $expense->id }}" 
                                            data-toggle="modal" href="#modaldemo9" title="حذف"><i class="las la-trash"></i>
                                            </a>

                                            <!-- طباعة -->
                                            <a class="btn btn-sm btn-success" href="javascript:void(0);" onclick="printExpense({{ $expense->id }})" title="طباعة">
                                                <i class="las la-print"></i>
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

       

       
       <!-- Modal Edit -->
            <div class="modal" id="modaldemo8">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content modal-content-demo">
                        <div class="modal-header">
                            <h6 class="modal-title">تعديل مصروف</h6>
                            <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body">
                        <form method="POST" action="#">

                                @csrf
                                @method('PUT')

                                <input type="hidden" id="expense_id" name="id">

                                <div class="form-group">
                                    <label for="exp_name">اسم المصروف</label>
                                    <input type="text" class="form-control" id="exp_name" name="exp_name">
                                </div>

                                <div class="form-group">
                                    <label for="exp_inv_name">اسم الفاتورة المورد</label>
                                    <input type="text" class="form-control" id="exp_inv_name" name="exp_inv_name">
                                </div>

                                <div class="form-group">
                                    <label for="exp_inv_number">رقم فاتورة المورد</label>
                                    <input type="text" class="form-control" id="exp_inv_number" name="exp_inv_number">
                                </div>

                                <div class="form-group">
                                    <label for="exp_inv_date">تاريخ فاتورة المورد</label>
                                    <input type="date" class="form-control" id="exp_inv_date" name="exp_inv_date" required>
                                </div>

                                <div class="form-group">
                                    <label for="category_id">الفئات</label>
                                    <select class="form-control" id="category_id" name="category_id">
                                        <option value="" disabled selected>اختر الفئة</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->cat_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="exp_amount">المبلغ</label>
                                    <input type="number" class="form-control" id="exp_amount" name="exp_amount" step="0.01">
                                </div>

                                <div class="form-group">
                                    <label for="Discount">الخصم</label>
                                    <input type="number" class="form-control" id="Discount" name="Discount" value="{{ old('Discount', 0) }}">
                                </div>

                                <div class="form-group">
                                    <label for="tax_id">الضريبة</label>
                                    <select class="form-control" id="tax_id" name="tax_id">
                                        @foreach($taxes as $tax)
                                            <option value="{{ $tax->id }}" data-taxrate="{{ $tax->tax_rate }}">{{ $tax->tax_rate }}%</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="tax_amount">قيمة الضريبة</label>
                                    <input type="number" class="form-control" id="tax_amount" name="tax_amount" step="0.01" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="final_amount">المجموع النهائي</label>
                                    <input type="number" class="form-control" id="final_amount" name="final_amount" step="0.01" readonly>
                                </div>

                                <div class="form-group">
                                    <label for="description">الوصف</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>

                                <div class="form-group">
                                    <label for="created_by">اسم المستخدم</label>
                                    <input type="text" class="form-control" id="created_by" name="created_by" value="{{ Auth::user()->name }}" readonly>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">تحديث</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <!-- End Modal Edit-->

         <!-- Modal DELETE -->
        <div class="modal" id="modaldemo9">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content modal-content-demo">
                    <div class="modal-header">
                        <h6 class="modal-title">تأكيد الحذف</h6>
                        <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <form action="#" method="POST">
                            @csrf
                            @method('DELETE')
                            <input type="hidden" id="expense_id_delete" name="id">
                            <p>هل أنت متأكد من حذف هذا المصروف؟</p>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">حذف</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Modal DELETE-->

    </div>
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
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<script src="{{URL::asset('assets/js/modal.js')}}"></script>

    <script>
        document.getElementById('exp_inv_date').value = new Date().toISOString().split('T')[0];
    </script>

<script>
     // عند فتح الـ Modal للتعديل
    $('#modaldemo8').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var expenseId = button.data('id'); 
        var expName = button.data('exp_name');
        var expInvName = button.data('exp_inv_name');
        var expInvNumber = button.data('exp_inv_number');
        var expInvDate = button.data('exp_inv_date');
        var expAmount = button.data('exp_amount');
        var discount = button.data('Discount');
        var taxAmount = button.data('tax_amount');
        var finalAmount = button.data('final_amount');
        var description = button.data('description');

        // تحديث الحقول في الـ Modal
        $('#expense_id').val(expenseId);
        $('#exp_name').val(expName);
        $('#exp_inv_name').val(expInvName);
        $('#exp_inv_number').val(expInvNumber);
        $('#exp_inv_date').val(expInvDate);
        $('#exp_amount').val(expAmount);
        $('#Discount').val(discount);
        $('#tax_amount').val(taxAmount);
        $('#final_amount').val(finalAmount);
        $('#description').val(description);
    });

    $('#modaldemo9').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget);
        var expenseId = button.data('id');
        var modal = $(this);
        modal.find('#expense_id_delete').val(expenseId);
    });


</script>

<script>
    function printExpense(expenseId) {
        var expenses = @json($expenses);

        var selectedExpense = expenses.find(exp => exp.id === expenseId);
         var expenses = {!! $expenses->toJson(JSON_UNESCAPED_UNICODE) !!};

        if (selectedExpense) {
            console.log("البيانات المختارة:", selectedExpense); // للمراجعة

            var printWindow = window.open('', '', 'height=700,width700');
            printWindow.document.write('<html><head><title>طباعة المصروف</title>');
            printWindow.document.write('<style>');
            printWindow.document.write('@page { size: A4; margin: 20mm; }');
            printWindow.document.write('body { font-family: Arial, sans-serif; direction: rtl; padding: 10px; font-size: 14px; }');
            printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
            printWindow.document.write('th, td { padding: 8px; border: 1px solid #ddd; text-align: center; }');
            printWindow.document.write('th { background-color: #4CAF50; color: white; }');
            printWindow.document.write('tr:nth-child(even) { background-color: #f2f2f2; }');
            printWindow.document.write('h2 { text-align: center; margin-bottom: 20px; font-size: 18px; }');
            printWindow.document.write('p { font-size: 16px; line-height: 1.6; }');
            printWindow.document.write('.discount { color: red; text-decoration: underline; font-weight: bold; }');
            printWindow.document.write('.amount { color: green; font-weight: bold; }'); // ✅ كلاس اللون الأخضر
            printWindow.document.write('@media print { html, body { height: auto; overflow: visible; } }');
            printWindow.document.write('</style>');

            printWindow.document.write('</head><body>');

            // تاريخ اليوم
            var currentDate = new Date();
            var formattedDate = currentDate.toLocaleDateString('ar-EG', {
                year: 'numeric', month: 'long', day: 'numeric', weekday: 'long'
            });
            printWindow.document.write('<p style="text-align: center; font-size: 16px;">تاريخ الطباعة: ' + formattedDate + '</p>');

            printWindow.document.write('<h2>تفاصيل المصروف</h2>');
            printWindow.document.write('<table>');
            printWindow.document.write('<tr><th>البيان</th><th>التفاصيل</th></tr>');

            // كل صف يتم التحقق من وجود القيم فيه
            printWindow.document.write('<tr><td>اسم المصروف</td><td>' + (selectedExpense.exp_name ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>الفئة</td><td>' + (selectedExpense.e_category?.cat_name ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>اسم الفاتورة</td><td>' + (selectedExpense.exp_inv_name ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>رقم الفاتورة</td><td>' + (selectedExpense.exp_inv_number ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>تاريخ المصروف</td><td>' + (selectedExpense.exp_date ? new Date(selectedExpense.exp_date).toLocaleDateString('ar-EG') : '---') + '</td></tr>');
            printWindow.document.write('<tr><td>المبلغ</td><td class="amount">' + (selectedExpense.exp_amount ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>الخصم</td><td class="discount">' + (selectedExpense.Discount ?? '0') + '</td></tr>');
            printWindow.document.write('<tr><td>الضريبة</td><td>' + (selectedExpense.tax_amount ?? '---') + '</td></tr>');
            printWindow.document.write('<tr><td>الاجمالي</td><td>' + (selectedExpense.final_amount ?? '---') + ' ر.س</td></tr>');
            printWindow.document.write('<tr><td>الوصف</td><td>' + (selectedExpense.description ?? '---') + '</td></tr>');

            printWindow.document.write('</table>');

            printWindow.document.write('<p style="text-align: center; font-size: 18px; font-weight: bold; margin-top: 20px;">الإجمالي: ' + (selectedExpense.final_amount ?? '---') + ' ر.س</p>');

            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        } else {
            alert("لم يتم العثور على المصروف المحدد.");
        }
    }
</script>

<script>
    function printAllExpenses() {
        var expense = @json($expenses);
        var username = "{{ auth()->user()->name }}"; // اسم المستخدم من Laravel

        var printWindow = window.open('', '', 'height=800,width=900');
        printWindow.document.write('<html><head><title>طباعة جميع المصروفات</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('@page { size: A4; margin: 10mm; }');
        printWindow.document.write('body { font-family: Arial, sans-serif; direction: rtl; padding: 10px; font-size: 10px; line-height: 1.4; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; page-break-inside: avoid; }');
        printWindow.document.write('th, td { padding: 8px; border: 1px solid #ddd; text-align: center; vertical-align: middle; font-size: 11px; }');
        printWindow.document.write('th { background-color: #4CAF50; color: white; }');
        printWindow.document.write('tr:nth-child(even) { background-color: #f2f2f2; }');
        printWindow.document.write('h2 { text-align: center; margin-bottom: 20px; font-size: 12px; }');
        printWindow.document.write('p { font-size: 12px; margin-bottom: 10px; }');
        printWindow.document.write('.discount { color: red; font-weight: bold; }');
        printWindow.document.write('.amount { color: green; font-weight: bold; }');
        printWindow.document.write('.footer-note { margin-top: 50px; font-size: 12px; }');
        printWindow.document.write('.signature-block { display: flex; justify-content: space-between; margin-top: 50px; }');
        printWindow.document.write('.signature-block div { width: 45%; text-align: center; }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');

        var currentDate = new Date();
        var formattedDate = currentDate.toLocaleDateString('ar-EG', {
            year: 'numeric', month: 'long', day: 'numeric', weekday: 'long'
        });

        // اسم المستخدم + التاريخ
        printWindow.document.write('<p style="display: flex; justify-content: space-between; font-size: 12px;">');
        printWindow.document.write('<span>تاريخ الطباعة: ' + formattedDate + '</span>');
        printWindow.document.write('<span>اسم المستخدم: ' + username + '</span>');
        printWindow.document.write('</p>');

        // عنوان الجدول
        printWindow.document.write('<h2>تفاصيل جميع المصروفات</h2>');
        printWindow.document.write('<table>');
        printWindow.document.write('<tr>');
        printWindow.document.write('<th>الرقم</th>');
        printWindow.document.write('<th>اسم المصروف</th>');
        printWindow.document.write('<th>الفئة</th>');
        printWindow.document.write('<th>اسم الفاتورة</th>');
        printWindow.document.write('<th>رقم الفاتورة</th>');
        printWindow.document.write('<th>تاريخ الفاتورة</th>');
        printWindow.document.write('<th>المبلغ</th>');
        printWindow.document.write('<th>الخصم</th>');
        printWindow.document.write('<th>الضريبة</th>');
        printWindow.document.write('<th>الاجمالي</th>');
        printWindow.document.write('<th>الوصف</th>');
        printWindow.document.write('</tr>');

        var totalAmount = 0;

        expense.forEach(function(selectedExpense, index) {
            totalAmount += parseFloat(selectedExpense.final_amount || 0);

            printWindow.document.write('<tr>');
            printWindow.document.write('<td>' + (index + 1) + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.exp_name ?? '---') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.e_category?.cat_name ?? '---') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.exp_inv_name ?? '---') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.exp_inv_number ?? '---') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.exp_date ?? '---') + '</td>');
            printWindow.document.write('<td class="amount">' + (selectedExpense.exp_amount ?? '---') + '</td>');
            printWindow.document.write('<td class="discount">' + (selectedExpense.Discount ?? '0') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.tax_amount ?? '---') + '</td>');
            printWindow.document.write('<td>' + (selectedExpense.final_amount ?? '---') + ' ر.س</td>');
            printWindow.document.write('<td>' + (selectedExpense.description ?? '---') + '</td>');
            printWindow.document.write('</tr>');
        });

        printWindow.document.write('</table>');

        printWindow.document.write('<p style="font-size: 18px; font-weight: bold; margin-top: 20px; text-align: center;">الإجمالي الكلي: ' + totalAmount.toFixed(2) + ' ر.س</p>');

        // التواقيع
        printWindow.document.write('<div class="signature-block">');
        printWindow.document.write('<div>');
        printWindow.document.write('<p><strong>توقيع المدير:</strong></p>');
        printWindow.document.write('<p>.................................</p>');
        printWindow.document.write('</div>');
        printWindow.document.write('<div>');
        printWindow.document.write('<p><strong>توقيع المستخدم:</strong></p>');
        printWindow.document.write('<p>' + username + '</p>'); // ← توقيع تلقائي باسم المستخدم
        printWindow.document.write('</div>');
        printWindow.document.write('</div>');

        // ملاحظة
        printWindow.document.write('<div class="footer-note">');
        printWindow.document.write('<p><strong>ملاحظة:</strong> هذا التقرير للاستخدام الداخلي فقط.</p>');
        printWindow.document.write('</div>');

        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }
</script>

<script>
        document.addEventListener("DOMContentLoaded", function () {
            const expAmountInput = document.getElementById('exp_amount');
            const discountInput = document.getElementById('Discount');
            const taxAmountInput = document.getElementById('tax_amount');
            const finalAmountInput = document.getElementById('final_amount');
            const taxSelect = document.getElementById('tax_id');

            function calculateTotal() {
                let expAmount = parseFloat(expAmountInput.value) || 0;
                let discount = parseFloat(discountInput.value) || 0;
                let selectedTaxOption = taxSelect.querySelector('option:checked');
                let taxRate = parseFloat(selectedTaxOption ? selectedTaxOption.dataset.taxrate : 0);

                let taxAmount = (expAmount * taxRate) / 100;
                let finalAmount = expAmount + taxAmount - discount;

                taxAmountInput.value = taxAmount.toFixed(2);
                finalAmountInput.value = finalAmount.toFixed(2);
            }

            expAmountInput.addEventListener('input', calculateTotal);
            discountInput.addEventListener('input', calculateTotal);
            taxSelect.addEventListener('change', calculateTotal);
        });
</script>
@endsection
