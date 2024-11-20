@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ URL('asset/insertData') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <select class="form-control" name="event_id" id="event_id">
                    <option value="">Select Event Name</option>
                    <?php if ($eventList) { 
                        foreach ($eventList as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->event_name }}</option>
                    <?php } } ?>
                </select>
                @error('event_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Store Name</label>
                <select class="form-control" name="user_id" id="user_id" required>
                    <option value="">Select Store Name</option>
                    {{-- <option value="0">All</option> --}}
                    <?php if ($userList) { 
                        foreach ($userList as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->storename }}</option>
                    <?php } } ?>
                </select>
                @error('user_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group hideCSS">
                <label for="exampleInputEmail1">Available Points:</label>
                <span id="showPoints" style="font-size: 18px"></span>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Asset Title</label>
                <input type="text" class="form-control " id="title" required name="title" aria-describedby="emailHelp">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Asset Quantity</label>
                <input type="text" class="form-control isNumberValid" required id="quantity" name="quantity"
                    aria-describedby="emailHelp">
                @error('quantity')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Asset Details</label>
                <textarea class="form-control " id="detail" required name="detail"></textarea>
                @error('detail')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Deduct Amount</label>
                <input type="text" class="form-control isNumberValid" required id="amount" name="amount"
                    aria-describedby="emailHelp">
                @error('amount')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
<script>
    $(".hideCSS").hide();
    $("#user_id").change(function() {
        var storeId = $(this).val();
        var eventId = $('#event_id').val();
        if(eventId == '') {
            alert('Please select an event first');
            $('#user_id').val('');
        } 
        if(storeId) {
            $.ajax({
                    url: 'asset/points/' + storeId +'/'+eventId,
                    type: 'GET',
                    async:false,
                    data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    $(".hideCSS").show();
                    $("#showPoints").html(response.points);
                }
            });
        } else {
            $("#showPoints").html('');
        }
    })
</script>
@endsection