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
        <form method="POST" action="{{ route('bill.store','bid='.$bill_id) }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="event_id" value="<?php echo $event_id  ?>" />
            <input type="hidden" name="user_id" value="<?php echo $user_id  ?>" />

            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Receipt</label><br />
                <input type="file" accept="image/*" name="receipt" class="form-control-file border">
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>

@endsection