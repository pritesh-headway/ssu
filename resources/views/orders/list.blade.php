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
                    <th>Seller Name</th>
                    <th>Event Year</th>
                    <th>No. of Coupons</th>
                    <th>Enquiry Date</th>
                    <th>Payment Verification</th>
                    <th>Order Status</th>
                    <th>Reasons</th>
                    <th width="105px">Action</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Order Declined</h5>
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
            ajax: "{{ route('order.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'seller_name',
                    name: 'seller_name',
                },
                {
                    data: 'event_year',
                    name: 'event_year',
                },
                {
                    data: 'quantity',
                    name: 'quantity',
                },
                {
                    data: 'order_date',
                    name: 'order_date',
                },
                {
                    data: 'receipt_payment',
                    name: 'receipt_payment',
                    "render": function(data, type, full, meta) {
                        return "<a target='_blank' href=\"receipt_images/" + data + "\"><img src=\"receipt_images/" + data + "\" height=\"50\" width=\"50\"/><a/>";
                    },
                },
                {
                    data: 'order_status',
                    name: 'order_status',
                    orderable: false,
                    searchable: false,
                    "render": function(data, type, full, meta) {
                        if(data == 'Approved') {
                            return "<span style=\"color:green\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Pending') {
                            return "<span style=\"color:red\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Declined') {
                            return "<span style=\"color:red\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        } else if(data == 'Delivered') {
                            return "<span style=\"color:orange\" height=\"50\" width=\"100\"/><b>"+data+"</b></span>";
                        }
                    },
                },
                {
                    data: 'reasons',
                    name: 'reasons',
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
            if (confirm('Are you sure you want to decline this item?')) {
                $.ajax({
                    url: 'order/' + id,
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
            url: 'order/' + id,
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