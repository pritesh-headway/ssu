@extends('layouts.master')
@section('content')
<div class="animate__animated p-6" :class="[$store.app.animation]">
    {{-- <a style="float: right;" href="{{ route('customer.create')}}" class="btn btn-primary">Add Customer</a> --}}
    <?php if($role == 1 || $role == 2) { ?>
    <form action="{{ route('addAsset') }}" method="POST" style="display: inline;">
        @csrf
        <input type="hidden" name="item_id" value="1">
        <button style="float: right;" type="submit" class="btn btn-primary">
            Add Asset
        </button>
    </form>
    <?php } ?>
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        @if ($message = Session::get('error'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
        </div>
        @endif
        <!-- <h2 class="mb-4" style="font-size: 2.0rem;">Events List</h2> -->
        <table id="myTable" class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Details</th>
                    <th>Quantity</th>
                    <th>Total Points</th>
                    <th>Remaining Points</th>
                    <th>Deduct Points</th>
                    <th>Date</th>
                    <th>Reason</th>
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
                <h5 class="modal-title" id="exampleModalLabel">Approve Assets</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label>Amount</label>
                <input type="text" name="amount" id="amount" class="form-control isNumberValid" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary subBtn">Submit</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2
    aria-hidden=" true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Asset Declined</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" name="reason" id="reason"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary subBtns">Submit</button>
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
            order: [0, "DESC"],
            processing: true,
            serverSide: true,
            searchDelay: 2000,
            paging: true,
            ajax: "{{ route('asset.index') }}",
            iDisplayLength: "25",
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'id'
                },
                {
                    data: 'seller_name',
                    name: 'seller_name'
                },
                {
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'detail',
                    name: 'detail'
                },
                {
                    data: 'quantity',
                    name: 'quantity'
                },
                {
                    data: 'total_points',
                    name: 'total_points'
                },
                {
                    data: 'remaining_points',
                    name: 'remaining_points'
                },
                {
                    data: 'amount',
                    name: 'amount'
                },
                {
                    data: 'bill_date',
                    name: 'bill_date'
                }
                ,
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
    function approveItem(id) {
       $("#exampleModal").modal('show');
       window.id = id;
    }
    function deleteItem(id) {
        $("#exampleModal2").modal('show');
        window.did = id;
    }
    $(".subBtns").click(function() {
        var reason = $("#reason").val();
        if(reason) {
            if (confirm('Are you sure you want to decline this item?')) {
                $.ajax({
                    url: 'asset/' + window.did,
                    type: 'DELETE',
                    async:false,
                    data: {
                        _token: '{{ csrf_token() }}',
                        reasons: reason
                    },
                    success: function(response) {
                        $("#exampleModal2").modal('hide');
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

    $(".subBtn").click(function() {
        var amount = $("#amount").val();
        if(amount) {
            $.ajax({
                url: 'asset/' + id,
                type: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    amount: amount
                },
                success: function(response) {
                    $("#exampleModal").modal('hide');
                    $('#myTable').DataTable().ajax.reload();
                    alert(response.success);
                }
            });
        } else {
            $("#amount").css('border-color','red');
        }
    })
   


</script>
@endsection