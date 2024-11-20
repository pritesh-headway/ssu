<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<?php 
$number = '';
if(isset($_POST['btn'])) {
  $customer_name = isset($data->customer_name) ? $data->customer_name : '';
  $city = isset($data->city) ? $data->city : '';
  $number = $_POST['number'] .'\a\a ' ."$customer_name".'\a\a'." $city";
}
?>

<style>
  @import url("//fonts.googleapis.com/css?family=Pacifico&text=Pure");
  @import url("//fonts.googleapis.com/css?family=Roboto:700&text=css");
  @import url("//fonts.googleapis.com/css?family=Kaushan+Script&text=!");

  body {
    margin: 0;
    padding: 0;
    background: #000;
    overflow: hidden;
  }

  .pyro>.before,
  .pyro>.after {
    position: absolute;
    width: 5px;
    height: 5px;
    border-radius: 50%;
    box-shadow: -120px -218.66667px blue, 248px -16.66667px #00ff84, 190px 16.33333px #002bff, -113px -308.66667px #ff009d, -109px -287.66667px #ffb300, -50px -313.66667px #ff006e, 226px -31.66667px #ff4000, 180px -351.66667px #ff00d0, -12px -338.66667px #00f6ff, 220px -388.66667px #99ff00, -69px -27.66667px #ff0400, -111px -339.66667px #6200ff, 155px -237.66667px #00ddff, -152px -380.66667px #00ffd0, -50px -37.66667px #00ffdd, -95px -175.66667px #a6ff00, -88px 10.33333px #0d00ff, 112px -309.66667px #005eff, 69px -415.66667px #ff00a6, 168px -100.66667px #ff004c, -244px 24.33333px #ff6600, 97px -325.66667px #ff0066, -211px -182.66667px #00ffa2, 236px -126.66667px #b700ff, 140px -196.66667px #9000ff, 125px -175.66667px #00bbff, 118px -381.66667px #ff002f, 144px -111.66667px #ffae00, 36px -78.66667px #f600ff, -63px -196.66667px #c800ff, -218px -227.66667px #d4ff00, -134px -377.66667px #ea00ff, -36px -412.66667px #ff00d4, 209px -106.66667px #00fff2, 91px -278.66667px #000dff, -22px -191.66667px #9dff00, 139px -392.66667px #a6ff00, 56px -2.66667px #0099ff, -156px -276.66667px #ea00ff, -163px -233.66667px #00fffb, -238px -346.66667px #00ff73, 62px -363.66667px #0088ff, 244px -170.66667px #0062ff, 224px -142.66667px #b300ff, 141px -208.66667px #9000ff, 211px -285.66667px #ff6600, 181px -128.66667px #1e00ff, 90px -123.66667px #c800ff, 189px 70.33333px #00ffc8, -18px -383.66667px #00ff33, 100px -6.66667px #ff008c;
    -moz-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
    -webkit-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
    -o-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
    -ms-animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
    animation: 1s bang ease-out infinite backwards, 1s gravity ease-in infinite backwards, 5s position linear infinite backwards;
  }

  .pyro>.after {
    -moz-animation-delay: 1.25s, 1.25s, 1.25s;
    -webkit-animation-delay: 1.25s, 1.25s, 1.25s;
    -o-animation-delay: 1.25s, 1.25s, 1.25s;
    -ms-animation-delay: 1.25s, 1.25s, 1.25s;
    animation-delay: 1.25s, 1.25s, 1.25s;
    -moz-animation-duration: 1.25s, 1.25s, 6.25s;
    -webkit-animation-duration: 1.25s, 1.25s, 6.25s;
    -o-animation-duration: 1.25s, 1.25s, 6.25s;
    -ms-animation-duration: 1.25s, 1.25s, 6.25s;
    animation-duration: 1.25s, 1.25s, 6.25s;
  }

  @-webkit-keyframes bang {
    from {
      box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
    }
  }

  @-moz-keyframes bang {
    from {
      box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
    }
  }

  @-o-keyframes bang {
    from {
      box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
    }
  }

  @-ms-keyframes bang {
    from {
      box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
    }
  }

  @keyframes bang {
    from {
      box-shadow: 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white, 0 0 white;
    }
  }

  @-webkit-keyframes gravity {
    to {
      transform: translateY(200px);
      -moz-transform: translateY(200px);
      -webkit-transform: translateY(200px);
      -o-transform: translateY(200px);
      -ms-transform: translateY(200px);
      opacity: 0;
    }
  }

  @-moz-keyframes gravity {
    to {
      transform: translateY(200px);
      -moz-transform: translateY(200px);
      -webkit-transform: translateY(200px);
      -o-transform: translateY(200px);
      -ms-transform: translateY(200px);
      opacity: 0;
    }
  }

  @-o-keyframes gravity {
    to {
      transform: translateY(200px);
      -moz-transform: translateY(200px);
      -webkit-transform: translateY(200px);
      -o-transform: translateY(200px);
      -ms-transform: translateY(200px);
      opacity: 0;
    }
  }

  @-ms-keyframes gravity {
    to {
      transform: translateY(200px);
      -moz-transform: translateY(200px);
      -webkit-transform: translateY(200px);
      -o-transform: translateY(200px);
      -ms-transform: translateY(200px);
      opacity: 0;
    }
  }

  @keyframes gravity {
    to {
      transform: translateY(200px);
      -moz-transform: translateY(200px);
      -webkit-transform: translateY(200px);
      -o-transform: translateY(200px);
      -ms-transform: translateY(200px);
      opacity: 0;
    }
  }

  @-webkit-keyframes position {

    0%,
    19.9% {
      margin-top: 10%;
      margin-left: 40%;
    }

    20%,
    39.9% {
      margin-top: 40%;
      margin-left: 30%;
    }

    40%,
    59.9% {
      margin-top: 20%;
      margin-left: 70%;
    }

    60%,
    79.9% {
      margin-top: 30%;
      margin-left: 20%;
    }

    80%,
    99.9% {
      margin-top: 30%;
      margin-left: 80%;
    }
  }

  @-moz-keyframes position {

    0%,
    19.9% {
      margin-top: 10%;
      margin-left: 40%;
    }

    20%,
    39.9% {
      margin-top: 40%;
      margin-left: 30%;
    }

    40%,
    59.9% {
      margin-top: 20%;
      margin-left: 70%;
    }

    60%,
    79.9% {
      margin-top: 30%;
      margin-left: 20%;
    }

    80%,
    99.9% {
      margin-top: 30%;
      margin-left: 80%;
    }
  }

  @-o-keyframes position {

    0%,
    19.9% {
      margin-top: 10%;
      margin-left: 40%;
    }

    20%,
    39.9% {
      margin-top: 40%;
      margin-left: 30%;
    }

    40%,
    59.9% {
      margin-top: 20%;
      margin-left: 70%;
    }

    60%,
    79.9% {
      margin-top: 30%;
      margin-left: 20%;
    }

    80%,
    99.9% {
      margin-top: 30%;
      margin-left: 80%;
    }
  }

  @-ms-keyframes position {

    0%,
    19.9% {
      margin-top: 10%;
      margin-left: 40%;
    }

    20%,
    39.9% {
      margin-top: 40%;
      margin-left: 30%;
    }

    40%,
    59.9% {
      margin-top: 20%;
      margin-left: 70%;
    }

    60%,
    79.9% {
      margin-top: 30%;
      margin-left: 20%;
    }

    80%,
    99.9% {
      margin-top: 30%;
      margin-left: 80%;
    }
  }

  @keyframes position {

    0%,
    19.9% {
      margin-top: 10%;
      margin-left: 40%;
    }

    20%,
    39.9% {
      margin-top: 40%;
      margin-left: 30%;
    }

    40%,
    59.9% {
      margin-top: 20%;
      margin-left: 70%;
    }

    60%,
    79.9% {
      margin-top: 30%;
      margin-left: 20%;
    }

    80%,
    99.9% {
      margin-top: 30%;
      margin-left: 80%;
    }
  }

  h1 {
    color: #fff;
    text-align: center;
    padding-top: 15%;
  }

  .stage {
    /* height: 300px; */
    width: 500px;
    margin: auto;
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    perspective: 9999px;
    transform-style: preserve-3d;
  }

  .layer {
    width: 100%;
    height: 100%;
    position: absolute;
    transform-style: preserve-3d;
    animation: ಠ_ಠ 5s infinite alternate ease-in-out -7.5s;
    animation-fill-mode: forwards;
    transform: rotateY(40deg) rotateX(33deg) translateZ(0);
  }

  .layer:after {
    font: 150px/0.65 "Pacifico", "Kaushan Script", Futura, "Roboto", "Trebuchet MS", Helvetica, sans-serif;
    content: "<?php echo $number ?>";
    white-space: pre;
    text-align: center;
    height: 100%;
    width: 100%;
    position: absolute;
    top: 50px;
    color: whitesmoke;
    letter-spacing: -2px;
    text-shadow: 4px 0 10px rgba(0, 0, 0, 0.13);
  }

  .layer:nth-child(1):after {
    transform: translateZ(0px);
  }

  .layer:nth-child(2):after {
    transform: translateZ(-1.5px);
  }

  .layer:nth-child(3):after {
    transform: translateZ(-3px);
  }

  .layer:nth-child(4):after {
    transform: translateZ(-4.5px);
  }

  .layer:nth-child(5):after {
    transform: translateZ(-6px);
  }

  .layer:nth-child(6):after {
    transform: translateZ(-7.5px);
  }

  .layer:nth-child(7):after {
    transform: translateZ(-9px);
  }

  .layer:nth-child(8):after {
    transform: translateZ(-10.5px);
  }

  .layer:nth-child(9):after {
    transform: translateZ(-12px);
  }

  .layer:nth-child(10):after {
    transform: translateZ(-13.5px);
  }

  .layer:nth-child(11):after {
    transform: translateZ(-15px);
  }

  .layer:nth-child(12):after {
    transform: translateZ(-16.5px);
  }

  .layer:nth-child(13):after {
    transform: translateZ(-18px);
  }

  .layer:nth-child(14):after {
    transform: translateZ(-19.5px);
  }

  .layer:nth-child(15):after {
    transform: translateZ(-21px);
  }

  .layer:nth-child(16):after {
    transform: translateZ(-22.5px);
  }

  .layer:nth-child(17):after {
    transform: translateZ(-24px);
  }

  .layer:nth-child(18):after {
    transform: translateZ(-25.5px);
  }

  .layer:nth-child(19):after {
    transform: translateZ(-27px);
  }

  .layer:nth-child(20):after {
    transform: translateZ(-28.5px);
  }

  .layer:nth-child(n+10):after {
    -webkit-text-stroke: 3px rgba(0, 0, 0, 0.25);
  }

  .layer:nth-child(n+11):after {
    -webkit-text-stroke: 15px dodgerblue;
    text-shadow: 6px 0 6px #00366b, 5px 5px 5px #002951, 0 6px 6px #00366b;
  }

  .layer:nth-child(n+12):after {
    -webkit-text-stroke: 15px #0077ea;
  }

  .layer:last-child:after {
    -webkit-text-stroke: 17px rgba(0, 0, 0, 0.1);
  }

  .layer:first-child:after {
    color: #fff;
    text-shadow: none;
  }

  @keyframes ಠ_ಠ {
    100% {
      transform: rotateY(-40deg) rotateX(-43deg);
    }
  }
</style>
<?php 
if(isset($_POST['btn'])) { 
  $number = $_POST['number'];
?>
<div class="pyro">
  <div class="before"></div>
  <div class="stage">
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
    <div class="layer"></div>
  </div>

  <div class="after"></div>
</div>
<?php } else { ?>
<div class="container">
  <form action="" method="POST">
    @csrf
    <div class="form-group">
      <label for="email">Coupon Number:</label>
      <input type="text" class="form-control" id="number" placeholder="Enter number" name="number">
    </div>
    <button type="submit" name="btn" class="btn btn-default">Submit</button>
  </form>
</div>


<?php } ?>