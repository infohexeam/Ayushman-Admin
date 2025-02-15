@extends('layouts.app')
@section('content')

   <div class="row" style="min-height: 70vh;">
      <div class="col-md-12">
         <div class="card">
            <div class="card-header">
               <h3 class="mb-0 card-title">Create Medicine</h3>
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
               <form id="addFm" action="{{ route('medicine.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="row">
                     <div class="col-md-4">
                        <div class="form-group">
                           <label class="form-label">Medicine Name*</label>
                           <input type="text" class="form-control" required name="medicine_name" value="{{ old('medicine_name') }}" placeholder="Medicine Name">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label class="form-label">Medicine Code*</label>
                           <input type="text" class="form-control" required name="medicine_code" value="{{ old('medicine_code') }}" placeholder="Medicine Code">
                        </div>
                     </div>
                     <div class="col-md-4">
                        <div class="form-group">
                           <label class="form-label">Generic Name*</label>
                           <input type="text" class="form-control" required name="generic_name" value="{{ old('generic_name') }}" placeholder="Generic Name">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Item Type*</label>
                           <select class="form-control" name="item_type" id="item_type" required>
                              <option value="">Select Item Type</option>
                              @foreach($itemType as $masterId => $masterValue)
                              <option value="{{ $masterId }}" {{ old('item_type') == $masterId ? 'selected' : '' }}>{{ $masterValue }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Medicine Type*</label>
                           <select class="form-control" name="medicine_type" id="medicine_type" required>
                              <option value="">Select Medicine Type</option>
                              @foreach($medicineType as $masterId => $masterValue)
                              <option value="{{ $masterId }}" {{ old('medicine_type') == $masterId ? 'selected' : '' }}>{{ $masterValue }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Hsn Code </label>
                           <input type="text" class="form-control" name="Hsn_code" value="{{ old('Hsn_code') }}" placeholder="Hsn Code">
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Tax*</label>
                           <select class="form-control" name="tax_id" id="tax_id" required>
                              <option value="">Choose Tax</option>
                              @foreach($taxes as $tax_id => $tax)
                              <option value="{{ $tax_id }}" {{ old('tax_id') == $tax_id ? 'selected' : '' }}>{{ $tax }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Manufacturer*</label>
                           <select class="form-control" name="manufacturer" id="manufacturer" required>
                              <option value="">Select Manufacturer</option>
                              @foreach($Manufacturer as $masterValue)
                              <option value="{{ $masterValue->manufacturer_id }}" {{ old('manufacturer') == $masterValue->manufacturer_id ? 'selected' : '' }}>{{ $masterValue->name }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Unit Price* (Including GST)</label>
                           <input type="text" class="form-control" oninput="validateNumericValuedec(this);" required name="unit_price" value="{{ old('unit_price') }}" placeholder="Unit Price">

                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Description</label>
                           <textarea class="form-control" name="description" placeholder="Description">{{ old('description') }}</textarea>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Unit*</label>
                           <select class="form-control" name="unit_id" id="unit_id" required>
                              <option value="">Choose Unit</option>
                              @foreach($units as $unit_id => $unit)
                              <option value="{{ $unit_id }}" {{ old('unit_id') == $unit_id ? 'selected' : '' }}>{{ $unit }}</option>
                              @endforeach
                           </select>
                        </div>
                     </div>
                     <div class="col-md-6">
                        <div class="form-group">
                           <label class="form-label">Reorder Limit</label>
                           <input type="number" class="form-control" name="reorder_limit" pattern="[0-9]*" oninput="this.value = this.value.replace(/[^0-9]/g, '')"  maxlength="10" value="{{ old('reorder_limit') }}" placeholder="Reorder Limit">
                        </div>
                     </div>
                   
                     <div class="col-md-6">
                        <div class="form-group">
                           <div class="form-label">Status</div>
                           <label class="custom-switch">
                              <input type="hidden" name="is_active" value="0"> <!-- Hidden field for false value -->
                              <input type="checkbox" id="is_active" name="is_active" onchange="toggleStatus(this)" class="custom-switch-input" checked>
                              <span id="statusLabel" class="custom-switch-indicator"></span>
                              <span id="statusText" class="custom-switch-description">Active</span>
                           </label>
                        </div>
                     </div>
                  </div>
                  <div class="form-group">
                     <center>
                        <button type="submit" id="submitForm" class="btn btn-raised btn-primary">
                           <i class="fa fa-check-square-o"></i> Add</button>
                        <button type="reset" class="btn btn-raised btn-success">
                           Reset</button>
                        <a class="btn btn-danger" href="{{route('medicine.index')}}">Cancel</a>
                     </center>
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>

@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script src="https://cdn.jsdelivr.net/jquery.validation/latest/jquery.validate.min.js"></script>
<script>
   $(document).ready(function() {
      var validator = $("#addFm").validate({
         ignore: "",
         rules: {
            medicine_name: {
               required: true,
               maxlength: 100
            },
            generic_name: {
               required: true,
               maxlength: 100
            },
            unit_price: {
               required: true,
               number: true,
               maxlength: 10
            },
              unit_id: {
               required: true,
               number: true,
               maxlength: 10
            },
         },
         messages: {
            medicine_name: {
               required: 'Please enter medicine name.',
               maxlength: 'Medicine name must not exceed 100 characters.'
            },
            generic_name: {
               required: 'Please enter generic name.',
               maxlength: 'Generic name must not exceed 100 characters.'
            },
            unit_price: {
               required: 'Please enter unit price.',
               number: 'Please enter a valid integer.',
               maxlength: 'Unit price must not exceed 10 characters.'
            }, 
               unit_id: {
               required: 'Please select unit .',
            },
         },
         errorPlacement: function(label, element) {
            label.addClass('text-danger');
            label.insertAfter(element.parent().children().last());
         },
         highlight: function(element, errorClass) {
            $(element).parent().addClass('has-error');
            $(element).addClass('form-control-danger');
         },
         unhighlight: function(element, errorClass, validClass) {
            $(element).parent().removeClass('has-error');
            $(element).removeClass('form-control-danger');
         }
      });

      $(document).on('click', '#submitForm', function() {
         if (validator.form()) {
            $('#addFm').submit();
         } else {
            flashMessage('w', 'Please fill all mandatory fields');
         }
      });

      function flashMessage(type, message) {
         // Implement or replace this function based on your needs
         console.log(type, message);
      }
   });
   // impliment jQuery Validation 
   function toggleStatus(checkbox) {
      if (checkbox.checked) {
         $("#statusText").text('Active');
         $("input[name=is_active]").val(1); // Set the value to 1 when checked
      } else {
         $("#statusText").text('Inactive');
         $("input[name=is_active]").val(0); // Set the value to 0 when unchecked
      }
   }
</script>
<script>
   function validateNumericValue(input) {
      input.value = input.value.replace(/[^0-9.]/g, '');
   }
</script>
<script>
   function validateNumericValuedec(input) {
      input.value = input.value.replace(/[^0-9.]/g, ''); 
      input.value = input.value.replace(/^(\d*\.\d{0,2})\d*$/, '$1');
   }
</script>

@endsection