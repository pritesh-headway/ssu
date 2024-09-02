@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ route('document.store') }}" enctype="multipart/form-data">
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
                <label for="exampleInputEmail1">Seller Name</label>
                <select class="form-control" name="user_id" id="user_id">
                    <option value="">Select Seller Name</option>
                    <option value="0">All</option>
                    <?php if ($userList) { 
                        foreach ($userList as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->name }} {{ $value->lname }}</option>
                    <?php } } ?>
                </select>
                @error('user_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Document Name</label>
                <input type="text" class="form-control " id="doc_name" name="doc_name" aria-describedby="emailHelp">
                @error('doc_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <input type="file" name="docFile" class="form-control-file border">
                @error('docFile')
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