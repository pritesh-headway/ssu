@extends('layouts.master')
@section('content')
<style>
    .dt-buttons {
        float: left !important;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    {{-- <a style="float: right;" href="{{ route('addBill')}}" class="btn btn-primary">Add Bill</a> --}}
    <?php if($role == 1 || $role == 2) { ?>
    <form action="{{ route('addBill') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="item_id" value="1">
        <button style="float: right;" type="submit" class="btn btn-primary">
            Add Bill
        </button>
    </form>
    <?php } ?>
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
                    <th>BIll Title</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Date</th>
                    <th>Payment Verification</th>
                    <th>Status</th>
                    <th>Reasons</th>
                    <th width="105px" class="noExport">Actions</th>
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
            ajax: "{{ route('bill.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'seller_name',
                    name: 'seller_name',
                    "render": function(data, type, full, meta) {
                        return "<a target='_blank' href=\"{{ URL::to('bill/') }}/"+ full.id +"\">"+data+"<a/>";
                    },
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
                    name: 'bill_date',
                    sWidth:'15%'
                },
                {
                    data: 'file',
                    name: 'file',
                    searchable: false,
                    "render": function(data, type, full, meta) {
                        ext = data.split('.').pop();
                        if(ext == 'pdf') {
                            return "<a target='_blank' href=\"bills/" + data + "\">" + data + "<a />";
                        } else {
                            return "<a target='_blank' href=\"bills/" + data + "\"><img src=\"bills/" + data + "\" height=\"50\" width=\"50\"/><a/>";
                        }
                    },
                },
                {
                    data: 'bill_status',
                    name: 'bill_status',
                    searchable: false,
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
            dom: 'Blfrtip',
            buttons: [
            {
            extend: 'excel',
            className:'btn btn-success btn-sm',
            text: 'Export to Excel',
            filename: 'SSU_Bill_Data', // Custom file name for Excel
            title: 'Coupons Bill Data',
            exportOptions: {
            columns: ':not(.noExport)'
            }
            }
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
                    url: 'bill/' + window.id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        reasons: reason
                    },
                    success: function(response) {
                        $("#exampleModal").modal('hide');
                        $('#myTable').DataTable().ajax.reload();
                        $("#reason").val('');
                        // alert(response.success);
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