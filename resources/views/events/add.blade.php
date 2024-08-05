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
        <form method="POST" action="{{ route('event.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <input type="text" class="form-control character" id="event_name" name="event_name"
                    aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Description</label>
                <textarea class="form-control character" rows="5" name="event_description" id="comment"></textarea>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Prize</label>
                <input type="text" class="form-control isNumberValid" id="prize" name="prize"
                    aria-describedby="emailHelp" required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" aria-describedby="emailHelp"
                    required>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date" aria-describedby="emailHelp"
                    required>
            </div>
            <br />
            <div class="form-group">
                <label for="sel1">Select Image Type:</label>
                <select class="form-control" id="image_type" name="image_type">
                    <option value="1">Desktop</option>
                    <option value="2">Mobile</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="file" name="event_image" class="form-control-file border" required>
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection