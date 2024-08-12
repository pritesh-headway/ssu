@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ route('gallery.store') }}" enctype="multipart/form-data">
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
                <label for="exampleInputEmail1">Gallery Type</label>
                <select class="form-control" name="type" id="type">
                    <option value="">Select Gallery Type</option>
                    <option value="1"> Image</option>
                    <option value="2"> Video</option>
                </select>
                @error('type')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Title</label>
                <input type="text" class="form-control character" id="title" name="title" aria-describedby="emailHelp">
                @error('title')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group image hide">
                <input type="file" accept="image/*" name="image" id="image" class="form-control-file border">
                @error('image')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group video hide">
                <input type="file" accept="video/*" name="video" id="video" class="form-control-file border">
                @error('video')
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