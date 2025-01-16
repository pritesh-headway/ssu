<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ramp Walk</title>
    <style>
        /* General Reset */
        body,
        html {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background-image: url('public/assets/images/rampwalk.png');
            background-repeat: no-repeat;
            background-size: 100% 100%;
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .form-group-elements {
            width: 40%;
        }

        .content-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            height: auto;
            padding: 20px;
            text-align: center;
        }


        .logo-container {
            position: fixed;
            top: 50px;
            left: 0;
            width: 100%;
            height: 100px;
            z-index: 50;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
        }

        .winner-container img.storeimage {
            width: 250px;
            height: 200px;
            border: 5px double gold;
            border-radius: 0;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .winner-container .city {
            font-size: 54px;
            color: lightblue;
            margin-bottom: 10px;
        }

        .winner-container h1 {
            font-size: 80px;
            color: gold;
            margin: 0;
            text-shadow: 2px 2px 10px rgba(255, 255, 0, 0.8);
        }
    </style>
</head>

<body>

    <?php 
        $number = '';
        if (isset($_POST['btn'])) {
    ?>
    <div class="content-container">
        <div class="winner-container">
            <img class="storeimage" src="{{ URL('public/profile_images/'.$data->avatar) }}">
            <h1>{{ $data->storename }}</h1>
            <h4 class="city">{{ $data->city }}</h4>

        </div>
    </div>
    </div>

    <?php } else {
        
        $stores = DB::table('users')
        ->select('storename','id')
        ->where('status', '1')->where('user_type', 2)->get();
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    {{-- <div class="logo-container">
        <img src="{{ URL('public/assets/images/SSU_LOGO.png') }}" class="ssu-logo left-logo" />
        <h1 style="color: #fff; text-align:center;margin-left: -9%; font-size:70px">Suvarna Saubhagya Utsav 2024</h1>
        <img src="{{ URL('public/assets/images/Logo With bg.png') }}" class="headway-logo right-logo" />
    </div> --}}
    <form action="" id="myForm" method="POST" class="form-group-elements">
        <div class="overlay" id="overlay"></div>
        <div class="loader" id="loader"></div>
        @csrf
        <div class="form-group">
            <label for="email" style="font-size: 20px; color: #fff">Select Event:</label>
            <select name="event" class="form-control" required style="font-size: 16px">
                <option value='1'>SSU 2024</option>
            </select>
        </div>
        <div class="form-group">
            <label for="email" style="font-size: 20px; color: #fff">Select Store Name:</label>
            <select name="user_id" class="form-control" required style="font-size: 16px">
                <option value=''>Select Store Name</option>
                <?php foreach ($stores as $key => $value) {
                   ?>
                <option value='{{ $value->id }}'>{{ $value->storename }}</option>
                <?php } ?>
            </select>
        </div>

        <button type="submit" name="btn" id="submitButton" class="btn btn-primary"
            style="font-size: 20px">Submit</button>
        <button type="reset" name="btn" class="btn btn-info" style="font-size: 20px">Reset</button>
    </form>
    <script>

    </script>
    <?php } ?>

</body>

</html>