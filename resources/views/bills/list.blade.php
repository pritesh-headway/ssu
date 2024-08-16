@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    {{-- <a style="float: right;" href="{{ route('customer.create')}}" class="btn btn-primary">Add Customer</a> --}}
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
                    <th>BIll Title</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Bill Date</th>
                    <th>Payment Verification</th>
                    <th>Order Status</th>
                    <th>Reasons</th>
                    <th width="105px">Actions</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bill Declined</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" name="reason" id="reason"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary subBtn">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
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
            order: [4, "DESC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: true,
            ajax: "{{ route('bill.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'detail',
                    name: 'detail'
                },
                {
                    data: 'bill_date',
                    name: 'bill_date'
                },
                {
                    data: 'file',
                    name: 'file',
                    "render": function(data, type, full, meta) {
                        return "<a target='_blank' href=\"bills/" + data + "\"><img src=\"bills/" + data + "\" height=\"50\" width=\"100\"/><a/>";
                    },
                },
                {
                    data: 'bill_status',
                    name: 'bill_status',
                    "render": function(data, type, full, meta) {
                        if(data == 'Approved') {
                            return "<span style=\"color:green\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Pending') {
                            return "<span style=\"color:red\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Declined') {
                            return "<span style=\"color:red\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Completed') {
                            return "<span style=\"color:orange\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        }
                    }
                },
                {
                    data: 'reasons',
                    name: 'reasons'
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
       $("#exampleModal").modal('show');
       window.id = id;
    }

    $(".subBtn").click(function() {
        var reason = $("#reason").val();
        if(reason) {
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url: 'bill/' + window.id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reasons: reason
                    },
                    success: function(response) {
                        $("#exampleModal").modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    }
                });
            }
        } else {
            $("#reason").css('border-color','red');
        }
    })
    function deleveryItem(id) {
        $.ajax({
            url: 'bill/' + id,
            type: 'PUT',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('#myTable').DataTable().ajax.reload();
                alert(response.success);
            }
        });
    }


</script>
@endsection