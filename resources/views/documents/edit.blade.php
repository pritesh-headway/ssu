@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">

        <form method="POST" action="{{ route('document.update',$document->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <select class="form-control" name="event_id" id="event_id">
                    <option value="">Select Event Name</option>
                    <?php if($eventList) { foreach ($eventList as $key => $value) { ?>
                    <option value="{{ $value->id }}" {{ $document->event_id == $value->id ? 'selected' : '' }}>{{
                        $value->event_name }}</option>
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
                    <?php if ($userList) { 
                        foreach ($userList as $key => $value) { ?>
                    <option value="0" {{ 0==$value->id ? 'selected' : '' }}>All</option>
                    <option value="{{ $value->id }}" {{ $document->user_id == $value->id ? 'selected' : '' }}>{{
                        $value->name }} {{ $value->lname }}</option>
                    <?php } } ?>
                </select>
                @error('user_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Document Name</label>
                <input type="text" class="form-control " id="doc_name" value="{{ $document->doc_name }}" name="doc_name"
                    aria-describedby="emailHelp">
                @error('doc_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />

            <br />
            <div class="form-group">
                <input type="file" name="docFile" class="form-control-file border"><br />
                <a target="_blank" href="{{ URL('public/documents') }}/{{ $document->file }}">{{ $document->file }}</a>
                @error('docFile')
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