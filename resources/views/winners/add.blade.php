@extends('layouts.master')
@section('content')
<link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container col-md-6">
        @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif
        <form method="POST" action="{{ route('winner.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="exampleInputEmail1">Store Name</label>
                <select class="form-control" name="user_id" id="user_id">
                    <option value="">Select Store Name</option>
                    <?php if ($userList) { 
                                    foreach ($userList as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->storename }} </option>
                    <?php } } ?>
                </select>
                @error('user_id')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="exampleInputEmail1">Prize</label>
                <select class="form-control" name="prize" id="prize" required>
                    <option value="">Select Prize</option>
                    <?php if ($userList) { 
                        foreach ($prizeList as $key => $value) { ?>
                    <option value="{{ $value->id }}">{{ $value->prize_name }} </option>
                    <?php } } ?>
                </select>
                @error('prize')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <br />
            <button type="submit" class="btn btn-primary">Generate Report</button>
            <button type="reset" class="btn btn-secondary ">Reset</button>
        </form>
    </div>
</div>

@endsection