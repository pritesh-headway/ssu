@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    {{-- <a style="float: right;" href="{{ route('customer.create')}}" class="btn btn-primary">Add Customer</a> --}}
    <form action="{{ url('import-coupons') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" required name="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        <button type="submit" class="btn btn-primary" style="font-size: 14px;">Import Data</button>
        <a class="btn btn-warning" href="{{ asset('sample/Customer-Coupons.xlsx') }}" style="font-size: 14px;">Download
            Sample
            Data</a>
    </form>
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <!-- <h2 class="mb-4" style="font-size: 2.0rem;">Events List</h2> -->
        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer Name</th>
                    <th>Assigned By</th>
                    <th>Event Year</th>
                    <th>City</th>
                    <th>Total No. of Coupons</th>
                    {{-- <th>Format</th> --}}
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<link rel="stylesheet" href="{{ URL::to('assets/css/buttons.dataTables.min.css') }}">
<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
<link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::to('assets/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::to('assets/js/jszip.min.js') }}"></script>
<script src="{{ URL::to('assets/js/buttons.html5.min.js') }}"></script>
<script src="{{ URL::to('assets/js/buttons.print.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{ URL::to('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        gb_DataTable = $("#myTable").DataTable({
            autoWidth: false,
            order: [0, "DESC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: true,
            ajax: "{{ route('customercoupon.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'customer_name',
                    name: 'customer_name'
                },
                {
                    data: 'assigned_name',
                    name: 'assigned_name'
                },
                {
                    data: 'event_date',
                    name: 'event_date'
                },
                {
                    data: 'city',
                    name: 'city'
                },
                {
                    data: 'totalCoupon',
                    name: 'totalCoupon'
                },
                // {
                //     data: 'assign_type',
                //     name: 'assign_type',
                //     "render": function(data, type, full, meta) {
                //         return "<span style=\"color:green\" height=\"50\" width=\"100\" /><b>"+data+"</b></span>";
                       
                //     }
                // },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                }
            ],
            dom: 'Blfrtip',
            buttons: [
                {
                    extend: 'excel',
                    text: 'Export to Excel',
                    className:'btn btn-success btn-sm',
                    filename: 'SSU_Customer_Coupons_Data', // Custom file name for Excel
                    title: 'Customers Coupons Orders List',
                    exportOptions: {
                        columns: ':not(.noExport)'
                    }
                }
            ],
            lengthMenu: [25, 50, 100]
        });
    });

    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: 'customer/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $('#myTable').DataTable().ajax.reload();
                    alert(response.success);
                }
            });
        }
    }
</script>
@endsection