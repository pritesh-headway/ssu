<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Winner Announcement</title>
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
            background: linear-gradient(to right, #B61874, #DE027E, #812868);
            color: #fff;
            font-family: 'Arial', sans-serif;
        }

        .form-group-elements {
            width: 40%;
        }

        /* Content Container */
        .content-container {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 90%;
            max-width: 1500px;
            height: auto;
            padding: 20px;
            gap: 20px;
        }

        .background-animation {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            pointer-events: none;
        }


        .fireworks {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
        }

        .firework {
            position: absolute;
            background: radial-gradient(circle, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 70%);
            border-radius: 50%;
            animation: explode 2s ease-out infinite;
        }

        @keyframes explode {
            0% {
                transform: scale(0.1);
                opacity: 1;
            }

            100% {
                transform: scale(1.5);
                opacity: 0;
            }
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

        /* General Logo Styles */
        .ssu-logo {
            height: 160px;
            z-index: 50;
        }

        /* Left Logo */
        .left-logo {
            margin-left: 40px;
        }

        /* Right Logo */
        .headway-logo {
            height: 145px;
            z-index: 50;
            margin-right: 20px;
        }

        .right-logo {
            margin-right: 40px;
        }

        /* Center Text */
        .center-text {
            font-size: 130px;
            color: gold;
            text-shadow: 2px 2px 10px rgba(255, 255, 0, 0.8);
        }

        /* Left Side (Prize Image and Name) */
        .left-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        #prize-image {
            width: 100%;
            max-width: 800px;
            height: auto;
            margin-top: 100px;
        }

        .prize-name {
            font-size: 65px;
            color: white;
            margin-top: 10px;
            /* margin-left: -200px; */
        }

        /* .coupon-number-yellow{
            font-size: 95px;
                        color: gold;
                        text-shadow: 2px 2px 10px rgba(255, 255, 0, 0.8);
            } */

        /* Right Side (Winner Content) */
        .right-side {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 20px;
        }

        .winner-container h1 {
            font-size: 80px;
            color: gold;
            margin-bottom: 35rem;
            text-shadow: 2px 2px 10px rgba(255, 255, 0, 0.8);
        }

        .winner-container p {
            font-size: 35px;
            margin-bottom: 1rem;
        }

        #customer-name {
            font-size: 50px;
            text-transform: capitalize;
        }

        .coupon-number-yellow {
            color: gold;
            text-shadow: 2px 2px 10px rgba(255, 255, 0, 0.8);
            font-size: 130px;
            animation: blink-animation 1s infinite;
        }

        @keyframes blink-animation {
            0% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        /* Responsive Design */
        @media screen and (max-width: 1200px) {
            #prize-image {
                max-width: 600px;
            }

            #prize-name {
                font-size: 50px;
            }

            .winner-container h1 {
                font-size: 80px;
            }

            .winner-container p {
                font-size: 30px;
            }
        }

        @media screen and (max-width: 768px) {
            .content-container {
                flex-direction: column;
                align-items: center;
                gap: 40px;
            }

            .right-side {
                align-items: center;
                text-align: center;
            }

            #prize-image {
                max-width: 400px;
            }

            #prize-name {
                font-size: 40px;
            }

            .winner-container h1 {
                font-size: 60px;
            }

            .winner-container p {
                font-size: 25px;
            }
        }

        @media screen and (max-width: 480px) {
            #prize-image {
                max-width: 300px;
            }

            #prize-name {
                font-size: 30px;
            }

            .winner-container h1 {
                font-size: 50px;
            }

            .winner-container p {
                font-size: 20px;
            }
        }

        .winner-container {
            text-align: center;
            width: 100%;
        }
    </style>
</head>

