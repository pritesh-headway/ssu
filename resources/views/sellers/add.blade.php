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
        <form method="POST" action="{{ route('seller.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Seller First Name</label>
                <input type="text" class="form-control character" id="name" name="name" aria-describedby="emailHelp"
                    required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller Last Name</label>
                <input type="text" class="form-control character" id="lname" name="lname" aria-describedby="emailHelp"
                    required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Store Name</label>
                <input type="text" class="form-control character" id="storename" name="storename"
                    aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller Phone Number</label>
                <input type="text" class="form-control isNumberValid" min="10" max="10" id="phone_number"
                    name="phone_number" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller PAN</label>
                <input type="text" class="form-control" id="PAN" name="PAN" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller GST</label>
                <input type="text" class="form-control" id="GST" name="GST" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Flat Number / Building</label>
                <input type="text" class="form-control" id="flatNo" name="flatNo" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Street/Area</label>
                <input type="text" class="form-control " id="area" name="area" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">City</label>
                <input type="text" class="form-control character" id="city" name="city" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">State</label>
                <input type="text" class="form-control character" id="state" name="state" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Pincode</label>
                <input type="text" class="form-control" id="pincode" name="pincode" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <input type="file" name="profile_image" class="form-control-file border" required>
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection