@extends('layouts.master')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
@endsection

@section('title')
 {{ __('home.MainPage') }} 
@stop

@section('page-header')
  <!-- breadcrumb -->
  <div class="breadcrumb-header justify-content-between">
      <div class="my-auto">
          <div class="d-flex">
              <h5 class="content-title mb-0 my-auto">{{ __('home.Program Setting') }}</h5><span class="text-muted mt-1 tx-15 mr-2 mb-0">/{{ __('home.Preparation-institution') }}</span>
          </div>
      </div>    
  </div>
  <!-- breadcrumb -->
@endsection

@section('content')
  <div class="container mt-4">
      @if($company)
          <!-- إذا كانت بيانات الشركة موجودة -->
          <div class="card shadow-lg">
              <div class="card-body">
                  <form action="{{ route('company.update', $company->id) }}" method="POST" enctype="multipart/form-data" class="company-form">
                      @csrf
                      @method('PUT')

                      <!-- الشعار -->
                      <div class="form-group d-flex align-items-center mb-4">
                          <label for="logo" class="mr-3">{{ __('home.Logo') }}</label>
                          <div class="logo-preview">
                              @if($company->logo)
                                  <img src="{{ asset('storage/logos/' . $company->logo) }}" alt="شعار المؤسسة" class="img-fluid" style="max-width: 150px; border-radius: 8px;">
                              @else
                                  <p>{{ __('home.No File Chosen') }}</p>
                              @endif
                          </div>
                          <!-- حقل تحميل الشعار --> 
                      </div>
                      <div class="main-img-user profile-user">
                        
							<img alt="" src="{{ asset('storage/' . $company->logo) }}"><a class="fas fa-camera profile-edit" href="JavaScript:void(0);"></a>
						</div>
                        <input type="file" name="logo" id="logo" accept="image/*" class="ml-1">
										
                      <!-- الحقول الأخرى -->
                      <div class="form-group">
                          <label for="company_name">{{ __('home.Company Name') }}</label>
                          <input type="text" name="company_name" id="company_name" value="{{ $company->company_name }}" placeholder="{{ __('home.Company Name') }}" required class="form-control">
                      </div>

                      <div class="row">
                          <div class="form-group col-md-6">
                              <label for="tax_number">{{ __('home.VAT Number') }}</label>
                              <input type="text" name="tax_number" id="tax_number" value="{{ $company->tax_number }}" placeholder="{{ __('home.VAT Number') }}" required class="form-control">
                          </div>

                          <div class="form-group col-md-6">
                              <label for="commercial_register">{{ __('home.Comerchal No') }}</label>
                              <input type="text" name="commercial_register" id="commercial_register" value="{{ $company->commercial_register }}" placeholder="{{ __('home.Comerchal No')}}" required class="form-control">
                          </div>
                      </div>

                      <div class="row">
                      <div class="form-group col-md-6">
                              <label for="email">{{ __('home.Email') }}</label>
                              <input type="text" name="email" id="email" value="{{ $company->email }}" placeholder="{{ __('home.Email') }}" required class="form-control">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="phone">{{ __('home.Telphone') }}</label>
                              <input type="text" name="phone" id="phone" value="{{ $company->phone }}" placeholder="{{ __('home.Telphone') }}" required class="form-control">
                          </div>

                          <div class="form-group col-md-3">
                              <label for="mobile">{{ __('home.Phone') }}</label>
                              <input type="text" name="mobile" id="mobile" value="{{ $company->mobile }}" placeholder="{{ __('home.Phone') }}" required class="form-control">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="address">{{ __('home.Address') }}</label>
                          <input type="text" name="address" id="address" value="{{ $company->address }}" placeholder="{{ __('home.Address') }}" required class="form-control">
                      </div>

                      <div class="form-group">
                          <label for="notes">{{ __('home.Notes') }}</label>
                          <textarea name="notes" id="notes" placeholder="{{ __('home.Notes') }}" class="form-control">{{ $company->notes }}</textarea>
                      </div>

                      <button type="submit" class="btn btn-success w-100 mt-3">{{ __('home.Data Edit') }}</button>
                  </form>
              </div>
          </div>
      @else
          <!-- إذا كانت بيانات الشركة غير موجودة (حالة الإضافة) -->
          <div class="card shadow-sm">
              <div class="card-header bg-secondary text-white">
                  <h5 class="card-title">{{ __('home.Add Company Data') }}</h5>
              </div>
              <div class="card-body">
                  <form action="{{ route('company.store') }}" method="POST" enctype="multipart/form-data" class="company-form">
                      @csrf

                      <!-- الشعار -->
                      <div class="form-group d-flex align-items-center mb-4">
                          <label for="logo" class="mr-3">{{ __('home.Logo') }}</label>
                          <div class="logo-preview">
                              <p>{{ __('home.No File Chosen') }}</p>
                          </div>
                          <!-- حقل تحميل الشعار -->
                          <input type="file" name="logo" id="logo" accept="image/*" class="ml-3">
                      </div>

                      <!-- الحقول الأخرى -->
                      <div class="form-group">
                          <label for="company_name"> {{ __('home.Company Name') }}</label>
                          <input type="text" name="company_name" id="company_name" placeholder="{{ __('home.Company Name') }} " required class="form-control">
                      </div>

                      <div class="row">
                          <div class="form-group col-md-6">
                              <label for="tax_number">{{ __('home.VAT Number') }} </label>
                              <input type="text" name="tax_number" id="tax_number" placeholder=" {{ __('home.VAT Number') }}" required class="form-control">
                          </div>

                          <div class="form-group col-md-6">
                              <label for="commercial_register">{{ __('home.Comerchal No') }} </label>
                              <input type="text" name="commercial_register" id="commercial_register" placeholder=" {{ __('home.Comerchal No') }}" required class="form-control">
                          </div>
                      </div>

                      <div class="row">
                      <div class="form-group col-md-6">
                              <label for="email">{{ __('home.Email') }}</label>
                              <input type="text" name="email" id="email" placeholder="{{ __('home.Email') }}" required class="form-control">
                          </div>
                          <div class="form-group col-md-3">
                              <label for="phone">{{ __('home.Telphone') }}</label>
                              <input type="text" name="phone" id="phone" placeholder="{{ __('home.Telphone') }}" required class="form-control">
                          </div>

                          <div class="form-group col-md-3">
                              <label for="mobile">{{ __('home.Phone') }}</label>
                              <input type="text" name="mobile" id="mobile" placeholder="{{ __('home.Phone') }}" required class="form-control">
                          </div>
                      </div>

                      <div class="form-group">
                          <label for="address">{{ __('home.Address') }}</label>
                          <input type="text" name="address" id="address" placeholder="{{ __('home.Address') }}" required class="form-control">
                      </div>

                      <div class="form-group">
                          <label for="notes">{{ __('home.Notes') }}</label>
                          <textarea name="notes" id="notes" placeholder="{{ __('home.Notes') }}" class="form-control"></textarea>
                      </div>

                      <button type="submit" class="btn btn-primary w-100 mt-3">{{ __('home.Save Company Data') }} </button>
                  </form>
              </div>
          </div>
      @endif
  </div>
@endsection

@section('js')
@endsection

<style>
  /* تخصيص النموذج باستخدام CSS */
  .company-form {
      width: 100%;
      background-color: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .form-group label {
      font-weight: bold;
  }

  .submit-btn, .btn {
      border-radius: 8px;
      padding: 12px 25px;
  }

  .btn:hover {
      opacity: 0.9;
  }

  .logo-preview img {
      max-width: 150px;
      border-radius: 8px;
  }
</style>
