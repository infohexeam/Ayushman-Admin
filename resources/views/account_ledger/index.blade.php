@extends('layouts.app')

@section('content')
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
        <h3 class="card-title">{{$pageTitle}}</h3>
    </div>
    <div class="card-body">
        <a href="{{ route('account.ledger.create') }}" class="btn btn-block btn-info">
            <i class="fa fa-plus"></i>
            Create {{$pageTitle}}
        </a>
        <div class="table-responsive">
        <table id="example" class="table table-striped table-bordered text-nowrap w-100">
                <thead>
                    <tr>
                        
                        <th class="wd-15p">SL.NO</th>
                         <th class="wd-15p">Group Name</th>
                        <th class="wd-15p">Sub Group Name</th>
                        <th class="wd-15p">Ledger Code</th>
                        <th class="wd-15p">Ledger Name</th>
                        <th class="wd-15p">Status</th>
                        <th class="wd-15p">Is System</th>
                        <th class="wd-15p">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 0;
                    @endphp
                    @foreach($account_ledgers as $account_ledgers)
                    <tr id="dataRow_{{ $account_ledgers->id }}">
                        <td id="sl_no">{{ ++$i }}</td>
                        <td> {{ @$account_ledgers->subGroups->Group['account_group_name'] }} </td>
                        <td>{{ $account_ledgers->account_sub_group_name }}</td>
                        <td>{{ $account_ledgers->ledger_code }}</td>
                        <td>{{ $account_ledgers->ledger_name }}</td>
                        <td>
                            <button type="button" style="width: 70px;"  onclick="changeStatus({{ $account_ledgers->id }})" class="btn btn-sm @if($account_ledgers->is_active == 0) btn-danger @else btn-success @endif">
                                @if($account_ledgers->is_active == 0)
                                Inactive
                                @else
                                Active
                                @endif
                            </button>
                        </td>
                        <td>
                            <button type="button" style="width: 70px;">
                                @if($account_ledgers->subGroups['is_system'] == 0)
                                No
                                @else
                                Yes
                                @endif
                            </button>
                        </td>
                        <td>
                            @if($account_ledgers->subGroups['is_system'] == 0)
                            <a class="btn btn-secondary" href="{{ route('account.ledger.edit', $account_ledgers->id) }}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit </a>
                            <button type="button" onclick="deleteData({{ $account_ledgers->id }})" class="btn btn-danger">
                                <i class="fa fa-trash" aria-hidden="true"></i> Delete
                            </button>
                            @else
                            System Defined
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
@section('js')

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
                    url: "{{ route('account.ledger.destroy', '') }}/" + dataId,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response == '1') {
                        $("#dataRow_" + dataId).remove();
                        i = 0; 
                        $("#example tbody tr").each(function() {
                            i++;
                            $(this).find("td:first").text(i);
                        });
                        flashMessage('s', 'Data deleted successfully');
                    } else {
                            flashMessage('e', 'An error occurred! Please try again later.');
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
                        url: "{{ route('account.ledger.changeStatus', '') }}/" + dataId,
                        type: "patch",
                        data: {
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            if (response == '1') {
                                var cell = $('#dataRow_' + dataId).find('td:eq(4)');

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
</script>

@endsection