<body>

    <?php 
        $number = '';
        if (isset($_POST['btn'])) {
            $customer_name = isset($data[0]->customer_name) ? $data[0]->customer_name : '';
            $storename = isset($data[0]->storename) ? $data[0]->storename : '';
            $city = isset($data[0]->city) ? $data[0]->city : '';
            $prize = $prize;
            $number = $_POST['number'];
    ?>
    <div class="content-container">

        <div class="fireworks">
            <div class="firework" style="width: 100px; height: 100px; left: 20%; top: 30%; animation-delay: 0s;">
            </div>
            <div class="firework" style="width: 120px; height: 120px; left: 50%; top: 10%; animation-delay: 0.5s;">
            </div>
            <div class="firework" style="width: 80px; height: 80px; left: 70%; top: 40%; animation-delay: 1s;">
            </div>
            <div class="firework" style="width: 90px; height: 90px; left: 30%; top: 70%; animation-delay: 1.5s;">
            </div>
            <div class="firework" style="width: 110px; height: 110px; left: 80%; top: 80%; animation-delay: 2s;">
            </div>
        </div>

        <div class="logo-container">
            <img src="{{ URL('public/assets/images/SSU_LOGO.png') }}" class="ssu-logo left-logo" />
            <h1 style="color: #fff; text-align:center;margin-left: -9%; font-size:70px;margin: 0;">Suvarna Saubhagya
                Utsav 2024
            </h1>
            <img src="{{ URL('public/assets/images/Logo With bg.png') }}" class="headway-logo right-logo" />
        </div>
        <div class="winner-container">
            <h1>Congratulations!</h1>
            {{-- <p>Coupon Number <br /><span class="coupon-number-yellow"><b>{{ $number }}</b></span></p> --}}
            <div style="width: 100%; text-align: center; ">
                <div
                    style="width: 80%; max-width: 1200px; margin: 0 auto; position: fixed; top: 65%; left: 50%; transform: translate(-50%, -50%);">
                    <!-- Table Header (fixed) -->
                    <table style="width: 100%; border-collapse: collapse;">
                        {{-- <thead style="background-color: #fff;color:#000;text-align:center;">
                            <tr>
                                <th>Store Name</th>
                                <th>Coupon Number</th>
                                <th>Customer Name</th>
                                <th>Customer City</th>
                                <th>Phone Number</th>
                            </tr>
                        </thead> --}}
                    </table>

                    <!-- Table Body (scrollable) -->
                    <a id="clickBtnDownload" style="color: white;display:none"
                        href="{{ url('/download-winner-list') }}?numberCountDigit={{ $numberCountDigit }}&number={{ $number }}&prizeName={{ $prize }}"
                        class="btn btn-primary"><b>Download Winner List</b></a>
                    <div style="max-height: 500px; overflow-y: auto;">

                        <table style="width: 100%; border-collapse: collapse;">
                            <thead style="background-color: #fff;color:#000;text-align:center;">
                                <tr>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Store Name</th>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Coupon Number</th>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Customer Name</th>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Customer City</th>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Phone Number</th>
                                    <th
                                        style="position: sticky; top: 0; z-index: 1; border: 1px solid #ddd; background-color:#fff;color:#000; padding: 8px; text-align: center;">
                                        Prize</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($data && count($data) > 0)
                                @foreach($data as $key => $value)
                                <tr>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        $value->storename }}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        $value->coupon_number }}
                                    </td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        ($value->customer_name) ? $value->customer_name: '-' }}
                                    </td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        ($value->city) ? $value->city : '-'
                                        }}</td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        ($value->phone_number) ? $value->phone_number : '-' }}
                                    </td>
                                    <td style="border: 1px solid #ddd; padding: 8px; text-align: left;width: auto;">{{
                                        $prize }}
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="5" style="text-align: center;">This number is already a winner.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <script>
        function callURL() {
            const button = document.getElementById('clickBtnDownload'); // Get the button element
            // button.click();
        }
        setTimeout(callURL, 3000); // 3000 milliseconds = 3 seconds
    </script>

    <?php } else {
        
        $prizes = DB::table('prizes')
        ->select('prize_name','id')
        ->where('status', '1')->orderBy('prize_name', 'ASC')->get();
    ?>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <div class="logo-container">
        <img src="{{ URL('public/assets/images/SSU_LOGO.png') }}" class="ssu-logo left-logo" />
        <h1 style="color: #fff; text-align:center;margin-left: -9%; font-size:70px">Suvarna Saubhagya Utsav 2024
        </h1>
        <img src="{{ URL('public/assets/images/Logo With bg.png') }}" class="headway-logo right-logo" />
    </div>
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
            <label for="email" style="font-size: 20px; color: #fff">Day Select:</label>
            <select name="day" class="form-control" required style="font-size: 16px">
                <?php $Currentdate = date('Y-m-d'); 
                    if ($Currentdate == '2025-01-09') { 
                ?>
                <option value='1'>First Day (Token Base)</option>
                <?php } else if ($Currentdate == '2025-01-11') { ?>
                <option value='2'>Second Day (Jewellers Wise)</option>
                <?php } else if ($Currentdate == '2025-01-12') { ?>
                <option value='3'>Third Day (Bumper Prize)</option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="email" style="font-size: 20px; color: #fff">Prize Select:</label>
            <select name="prize" class="form-control" required style="font-size: 16px">
                <option value="">Select Prize</option>
                @if ($prizes)
                @foreach ($prizes as $prize)
                <option value="{{ $prize->id }}">{{ $prize->prize_name }}</option>
                @endforeach

                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="email" style="font-size: 20px; color: #fff">Coupon Number:</label>
            <input type="text" class="form-control" minlength="3" maxlength="10" id="number" required
                placeholder="Enter number" name="number" style="font-size: 16px">
        </div>
        <button type="submit" name="btn" id="submitButton" class="btn btn-primary"
            style="font-size: 20px">Submit</button>
        <button type="reset" name="btn" class="btn btn-info" style="font-size: 20px">Reset</button>
    </form>
    <?php } ?>

</body>

</html>