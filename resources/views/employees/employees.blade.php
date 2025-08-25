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
    قائمة الموظفين - ناينوكس
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-17 mr-2 mb-0">/  قائمة الموظفين</span>
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
        <div class="card mg-b-20">
            <div class="card-header pb-0">
                <div class="d-flex justify-content-between">
                <a href="{{ route('employees.create') }}" class="btn btn-success">إضافة موظف جديد</a>
                    <button onclick="printAllEmployees()" style="padding: 10px 20px; font-size: 16px; background-color: #3498db; color: white; border: none; cursor: pointer;">طباعة جميع الموظفين</button>
                    <i class="mdi mdi-dots-horizontal text-gray"></i>
                </div>
            </div>
            <div class="card-body">
                <table class="table text-md-nowrap display nowrap" id="example1" data-page-length='50'>
                    <thead>
                        <tr>
                            <th class="wd-5p border-bottom-0 text-center">تسلسل</th>
                            <th class="wd-15p border-bottom-0 text-center">إسم الموظف</th>
                            <th class="wd-10p border-bottom-0 text-center">رقم الموظف</th>
                            <th class="wd-15p border-bottom-0 text-center">رقم الهوية</th>
                            <th class="wd-10p border-bottom-0 text-center">الجوال</th>
                            <th class="wd-10p border-bottom-0 text-center">الراتب</th>
                            <th class="wd-10p border-bottom-0 text-center">البدلات</th>
                            <th class="wd-15p border-bottom-0 text-center">الأحداث</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $employee)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td> 
                            <td class="text-center">{{ $employee->emp_name }} </td>
                            <td class="text-center" style="color: red;">{{ $employee->emp_number }}</td>
                            <td class="text-center">{{ $employee->emp_id_number }}</td>
                            <td class="text-center">{{ $employee->emp_phone }}</td>
                            <td class="text-center" style="color: green; font-weight: bold;">{{ $employee->emp_salary }}</td>
                            <td class="text-center" style="color: green; font-weight: bold;">{{ $employee->emp_allowance }}</td>
                            <td class="text-center">
							<a class="modal-effect btn btn-sm btn-info" data-effect="effect-scale"
								data-id="{{ $employee->id }}" 
								data-emp_name="{{ $employee->emp_name }}" 
								data-emp_id_number="{{ $employee->emp_id_number }}"
								data-emp_age="{{ $employee->emp_age }}" 
								data-emp_salary="{{ $employee->emp_salary }}" 
								data-emp_allowance="{{ $employee->emp_allowance }}"
								data-emp_birth_date="{{ $employee->emp_birth_date }}" 
								data-emp_hire_date="{{ $employee->emp_hire_date }}"
								data-emp_phone="{{ $employee->emp_phone }}" 
								data-emp_mobile="{{ $employee->emp_mobile }}" 
								data-emp_email="{{ $employee->emp_email }}"
								data-emp_department="{{ $employee->emp_department }}" 
								data-emp_position="{{ $employee->emp_position }}" 
								data-emp_notes="{{ $employee->emp_notes }}"
								data-toggle="modal" href="#modaldemo8" title="تعديل">
								<i class="las la-pen"></i>
								</a>
                                <a class="modal-effect btn btn-sm btn-danger" data-effect="effect-scale" 
									data-id="{{ $employee->id }}" 
									data-emp_name="{{ $employee->emp_name }}" 
									data-toggle="modal" 
									href="#modaldemo9" 
									title="حذف">
									<i class="las la-trash"></i>
								</a>
                                <button onclick="window.open('{{ route('employees.print', $employee->id) }}', '_blank')" 
                                        class="btn btn-primary" 
                                        style="font-size: 5px; width: 40px; height: 25px;">
                                    طباعة  
                                </button>

                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-center" style="font-size: 20px; font-weight: bold;">
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


