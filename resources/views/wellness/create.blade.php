@extends('layouts.app')

@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <div class="row" style="min-height: 70vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Create Wellness</h3>
                </div>

                <div class="col-lg-12" style="background-color: #fff;">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <!-- <strong>Whoops!</strong> There were some problems with your input.<br><br> -->
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <form action="{{ route('wellness.store') }}" method="POST" enctype="multipart/form-data" id="myForm"> 
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness Name*</label>
                                    <input type="text" class="form-control" required name="wellness_name" value="{{ old('wellness_name') }}" placeholder="Wellness Name" maxlength="100">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness Description*</label>
                                    <textarea class="form-control" required name="wellness_description" required name="wellness_description" placeholder="Wellness Description">{{ old('wellness_description') }}</textarea>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Regular Price*</label>
                                   <input type="text" id="regularPrice" class="form-control" required name="wellness_cost" value="{{ old('wellness_cost') }}" placeholder="Wellness Price" oninput="validateDecimalInput(this)">


                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness Offer Price</label>
                                    <input type="text" class="form-control" id="offerPrice"  name="wellness_offer_price" value="{{ old('wellness_offer_price') }}" placeholder="Wellness Offer Price" oninput="validatePrices()">
                                    <span id="priceError" style="color: red;"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness Duration(Minutes)*</label>
                                    <input type="number" class="form-control" required name="wellness_duration" value="{{ old('wellness_duration') }}" placeholder="Wellness Duration(Minutes)" min="0" pattern="\d+">       
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group checkbox">
                                <label for="branch_id" class="form-label">
                                Wellness Image* 
                                <span style="color: #0bb15d;">(Size: 500x500, Supported Formats: .png, .jpeg, .jpg)</span>
                               </label>
                                    <input type="file" class="form-control" required name="wellness_image" value="{{ old('wellness_image') }}" placeholder="Wellness Image" accept="image/*">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Remarks</label>
                                    <textarea type="text" class="form-control" name="remarks" value="{{ old('remarks') }}" placeholder="Remarks"></textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group checkbox">
                                    <label for="branch_id" class="form-label">Branch*</label>
                                       @if(session()->has('pharmacy_id') && session()->has('pharmacy_name') && session('pharmacy_id') != "all")
                                        <select class="form-control" name="branch[]" id="pharmacy_id" required readonly>
                                            <option value="{{ session('pharmacy_id') }}">{{ session('pharmacy_name') }}</option>
                                        </select>
                                    @else
                                        <select class="multi-select" name="branch[]" multiple style="width: 100%;" >
                                            <option value="" selected disabled>Select a Branch</option>
                                            @foreach($pharmacies as $branchName)
                                                <option value="{{ $branchName->id }}">{{ $branchName->pharmacy_name }}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness Inclusions*</label>
                                    <textarea class="form-control" id="wellnessInclusion" required name="wellness_inclusions" placeholder="Wellness Inclusions">{{ old('wellness_inclusions') }}</textarea>
                                    <span style="color: red;">*Please provide wellness inclusions using bullet points only.</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Wellness T&C*</label>
                                    <textarea class="form-control" id="termsandCondition" name="wellness_terms_conditions" placeholder="Wellness T&C">{{ old('wellness_terms_conditions') }}</textarea>
                                </div>
                            </div>
                            <!-- <div class="col-md-1">
                                <div class="form-group">
                                    <div class="form-label">Status</div>
                                    <label class="custom-switch">
                                        <input type="hidden" name="is_active" value="1">
                                        <input type="checkbox" id="is_active" value="1" name="is_active" onchange="toggleStatus(this)" class="custom-switch-input" checked>
                                        <span id="statusLabel" class="custom-switch-indicator"></span>
                                        <span id="statusText" class="custom-switch-description">Active</span>
                                    </label>
                                </div>
                            </div> -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="form-label">Status</div>
                                    <label class="custom-switch">
                                        <input type="hidden" name="is_active" value="0">
                                        <input type="checkbox" id="is_active" value="1" name="is_active" onchange="toggleStatus(this)" class="custom-switch-input" checked>
                                        <span id="statusLabel" class="custom-switch-indicator"></span>
                                        <span id="statusText" class="custom-switch-description">Active</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <center>
                                <button type="submit" class="btn btn-raised btn-primary">
                                    <i class="fa fa-check-square-o"></i> Add
                                </button>
                                <button type="reset" class="btn btn-raised btn-success">Reset</button>
                                <a class="btn btn-danger" href="{{ route('wellness.index') }}">Cancel</a>
                            </center>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.ckeditor.com/4.17.2/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script type="text/javascript">
    function validatePrices() {
      var regularPrice = parseFloat(document.getElementById('regularPrice').value);
      var offerPrice = parseFloat(document.getElementById('offerPrice').value);
      var priceError = document.getElementById('priceError');

      if (offerPrice >= regularPrice) {
         priceError.textContent = 'Offer Price must be less than Regular Price';
      } else {
         priceError.textContent = '';
      }
   }
    $(document).ready(function() {
        CKEDITOR.replace('wellnessInclusion', {
            removePlugins: 'image',
        });

        $(document).ready(function() {
            CKEDITOR.replace('termsandCondition', {
                removePlugins: 'image',
            });
        });

        function toggleStatus(checkbox) {
            if (checkbox.checked) {
                $("#statusText").text('Active');
                $("input[name=is_active]").val(1);
            } else {
                $("#statusText").text('Inactive');
                $("input[name=is_active]").val(0);
            }

        }
    });
    //js for dropdown:
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

@endsection