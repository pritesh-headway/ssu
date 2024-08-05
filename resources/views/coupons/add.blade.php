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
        <form method="POST" action="{{ route('coupon.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Coupon Name</label>
                <input type="text" class="form-control" id="coupon_name" name="coupon_name" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Coupon Number</label>
                <input type="text" class="form-control" id="coupon_number" name="coupon_number" aria-describedby="emailHelp" required>
            </div>
            <br />

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection