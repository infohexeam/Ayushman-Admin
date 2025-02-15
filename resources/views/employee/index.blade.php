@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-md-12 col-lg-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Search Leave Request</h3>
            </div>
            <form action="{{ route('employee.index') }}" method="GET">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="from_date" class="form-label">From Date:</label>
                            <input type="date" id="from_date" name="from_date" class="form-control" value="{{ request('from_date') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="to_date" class="form-label">To Date:</label>
                            <input type="date" id="to_date" name="to_date" class="form-control" value="{{ request('to_date') }}">
                        </div>
                
                    
                    <div class="col-md-3 d-flex align-items-end">
                        <div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-filter" aria-hidden="true"></i> Filter</button>&nbsp;
                            <a class="btn btn-primary" href="{{ route('employee.index') }}"><i class="fa fa-times" aria-hidden="true"></i> Reset</a>
                        </div>
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
            <h3 class="card-title">Leave Request List</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('employee.create') }}" class="btn btn-block btn-info">
                <i class="fa fa-plus"></i>
                Create Leave Request
            </a>
            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered text-nowrap w-100 leave_request_table">
                    <thead>
                        <tr>
                            <th class="wd-15p">SL.NO</th>
                            <th class="wd-10p">Branch</th>
                            <th class="wd-10p">From date</th>
                            <th class="wd-10p">To Date</th>
                            <th class="wd-15p">No Of Leave Days</th>
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 0;
                        @endphp
                        @foreach($staffleaves as $staffleave)
                        @php
                            try {
                                $currentDate = now();
                                $fromDate = \Carbon\Carbon::parse($staffleave->from_date);
                                $toDate = \Carbon\Carbon::parse($staffleave->to_date);
                                $leaveCompleted = $currentDate->gt($toDate);
                                $leaveNotStarted = $currentDate->lt($fromDate);
                            } catch (\Exception $e) {
                                $leaveCompleted = false; 
                                $leaveNotStarted = false; 
                            }
                        @endphp
                        <tr id="dataRow_{{$staffleave->id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $staffleave->branch_name }}</td>
                            <td>{{ $staffleave->from_date }}</td>
                            <td>{{ $staffleave->to_date }}</td>
                            <td>{{ $staffleave->days}}</td>
                            <td>
                            @if(!$leaveCompleted && $currentDate->lt($toDate))
                                <a class="btn btn-primary btn-sm edit-custom" href="{{ route('employee.edit', $staffleave->id) }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                </a>
                                <form style="display: inline-block" action="{{ route('employee.destroy', $staffleave->id) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="button" onclick="deleteData({{ $staffleave->id }})" class="btn-danger btn-sm">
                                        <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                    </button>
                                </form>
                            @endif
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
                url: "{{ route('employee.destroy', '') }}/" + dataId,
                type: "DELETE",
                data: {
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    console.log(response.success);
                    // Handle the success response
                    if (response.success == true) {
                        // Display a success message using sweetalert
                        swal("Success", response.message, "success");

                        // Remove the row from the table
                        $("#dataRow_" + dataId).remove();
                    } else {
                        // Display an error message using sweetalert
                        swal("Error", "An error occurred! Please try again later.", "error");
                    }
                },
                error: function() {
                    // Display an error message using sweetalert
                    swal("Error", "An error occurred while deleting the data.", "error");
                },
            });
        } else {
            return;
        }
    });
}

</script>