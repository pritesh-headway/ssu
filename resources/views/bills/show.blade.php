@extends('layouts.master')
@section('content')
<style>
    .font-size38 {
        font-size: 38px;
    }

    .team-single-text .section-heading h4,
    .section-heading h5 {
        font-size: 36px
    }

    .team-single-text .section-heading.half {
        margin-bottom: 20px
    }

    @media screen and (max-width: 1199px) {

        .team-single-text .section-heading h4,
        .section-heading h5 {
            font-size: 32px
        }

        .team-single-text .section-heading.half {
            margin-bottom: 15px
        }
    }

    @media screen and (max-width: 991px) {

        .team-single-text .section-heading h4,
        .section-heading h5 {
            font-size: 28px
        }

        .team-single-text .section-heading.half {
            margin-bottom: 10px
        }
    }

    @media screen and (max-width: 767px) {

        .team-single-text .section-heading h4,
        .section-heading h5 {
            font-size: 24px
        }
    }


    .team-single-icons ul li {
        display: inline-block;
        border: 1px solid #02c2c7;
        border-radius: 50%;
        color: #86bc42;
        margin-right: 8px;
        margin-bottom: 5px;
        -webkit-transition-duration: .3s;
        transition-duration: .3s
    }

    .team-single-icons ul li a {
        color: #02c2c7;
        display: block;
        font-size: 14px;
        height: 25px;
        line-height: 26px;
        text-align: center;
        width: 25px
    }

    .team-single-icons ul li:hover {
        background: #02c2c7;
        border-color: #02c2c7
    }

    .team-single-icons ul li:hover a {
        color: #fff
    }

    .team-social-icon li {
        display: inline-block;
        margin-right: 5px
    }

    .team-social-icon li:last-child {
        margin-right: 0
    }

    .team-social-icon i {
        width: 30px;
        height: 30px;
        line-height: 30px;
        text-align: center;
        font-size: 15px;
        border-radius: 50px
    }

    .padding-30px-all {
        padding: 30px;
    }

    .bg-light-gray {
        background-color: #f7f7f7;
    }

    .text-center {
        text-align: center !important;
    }

    img {
        max-width: 100%;
        height: auto;
    }


    .list-style9 {
        list-style: none;
        padding: 0
    }

    .list-style9 li {
        position: relative;
        padding: 0 0 15px 0;
        margin: 0 0 15px 0;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1)
    }

    .list-style9 li:last-child {
        border-bottom: none;
        padding-bottom: 0;
        margin-bottom: 0
    }


    .text-sky {
        color: #02c2c7
    }

    .text-orange {
        color: #e95601
    }

    .text-green {
        color: #5bbd2a
    }

    .text-yellow {
        color: #f0d001
    }

    .text-pink {
        color: #ff48a4
    }

    .text-purple {
        color: #9d60ff
    }

    .text-lightred {
        color: #ff5722
    }

    a.text-sky:hover {
        opacity: 0.8;
        color: #02c2c7
    }

    a.text-orange:hover {
        opacity: 0.8;
        color: #e95601
    }

    a.text-green:hover {
        opacity: 0.8;
        color: #5bbd2a
    }

    a.text-yellow:hover {
        opacity: 0.8;
        color: #f0d001
    }

    a.text-pink:hover {
        opacity: 0.8;
        color: #ff48a4
    }

    a.text-purple:hover {
        opacity: 0.8;
        color: #9d60ff
    }

    a.text-lightred:hover {
        opacity: 0.8;
        color: #ff5722
    }

    .custom-progress {
        height: 10px;
        border-radius: 50px;
        box-shadow: none;
        margin-bottom: 25px;
    }

    .progress {
        display: -ms-flexbox;
        display: flex;
        height: 1rem;
        overflow: hidden;
        font-size: .75rem;
        background-color: #e9ecef;
        border-radius: .25rem;
    }


    .bg-sky {
        background-color: #02c2c7
    }

    .bg-orange {
        background-color: #e95601
    }

    .bg-green {
        background-color: #5bbd2a
    }

    .bg-yellow {
        background-color: #f0d001
    }

    .bg-pink {
        background-color: #ff48a4
    }

    .bg-purple {
        background-color: #9d60ff
    }

    .bg-lightred {
        background-color: #ff5722
    }
