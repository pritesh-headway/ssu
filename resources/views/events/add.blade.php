@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">
        <form method="POST" action="{{ route('event.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <input type="text" class="form-control" id="event_name" name="event_name" aria-describedby="emailHelp">
                @error('event_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Description</label>
                <textarea class="form-control  " rows="5" name="event_description" id="comment"></textarea>
                @error('event_description')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Event Prize</label>
                <input type="text" class="form-control isNumberValid" id="prize" name="prize"
                    aria-describedby="emailHelp">
                @error('prize')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Start Date</label>
                <input type="text" class="form-control" id="start_date" name="start_date" aria-describedby="emailHelp">
                @error('start_date')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">End Date</label>
                <input type="text" class="form-control" id="end_date" name="end_date" aria-describedby="emailHelp">
                @error('end_date')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="sel1">Select Device Type:</label>
                <select class="form-control" id="image_type" name="image_type">
                    <option value="3">Both</option>
                    <option value="1">Desktop</option>
                    <option value="2">Mobile</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <input type="file" accept="image/*" name="event_image" class="form-control-file border">
                @error('event_image')
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