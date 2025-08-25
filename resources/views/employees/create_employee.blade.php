@extends('layouts.master')


@section('css')
@endsection

@section('title')
    إضافة الموظفين - ناينوكس
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ إضافة موظف</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection
@section('content')
				<!-- Container open -->
<div class="container">
    <div class="row">
        <!-- Card start -->
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">إضافة موظف جديد</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        
                        <!-- بداية الحقول داخل الـ Card -->
                        <div class="row">
							<div class="form-group col-md-2">
								<label for="emp_number">رقم الموظف</label>
								<input type="text" name="emp_number" id="emp_number" class="form-control"
									value="{{ old('emp_number', $newEmployeeNumber) }}" readonly style="text-align: center;">
							</div>
                            <div class="form-group col-md-6">
                                <label for="emp_name">اسم الموظف</label>
                                <input type="text" class="form-control" id="emp_name" name="emp_name" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emp_id_number">رقم الهوية</label>
                                <input type="text" class="form-control" id="emp_id_number" name="emp_id_number" required>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="emp_age">العمر</label>
                                <input type="number" class="form-control" id="emp_age" name="emp_age">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emp_salary">الراتب</label>
                                <input type="number" class="form-control" id="emp_salary" name="emp_salary" step="0.01" required>
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emp_allowance">البدلات</label>
                                <input type="number" class="form-control" id="emp_allowance" name="emp_allowance" step="0.01">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="emp_birth_date">تاريخ الميلاد</label>
                                <input type="date" class="form-control" id="emp_birth_date" name="emp_birth_date">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="emp_hire_date">تاريخ التوظيف</label>
                                <input type="date" class="form-control" id="emp_hire_date" name="emp_hire_date" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-4">
                                <label for="emp_phone">الهاتف</label>
                                <input type="text" class="form-control" id="emp_phone" name="emp_phone">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emp_mobile">الجوال</label>
                                <input type="text" class="form-control" id="emp_mobile" name="emp_mobile">
                            </div>
                            <div class="form-group col-md-4">
                                <label for="emp_email">الايميل</label>
                                <input type="email" class="form-control" id="emp_email" name="emp_email" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="emp_department">القسم</label>
                                <select class="form-control" id="emp_department" name="emp_department" required>
                                    <option value="HR">الموارد البشرية</option>
                                    <option value="Finance">المالية</option>
                                    <option value="IT">تقنية المعلومات</option>
                                    <option value="Marketing">التسويق</option>
                                    <option value="Sales">المبيعات</option>
                                    <option value="Operations">العمليات</option>
                                    <option value="Customer Service">خدمة العملاء</option>
                                    <option value="R&D">البحث والتطوير</option>
                                    <option value="Logistics">اللوجستيات</option>
                                    <option value="Legal">القانونية</option>
                                    <option value="Procurement">المشتريات</option>
                                    <option value="Administration">الإدارة</option>
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="emp_position">الوظيفة</label>
                                <input type="text" class="form-control" id="emp_position" name="emp_position" required>
                            </div>
                        
                        
                        <div class="form-group col-md-3">
                            <label for="emp_status">حالة الموظف</label>
                            <select class="form-control" id="emp_status" name="emp_status" required>
                                <option value="active" {{ old('emp_status', $employee->emp_status ?? '') == 'active' ? 'selected' : '' }}>نشط</option>
                                <option value="inactive" {{ old('emp_status', $employee->emp_status ?? '') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            </select>
                        </div>
						</div>
						<div class="row">
                        <div class="form-group col-md-12">
                            <label for="emp_notes">ملاحظات</label>
                            <textarea class="form-control" id="emp_notes" name="emp_notes" rows="3"></textarea>
                        </div>
						</div>
						<div class="row">
                        <div class="form-group col-md-">
                            <label for="emp_image">صورة الموظف</label>
                            <input type="file" class="form-control" id="emp_image" name="emp_image">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="emp_id_image">صورة الهوية</label>
                            <input type="file" class="form-control" id="emp_id_image" name="emp_id_image">
                        </div>

                        <div class="form-group col-md-4">
                            <label for="emp_contract_image">صورة العقد</label>
                            <input type="file" class="form-control" id="emp_contract_image" name="emp_contract_image">
                        </div>
						</div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" style="font-size: 15px; width: 300px; height: 40px;">حفظ</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Card end -->
    </div>
</div>
<!-- Container end -->

@endsection
@section('js')
@endsection