<!-- Modal for Edit Employee -->
@foreach ($employees as $employee)
<div class="modal" id="modaldemo8">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">تعديل بيانات الموظف</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <form action="{{ route('employees.update', $employee->id) }}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    {{ method_field('PUT') }} <!-- نحدد نوع الطلب PUT لأنه تعديل -->
                    
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="emp_name">اسم الموظف</label>
                            <input type="text" class="form-control" id="emp_name" name="emp_name" value="{{ $employee->emp_name }}" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="emp_id_number">رقم الهوية</label>
                            <input type="text" class="form-control" id="emp_id_number" name="emp_id_number" value="{{ $employee->emp_id_number }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="emp_age">العمر</label>
                            <input type="number" class="form-control" id="emp_age" name="emp_age" value="{{ $employee->emp_age }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="emp_salary">الراتب</label>
                            <input type="number" class="form-control" id="emp_salary" name="emp_salary" step="0.01" value="{{ $employee->emp_salary }}" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="emp_allowance">البدلات</label>
                            <input type="number" class="form-control" id="emp_allowance" name="emp_allowance" step="0.01" value="{{ $employee->emp_allowance }}">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="emp_birth_date">تاريخ الميلاد</label>
                           
                            <input type="text" name="emp_birth_date" id="emp_birth_date" class="form-control"
                            value="{{ old('emp_birth_date', $purchase->emp_birth_date ?? now()->toDateString()) }}" required style="text-align: center;">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="emp_hire_date">تاريخ التوظيف</label>
                           
                            <input type="text" name="emp_hire_date" id="emp_hire_date" class="form-control"
                            value="{{ old('emp_hire_date', $purchase->emp_hire_date ?? now()->toDateString()) }}" required style="text-align: center;">
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="emp_phone">الهاتف</label>
                            <input type="text" class="form-control" id="emp_phone" name="emp_phone" value="{{ $employee->emp_phone }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="emp_mobile">الجوال</label>
                            <input type="text" class="form-control" id="emp_mobile" name="emp_mobile" value="{{ $employee->emp_mobile }}">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="emp_email">الايميل</label>
                            <input type="email" class="form-control" id="emp_email" name="emp_email" value="{{ $employee->emp_email }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="emp_department">القسم</label>
                            <select class="form-control" id="emp_department" name="emp_department" required>
                                <option value="HR" {{ $employee->emp_department == 'HR' ? 'selected' : '' }}>الموارد البشرية</option>
                                <option value="Finance" {{ $employee->emp_department == 'Finance' ? 'selected' : '' }}>المالية</option>
                                <option value="IT" {{ $employee->emp_department == 'IT' ? 'selected' : '' }}>تقنية المعلومات</option>
                                <option value="Marketing" {{ $employee->emp_department == 'Marketing' ? 'selected' : '' }}>التسويق</option>
                                <option value="Sales" {{ $employee->emp_department == 'Sales' ? 'selected' : '' }}>المبيعات</option>
                                <option value="Operations" {{ $employee->emp_department == 'Operations' ? 'selected' : '' }}>العمليات</option>
                                <option value="Customer Service" {{ $employee->emp_department == 'Customer Service' ? 'selected' : '' }}>خدمة العملاء</option>
                                <option value="R&D" {{ $employee->emp_department == 'R&D' ? 'selected' : '' }}>البحث والتطوير</option>
                                <option value="Logistics" {{ $employee->emp_department == 'Logistics' ? 'selected' : '' }}>اللوجستيات</option>
                                <option value="Legal" {{ $employee->emp_department == 'Legal' ? 'selected' : '' }}>القانونية</option>
                                <option value="Procurement" {{ $employee->emp_department == 'Procurement' ? 'selected' : '' }}>المشتريات</option>
                                <option value="Administration" {{ $employee->emp_department == 'Administration' ? 'selected' : '' }}>الإدارة</option>
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="emp_position">الوظيفة</label>
                            <input type="text" class="form-control" id="emp_position" name="emp_position" value="{{ $employee->emp_position }}" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="emp_notes">ملاحظات</label>
                        <textarea class="form-control" id="emp_notes" name="emp_notes" rows="3">{{ $employee->emp_notes }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="emp_image">صورة الموظف</label>
                        <input type="file" class="form-control" id="emp_image" name="emp_image">
                        @if($employee->emp_image)
                            <img src="{{ asset('storage/' . $employee->emp_image) }}" alt="Employee Image" style="width: 100px; margin-top: 10px;">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="emp_id_image">صورة الهوية</label>
                        <input type="file" class="form-control" id="emp_id_image" name="emp_id_image">
                        @if($employee->emp_id_image)
                            <img src="{{ asset('storage/' . $employee->emp_id_image) }}" alt="ID Image" style="width: 100px; margin-top: 10px;">
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="emp_contract_image">صورة العقد</label>
                        <input type="file" class="form-control" id="emp_contract_image" name="emp_contract_image">
                        @if($employee->emp_contract_image)
                            <img src="{{ asset('storage/' . $employee->emp_contract_image) }}" alt="Contract Image" style="width: 100px; margin-top: 10px;">
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">حفظ التعديلات</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach

<!-- Modal for Delete Employee -->
<div class="modal" id="modaldemo9">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content modal-content-demo">
            <div class="modal-header">
                <h6 class="modal-title">تأكيد الحذف</h6>
                <button aria-label="Close" class="close" data-dismiss="modal" type="button"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">
                <p>هل أنت متأكد أنك تريد حذف الموظف <span id="employee_name" class="font-weight-bold"></span>؟</p>
                <form action="{{ route('employees.destroy', 'employee_id') }}" method="POST" id="deleteForm">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">نعم، حذف الموظف</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إلغاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                totalVat += parseFloat(data[5] || 0); // vat_value column index
                totalSum += parseFloat(data[6] || 0); // total_deu column index
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
    $('#modaldemo9').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var employeeId = button.data('id'); // استخراج معرّف الموظف
        var employeeName = button.data('emp_name'); // استخراج اسم الموظف
        
        var modal = $(this);
        modal.find('.modal-body #employee_name').text(employeeName); // عرض اسم الموظف في المودال
        var form = modal.find('form');
        var actionUrl = form.attr('action').replace('employee_id', employeeId);
        form.attr('action', actionUrl); // تحديث رابط الـ Form ليحمل معرّف الموظف الذي سيتم حذفه
    });
