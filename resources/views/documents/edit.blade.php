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
        <form method="POST" action="{{ route('document.update',$document->id) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Event Name</label>
                <select class="form-control" name="event_id" id="event_id">
                    <option>Select Event Name</option>
                    <?php if($eventList) { foreach ($eventList as $key => $value) { ?>
                    <option value="{{ $value->id }}" {{ $document->event_id == $value->id ? 'selected' : '' }}>{{
                        $value->event_name }}</option>
                    <?php } } ?>
                </select>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Seller Name</label>
                <select class="form-control" name="user_id" id="user_id">
                    <option>Select Seller Name</option>
                    <?php if ($userList) { 
                        foreach ($userList as $key => $value) { ?>
                    <option value="{{ $value->id }}" {{ $document->user_id == $value->id ? 'selected' : '' }}>{{
                        $value->name }} {{ $value->lname }}</option>
                    <?php } } ?>
                </select>
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Document Name</label>
                <input type="text" class="form-control character" id="doc_name" value="{{ $document->doc_name }}"
                    name="doc_name" aria-describedby="emailHelp" required>
            </div>
            <br />

            <br />
            <div class="form-group">
                <input type="file" name="docFile" class="form-control-file border"><br />
                <a target="_blank" href="{{ URL('public/documents') }}/{{ $document->file }}">{{ $document->file }}</a>
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
@endsection