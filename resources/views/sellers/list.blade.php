@extends('layouts.master')
@section('content')
<style>
    button.delete.btn.btn-danger.btn-sm {
        font-size: 10px;
        margin-top: 2px;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <a style="float: right;" href="{{ route('seller.create')}}" class="btn btn-primary">Add Seller</a>
    <form action="{{ url('import-users') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" required name="file"
            accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
        <button type="submit" class="btn btn-primary" style="font-size: 14px;">Import Data</button>
        <a class="btn btn-warning" href="{{ asset('sample/User-Sample.xlsx') }}" style="font-size: 14px;">Download
            Sample
            Data</a>
    </form>
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
                    <th>City</th>
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
            ajax: "{{ route('seller.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name',
                },
                {
                    data: 'city',
                    name: 'city',
                },
                {
                    data: 'storename',
                    name: 'storename',
                },
                {
                    data: 'avatar',
                    name: 'avatar',
                    orderable: false,
                    searchable: false,
                    "render": function(data, type, full, meta) {
                        if (data) {
                            return "<img src=\"profile_images/" + data + "\" height=\"50\" width=\"50\"/>";
                        } else {
                            return "<img src=\"Image_not_available.png\" height=\"50\" width=\"50\" />";
                        }
                    },
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                },
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