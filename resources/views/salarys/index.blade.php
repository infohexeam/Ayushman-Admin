@extends('layouts.app') @section('content') <div class="row">
  <div class="col-md-12 col-lg-12">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Salary Head</h3>
      </div>
      <form action="{{ route('salarys.index') }}" method="GET">
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-3">
              <label for="salary_head_name" class="form-label">Salary Head</label>
              <input type="text" id="salary_head_name" name="salary_head_name" class="form-control" value="{{ request('salary_head_name') }}">
            </div>
            <div class="col-md-3">
              <label for="salary_head_type" class="form-label">Salary Head Type</label>
              <select class="form-control" name="salary_head_type" id="salary_head_type">
                <option value="" disabled selected>Choose Salary Head Type</option> @foreach($branch as $branchName) <option value="{{ $branchName->id }}">{{ $branchName->salary_head_type }}</option> @endforeach
              </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-filter" aria-hidden="true"></i> Filter </button>
              <a class="btn btn-primary ml-2" href="{{ route('salarys.index') }}">
                <i class="fa fa-times" aria-hidden="true"></i> Reset </a>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<div class="card"> @if ($message = Session::get('success')) <div class="alert alert-success">
    <p>{{$message}}</p>
  </div> @endif @if ($message = Session::get('error')) <div class="alert alert-danger">
    <p>{{$message}}</p>
  </div> @endif <div id="flash-messages"></div>
  <div class="card-header">
    <h3 class="card-title">List Salary Head</h3>
  </div>
  <div class="card-body">
    <a href="{{ route('salarys.create') }}" class="btn btn-block btn-info">
      <i class="fa fa-plus"></i> Create Salary Head </a>
    <div class="table-responsive">
      <table id="example" class="table table-striped table-bordered text-nowrap w-100 leave_request_table">
        <thead>
          <tr>
            <th class="wd-15p">SL.NO</th>
            <th class="wd-10p">Salary Head Name</th>
            <th class="wd-10p">Salary Head Type</th>
            <th class="wd-15p">Status</th>
            <th class="wd-15p">Action</th>
          </tr>
        </thead>
        <tbody> @php $i = 0; @endphp @foreach($masters as $master) <tr id="dataRow_{{$master->id }}">
            <td>{{ ++$i }}</td>
            <td>{{ $master->salary_head_name }}</td>
            <td>{{ $master->salary_head_type }}</td>
            <td> @if($master->status == "1") Active @else Inactive @endif </td>
            <td>
              <a class="btn btn-secondary btn-sm" href="{{ route('salarys.show', $master->id) }}">
                <i class="fa fa-eye" aria-hidden="true"></i> View </a>
              <a class="btn btn-primary btn-sm edit-custom" href="{{ route('salarys.edit', $master->id) }}">
                <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </a>
              <form style="display: inline-block" action="#" method="post"> @csrf @method('delete') <button type="button" onclick="deleteData({{ $master->id }})" class="btn-danger btn-sm">
                  <i class="fa fa-trash" aria-hidden="true"></i> Delete </button>
              </form>
            </td>
          </tr> @endforeach </tbody>
      </table>
    </div>
    <!-- TABLE WRAPPER -->
  </div>
  <!-- SECTION WRAPPER -->
</div>
</div></div>
<!-- ROW-1 CLOSED -->
 @endsection 
 <script src="path/to/flash-message.js"></script>
 <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        function deleteData(dataId) {
            swal({
                title: "Delete selected data?",
                text: "Are you sure you want to delete this data?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes",
                cancelButtonText: "No",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function (isConfirm) {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ route('salarys.destroy', '') }}/" + dataId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            handleDeleteResponse(response, dataId);
                        },
                        error: function () {
                            // Display an error message using sweetalert
                            swal("Error", "An error occurred while deleting the data.", "error");
                        },
                    });
                }
            });
        }

        function handleDeleteResponse(response, dataId) {
            if (response.success) {
                // Display a success message using sweetalert
                swal("Success", response.message, "success");

                // Remove the row from the table
                $("#dataRow_" + dataId).remove();
            } else {
                // Display an error message using sweetalert
                swal("Error", response.message || "An error occurred! Please try again later.", "error");
            }
        }

        $(document).ready(function () {
            // Add any additional scripts or functions you may need
            // ...

            // Optional: Auto-hide success message after 2 seconds
            setTimeout(function () {
                $('.alert-success').fadeOut('slow');
            }, 2000);
        });
    </script>

