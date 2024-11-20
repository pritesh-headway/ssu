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
                    <option value="3" {{ $gallery->type == 3 ? 'selected' : '' }}> Youtube Link</option>
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" class="form-control specialcharcterValid" id="title" value="{{ $gallery->title }}"
                    name="title" aria-describedby="emailHelp">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />

            <?php if($gallery->type == 1) { ?>
            <div class="form-group image ">
                <input type="file" multiple name="image[]" class="form-control-file border"><br />
                <img width="100" height="80" src="{{URL('public/event_images') }}/{{ $gallery->image }}" width="300px">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <?php } ?>
            <?php if($gallery->type == 2) { ?>
            <div class="form-group video ">
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
            <?php if($gallery->type == 3) { ?>
            <div class="form-group link ">
                <label for="exampleInputEmail1">Youtube Link</label>
                <input type="text" name="link" value="{{ $gallery->video }}" class="form-control "><br />
                @error('link')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <?php } ?>
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
                $(".link").addClass('hide');
            }
            //video
            if(status == 2) {
                $(".video").removeClass('hide');
                $(".image").addClass('hide');
                $(".link").addClass('hide');
            }

            //link
            if(status == 3) {
                $(".link").removeClass('hide');
                $(".image").addClass('hide');
                $(".video").addClass('hide');
            }
        });
    });
</script>
@endsection