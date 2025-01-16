@extends('layouts.master')
@section('content')
<style>
    button.delete.btn.btn-danger.btn-sm {
        font-size: 10px;
        margin-top: 2px;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">

    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        @if(session()->has('failures'))
        <ul class="alert alert-danger">
            @foreach (session('failures') as $failure)
            @foreach ($failure->errors() as $error)
            <li>{{ $error }}</li>
            @endforeach
            @endforeach
        </ul>

        @endif
        <!-- <h2 class="mb-4" style="font-size: 2.0rem;">Events List</h2> -->

        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Seller Name</th>
                    <th>Used Points</th>
                    <th>Remaining Points</th>
                    <th>Total Points</th>
                    <th>Total Coupons</th>
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
            searchDelay: 100,
            pageLength: 25,
            paging: false,
            ajax: "{{ route('sellerpoints.index') }}",
            iDisplayLength: "500",
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'seller_name',
                    name: 'seller_name',
                },
                {
                    data: 'difference_points',
                    name: 'difference_points',
                },
                {
                    data: 'remaining_points',
                    name: 'remaining_points',
                },
                {
                    data: 'total_points',
                    name: 'total_points'
                },
                {
                    data: 'totalCoupons',
                    name: 'totalCoupons',
                }
            ],
            dom: 'Blfrtip',
            buttons: [
            {
                extend: 'excel',
                className:'btn btn-success btn-sm',
                text: 'Export to Excel',
                filename: 'Seller_points_Data', // Custom file name for Excel
                title: 'Seller Points Data',
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
                url: 'seller/' + id,
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