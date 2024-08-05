@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">
        @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="POST" action="{{ route('order.store','oid='.$order_id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="event_id" value="<?php echo $event_id  ?>" />
            <input type="hidden" name="user_id" value="<?php echo $user_id  ?>" />
            <input type="hidden" name="quantity" value="<?php echo $quantity  ?>" />
            <h6><b>Store Name: </b>
                <?php echo UCWORDS($sellecrOrderData->storename) ?>
            </h6>
            <h6><b>Seller Name: </b>
                <?php echo UCWORDS($sellecrOrderData->seller_name) ?>
            </h6>
            <h6><b>Order Coupons Quantity: </b>
                <?php echo $quantity ?>
            </h6>
            <br />
            &nbsp;
            <table class="table table-bordered" id="dynamicTable">
                <tr>
                    <th>Book Slot</th>
                    <th>From Coupon</th>
                    <th>To Coupon</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <td>Slot 1</td>
                    <td><input type="text" name="addmore[0][from]" placeholder="From Coupon"
                            class="form-control from" /></td>
                    <td><input type="text" name="addmore[0][to]" max="100" placeholder="To Coupon"
                            class="form-control to" /></td>
                    <?php $cls =''; if($quantity <= 100) { $cls = 'disabled'; } ?>
                    <td>
                        <button type="button" name="add" id="add" class="btn btn-success" <?php echo $cls ?>>Add
                            More</button>
                    </td>
                </tr>
            </table>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
<script type="text/javascript">
    var i = 0;
    var slot = 1;
    var quantity = '<?php echo $quantity ?>';
    var defaultQty = 100; // plus 100 extra bcoz by default 100 add first
    var removeQty = 100;
    $("#add").click(function(){
   
        ++i;
        slot = slot + 1;
        quantity = quantity - defaultQty;

        $("#dynamicTable").append('<tr><td>Slot '+slot+'</td> <td><input type="text" name="addmore['+i+'][from]" placeholder="From Coupon" class="form-control" /></td><td><input type="text" name="addmore['+i+'][to]" placeholder="To Coupon" class="form-control" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
        if(quantity == 100) {
            $("#add").prop('disabled', true);
        }
    });
   
    $(document).on('click', '.remove-tr', function(){  
        slot = slot - 1;
        quantity = quantity + removeQty;
        $(this).parents('tr').remove();
        if(quantity != 100) {
            $("#add").prop('disabled', false);
        }
    });  
   

    $('.from').on("input", function() {
        var dInput = this.value;
        if(dInput > quantity){
            $('.from').val('');
        }
    });

    $('.to').on("input", function() {
        var dInput = this.value;
        if(dInput > quantity){
            $('.to').val('');
        }
    });
</script>
@endsection