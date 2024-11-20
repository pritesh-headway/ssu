@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-06">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <form method="POST" action="{{ route('home.update',$linkData['id']) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="exampleInputEmail1">Meeting Title</label>
                <input type="text" class="form-control" id="meeting_title" name="meeting_title"
                    value="{{ $linkData['meeting_title'] }}" aria-describedby="emailHelp">
                <input type="hidden" class="form-control" id="id" name="id" value="1" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <label for="exampleInputEmail1">Meeting Link</label>
                <input type="text" class="form-control" id="link" name="link" value="{{ $linkData['link'] }}"
                    aria-describedby="emailHelp">
                <input type="hidden" class="form-control" id="id" name="id" value="1" aria-describedby="emailHelp">
            </div>
            <br />
            <div class="form-group">
                <input type="hidden" class="form-control" id="id" name="id" value="{{ $linkData['id'] }}"
                    aria-describedby="emailHelp">
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="0" name="is_today" id="is_today" <?php
                    if($linkData['is_today']==1) { ?> checked
                <?php } ?>>
                <label class="form-check-label" for="is_today">
                    Is Today
                </label>
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Update</button>
            <button type="button" onclick="history.back()" class="btn btn-secondary ">Back</button>
        </form>
    </div>
</div>
<script>

</script>
@endsection