@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">
        {{-- @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif --}}
        {{-- {{ dd($slotdata); }} --}}
        <form method="POST" action="{{ URL('updateslot',$id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <?php 
            $quantity = $slotdata[0]->quantity;
            $user_id = $slotdata[0]->user_id;
            $event_id = $slotdata[0]->event_id; 
            ?>
            <input type="hidden" name="event_id" value="<?php echo $event_id  ?>" />
            <input type="hidden" name="user_id" value="<?php echo $user_id  ?>" />
            <input type="hidden" name="quantity" value="<?php echo $quantity  ?>" />
            <table class="table table-bordered" id="dynamicTable">
                <tr>
                    <th>Book Slot</th>
                    <th>From Coupon</th>
                    <th>To Coupon</th>
                </tr>
                <?php if($slotdata) {  
                   
                    foreach ($slotdata as $key => $value) {
                    $cnt = $key+1; 
                    $id = $value->slot_id;
                    $splitVal = explode(' - ', $value->slot);
                ?>
                <tr>
                    <?php $slotCal = $quantity / 100; ?>
                    <td>Slot {{ $cnt }}</td>
                    <td><input required type="text" name="addmore[{{ $id }}][from]" value="{{ $splitVal[0] }}"
                            id="addmorefrom_{{ $cnt }}" placeholder="From Coupon" class="form-control from" /></td>
                    <td><input required type="text" name="addmore[{{ $id }}][to]" max="100" value="{{ $splitVal[1] }}"
                            placeholder="To Coupon" class="form-control to" id="addmoreto_{{ $cnt }}" /></td>
                </tr>
                <?php } } ?>
            </table>
            <br />
            <button type="submit" id="Submit" class="btn btn-primary">Update</button>
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

 

    $('.to').on("input", function() {
        var dInput = this.value;

        $("#Submit").prop('disabled', false); 
        $("#add").prop('disabled', false);
    });
</script>
@endsection