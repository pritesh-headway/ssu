@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
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
        <form method="POST" action="{{ route('slab.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <select class="form-control" name="event_id" id="event_id">
                    <option>Select Event Name</option>
                    <?php if($event) { foreach ($event as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->event_name }}</option>
                    <?php } } ?>
                </select>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Minimum no. of Coupons</label>
                <input type="text" class="form-control" id="min_coupons" name="min_coupons" />
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Maximum no. of Coupons</label>
                <input type="text" class="form-control" id="max_coupons" name="max_coupons" />
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Prize</label>
                <input type="text" class="form-control isNumberValid" id="prize" name="prize"
                    aria-describedby="emailHelp" required>
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection