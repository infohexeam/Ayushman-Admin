@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search Holiday</h3>
            </div>
            <form action="{{ route('holidays.index') }}" method="GET">
                <div class="card-body">
                    <div class="row mb-3">
                    <div class="col-md-3">
                            <label for="staff_name" class="form-label">Holiday Name</label>
                            <input type="text" id="staff_name" name="holiday_name" class="form-control" value="{{ request('holiday_name') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">Year</label>
                            <input type="numbers" id="to_date" name="year" class="form-control" value="{{ request('year') }}">
                        </div>
                
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>&nbsp;
                            <a class="btn btn-primary" href="{{ route('holidays.index') }}"><i class="fa fa-times" aria-hidden="true"></i> Reset</a>
                        </div>
                    </div>
                </div>
        </div>
        </form>
    </div>
    <div class="card">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{$message}}</p>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-danger">
            <p>{{$message}}</p>
        </div>
        @endif
        <div class="card-header">
            <h3 class="card-title">Holiday List</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('holidays.create') }}" class="btn btn-block btn-info">
                <i class="fa fa-plus"></i>
                Create Holiday
            </a>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered text-nowrap w-100 leave_request_table">
                    <thead>
                        <tr>
                            <th class="wd-15p">SL.NO</th>
                            <th class="wd-10p">Holiday Name</th>
                            <th class="wd-10p">Year</th>
                            <th class="wd-15p">From Date</th>
                            <th class="wd-15p">To Date</th>
                            <th class="wd-15p">Holiday Type</th>
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 0;
                        @endphp
                        @foreach ($holidays as $holiday)
                        <tr id="dataRow_{{ $holiday->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $holiday->holiday_name }}</td>
                            <td>{{ $holiday->year }}</td>
                            <td>{{ $holiday->from_date }}</td>
                            <td>{{ $holiday->to_date }}</td>
                            <td>{{ $holiday->leave_type }}</td>
                            <td>
                                <a class="btn btn-secondary btn-sm" href="{{ route('holidays.staff-mapping', ['holiday_id' => $holiday->id]) }}">
                                    <i class="fa fa-eye" aria-hidden="true"></i> Link
                                </a>
                                <a class="btn btn-primary btn-sm edit-custom" href="{{ route('holidays.edit', $holiday->id) }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                </a>
                                <form style="display: inline-block" action="{{ route('holidays.destroy', $holiday->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="deleteData({{ $holiday->id }})" class="btn-danger btn-sm">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
            </div>
            <!-- TABLE WRAPPER -->
        </div>
        <!-- SECTION WRAPPER -->
    </div>
</div>
</div>
<!-- ROW-1 CLOSED -->
@endsection
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10"></link>
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
    function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                url: "{{ route('holidays.destroy', '') }}/" + dataId,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    handleDeleteResponse(response, dataId);
                },
                error: function() {
                    // Display an error message using sweetalert
                    swal("Error", "An error occurred while deleting the data.", "error");
                },
            });
        }
    });
}

function handleDeleteResponse(response, dataId) {
    console.log(response.success);
    // Handle the success response
    if (response.success) {
        // Display a success message using sweetalert
        swal("Success", response.message, "success");

        // Remove the row from the table
        $("#dataRow_" + dataId).remove();
    } else {
        // Display an error message using sweetalert
        swal("Error", "An error occurred! Please try again later.", "error");
    }
}

setTimeout(function(){
            $('.alert-success').fadeOut('slow');
        }, 2000); // 5000 milliseconds = 3 seconds
</script>
