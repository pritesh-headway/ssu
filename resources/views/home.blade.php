@extends('layouts.master')
@section('content')
{{-- message --}}
{!! Toastr::message() !!}
<div class="animate__animated p-6" :class="[$store.app.animation]">
    <!-- start main content section -->
    <div x-data="sales">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>
            <li class="before:content-[''] ltr:before:mr-1 rtl:before:ml-1">
                <span></span>
            </li>
        </ul>

        <div class="pt-5">
            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-6">
                <div class="panel h-full">
                    <div class="space-y-9">
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5"
                                            d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Number Of Coupons</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $totalCoupn }}</p>
                                </div>
                                {{-- <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-11/12 rounded-full bg-gradient-to-r from-[#7579ff] to-[#b224ef]"
                                        style="width: 60%">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                            transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                            stroke-width="1.5"></circle>
                                        <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Number of Sold Coupons</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $soldCoupon }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]"
                                        style="width: 60%"></div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Total Sellers Count</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $sellerCount }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#f09819] to-[#ff5858]"
                                        style="width: {{ $sellerCount / 100 }}%"></div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round">
                                        </path>
                                        <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Total Customer</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $customerCount }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#04befe] to-[#ff5858]"
                                        style="width: {{ $customerCount / 100 }}%">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel h-full">

                    <div class="space-y-9">
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5"
                                            d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Todays Total Number of Coupons</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $todaysCoupons }}</p>
                                </div>
                                {{-- <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-11/12 rounded-full bg-gradient-to-r from-[#7579ff] to-[#b224ef]"
                                        style="width: 60%">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                            transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                            stroke-width="1.5"></circle>
                                        <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                            stroke-width="1.5" stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Total Remaining Coupons</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $todaysRemainingCoupons }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]"
                                        style="width: {{ $todaysRemainingCoupons / 100 }}%"></div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Todays New Sellers Count</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $sellerTodayCount }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#f09819] to-[#ff5858]"
                                        style="width: {{ $sellerTodayCount / 100 }}%"></div>
                                </div> --}}
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                <div
                                    class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                                    <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                            stroke="currentColor" stroke-width="1.5"></path>
                                        <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round"></path>
                                        <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round">
                                        </path>
                                        <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round">
                                        </path>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1">
                                <div class="mb-2 flex font-semibold text-white-dark">
                                    <h6>Todays New Customers</h6>
                                    <p class="ltr:ml-auto rtl:mr-auto">{{ $customerTodayCount }}</p>
                                </div>
                                {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                    <div class="h-full w-full rounded-full bg-gradient-to-r from-[#04befe] to-[#ff5858]"
                                        style="width: {{ $customerTodayCount / 100 }}%">
                                    </div>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <div class="panel h-full pb-0 sm:col-span-3 xl:col-span-2">
                    <h5 class="mb-5 text-lg font-semibold dark:text-white-light">Coupon History</h5>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th class="ltr:rounded-l-md rtl:rounded-r-md">Order ID</th>
                                    <th>Seller</th>
                                    <th>No. of Coupons</th>
                                    <th class="ltr:rounded-r-md rtl:rounded-l-md">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if($orderList) { foreach ($orderList as $key => $value) {
                                    if($key <= 6) {
                                ?>
                                <tr class="group text-white-dark hover:text-black dark:hover:text-white-light/90">
                                    <td class="text-primary">#00010{{ $value->id }}</td>
                                    <td class="min-w-[150px] text-black dark:text-white">
                                        <div class="flex items-center">
                                            <img class="h-8 w-8 rounded-md object-cover ltr:mr-3 rtl:ml-3"
                                                src="{{ $base_url.'/public/profile_images/'.$value->avatar }}"
                                                alt="avatar">
                                            <span class="whitespace-nowrap">{{ $value->seller_name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-primary">{{ $value->quantity }}</td>
                                    <td>
                                        <?php if($value->order_status == 'Approved') { ?>
                                        <span
                                            class="badge bg-success shadow-md dark:group-hover:bg-transparent">Approved</span>
                                        <?php } ?>

                                        <?php if($value->order_status == 'Pending') { ?>
                                        <span
                                            class="badge bg-danger shadow-md dark:group-hover:bg-transparent">Pending</span>
                                        <?php } ?>

                                        <?php if($value->order_status == 'Declined') { ?>
                                        <span
                                            class="badge bg-warning shadow-md dark:group-hover:bg-transparent">Declined</span>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } } } ?>

                            </tbody>
                        </table>
                    </div>
                    <?php if(count($orderList) > 7) { ?>
                    <div class="border-t border-white-light dark:border-white/10">
                        <a href="{{ route('seller.index') }}"
                            class="group group flex items-center justify-center p-4 font-semibold hover:text-primary">
                            View All
                            <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1 ltr:ml-1 rtl:mr-1 rtl:rotate-180 rtl:group-hover:-translate-x-1"
                                viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                    <?php } ?>
                </div>

                {{-- <div class="panel h-full pb-0 sm:col-span-2 xl:col-span-1">
                    <h5 class="mb-5 text-lg font-semibold dark:text-white-light">Event Listing</h5>

                    <div class="perfect-scrollbar relative -mr-3 mb-4 h-[290px] pr-3">
                        <div class="cursor-pointer text-sm">
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-primary ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Updated Server Logs</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">Just Now
                                </div>

                                <span
                                    class="badge badge-outline-primary absolute bg-primary-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-success ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Send Mail to HR and Admin</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">2 min
                                    ago</div>

                                <span
                                    class="badge badge-outline-success absolute bg-success-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-danger ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Backup Files EOD</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">14:00
                                </div>

                                <span
                                    class="badge badge-outline-danger absolute bg-danger-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-black ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Collect documents from Sara</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">16:00
                                </div>

                                <span
                                    class="badge badge-outline-dark absolute bg-dark-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-warning ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Conference call with Marketing Manager.</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">17:00
                                </div>

                                <span
                                    class="badge badge-outline-warning absolute bg-warning-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">In
                                    progress</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-info ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Rebooted Server</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">17:00
                                </div>

                                <span
                                    class="badge badge-outline-info absolute bg-info-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-secondary ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Send contract details to Freelancer</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">18:00
                                </div>

                                <span
                                    class="badge badge-outline-secondary absolute bg-secondary-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-primary ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Updated Server Logs</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">Just Now
                                </div>

                                <span
                                    class="badge badge-outline-primary absolute bg-primary-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-success ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Send Mail to HR and Admin</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">2 min
                                    ago</div>

                                <span
                                    class="badge badge-outline-success absolute bg-success-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-danger ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Backup Files EOD</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">14:00
                                </div>

                                <span
                                    class="badge badge-outline-danger absolute bg-danger-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-black ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Collect documents from Sara</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">16:00
                                </div>

                                <span
                                    class="badge badge-outline-dark absolute bg-dark-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-warning ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Conference call with Marketing Manager.</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">17:00
                                </div>

                                <span
                                    class="badge badge-outline-warning absolute bg-warning-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">In
                                    progress</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-info ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Rebooted Server</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">17:00
                                </div>

                                <span
                                    class="badge badge-outline-info absolute bg-info-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Completed</span>
                            </div>
                            <div class="group relative flex items-center py-1.5">
                                <div class="h-1.5 w-1.5 rounded-full bg-secondary ltr:mr-1 rtl:ml-1.5"></div>
                                <div class="flex-1">Send contract details to Freelancer</div>
                                <div class="text-xs text-white-dark ltr:ml-auto rtl:mr-auto dark:text-gray-500">18:00
                                </div>

                                <span
                                    class="badge badge-outline-secondary absolute bg-secondary-light text-xs opacity-0 group-hover:opacity-100 ltr:right-0 rtl:left-0 dark:bg-[#0e1726]">Pending</span>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-white-light dark:border-white/10">
                        <a href="javascript:;"
                            class="group group flex items-center justify-center p-4 font-semibold hover:text-primary">
                            View All
                            <svg class="h-4 w-4 transition duration-300 group-hover:translate-x-1 ltr:ml-1 rtl:mr-1 rtl:rotate-180 rtl:group-hover:-translate-x-1"
                                viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 12H20M20 12L14 6M20 12L14 18" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                        </a>
                    </div>
                </div> --}}

                <div class="panel h-full overflow-hidden border-0 p-0">
                    <div class="min-h-[20px] bg-gradient-to-r from-[#4361ee] to-[#160f6b] p-6">

                        <div class="flex items-center justify-between text-white">
                            <p class="text-xl">Coupon Overview</p>
                        </div>
                    </div>

                    <div class="p-5">
                        <div class="space-y-9">
                            <div class="flex items-center">
                                <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                    <div
                                        class="grid h-9 w-9 place-content-center rounded-full bg-secondary-light text-secondary dark:bg-secondary dark:text-secondary-light">
                                        <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.74157 18.5545C4.94119 20 7.17389 20 11.6393 20H12.3605C16.8259 20 19.0586 20 20.2582 18.5545M3.74157 18.5545C2.54194 17.1091 2.9534 14.9146 3.77633 10.5257C4.36155 7.40452 4.65416 5.84393 5.76506 4.92196M3.74157 18.5545C3.74156 18.5545 3.74157 18.5545 3.74157 18.5545ZM20.2582 18.5545C21.4578 17.1091 21.0464 14.9146 20.2235 10.5257C19.6382 7.40452 19.3456 5.84393 18.2347 4.92196M20.2582 18.5545C20.2582 18.5545 20.2582 18.5545 20.2582 18.5545ZM18.2347 4.92196C17.1238 4 15.5361 4 12.3605 4H11.6393C8.46374 4 6.87596 4 5.76506 4.92196M18.2347 4.92196C18.2347 4.92196 18.2347 4.92196 18.2347 4.92196ZM5.76506 4.92196C5.76506 4.92196 5.76506 4.92196 5.76506 4.92196Z"
                                                stroke="currentColor" stroke-width="1.5"></path>
                                            <path opacity="0.5"
                                                d="M9.1709 8C9.58273 9.16519 10.694 10 12.0002 10C13.3064 10 14.4177 9.16519 14.8295 8"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-2 flex font-semibold text-white-dark">
                                        <h6>Order Pending</h6>
                                        <p class="ltr:ml-auto rtl:mr-auto">{{ $pedingCoupon }}</p>
                                    </div>
                                    {{-- <div class="h-2 rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                        <div class="h-full w-11/12 rounded-full bg-gradient-to-r from-[#7579ff] to-[#b224ef]"
                                            style="width: {{ $pedingCoupon / 100 }}%">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                    <div
                                        class="grid h-9 w-9 place-content-center rounded-full bg-success-light text-success dark:bg-success dark:text-success-light">
                                        <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.72848 16.1369C3.18295 14.5914 2.41018 13.8186 2.12264 12.816C1.83509 11.8134 2.08083 10.7485 2.57231 8.61875L2.85574 7.39057C3.26922 5.59881 3.47597 4.70292 4.08944 4.08944C4.70292 3.47597 5.59881 3.26922 7.39057 2.85574L8.61875 2.57231C10.7485 2.08083 11.8134 1.83509 12.816 2.12264C13.8186 2.41018 14.5914 3.18295 16.1369 4.72848L17.9665 6.55812C20.6555 9.24711 22 10.5916 22 12.2623C22 13.933 20.6555 15.2775 17.9665 17.9665C15.2775 20.6555 13.933 22 12.2623 22C10.5916 22 9.24711 20.6555 6.55812 17.9665L4.72848 16.1369Z"
                                                stroke="currentColor" stroke-width="1.5"></path>
                                            <circle opacity="0.5" cx="8.60699" cy="8.87891" r="2"
                                                transform="rotate(-45 8.60699 8.87891)" stroke="currentColor"
                                                stroke-width="1.5"></circle>
                                            <path opacity="0.5" d="M11.5417 18.5L18.5208 11.5208" stroke="currentColor"
                                                stroke-width="1.5" stroke-linecap="round">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-2 flex font-semibold text-white-dark">
                                        <h6>Coupon Sold</h6>
                                        <p class="ltr:ml-auto rtl:mr-auto">{{ $soldCoupon }}</p>
                                    </div>
                                    {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                        <div class="h-full w-full rounded-full bg-gradient-to-r from-[#3cba92] to-[#0ba360]"
                                            style="width: {{ $soldCoupon / 100 }}%">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                            <div class="flex items-center">
                                <div class="h-9 w-9 ltr:mr-3 rtl:ml-3">
                                    <div
                                        class="grid h-9 w-9 place-content-center rounded-full bg-warning-light text-warning dark:bg-warning dark:text-warning-light">
                                        <svg width="20" height="20" viewbox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M2 12C2 8.22876 2 6.34315 3.17157 5.17157C4.34315 4 6.22876 4 10 4H14C17.7712 4 19.6569 4 20.8284 5.17157C22 6.34315 22 8.22876 22 12C22 15.7712 22 17.6569 20.8284 18.8284C19.6569 20 17.7712 20 14 20H10C6.22876 20 4.34315 20 3.17157 18.8284C2 17.6569 2 15.7712 2 12Z"
                                                stroke="currentColor" stroke-width="1.5"></path>
                                            <path opacity="0.5" d="M10 16H6" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round"></path>
                                            <path opacity="0.5" d="M14 16H12.5" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round">
                                            </path>
                                            <path opacity="0.5" d="M2 10L22 10" stroke="currentColor" stroke-width="1.5"
                                                stroke-linecap="round">
                                            </path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="mb-2 flex font-semibold text-white-dark">
                                        <h6>Order Enquiry</h6>
                                        <p class="ltr:ml-auto rtl:mr-auto">{{ $enquiryCoupn }}</p>
                                    </div>
                                    {{-- <div class="h-2 w-full rounded-full bg-dark-light shadow dark:bg-[#1b2e4b]">
                                        <div class="h-full w-full rounded-full bg-gradient-to-r from-[#f09819] to-[#ff5858]"
                                            style="width: {{ $enquiryCoupn / 100 }}%">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- end main content section -->
</div>
@endsection