</script>

<script>
    $('#modaldemo8').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // الزر الذي تم النقر عليه
        var employeeId = button.data('id'); // استخراج معرّف الموظف
        var employeeName = button.data('emp_name'); // استخراج اسم الموظف
        var empIdNumber = button.data('emp_id_number');
        var empAge = button.data('emp_age');
        var empSalary = button.data('emp_salary');
        var empAllowance = button.data('emp_allowance');
        var empBirthDate = button.data('emp_birth_date');
        var empHireDate = button.data('emp_hire_date');
        var empPhone = button.data('emp_phone');
        var empMobile = button.data('emp_mobile');
        var empEmail = button.data('emp_email');
        var empDepartment = button.data('emp_department');
        var empPosition = button.data('emp_position');
        var empNotes = button.data('emp_notes');

        var modal = $(this);
        modal.find('.modal-body #emp_name').val(employeeName); // ملء حقل اسم الموظف
        modal.find('.modal-body #emp_id_number').val(empIdNumber); // ملء حقل رقم الهوية
        modal.find('.modal-body #emp_age').val(empAge); // ملء حقل العمر
        modal.find('.modal-body #emp_salary').val(empSalary); // ملء حقل الراتب
        modal.find('.modal-body #emp_allowance').val(empAllowance); // ملء حقل البدلات
        modal.find('.modal-body #emp_birth_date').val(empBirthDate); // ملء حقل تاريخ الميلاد
        modal.find('.modal-body #emp_hire_date').val(empHireDate); // ملء حقل تاريخ التوظيف
        modal.find('.modal-body #emp_phone').val(empPhone); // ملء حقل الهاتف
        modal.find('.modal-body #emp_mobile').val(empMobile); // ملء حقل الجوال
        modal.find('.modal-body #emp_email').val(empEmail); // ملء حقل الايميل
        modal.find('.modal-body #emp_department').val(empDepartment); // ملء حقل القسم
        modal.find('.modal-body #emp_position').val(empPosition); // ملء حقل الوظيفة
        modal.find('.modal-body #emp_notes').val(empNotes); // ملء حقل الملاحظات

        var formAction = modal.find('form').attr('action').replace('employees.update', 'employees.update/' + employeeId);
        modal.find('form').attr('action', formAction); // تعديل الرابط داخل النموذج ليناسب معرّف الموظف
    });
</script>


@endsection