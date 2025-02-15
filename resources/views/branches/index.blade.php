@extends('layouts.app')
@section('content')
    <style>
        .fa-eye:before {
            color: #fff !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Branch Search</h3>
                </div>
                <form action="{{ route('branches') }}" method="GET">

                    <div class="card-body border">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Branch Code</label>
                                    <input type="text" id="branch-code" name="branch_code" class="form-control"
                                        value="{{ request('branch_code') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Branch Name</label>
                                    <input type="text" id="branch-name" name="branch_name" class="form-control"
                                        value="{{ request('branch_name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"
                                                aria-hidden="true"></i> Filter</button> &nbsp;
                                        <a class="btn btn-primary" href="{{ route('branches') }}"><i class="fa fa-times"
                                                aria-hidden="true"></i> Reset</a>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="card-header">
                    <h3 class="card-title">List Branches</h3>
                </div>
                <div class="card-body">
                    <a href="{{ route('branches.create') }}" class="btn btn-block btn-info">
                        <i class="fa fa-plus"></i>
                        Create Branch
                    </a>
                    <div class="table-responsive">
                        <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th class="wd-15p">SL.NO</th>
                                    <th class="wd-15p">Branch<br>Code</th>
                                    <th class="wd-15p">Branch Name</th>
                                    <th class="wd-15p">Contact</th>
                                    <th class="wd-15p">Branch Admin</th>
                                    <th class="wd-20p">Status</th>
                                    <th class="wd-15p">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($branches as $branch)
                                    <tr id="dataRow_{{ $branch->branch_id }}">
                                        <td>{{ ++$i }}</td>
                                        <td>{{ $branch->branch_code }}</td>
                                        <td>{{ $branch->branch_name }}</td>
                                        <td>{{ $branch->branch_contact_number }}</td>
                                        <td>Name: {{ $branch->branch_admin_name }}<br>
                                            Contact: {{ $branch->branch_admin_contact_number }}
                                        </td>
                                        <td>

                                            <button type="button" style="width: 70px;"
                                                onclick="changeStatus({{ $branch->branch_id }})"
                                                class="btn btn-sm @if ($branch->is_active == 0) btn-danger @else btn-success @endif">
                                                @if ($branch->is_active == 0)
                                                    Inactive
                                                @else
                                                    Active
                                                @endif
                                            </button>

                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm edit-custom"
                                                href="{{ route('branches.edit', $branch->branch_id) }}"><i
                                                    class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </a>
                                            <a class="btn btn-secondary btn-sm"
                                                href="{{ route('branches.show', $branch->branch_id) }}">
                                                <i class="fa fa-eye" aria-hidden="true"></i> View </a>
                                            <form style="display: inline-block"
                                                action="{{ route('branches.destroy', $branch->branch_id) }}"
                                                method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="button" onclick="deleteData({{ $branch->branch_id }})"
                                                    class="btn-danger btn-sm">
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
                        url: "{{ route('branches.destroy', '') }}/" + dataId,
                        type: "DELETE",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            handleDeleteResponse(response, dataId);
                        },
                        error: function() {
                            alert('An error occurred while deleting the branch.');
                        },
                    });
                }
            });
    }

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
                        url: "{{ route('branches.changeStatus', '') }}/" + dataId,
                        type: "patch",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response == '1') {
                                var cell = $('#dataRow_' + dataId).find('td:eq(5)');

                                if (cell.find('.btn-success').length) {
                                    cell.html(
                                        '<button type="button" style="width: 70px;"  onclick="changeStatus(' +
                                        dataId +
                                        ')" class="btn btn-sm btn-danger">Inactive</button>');
                                } else {
                                    cell.html(
                                        '<button type="button" style="width: 70px;"  onclick="changeStatus(' +
                                        dataId + ')" class="btn btn-sm btn-success">Active</button>'
                                        );
                                }
                              
                                $.growl.notice({
                                    message: "Status updated"
                                });
                            } else {
                                $.growl.error({
                                    title: "Oops!",
                                    message: "Something went wrong"
                                    });
                            }
                        },
                        error: function() {
                            $.growl.error({
                                title: "Oops!",
                                message: "Something went wrong"
                            });
                        },
                    });
                }
            });
    }
    


    function handleDeleteResponse(response, dataId) {
        console.log(response.success);
        if (response.success) {
            swal("Success", response.message, "success");
            $("#dataRow_" + dataId).remove();
        } else {
            swal("Error", "An error occurred! Please try again later.", "error");
        }
    }

    setTimeout(function() {
        $('.alert-success').fadeOut('slow');
    }, 2000);
</script>