</style>
<style>
    h3 {
        padding-left: 20px;
        text-transform: uppercase;
    }

    .box {
        position: relative;
        /* margin: 20px; */
        padding: 15px 7px 5px;
        width: 400px;
        min-height: 65px;
        border: 1px solid grey;
        border-radius: 3px;
        background: #fff;
    }

    .editable {
        border-color: #bd0f18;
        box-shadow: inset 0 0 10px #555;
        background: #f2f2f2;
    }

    .text {
        outline: none;
    }

    .edit,
    .save {
        width: 45px;
        display: block;
        position: absolute;
        top: 0px;
        right: 0px;
        padding: 4px 10px;
        border-top-right-radius: 2px;
        border-bottom-left-radius: 10px;
        text-align: center;
        cursor: pointer;
        box-shadow: -1px 1px 4px rgba(0, 0, 0, 0.5);
    }

    .edit {
        background: #557a11;
        color: #f0f0f0;
        opacity: 0;
        transition: opacity .2s ease-in-out;
    }

    .save {
        display: none;
        background: #bd0f18;
        color: #f0f0f0;
    }

    .box:hover .edit {
        opacity: 1;
    }
</style>
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <div class="container mt-5">
        @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
        @endif
        <div class="container">
            <div class="team-single">
                <div class="row">
                    <div class="col-lg-4 col-md-5 xs-margin-30px-bottom">
                        <div class="team-single-img">
                            <img class="team-single-imgs" src="<?php echo $User->avatar ?>" alt="">
                        </div>
                        <div class="bg-light-gray padding-30px-all md-padding-25px-all sm-padding-20px-all text-center">
                            <h4 class="margin-10px-bottom font-size24 md-font-size22 sm-font-size20 font-weight-600">
                                <?php echo UCWORDS($User->storename) ?>
                            </h4>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="team-single-text padding-50px-left sm-no-padding-left">
                            <h4 class="font-size38 sm-font-size32 xs-font-size30">
                                <?php echo UCWORDS($User->seller_name) ?>
                            </h4>
                            <br />
                            <div class="contact-info-section margin-40px-tb">
                                <ul class="list-style9 no-margin">
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Bill Title:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $Bill->title ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Bill Details:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <div class="box b_<?php echo $Bill->id ?>">
                                                    <span class="edit e_<?php echo $Bill->id ?>"
                                                        data-id='<?php echo $Bill->id ?>'>edit</span>
                                                    <span class="save s_<?php echo $Bill->id ?>">save</span>

                                                    <div class="text t_<?php echo $Bill->id ?>">
                                                        <?php echo $Bill->detail ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Date:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $Bill->created_at ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Amount:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $Bill->amount ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="row">
                                            <?php if($Reward) { foreach ($Reward as $key => $value) { ?>
                                            <div class="col-md-5 col-5">
                                                <?php if($key == 0) { ?>
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Points:</strong>
                                                <?php } ?>
                                            </div>

                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $value->points ?>
                                                </p>
                                            </div>
                                            <?php } } ?>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div style="float: right">
                            <?php 
                        if ($role == "1" || $role == "2") {
                            $actionBtn = '<a href="' . route("bill.create", 'bid='.base64_encode($id).'/'.base64_encode($event_id).'/'.base64_encode($user_id)) . '"
                        class="store btn btn-success btn-sm approve">Approve</a> 
                        <button class="delete btn btn-danger btn-sm decline" onclick="deleteItem('.$id.')">Decline</button>';
                        } 
                        echo $actionBtn;
                        ?>
                            <div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script>
            $('.edit').click(function() {
                $(this).hide();
                rowId = $(this).attr('data-id');
                $('.b_' + rowId).addClass('editable');
                $('.t_' + rowId).attr('contenteditable', 'true');
                $('.s_' + rowId).show();
                $(this).addClass('editable');
        
            });
        
            $('.save').click(function() {
                $(this).hide();
                $('.b_' + rowId).removeClass('editable');
                $('.t_' + rowId).removeAttr('contenteditable');
                $('.e_' + rowId).show();
                textVal = $.trim($('.t_' + rowId).html());
                $("title_" + rowId).val(textVal);
                $.ajax({
                    url: 'savekeywordDetails',
                    type: 'GET',
                    data: {
                        // _token: '{{ csrf_token() }}',
                        Title: textVal,
                        id: rowId
                    },
                    success: function(response) {}
                });
            });
        </script>
        <link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
        <script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
        <link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
        <script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script src="{{ URL::to('assets/js/dataTables.bootstrap5.min.js') }}"></script>
        <script>
            function deleteItem(id) {
                $("#exampleModal").modal('show');
                window.id = id;
            }
    
            $(".subBtn").click(function() {
                var reason = $("#reason").val();
                if(reason) {
                    if (confirm('Are you sure you want to decline this item?')) {
                        $.ajax({
                            url: 'bill/' + window.id,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                reasons: reason
                            },
                            success: function(response) {
                                $("#exampleModal").modal('hide');
                                $('#myTable').DataTable().ajax.reload();
                                $("#reason").val('');
                                // alert(response.success);
                            }
                        });
                    }
                } else {
                    $("#reason").css('border-color','red');
                }
            })
            function deleveryItem(id) {
                $.ajax({
                    url: 'bill/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#myTable').DataTable().ajax.reload();
                        alert(response.success);
                    }
                });
            }
        </script>
        @endsection