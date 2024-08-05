@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <a style="float: right;" href="{{ route('seller.create')}}" class="btn btn-primary">Add Seller</a>
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
                    <th>Seller Name</th>
                    <th>Store Name</th>
                    <th>Seller Profile</th>
                    <th width="105px">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
<link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="{{ URL::to('assets/js/dataTables.bootstrap5.min.js') }}"></script>
<script type="text/javascript">
    $(function() {
        gb_DataTable = $("#myTable").DataTable({
            autoWidth: false,
            order: [0, "ASC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: true,
            ajax: "{{ route('seller.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'storename',
                    name: 'storename'
                },
                {
                    data: 'avatar',
                    name: 'avatar',
                    "render": function(data, type, full, meta) {
                        return "<img src=\"profile_images/" + data + "\" height=\"50\" width=\"100\"/>";
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ],
            lengthMenu: [25, 50, 100]
        });
    });
</script>
@endsection