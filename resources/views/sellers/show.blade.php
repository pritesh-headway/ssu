@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
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
                    <th>Coupon Number</th>
                    <th>Is Assigned</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
<link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
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
            pageLength: 25,
            paging: true,
            ajax: "{{ route('seller.show', $id) }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'coupon_number',
                    name: 'coupon_number',
                },
                {
                    data: 'is_assign',
                    name: 'is_assign',
                }
            ],
            lengthMenu: [25, 50, 100],
        });
       
    });
</script>
@endsection