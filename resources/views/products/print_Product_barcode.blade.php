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
       {{ __('home.MainPage12') }}
@stop

@section('page-header')
    <div class="breadcrumb-header justify-content-between">
        <div class="my-auto">
            <div class="d-flex">
                <h4 class="content-title mb-0 my-auto"> {{ __('home.BarcodePrint') }}</h4>
                <span class="text-muted mt-1 tx-17 mr-2 mb-0">/ {{ __('home.ProductList') }}</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <?php $company_name = $company->company_name; ?>
        @if(isset($company))
        <div id="companyName" style="display: none;">
            <h1>{{ $company->company_name }}</h1>
        </div>
        @else
            <p>لا توجد بيانات للشركة.</p>
        @endif


    <div class="row">
        <!-- جدول المنتجات -->
         
        <div class="card-body mt-4">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-md-nowrap" id="example1" data-page-length="50">
                    <thead>
                        <tr>
                            <th class="text-center" style=" width: 50px; ">{{ __('home.No.') }}</th>
                            <th class="text-center" style=" width: 250px;">{{ __('home.Product') }}</th>
                            <th class="text-center" style=" width: 100px;">{{ __('home.Barcode') }}</th>
                            <th class="text-center" style=" width: 40px;">{{ __('home.Price') }}</th>
                            <th class="text-center" style=" width: 80px;">{{ __('home.StoreQty') }}</th>
                            <th class="text-center" style=" width: 100px;">{{ __('home.Events') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; ?>
                        @foreach($products as $product)
                        <?php $i++; ?>
                            <tr>
                                <td class="text-center">{{ $i }}</td>
                                <td class="text-center">{{ $product->product_name }}</td>
                                <td class="text-center">{{ $product->product_barcode }}</td>
                                <td class="text-center">{{ $product->product_total_price }}</td>
                                <td class="text-center">{{ $product->product_quantity }}</td>
                                <td class="text-center">
                                    <button class="btn btn-primary show-barcode" 
                                        data-barcode="{{ $product->product_barcode }}" 
                                        data-name="{{ $product->product_name }}"
                                        data-price="{{ $product->product_total_price }}"
                                        style="font-size: 12px; padding: 5px 8px; width: 100%;">
                                        {{ __('home.BarcodeShow') }}
                                    </button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
             <!-- نموذج لتحديد الحجم وعدد الملصقات تحت الجدول -->
             <form id="barcodeForm" class="form-group mt-1">
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="width">{{ __('home.BarcodeWidth') }}</label>
                        <input type="number" id="width" name="width" value="650" min="100" max="1000" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="height">{{ __('home.BarcodeHeight') }}</label>
                        <input type="number" id="height" name="height" value="200" min="50" max="500" class="form-control">
                    </div>
                    <div class="col-md-4">
                        <label for="quantity">{{ __('home.LabelQuantity') }}</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="50" class="form-control">
                    </div>
                </div>
            </form>

        </div>

       
    </div>

    <!-- نافذة منبثقة لعرض الباركود -->
    <div id="barcodeModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="barcodeDisplay"></div>
        </div>
    </div>

    <style>
        .barcode-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .barcode-item {
            text-align: center;
            padding: 10px;
            border: 1px solid #ccc;
        }

        .table {
            margin-bottom: 30px;
        }

        /* Modal styling */
        #barcodeModal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        #barcodeModal .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 50%;
        }

        #barcodeModal .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        #barcodeModal .close:hover,
        #barcodeModal .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Styling for the form inputs */
        .form-group input[type="number"] {
            font-size: 16px;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            width: 100%;
            margin-top: 5px;
        }

        .form-group label {
            font-size: 16px;
            color: #555;
            font-weight: bold;
        }

        /* Button styling */
        .btn {
            font-size: 16px;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        /* Form styling to ensure it appears under the table */
        #barcodeForm {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-row .col-md-4 {
            margin-bottom: 20px;
        }

        /* Ensure the form is positioned below the table */
        .table-responsive {
            clear: both;
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
<!--Internal  Datatable js -->
<script src="{{URL::asset('assets/js/table-data.js')}}"></script>
<!-- Internal Modal js-->
<script src="{{URL::asset('assets/js/modal.js')}}"></script>

<script src="{{URL::asset('assets/js/modal.js')}}"></script>


<script>
     // عرض الباركود عند الضغط على الزر
     document.querySelectorAll('.show-barcode').forEach(button => {
         button.addEventListener('click', function() {
             const barcode = this.getAttribute('data-barcode');
             const name = this.getAttribute('data-name');
             const price = this.getAttribute('data-price');
             
             // الحصول على القيم من النموذج
             const width = document.getElementById('width').value;
             const height = document.getElementById('height').value;
             const quantity = document.getElementById('quantity').value;

             // تحديد اسم الشركة من خلال Blade
             const companyName = @json($company_name);

             // إنشاء محتوى الباركود بناءً على القيم المدخلة
             let barcodeContent = '';
             for (let i = 0; i < quantity; i++) {
                 barcodeContent += ` 
                     <div class="barcode-item">
                         <h2>${companyName}</h2> 
                         <h3>${name}</h3>
                         <img src="https://barcode.tec-it.com/barcode.ashx?data=${barcode}&code=Code128" 
                             alt="barcode" style="width: ${width}px; height: ${height}px;">
                       <h3>${price} ر.س</h3>
                     </div>
                 `;
             }

             // عرض الباركود في الـ modal
             const barcodeModal = document.getElementById('barcodeModal');
             const barcodeDisplay = document.getElementById('barcodeDisplay');
             barcodeDisplay.innerHTML = barcodeContent;
             barcodeModal.style.display = "block";
         });
     });

     // إغلاق الـ modal عند الضغط على "x"
     document.querySelector('.close').addEventListener('click', function() {
         document.getElementById('barcodeModal').style.display = "none";
     });

     // إغلاق الـ modal عند الضغط في أي مكان خارج الـ modal
     window.onclick = function(event) {
         if (event.target === document.getElementById('barcodeModal')) {
             document.getElementById('barcodeModal').style.display = "none";
         }
     }
 </script>

@endsection
