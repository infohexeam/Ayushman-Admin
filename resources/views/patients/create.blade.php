@extends('layouts.app')

@section('content')
    <div class="row" style="min-height: 70vh;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 card-title">Create Patient</h3>
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
                    <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Name*</label>
                                    <input type="text" class="form-control" required name="patient_name" maxlength="100"
                                        value="{{ old('patient_name') }}" placeholder="Patient Name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Email</label>
                                    <input type="email" class="form-control" value="{{ old('patient_email') }}" maxlength="200"
                                        name="patient_email" placeholder="Patient Email">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Mobile*</label>
                                    <input type="text" class="form-control" required name="patient_mobile"  maxlength="10" oninput="validateInput(this)"
                                        value="{{ old('patient_mobile') }}" placeholder="Patient Mobile">
                                        <p class="error-message" style="color: red; display: none;">Only numbers are allowed.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Address</label>
                                    <textarea class="form-control" name="patient_address" 
                                        placeholder="Patient Address">{{ old('patient_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_gender" class="form-label">Gender</label>
                                    <select class="form-control" name="patient_gender" id="patient_gender">
                                        <option value="">Choose Gender</option>
                                        @foreach($gender as $id => $gender)
                                        <option value="{{ $id }}" @if(old('patient_gender') == $id) selected @endif>{{ $gender }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Date Of Birth</label>
                                    <input type="date" class="form-control" name="patient_dob" id="patient_dob"
                                        placeholder="Patient Dob" value="{{ old('patient_dob') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="patient_blood_group_id" class="form-label">Blood Group</label>
                                    <select class="form-control" name="patient_blood_group_id"
                                        id="patient_blood_group_id">
                                        <option value="">Choose Blood Group</option>
                                        @foreach($bloodgroup as $id => $bloodgroup)
                                        <option value="{{ $id }}" @if(old('patient_blood_group_id') == $id) selected @endif>{{ $bloodgroup }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Emergency Contact Person</label>
                                    <input type="text" class="form-control" name="emergency_contact_person" maxlength="100"
                                        placeholder="Emergency Contact Person" value="{{ old('emergency_contact_person') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Emergency Contact</label>
                                    <input type="text" class="form-control" name="emergency_contact"  maxlength="10" oninput="validateInput(this)"
                                        placeholder="Emergency Contact" value="{{ old('emergency_contact') }}">
                                        <p class="error-message" style="color: red; display: none;">Only numbers are allowed.</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Marital Status</label>
                                    <select class="form-control" name="marital_status" id="marital_status">
                                        <option value="">Choose Marital Status</option>
                                        @foreach($maritialstatus as $masterId => $masterValue)
                                        <option value="{{ $masterId }}" @if(old('marital_status') == $masterId) selected @endif>{{ $masterValue }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Registration Type</label>
                                    <select class="form-control" name="patient_registration_type" id="patient_registration_type" required>
                                        <option value="self" selected>Self</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Whatsapp Number</label>
                                    <input type="text" class="form-control" value="{{ old('whatsapp_number') }}"  maxlength="10" oninput="validateInput(this)"
                                        name="whatsapp_number" placeholder="Whatsapp Number">
                                        <p class="error-message" style="color: red; display: none;">Only numbers are allowed.</p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Medical History</label>
                                    <textarea class="form-control" name="patient_medical_history" id="medicalHistory"
                                        placeholder="Medical History">{{ old('patient_medical_history') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Patient Current Medication</label>
                                    <textarea class="form-control" name="patient_current_medications" id="currentMedication"
                                        placeholder="Patient Current Medication">{{ old('patient_current_medications') }}</textarea>
                                </div>
                            </div>
                        </div>
                           <div class="row">
                              <div class="col-md-6">
                                 <div class="form-group">
                                    <div class="form-label">Status</div>
                                    <label class="custom-switch">
                                       <input type="hidden" name="is_active" value="0">
                                       <input type="checkbox" id="is_active" name="is_active" onchange="toggleStatus(this)" class="custom-switch-input" checked>
                                       <span id="statusLabel" class="custom-switch-indicator"></span>
                                       <span id="statusText" class="custom-switch-description">Active</span>
                                    </label>
                                 </div>
                              </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <div class="form-label">Has Credit</div>
                        <label class="custom-switch">
                            <input type="hidden" name="has_credit" value="0">
                            <input type="checkbox" id="has_credit" name="has_credit" onchange="toggleStatus1(this)" class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span id="statusText1" class="custom-switch-description">Inactive</span>
                        </label>
                    </div>
                </div>
                           </div>
                        <div class="form-group">
                            <center>
                                <button type="submit" class="btn btn-raised btn-primary">
                                    <i class="fa fa-check-square-o"></i> Add
                                </button>
                                <button type="reset" class="btn btn-raised btn-success">
                                    Reset
                                </button>
                                <a class="btn btn-danger" href="{{ route('patients.index') }}">Cancel</a>
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
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>


    
<script type="text/javascript">
 $(document).ready(function() {
        var currentDate = new Date().toISOString().split('T')[0];
        $('#patient_dob').attr('max', currentDate);
    });
    $(document).ready(function() {
        CKEDITOR.replace('medicalHistory', {
            removePlugins: 'image',
           
        });

        $(document).ready(function() {
        CKEDITOR.replace('currentMedication', {
            removePlugins: 'image',
           
        });

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
   function toggleStatus1(checkbox) {
      if (checkbox.checked) {
         $("#statusText1").text('Active');
      } else {
         $("#statusText1").text('Inactive');
      }
   }
</script>
<script>
    function validateInput(input) {
        var inputValue = input.value;

        // Remove any non-numeric characters from the input
        var numericValue = inputValue.replace(/[^0-9]/g, '');

        // Ensure the input does not exceed 10 characters
        if (numericValue.length > 10) {
            // Truncate the input to the first 10 digits
            numericValue = numericValue.slice(0, 10);
        }

        // Update the input value with the numeric value
        input.value = numericValue;

        // Check if the resulting value has exactly 10 digits
        if (numericValue.length !== 10) {
            input.setCustomValidity("Please enter exactly 10-digit numbers.");
            input.parentNode.querySelector('.error-message').style.display = 'block';
        } else {
            input.setCustomValidity("");
            input.parentNode.querySelector('.error-message').style.display = 'none';
        }
    }
    
    $(document).ready(function() {
        $('#patient_registration_type').on('mousedown', function(event) {
            event.preventDefault();
            this.blur();
            window.focus();
        });
    });
</script>


