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
        <form method="POST" action="{{ route('event.update',$event->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <input type="text" class="form-control character" id="event_name" value="{{ $event->event_name }}" name="event_name" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Description</label>
                <textarea class="form-control character" rows="5" name="event_description" id="comment">{{ $event->event_description }}</textarea>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Prize</label>
                <input type="text" class="form-control isNumberValid" id="prize" name="prize" value="{{ $event->prize }}" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Start Date</label>
                <input type="date" class="form-control" id="start_date" value="{{ $event->start_date }}" name="start_date" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">End Date</label>
                <input type="date" class="form-control" id="end_date" value="{{ $event->end_date }}" name="end_date" aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="sel1">Select Image Type:</label>
                <select class="form-control" id="image_type" name="image_type">
                    <option value="1" <?php echo ($event->image_type == 1) ? 'selected' : '' ?>>Desktop</option>
                    <option value="2" <?php echo ($event->image_type == 2) ? 'selected' : '' ?>>Mobile</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="file" name="event_image" class="form-control-file border">
                <img src="{{URL('public/event_images') }}/{{ $event->image }}" width="300px">
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection