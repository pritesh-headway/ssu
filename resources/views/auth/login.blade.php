@extends('layouts.app')
@section('content')
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
{!! Toastr::message() !!}
<div class="main-container min-h-screen text-black dark:text-white-dark">
    <!-- start main content section -->
    <div x-data="auth">
        <div class="absolute inset-0">
            <img src="assets/images/auth/bg-gradient.png" alt="image"
                class="h-full w-full object-cover team-single-imgs">
        </div>
        <div
            class="relative flex min-h-screen items-center justify-center bg-[url(../images/auth/map.png)] bg-cover bg-center bg-no-repeat px-6 py-10 dark:bg-[#060818] sm:px-16">
            <img src="assets/images/auth/coming-soon-object1.png" alt="image"
                class="absolute left-0 top-1/2 h-full max-h-[893px] -translate-y-1/2 team-single-imgs">
            <img src="assets/images/auth/coming-soon-object2.png" alt="image"
                class="absolute left-24 top-0 h-40 md:left-[30%] team-single-imgs">
            <img src="assets/images/auth/coming-soon-object3.png" alt="image"
                class="absolute right-0 top-0 h-[300px] team-single-imgs">
            <img src="assets/images/auth/polygon-object.svg" alt="image"
                class="absolute bottom-0 end-[28%] team-single-imgs">
            <div
                class="relative flex w-full max-w-[1502px] flex-col justify-between overflow-hidden rounded-md bg-white/60 backdrop-blur-lg dark:bg-black/50 lg:min-h-[758px] lg:flex-row lg:gap-10 xl:gap-0">
                <div
                    class="relative hidden w-full items-center justify-center bg-[linear-gradient(225deg,rgba(239,18,98,1)_0%,rgba(67,97,238,1)_100%)] p-5 lg:inline-flex lg:max-w-[835px] xl:-ms-32 ltr:xl:skew-x-[14deg] rtl:xl:skew-x-[-14deg]">
                    <div
                        class="absolute inset-y-0 w-8 from-primary/10 via-transparent to-transparent ltr:-right-10 ltr:bg-gradient-to-r rtl:-left-10 rtl:bg-gradient-to-l xl:w-16 ltr:xl:-right-20 rtl:xl:-left-20">
                    </div>
                    <div class="ltr:xl:-skew-x-[14deg] rtl:xl:skew-x-[14deg]">

                        <div class="mt-24 hidden w-full max-w-[430px] lg:block">
                            <img src="assets/images/auth/login.svg" alt="Cover Image" class="w-full team-single-imgs">
                        </div>
                    </div>
                </div>
                <div
                    class="relative flex w-full flex-col items-center justify-center gap-6 px-4 pb-16 pt-6 sm:px-6 lg:max-w-[667px]">
                    <div
                        class="flex w-full max-w-[440px] items-center gap-2 lg:absolute lg:end-6 lg:top-6 lg:max-w-full">
                        <a href="index.html" class="block w-8 lg:hidden">
                            <img src="assets/images/logo.png" alt="Logo" class="w-full team-single-imgs">
                        </a>
                        <div class="dropdown ms-auto w-max" x-data="dropdown" @click.outside="open = false">

                            <ul x-cloak="" x-show="open" x-transition="" x-transition.duration.300ms=""
                                class="top-11 grid w-[280px] grid-cols-2 gap-y-2 !px-2 font-semibold text-dark ltr:-right-14 rtl:-left-14 dark:text-white-dark dark:text-white-light/90 sm:ltr:-right-2 sm:rtl:-left-2">
                                <template x-for="item in languages">
                                    <li>
                                        <a href="javascript:;" class="hover:text-primary"
                                            @click="$store.app.toggleLocale(item.value),toggle()"
                                            :class="{'bg-primary/10 text-primary' : $store.app.locale == item.value}">
                                            <img class="h-5 w-5 rounded-full object-cover"
                                                :src="`assets/images/flags/${item.value.toUpperCase()}.svg`"
                                                alt="image">
                                            <span class="ltr:ml-3 rtl:mr-3" x-text="item.key"></span>
                                        </a>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div class="w-full max-w-[440px] lg:mt-16">
                        <div class="mb-10">
                            <h1 class="text-3xl font-extrabold uppercase !leading-snug text-primary md:text-4xl">Sign in
                            </h1>
                            <p class="text-base font-bold leading-normal text-white-dark">Enter your email and password
                                to login</p>
                        </div>
                        <form class="space-y-5 dark:text-white" action="{{ route('login') }}" method="POST">
                            @csrf
                            <div>
                                <label for="Email">Email</label>
                                <div class="relative text-white-dark">
                                    <input id="Email" required type="email" name="email" placeholder="Enter Email"
                                        class="form-input ps-10 placeholder:text-white-dark">
                                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                        <svg width="18" height="18" viewbox="0 0 18 18" fill="none">
                                            <path opacity="0.5"
                                                d="M10.65 2.25H7.35C4.23873 2.25 2.6831 2.25 1.71655 3.23851C0.75 4.22703 0.75 5.81802 0.75 9C0.75 12.182 0.75 13.773 1.71655 14.7615C2.6831 15.75 4.23873 15.75 7.35 15.75H10.65C13.7613 15.75 15.3169 15.75 16.2835 14.7615C17.25 13.773 17.25 12.182 17.25 9C17.25 5.81802 17.25 4.22703 16.2835 3.23851C15.3169 2.25 13.7613 2.25 10.65 2.25Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M14.3465 6.02574C14.609 5.80698 14.6445 5.41681 14.4257 5.15429C14.207 4.89177 13.8168 4.8563 13.5543 5.07507L11.7732 6.55931C11.0035 7.20072 10.4691 7.6446 10.018 7.93476C9.58125 8.21564 9.28509 8.30993 9.00041 8.30993C8.71572 8.30993 8.41956 8.21564 7.98284 7.93476C7.53168 7.6446 6.9973 7.20072 6.22761 6.55931L4.44652 5.07507C4.184 4.8563 3.79384 4.89177 3.57507 5.15429C3.3563 5.41681 3.39177 5.80698 3.65429 6.02574L5.4664 7.53583C6.19764 8.14522 6.79033 8.63914 7.31343 8.97558C7.85834 9.32604 8.38902 9.54743 9.00041 9.54743C9.6118 9.54743 10.1425 9.32604 10.6874 8.97558C11.2105 8.63914 11.8032 8.14522 12.5344 7.53582L14.3465 6.02574Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div>
                                <label for="Password">Password</label>
                                <div class="relative text-white-dark">
                                    <input id="Password" required type="password" name="password"
                                        placeholder="Enter Password"
                                        class="form-input ps-10 placeholder:text-white-dark">
                                    <span class="absolute start-4 top-1/2 -translate-y-1/2">
                                        <svg width="18" height="18" viewbox="0 0 18 18" fill="none">
                                            <path opacity="0.5"
                                                d="M1.5 12C1.5 9.87868 1.5 8.81802 2.15901 8.15901C2.81802 7.5 3.87868 7.5 6 7.5H12C14.1213 7.5 15.182 7.5 15.841 8.15901C16.5 8.81802 16.5 9.87868 16.5 12C16.5 14.1213 16.5 15.182 15.841 15.841C15.182 16.5 14.1213 16.5 12 16.5H6C3.87868 16.5 2.81802 16.5 2.15901 15.841C1.5 15.182 1.5 14.1213 1.5 12Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M6 12.75C6.41421 12.75 6.75 12.4142 6.75 12C6.75 11.5858 6.41421 11.25 6 11.25C5.58579 11.25 5.25 11.5858 5.25 12C5.25 12.4142 5.58579 12.75 6 12.75Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M9 12.75C9.41421 12.75 9.75 12.4142 9.75 12C9.75 11.5858 9.41421 11.25 9 11.25C8.58579 11.25 8.25 11.5858 8.25 12C8.25 12.4142 8.58579 12.75 9 12.75Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M12.75 12C12.75 12.4142 12.4142 12.75 12 12.75C11.5858 12.75 11.25 12.4142 11.25 12C11.25 11.5858 11.5858 11.25 12 11.25C12.4142 11.25 12.75 11.5858 12.75 12Z"
                                                fill="currentColor"></path>
                                            <path
                                                d="M5.0625 6C5.0625 3.82538 6.82538 2.0625 9 2.0625C11.1746 2.0625 12.9375 3.82538 12.9375 6V7.50268C13.363 7.50665 13.7351 7.51651 14.0625 7.54096V6C14.0625 3.20406 11.7959 0.9375 9 0.9375C6.20406 0.9375 3.9375 3.20406 3.9375 6V7.54096C4.26488 7.51651 4.63698 7.50665 5.0625 7.50268V6Z"
                                                fill="currentColor"></path>
                                        </svg>
                                    </span>
                                </div>
                            </div>

                            <button type="submit"
                                class="btn btn-gradient !mt-6 w-full border-0 uppercase shadow-[0_10px_20px_-10px_rgba(67,97,238,0.44)]">
                                Sign in
                            </button>
                        </form>

                        <div class="relative my-7 text-center md:mb-9">
                            <span
                                class="absolute inset-x-0 top-1/2 h-px w-full -translate-y-1/2 bg-white-light dark:bg-white-dark"></span>
                        </div>

                        {{-- <div class="text-center dark:text-white">
                            Don't have an account ?
                            <a href="{{ route('register') }}"
                                class="uppercase text-primary underline transition hover:text-black dark:hover:text-white">SIGN
                                UP</a>
                        </div> --}}
                    </div>
                    <p class="absolute bottom-6 w-full text-center dark:text-white">
                        © <span id="footer-year">
                            <?php echo date('Y') ?>
                        </span>. SSU All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <!-- end main content section -->
</div>

@endsection
<script>
    @if(session('success'))
        toastr.success("{{ session('success') }}");
    @endif

    @if(session('error'))
        toastr.error("{{ session('error') }}");
    @endif

    @if(session('info'))
        toastr.info("{{ session('info') }}");
    @endif

    @if(session('warning'))
        toastr.warning("{{ session('warning') }}");
    @endif
</script>

<script>
    toastr.options = {
       "closeButton": true,
       "progressBar": true,
       "positionClass": "toast-top-right",
       "showDuration": "300",
       "hideDuration": "1000",
       "timeOut": "5000",
       "extendedTimeOut": "1000",
       "showEasing": "swing",
       "hideEasing": "linear",
       "showMethod": "fadeIn",
       "hideMethod": "fadeOut"
   }
</script>