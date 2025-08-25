@extends('layouts.master')

@section('css')
@endsection

@section('title')
    طباعـة الموظـف - ناينوكس
@stop

@section('page-header')
				<!-- breadcrumb -->
				<div class="breadcrumb-header justify-content-between">
					<div class="my-auto">
						<div class="d-flex">
							<h4 class="content-title mb-0 my-auto">الموظفين</h4><span class="text-muted mt-1 tx-13 mr-2 mb-0">/ طباعـة موظـف</span>
						</div>
					</div>
				</div>
				<!-- breadcrumb -->
@endsection


@section('content')
    <!-- row -->
    <div class="row">
        <!-- resources/views/employees/print_employee.blade.php -->

        <!DOCTYPE html>
        <html lang="ar">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>طباعة بيانات الموظف</title>
            <style>
                body {
                    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                    direction: rtl;
                    margin: 0;
                    padding: 0;
                    background-color: #f4f7fc;
                    color: #333;
                }

                .container {
                    max-width: 900px;
                    margin: 30px auto;
                    background-color: #fff;
                    border-radius: 8px;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                    padding: 30px;
                }

                h2 {
                    text-align: center;
                    color: #4CAF50;
                    font-size: 24px;
                    margin-bottom: 20px;
                    font-weight: 600;
                }

                .employee-header {
                    text-align: center;
                    font-size: 22px;
                    color: #333;
                    margin-bottom: 20px;
                    font-weight: bold;
                }

                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }

                table, th, td {
                    border: 1px solid #ddd;
                }

                th, td {
                    padding: 12px;
                    text-align: center; /* وسط البيانات */
                    font-size: 16px;
                }

                th {
                    background-color: #4CAF50;
                    color: white;
                }

                tr:nth-child(even) {
                    background-color: #f2f2f2;
                }

                .btn-print {
                    display: block;
                    width: 100%;
                    padding: 10px;
                    background-color: #4CAF50;
                    color: white;
                    font-size: 16px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    text-align: center;
                    margin-top: 30px;
                    transition: background-color 0.3s ease;
                }

                .btn-print:hover {
                    background-color: #45a049;
                }

                @media print {
                    /* إخفاء العناصر عند الطباعة */
                    .btn-print, .employee-header {
                        display: none;
                    }

                    body {
                        background-color: white;
                        margin: 0;
                        padding: 0;
                    }

                    .container {
                        margin: 0;
                        box-shadow: none;
                        padding: 0;
                    }

                    table {
                        border: 1px solid #ddd;
                    }
                    th, td {
                        border: 1px solid #ddd;
                        text-align: center; /* وسط البيانات أثناء الطباعة */
                    }
                }
            </style>
        </head>

        <body>

            <div class="container">
                <!-- إضافة العنوان مع اسم الموظف -->
                <div class="employee-header">
                    <h2>بيانات الموظف - {{ $employee->emp_name }}</h2>
                </div>

                <table>
                    <tr>
                        <th>رقم الموظف</th>
                        <td>{{ $employee->emp_number }}</td>
                    </tr>
                    <tr>
                        <th>رقم الهوية</th>
                        <td>{{ $employee->emp_id_number }}</td>
                    </tr>
                    <tr>
                        <th>الراتب</th>
                        <td>{{ number_format($employee->emp_salary, 2) }} ريال</td>
                    </tr>
                    <tr>
                        <th>العمر</th>
                        <td>{{ $employee->emp_age }} سنة</td>
                    </tr>
                    <tr>
                        <th>تاريخ الميلاد</th>
                        <td>{{ $employee->emp_birth_date }}</td>
                    </tr>
                    <tr>
                        <th>تاريخ التوظيف</th>
                        <td>{{ $employee->emp_hire_date }}</td>
                    </tr>
                    <tr>
                        <th>القسم</th>
                        <td>{{ $employee->emp_department }}</td>
                    </tr>
                    <tr>
                        <th>الوظيفة</th>
                        <td>{{ $employee->emp_position }}</td>
                    </tr>
                    <tr>
                        <th>حالة الموظف</th>
                        <td>{{ $employee->emp_status == 'active' ? 'نشط' : 'غير نشط' }}</td>
                    </tr>
                </table>

                <button class="btn-print" onclick="window.print()">طباعة</button>
            </div>

        </body>

        </html>
    </div>
    <!-- row closed -->
@endsection



@section('js')
@endsection