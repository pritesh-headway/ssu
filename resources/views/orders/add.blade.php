@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}" />
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
        @if ($message = Session::get('warning'))
        <div class="alert alert-warning">
            <p>{{ $message }}</p>
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
            <span><b>NOTE:</b>
                <span style="color: red">Only 100 coupon numbers are allowed per slot. Please ensure you do not exceed
                    this limit.
                </span>
            </span>
            &nbsp;
            <table class="table table-bordered" id="dynamicTable">
                <tr>
                    <th>Book Slot</th>
                    <th>From Coupon</th>
                    <th>To Coupon</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <?php $slotCal = $quantity / 100; ?>

                    <td>Slot 1</td>
                    <td><input type="text" name="addmore[1][from]" id="addmorefrom_1" placeholder="From Coupon"
                            class="form-control from" /></td>
                    <td><input type="text" name="addmore[1][to]" max="100" placeholder="To Coupon"
                            class="form-control to" id="addmoreto_1" /></td>
                    <?php $cls =''; if($quantity <= 100) { $cls = 'disabled'; } ?>
                    <td>
                        <button type="button" name="add" id="add" class="btn btn-success" <?php echo $cls ?>>Add
                            More</button>
                    </td>
                </tr>
            </table>
            <button type="submit" id="Submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
<script type="text/javascript">
    var i = 0;
    var k = 1;
    var slot = 1;
    var quantity = '<?php echo $quantity ?>';
    var defaultQty = 100; // plus 100 extra bcoz by default 100 add first
    var removeQty = 100;
    var finalToVal;
    var finalFromVal;
    var firstStepVal;
    var SlotAvailable = {{ $slotCal }}

    $("#Submit").click(function() {
        if (slot != SlotAvailable) {
            alert('Please add proper unique slot.');
            return false;
        }
    })

    $(".to").focusout(function(){

        if(parseFloat($(".from").val()) > parseFloat($(".to").val()))
        {
            alert("To Coupons value must be greater than From Coupon");
            $("#Submit").prop('disabled',true);
            // $("#add").prop('disabled', true);
        }
        else {
            $("#Submit").prop('disabled',false);
            // $("#add").prop('disabled', false);
        }    
    });

    $("#add").click(function(){
        ++i;
       
        quantity = quantity - defaultQty;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // console.log(slot, ' ------ ',k, ' ---- ',$("#addmorefrom_"+k).val());
        if($("#addmorefrom_"+k).val() == undefined && $("#addmoreto_"+k).val() == undefined || $("#addmorefrom_"+k).val() == '' && $("#addmoreto_"+k).val() == '') {
            alert('Please add the coupon number');
            return false;
        } else {
        
            fromVal = $("#addmorefrom_"+k).val();
            toVal = $("#addmoreto_"+k).val();
            isValid = true;
          
            finalToVal = toVal - fromVal;
            // console.log(' ------ ',k);
            if(defaultQty < finalToVal) { 
                $("#Submit").prop('disabled', true); 
                $("#add").prop('disabled', true); 
                alert('Please add only 100 Coupons in 1 Slot'); 
                return false; 
            }
            
            if(isValid) {
                $.ajax({
                    url: "{{ route('ajaxRequest.post') }}",
                    type: 'POST',
                    method: 'POST',
                    dataType: "json",
                    async:false,
                    cache: false,
                    data: {
                        _fromVal: fromVal,
                        _toVal: toVal,
                        _user_id: {{ $user_id }},
                        _event_id: {{ $event_id }},
                    },
                    success: function(response) {
                        if(!response.status) {
                            isValid = false;
                            $("#addmorefrom_"+k).css('border-color','red');
                            $("#addmoreto_"+k).css('border-color','red');
                            $("#Submit").prop('disabled', true);
                            alert("Coupons al-ready exist for the sellers acount, Please try another.")
                            return false;
                        } else {
                            $("#Submit").prop('disabled', false);
                            $("#addmorefrom_"+k).removeAttr('style');
                            $("#addmoreto_"+k).removeAttr('style');
                            slot = slot + 1;
                            k = k + 1;
                        }
                    }
                });
            }
        }
        console.log(slot +' slot '+ SlotAvailable);
        if (slot == SlotAvailable) {
            $("#add").prop('disabled', true);
        }
        if(isValid) {
            $("#dynamicTable").append('<tr><td>Slot '+slot+'</td> <td><input type="text" id="addmorefrom_'+slot+'" name="addmore['+slot+'][from]" placeholder="From Coupon" class="form-control from" /></td><td><input type="text" name="addmore['+slot+'][to]" id="addmoreto_'+slot+'" placeholder="To Coupon" class="form-control to" /></td><td><button type="button" class="btn btn-danger remove-tr">Remove</button></td></tr>');
        }
        // console.log(quantity +' quantity '+ quantity);

        $("#Submit").prop('disabled', false);
    });
   
    $(document).on('click', '.remove-tr', function(){  
        slot = slot - 1;
        k = k-1;
        quantity = quantity + removeQty;
     
        $(this).parents('tr').remove();
        if(quantity != 100) {
            $("#add").prop('disabled', false);
        }
    });  
   

    $('.to').on("input", function() {
        var dInput = this.value;

        $("#Submit").prop('disabled', false); 
        $("#add").prop('disabled', false);
    });
</script>
@endsection