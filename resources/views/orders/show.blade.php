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
                            <img class="team-single-imgs" src="<?php echo $couponData->avatar ?>" alt="">
                        </div>
                        <div class="bg-light-gray padding-30px-all md-padding-25px-all sm-padding-20px-all text-center">
                            <h4 class="margin-10px-bottom font-size24 md-font-size22 sm-font-size20 font-weight-600">
                                <?php echo UCWORDS($couponData->storename) ?>
                            </h4>
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-7">
                        <div class="team-single-text padding-50px-left sm-no-padding-left">
                            <h4 class="font-size38 sm-font-size32 xs-font-size30">
                                <?php echo UCWORDS($couponData->seller_name) ?>
                            </h4>
                            <br />
                            <div class="contact-info-section margin-40px-tb">
                                <ul class="list-style9 no-margin">
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-graduation-cap text-orange"></i>
                                                <strong class="margin-10px-left text-orange">Date:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $couponData->order_date ?>
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="far fa-gem text-yellow"></i>
                                                <strong class="margin-10px-left text-yellow">Quantity:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <?php echo $couponData->quantity ?>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="far fa-file text-lightred"></i>
                                                <strong class="margin-10px-left text-lightred">Coupons Range:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">

                                                <?php if($slotdata) { foreach ($slotdata as $key => $value) { ?>

                                                <p>
                                                    <b>Book Slot {{ $key+1 }}: </b>
                                                    <?php echo $value->slot ?>
                                                </p>
                                                <?php } } ?>
                                                <a style="float: right" class="store btn btn-warning btn-sm approve"
                                                    href="{{ URL('slots/'.$order['id']) }}">Edit Slot</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-map-marker-alt text-green"></i>
                                                <strong class="margin-10px-left text-green">Address:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo ($couponData->address) ? $couponData->address : '-' ?>
                                                </p>
                                            </div>
                                        </div>

                                    </li>
                                    <li>

                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-mobile-alt text-purple"></i>
                                                <strong
                                                    class="margin-10px-left xs-margin-four-left text-purple">Phone:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p>
                                                    <?php echo $couponData->phone_number ?>
                                                </p>
                                            </div>
                                        </div>

                                    </li>
                                    <li>
                                        <div class="row">
                                            <div class="col-md-5 col-5">
                                                <i class="fas fa-envelope text-pink"></i>
                                                <strong
                                                    class="margin-10px-left xs-margin-four-left text-pink">Email:</strong>
                                            </div>
                                            <div class="col-md-7 col-7">
                                                <p><a href="javascript:void(0)">
                                                        <?php echo $couponData->email ?>
                                                    </a></p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<link href="{{ URL::to('assets/css/bootstrap.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery-3.js') }}"></script>
<link href="{{ URL::to('assets/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet">
<script src="{{ URL::to('assets/js/jquery.dataTables.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
</script>
<script src="{{ URL::to('assets/js/dataTables.bootstrap5.min.js') }}"></script>

@endsection