@extends('layouts.app')

@section('content')
<div class="row">
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
        @if ($message = Session::get('exists'))
        <div class="alert alert-danger">
            <p>{{$message}}</p>
        </div>
        @endif
        <div class="card-header">
            <h3 class="card-title">{{$pageTitle}}</h3>
        </div>
        <div class="card-body">
            <a href="{{ route('leave.type.create') }}" class="btn btn-block btn-info">
                <i class="fa fa-plus"></i>
                Create {{$pageTitle}}
            </a>



            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                    <thead>
                        <tr>
                            <th class="wd-15p">SL.NO</th>
                            <th class="wd-20p">Leave Types</th>
                            <th class="wd-15p">Status</th>
                            <th class="wd-15p">Deductible</th>
                            <th class="wd-15p">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                        $i = 0;
                        @endphp
                        @foreach($leave_types as $leave_type)
                        <tr id="dataRow_{{ $leave_type->leave_type_id }}">
                            <td>{{ ++$i }}</td>
                            <td>{{ $leave_type->name}}</td>
                            <td>
                                <button type="button" style="width: 70px;" @if($leave_type->is_system==0) onclick="changeStatus({{ $leave_type->leave_type_id }})" @endif class="btn btn-sm @if($leave_type->is_active == 0) btn-danger @else btn-success @endif">
                                    @if($leave_type->is_active == 0)
                                    Inactive
                                    @else
                                    Active
                                    @endif
                                </button>
                            </td>
                            <td>
                                <button type="button" style="width: 115px;" @if($leave_type->is_system==0) onclick="changeDeductible({{ $leave_type->leave_type_id }})" @endif class="btn btn-sm @if($leave_type->is_dedactable == 0) btn-danger @else btn-success @endif">
                                    @if($leave_type->is_dedactable == 0)
                                    Non-Deductible
                                    @else
                                    Deductible
                                    @endif
                                </button>
                            </td>
                            <td>
                            @if($leave_type->is_system==0)
                                <a class="btn btn-primary" href="{{ route('leave.type.edit', $leave_type->leave_type_id) }}">
                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                                </a>
                                <button type="button" onclick="deleteData({{ $leave_type->leave_type_id }})" class="btn btn-danger">
                                    <i class="fa fa-trash" aria-hidden="true"></i> Delete
                                </button>
                            </td>
                            @else
                            --
                            @endif
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
                text: "Are you sure you want to delete this data",
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
                        url: "{{ route('leave.type.destroy', '') }}/" + dataId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            // Handle the success response, e.g., remove the row from the table
                            if (response == '1') {
                                $("#dataRow_" + dataId).remove();
                                i = 0;
                                $("#example tbody tr").each(function() {
                                    i++;
                                    $(this).find("td:first").text(i);
                                });
                                flashMessage('s', 'Data deleted successfully');
                            } else {
                                flashMessage('e', 'An error occured! Please try again later.');
                            }
                        },
                        error: function() {
                            alert('An error occurred while deleting the qualification.');
                        },
                    });
                } else {
                    return;
                }
            });
    }
    // Change status 
    function changeStatus(dataId) {
        swal({
                title: "Change Status?",
                text: "Are you sure you want to change the status?",
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
                        url: "{{ route('leave.type.changeStatus', '') }}/" + dataId,
                        type: "patch",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response == '1') {
                                var cell = $('#dataRow_' + dataId).find('td:eq(2)');

                                if (cell.find('.btn-success').length) {
                                    cell.html('<button type="button" style="width: 70px;"  onclick="changeStatus(' + dataId + ')" class="btn btn-sm btn-danger">Inactive</button>');
                                } else {
                                    cell.html('<button type="button" style="width: 70px;"  onclick="changeStatus(' + dataId + ')" class="btn btn-sm btn-success">Active</button>');
                                }

                                flashMessage('s', 'Status changed successfully');
                            } else {
                                flashMessage('e', 'An error occurred! Please try again later.');
                            }
                        },
                        error: function() {
                            alert('An error occurred while changing the qualification status.');
                        },
                    });
                }
            });
    }

    // Change deductible status 
    function changeDeductible(dataId) {
        swal({
                title: "Change Deductible Status?",
                text: "Are you sure you want to change the deductible status?",
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
                        url: "{{ route('leave.type.changeDeductible', '') }}/" + dataId,
                        type: "patch",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response == '1') {
                                var cell = $('#dataRow_' + dataId).find('td:eq(3)');

                                if (cell.find('.btn-success').length) {
                                    
                                    cell.html('<button type="button" style="width: 115px;" onclick="changeDeductible(' + dataId + ')" class="btn btn-sm btn-danger">Deductible</button>');
                                } else {
                                    cell.html('<button type="button" style="width: 115px;" onclick="changeDeductible(' + dataId + ')" class="btn btn-sm btn-success">Non-Deductible</button>');
                                }

                                flashMessage('s', 'Deductible status changed successfully');
                            } else {
                                flashMessage('e', 'An error occurred! Please try again later.');
                            }
                        },
                        error: function() {
                            alert('An error occurred while changing the qualification status.');
                        },
                    });
                }
            });
    }
</script>