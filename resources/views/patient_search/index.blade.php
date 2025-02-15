@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Patient Search</h3>
            </div>
           <form action="{{ route('patient_search.index') }}" method="GET">

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="pat-code">Patient Code</label>
                            <input type="text" id="pat-code" name="pat_code" class="form-control" value="{{ request('pat_code') }}" placeholder="Patient Code">
                        </div>
                        <div class="col-md-4">
                            <label for="pat-name">Patient Name</label>
                            <input type="text" id="pat-name" name="pat_name" class="form-control" value="{{ request('pat_name') }}" placeholder="Patient Name">
                        </div>
                         <div class="col-md-4">
                            <label for="pat-mobile">Patient Mobile</label>
                            <input type="text" id="pat-mobile" name="pat_mobile" class="form-control" value="{{ request('pat_mobile') }}" placeholder="Patient Mobile Number" oninput="restrictCharacters(this)">

                        </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-center">
                            <div>
                                <button type="submit" class="btn btn-secondary"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                                <a class="btn btn-secondary ml-2" href="{{ route('patient_search.index') }}"><i class="fa fa-times" aria-hidden="true"></i>Reset</a>
                            </div>
                        </div>
                    </div>
               
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List Patients</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                        <thead>
                            <tr>
                                <th class="wd-15p">SL.NO</th>
                                <th class="wd-15p">Patient Code</th>
                                <th class="wd-20p">Patient Name</th>
                                <th class="wd-15p">Patient Email</th>
                                <th class="wd-15p">Patient Mobile</th>
                                <th class="wd-15p">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 0;
                            @endphp
                            @foreach($patients as $patient)
                            <tr>
                                <td>{{ ++$i }}</td>
                                <td>{{ $patient->patient_code}}</td>
                                <td>{{ $patient->patient_name}}</td>
                                <td>{{ $patient->patient_email}}</td>
                                <td>{{ $patient->patient_mobile}}</td>
                                <td>
                                
                    <a class="btn btn-secondary" href="{{ route('patient_search.show', $patient->id) }}">
    <i class="fa fa-eye" aria-hidden="true"></i> View
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
</div>
@endsection
@section('js')
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
<!--<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

<script>
function restrictCharacters(input) {
    input.value = input.value.replace(/[^\d]/g, ''); // Remove all non-numeric characters
    
      if (input.value.length > 10) {
        input.value = input.value.slice(0, 10);
    }
}

</script>
