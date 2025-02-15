@extends('layouts.app')

@section('content')
    <style>
        .dt-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        div.dt-buttons .dt-button {
            background-color: #27c533;
            border: 1px solid #fff;
            border-radius: 5px;
            color: #fff;
        }
    </style>
    <!-- exports starts -->
    <link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" rel="stylesheet" />
    <!-- exports ends -->
    <style>
        .fa-eye:before {
            color: #fff !important;
        }
    </style>
    <div class="row">
        <div class="col-md-12 col-lg-12">
            <button class="btn btn-blue displayfilter"><i class="fa fa-filter" aria-hidden="true"></i>
                <span>Show Filters</span></button>
            <div class="card displaycard ShowFilterBox">
                <div class="card-header">
                    <h3 class="card-title">Search Reports</h3>
                </div>
                <form action="{{ route('sales.return.report.detail', ['id' => $return_id]) }}" method="GET" class="card-body">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="medicine_name" class="form-label">Medicine</label>
                                    <input type="text" id="medicine_name" name="medicine_name" class="form-control"
                                        value="{{ request()->input('medicine_name') }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <center>
                                        <button type="submit" class="btn btn-primary"><i class="fa fa-filter"
                                                aria-hidden="true"></i>
                                            Filter</button>&nbsp;
                                        <a class="btn btn-primary"
                                            href="{{ route('sales.return.report.detail', ['id' => $return_id]) }}"><i
                                                class="fa fa-times" aria-hidden="true"></i> Reset</a>
                                    </center>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="col-md-6">
                    <h3 class="card-title">Sales Return Report Detail</h3>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a class="btn btn-raised btn-primary" href="{{route('sales.return.report')}}">BACK</a>
                     </div>
                </div>
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="report" class="table table-striped table-bordered text-nowrap w-100">
                            <thead>
                                <tr>
                                    <th>SL.NO</th>
                                    <th>Return<br>Number</th>
                                    <th>Medicine</th>
                                    <th>Batch</th>
                                    <th>Unit</th>
                                    <th>Return<br>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($sale_return_detail as $key => $sale_return)
                                    <tr id="dataRow_{{ $sale_return->sales_return_details_id    }}">
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ @$sale_return->returnInvoice['sales_return_no'] }}</td>
                                        <td>{{ @$sale_return->Medicine['medicine_name'] }}</td>
                                        <td>{{ $sale_return->batch_id }}</td>
                                        <td>{{ @$sale_return->Unit['unit_name'] }}</td>
                                        <td>{{ $sale_return->quantity }}
                                        </td>
                                        <td>{{ $sale_return->rate }}</td>
                                        <td>{{ $sale_return->amount }}</td> 
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </div>
        <script>
            $(document).ready(function() {

                $('#report').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excel',
                            text: 'Export to Excel',
                            title: 'Sales Return Report Detailed',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7]
                            }
                        },
                        {
                            extend: 'pdf',
                            text: 'Export to PDF',
                            title: 'Sales Return Report Detailed',
                            footer: true,
                            orientation: 'landscape',
                            pageSize: 'LEGAL',
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7],
                                alignment: 'right',
                            },
                            customize: function(doc) {
                                doc.content[1].margin = [100, 0, 100, 0]; //left, top, right, bottom
                                doc.content.forEach(function(item) {
                                    if (item.table) {
                                        item.table.widths = [40, '*', '*', '*', '*', '*', '*','*'
                                        ]
                                    }
                                })
                            }
                        }
                    ]
                });
            });
        </script>
    @endsection
