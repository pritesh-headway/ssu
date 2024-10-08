@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> -->
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ route('banner.update',$banner->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Banner Name</label>
                <input type="text" class="form-control character" id="banner_name" value="{{ $banner->banner_name }}"
                    name="banner_name" aria-describedby="emailHelp">
                @error('banner_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="sel1">Select Banner Type:</label>
                <select class="form-control" id="banner_type" name="banner_type">
                    <option value="3" <?php echo ($banner->banner_type == 3) ? 'selected' : '' ?>>Both</option>
                    <option value="1" <?php echo ($banner->banner_type == 1) ? 'selected' : '' ?>>Desktop</option>
                    <option value="2" <?php echo ($banner->banner_type == 2) ? 'selected' : '' ?>>Mobile</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="file" accept="image/*" name="banner_image" class="form-control-file border">
                <img width="100" height="80" src="{{URL('public/banner_images') }}/{{ $banner->image }}" width="300px">
                @error('banner_image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection