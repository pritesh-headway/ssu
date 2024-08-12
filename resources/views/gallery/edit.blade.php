@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ route('gallery.update',$gallery->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <select class="form-control" name="event_id" id="event_id">
                    <option value="">Select Event Name</option>
                    <?php if($eventList) { foreach ($eventList as $key => $value) { ?>
                    <option value="{{ $value->id }}" {{ $gallery->event_id == $value->id ? 'selected' : '' }}>{{
                        $value->event_name }}</option>
                    <?php } } ?>
                </select>
                @error('event_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Gallery Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">Select Gallery Type</option>
                    <option value="1" {{ $gallery->type == 1 ? 'selected' : '' }}> Image</option>
                    <option value="2" {{ $gallery->type == 2 ? 'selected' : '' }}> Video</option>
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" class="form-control character" id="title" value="{{ $gallery->title }}" name="title"
                    aria-describedby="emailHelp">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />

            <?php if($gallery->type == 1) { ?>
            <div class="form-group">
                <input type="file" name="image" class="form-control-file border"><br />
                <img width="100" height="80" src="{{URL('public/event_images') }}/{{ $gallery->image }}" width="300px">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <?php } ?>
            <?php if($gallery->type == 2) { ?>
            <div class="form-group">
                <input type="file" name="video" class="form-control-file border"><br />

                <video width="320" height="240" controls>
                    <source src="{{URL('public/event_videos') }}/{{ $gallery->video }}" type="video/mp4">
                    <source src="{{URL('public/event_videos') }}/{{ $gallery->video }}" type="video/ogg">
                    Your browser does not support the video tag.
                </video>
                @error('video')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <?php } ?>
            <br />
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
<script>
    $(function() {
        $("#type").change(function () {
            var status = this.value;
            //image
            if(status == 1) {
                $(".image").removeClass('hide');
                $(".video").addClass('hide');
            }
            //video
            if(status == 2) {
                $(".video").removeClass('hide');
                $(".image").addClass('hide');
            }
        });
    });
</script>
@endsection