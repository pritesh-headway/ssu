@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">
        <form method="POST" action="{{ route('banner.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Banner Name</label>
                <input type="text" class="form-control character" id="banner_name" name="banner_name"
                    aria-describedby="emailHelp">
                @error('banner_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="sel1">Select Banner Type:</label>
                <select class="form-control" id="banner_type" name="banner_type">
                    <option value="3">Both</option>
                    <option value="1">Desktop</option>
                    <option value="2">Mobile</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="file" accept="image/*" name="banner_image" class="form-control-file border">
                @error('banner_image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection