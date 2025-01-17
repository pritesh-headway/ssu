@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <a style="float: right;" href="{{ route('prize.create')}}" class="btn btn-primary">Add Prize</a>
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <!-- <h2 class="mb-4" style="font-size: 2.0rem;">Banners List</h2> -->
        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Event Name</th>
                    <th>Prize Name</th>
                    <th>Prize Qty</th>
                    <th>Prize Amount</th>
                    <th>Image</th>
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
            paging: true,
            ajax: "{{ route('prize.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'event_name',
                    name: 'event_name',
                    searchable: false
                },
                {
                    data: 'prize_name',
                    name: 'prize_name'
                },
                {
                    data: 'prize_qty',
                    name: 'prize_qty'
                },
                {
                    data: 'prize_amount',
                    name: 'prize_amount'
                },
                {
                    data: 'image',
                    name: 'image',
                    "render": function(data, type, full, meta) {
                        if(data) {
                            return "<img src=\"public/prize_images/" + data + "\" height=\"50\" width=\"50\"/>";
                        } else{
                            return "<img src=\"public/no-img.jpg\" height=\"50\" width=\"50\" />";
                        }
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
    function deleteItem(id) {
        if (confirm('Are you sure you want to delete this item?')) {
            $.ajax({
                url: 'prize/' + id,
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