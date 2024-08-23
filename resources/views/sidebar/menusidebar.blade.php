<div :class="{'dark text-white-dark' : $store.app.semidark}">
    <nav x-data="sidebar"
        class="sidebar fixed bottom-0 top-0 z-50 h-full min-h-screen w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] transition-all duration-300">
        <div class="h-full bg-white dark:bg-[#0e1726]">
            <div class="flex items-center justify-between px-4 py-3">
                <a href="" class="main-logo flex shrink-0 items-center">
                    <img class="ml-[5px] w-8 flex-none" src="{{ asset('assets/images/logo.png') }}" width="100"
                        alt="image">
                    <span
                        class="align-middle text-2xl font-semibold ltr:ml-1.5 rtl:mr-1.5 dark:text-white-light lg:inline">SSU
                        - Admin</span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon flex h-8 w-8 items-center rounded-full transition duration-300 hover:bg-gray-500/10 rtl:rotate-180 dark:text-white-light dark:hover:bg-dark-light/10"
                    @click="$store.app.toggleSidebar()">
                    <svg class="m-auto h-5 w-5" width="20" height="20" viewbox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative h-[calc(100vh-80px)] space-y-0.5 overflow-y-auto overflow-x-hidden p-4 py-0 font-semibold"
                x-data="{ activeDropdown: 'dashboard' }">
                <li class="menu nav-item">
                    <a href="{{ URL('home') }}" class="group {{ request()->is('home*') ? 'active' : '' }}">
                        <button type="button" class="nav-link group"
                            :class="{'active' : activeDropdown === 'dashboard'}"
                            @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                            <div class="flex items-center">
                                <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                    viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path opacity="0.5"
                                        d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                        fill="currentColor"></path>
                                    <path
                                        d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                        fill="currentColor"></path>
                                </svg>

                                <span
                                    class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                            </div>
                            <div class="rtl:rotate-180" :class="{'!rotate-90' : activeDropdown === 'dashboard'}">

                                </svg>
                            </div>
                        </button>
                    </a>
                </li>
                <li class="nav-item">
                    <ul>
                        <li class="nav-item">
                            <a href="{{ URL('banner') }}" class="group {{ request()->is('banner*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="m3 16 5-7 6 6.5m6.5 2.5L16 13l-4.286 6M14 10h.01M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z" />
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Banners
                                        List</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('event.index') }}"
                                class="group {{ request()->is('event*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7h1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V5a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h11.5M7 14h6m-6 3h6m0-10h.5m-.5 3h.5M7 7h3v3H7V7Z" />
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Events
                                        List</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('seller.index') }}"
                                class="group {{ request()->is('seller*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="shrink-0 group-hover:!text-primary" width="20" height="20"
                                        viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle opacity="0.5" cx="15" cy="6" r="3" fill="currentColor"></circle>
                                        <ellipse opacity="0.5" cx="16" cy="17" rx="5" ry="3" fill="currentColor">
                                        </ellipse>
                                        <circle cx="9.00098" cy="6" r="4" fill="currentColor"></circle>
                                        <ellipse cx="9.00098" cy="17.001" rx="7" ry="4" fill="currentColor"></ellipse>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">SSU
                                        Member List</span>
                                </div>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('order.index') }}"
                                class="group {{ request()->is('order*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6h8m-8 6h8m-8 6h8M4 16a2 2 0 1 1 3.321 1.5L4 20h5M4 5l2-1v6m-2 0h4" />
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Order
                                        List</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('customercoupon.index') }}"
                                class="group {{ request()->is('customercoupon*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                        viewBox="0 0 24 24">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                                            d="M8 7V6a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1h-1M3 18v-7a1 1 0 0 1 1-1h11a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Customer
                                        Coupons</span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('slab.index') }}"
                                class="group {{ request()->is('slab*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg class="svg-icon" viewBox="0 0 20 20">
                                        <path
                                            d="M8.627,7.885C8.499,8.388,7.873,8.101,8.13,8.177L4.12,7.143c-0.218-0.057-0.351-0.28-0.293-0.498c0.057-0.218,0.279-0.351,0.497-0.294l4.011,1.037C8.552,7.444,8.685,7.667,8.627,7.885 M8.334,10.123L4.323,9.086C4.105,9.031,3.883,9.162,3.826,9.38C3.769,9.598,3.901,9.82,4.12,9.877l4.01,1.037c-0.262-0.062,0.373,0.192,0.497-0.294C8.685,10.401,8.552,10.18,8.334,10.123 M7.131,12.507L4.323,11.78c-0.218-0.057-0.44,0.076-0.497,0.295c-0.057,0.218,0.075,0.439,0.293,0.495l2.809,0.726c-0.265-0.062,0.37,0.193,0.495-0.293C7.48,12.784,7.35,12.562,7.131,12.507M18.159,3.677v10.701c0,0.186-0.126,0.348-0.306,0.393l-7.755,1.948c-0.07,0.016-0.134,0.016-0.204,0l-7.748-1.948c-0.179-0.045-0.306-0.207-0.306-0.393V3.677c0-0.267,0.249-0.461,0.509-0.396l7.646,1.921l7.654-1.921C17.91,3.216,18.159,3.41,18.159,3.677 M9.589,5.939L2.656,4.203v9.857l6.933,1.737V5.939z M17.344,4.203l-6.939,1.736v9.859l6.939-1.737V4.203z M16.168,6.645c-0.058-0.218-0.279-0.351-0.498-0.294l-4.011,1.037c-0.218,0.057-0.351,0.28-0.293,0.498c0.128,0.503,0.755,0.216,0.498,0.292l4.009-1.034C16.092,7.085,16.225,6.863,16.168,6.645 M16.168,9.38c-0.058-0.218-0.279-0.349-0.498-0.294l-4.011,1.036c-0.218,0.057-0.351,0.279-0.293,0.498c0.124,0.486,0.759,0.232,0.498,0.294l4.009-1.037C16.092,9.82,16.225,9.598,16.168,9.38 M14.963,12.385c-0.055-0.219-0.276-0.35-0.495-0.294l-2.809,0.726c-0.218,0.056-0.351,0.279-0.293,0.496c0.127,0.506,0.755,0.218,0.498,0.293l2.807-0.723C14.89,12.825,15.021,12.603,14.963,12.385">
                                        </path>
                                    </svg>

                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Slabs
                                        List
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('bill.index') }}"
                                class="group {{ request()->is('bill*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 97.92 122.88"
                                        style="enable-background:new 0 0 97.92 122.88" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M68.17,0.66C67.73,0.26,67.11,0,66.5,0c-0.13,0-0.26,0-0.4,0.04H5.54c-1.49,0-2.9,0.62-3.91,1.63C0.62,2.68,0,4.04,0,5.58 v111.76c0,1.54,0.62,2.9,1.63,3.91c1.01,1.01,2.37,1.63,3.91,1.63c28.22,0,58.68,0,86.76,0c1.54,0,2.9-0.62,3.91-1.63 c1.01-1.01,1.63-2.37,1.63-3.91V32.3c0.04-0.22,0.09-0.4,0.09-0.62c0-0.75-0.35-1.41-0.84-1.89L68.47,0.84 c-0.09-0.09-0.13-0.13-0.22-0.18H68.17L68.17,0.66z M20.53,19.68c-1.36,0-2.5,1.1-2.5,2.51c0,1.36,1.1,2.5,2.5,2.5h17.15 c1.36,0,2.51-1.1,2.51-2.5c0-1.36-1.1-2.51-2.51-2.51H20.53L20.53,19.68z M39.13,83.35h-8.88v-1.47c0-1.57-0.11-2.59-0.31-3.07 c-0.2-0.49-0.64-0.73-1.31-0.73c-0.54,0-0.95,0.21-1.22,0.62C27.14,79.13,27,79.75,27,80.59c0,1.39,0.28,2.37,0.83,2.92 c0.54,0.56,2.14,1.64,4.79,3.25c2.25,1.37,3.79,2.41,4.61,3.13c0.82,0.73,1.51,1.75,2.07,3.08c0.56,1.33,0.85,2.98,0.85,4.96 c0,3.16-0.76,5.65-2.28,7.45c-1.52,1.8-3.82,2.91-6.86,3.34v3.33h-4.1v-3.42c-2.37-0.23-4.45-1.15-6.22-2.74 c-1.77-1.58-2.66-4.36-2.66-8.32v-1.74h8.88v2.17c0,2.39,0.09,3.87,0.28,4.45c0.18,0.58,0.62,0.86,1.32,0.86 c0.6,0,1.05-0.2,1.34-0.6c0.29-0.41,0.44-1.01,0.44-1.8c0-1.99-0.14-3.42-0.42-4.27c-0.28-0.86-1.22-1.8-2.85-2.8 c-2.71-1.7-4.55-2.95-5.53-3.75c-0.97-0.8-1.82-1.92-2.52-3.38c-0.71-1.45-1.07-3.09-1.07-4.92c0-2.65,0.75-4.73,2.25-6.24 c1.5-1.51,3.76-2.44,6.76-2.79v-2.84h4.1v2.84c2.74,0.35,4.79,1.27,6.16,2.76c1.36,1.49,2.04,3.55,2.04,6.17 C39.22,82.05,39.19,82.61,39.13,83.35L39.13,83.35z M63.99,5.01v21.67c0,2.07,0.84,3.96,2.2,5.32c1.36,1.36,3.25,2.2,5.32,2.2 h21.27v83.15c0,0.13-0.04,0.31-0.18,0.4c-0.09,0.09-0.22,0.18-0.4,0.18c-22.34,0-64.98,0-86.71,0c-0.13,0-0.31-0.04-0.4-0.18 c-0.09-0.09-0.18-0.26-0.18-0.4V5.58c0-0.18,0.04-0.31,0.18-0.4c0.09-0.09,0.22-0.18,0.4-0.18h58.45H63.99L63.99,5.01z M68.96,26.68V8.53l20.44,20.7H71.51c-0.7,0-1.32-0.31-1.8-0.75C69.26,28.04,68.96,27.38,68.96,26.68L68.96,26.68z M20.52,36.96 c-1.36,0-2.5,1.1-2.5,2.51c0,1.36,1.1,2.51,2.5,2.51h43.86c1.36,0,2.51-1.1,2.51-2.51c0-1.36-1.1-2.51-2.51-2.51H20.52L20.52,36.96 z M49,70.36c-1.36,0-2.5,1.1-2.5,2.51c0,1.36,1.1,2.51,2.5,2.51h28.22c1.36,0,2.51-1.1,2.51-2.51c0-1.36-1.1-2.51-2.51-2.51H49 L49,70.36z M20.52,53.66c-1.36,0-2.5,1.1-2.5,2.51c0,1.36,1.1,2.51,2.5,2.51h56.69c1.36,0,2.51-1.1,2.51-2.51 c0-1.36-1.1-2.51-2.51-2.51H20.52L20.52,53.66z" />
                                        </g>
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Bills
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('asset.index') }}"
                                class="group {{ request()->is('asset*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round">
                                        </g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path
                                                d="M12.5306 7.90879V5.57711C12.5306 4.59536 12.6609 3 14.3265 3C15.7755 3 16.0816 4.32946 16.0816 5.20895V12.3062H19.0612C19.7006 12.2653 21 12.7358 21 14.2084C21 15.681 19.9388 16.0901 19.0612 16.0901H11.2245V19.3217C11.2245 20.1603 10.9143 20.9662 9.67351 20.9989C8.4327 21.0316 7.95917 20.3444 7.95917 19.3217V11.5904H5.06122C4.12245 11.5904 3 11.2631 3 9.66777C3 8.2974 4.12245 7.82697 5.06122 7.82697H10.3653"
                                                stroke="#000000" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Asset
                                        Order
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('document.index') }}"
                                class="group {{ request()->is('document*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 115.28 122.88"
                                        style="enable-background:new 0 0 115.28 122.88" xml:space="preserve">
                                        <style type="text/css">
                                            .st0 {
                                                fill-rule: evenodd;
                                                clip-rule: evenodd;
                                            }
                                        </style>
                                        <g>
                                            <path class="st0"
                                                d="M25.38,57h64.88V37.34H69.59c-2.17,0-5.19-1.17-6.62-2.6c-1.43-1.43-2.3-4.01-2.3-6.17V7.64l0,0H8.15 c-0.18,0-0.32,0.09-0.41,0.18C7.59,7.92,7.55,8.05,7.55,8.24v106.45c0,0.14,0.09,0.32,0.18,0.41c0.09,0.14,0.28,0.18,0.41,0.18 c22.78,0,58.09,0,81.51,0c0.18,0,0.17-0.09,0.27-0.18c0.14-0.09,0.33-0.28,0.33-0.41v-11.16H25.38c-4.14,0-7.56-3.4-7.56-7.56 V64.55C17.82,60.4,21.22,57,25.38,57L25.38,57z M29.51,68.52h10.81c2.12,0,3.85,0.29,5.16,0.87c1.31,0.58,2.39,1.41,3.25,2.49 c0.85,1.08,1.47,2.34,1.86,3.77c0.39,1.43,0.58,2.95,0.58,4.56c0,2.51-0.28,4.46-0.86,5.85c-0.57,1.39-1.36,2.55-2.38,3.48 c-1.02,0.94-2.11,1.56-3.27,1.87c-1.59,0.43-3.04,0.64-4.33,0.64H29.51V68.52L29.51,68.52z M36.77,73.85V86.7h1.79 c1.52,0,2.61-0.17,3.25-0.5c0.64-0.34,1.14-0.93,1.51-1.76c0.37-0.84,0.55-2.2,0.55-4.09c0-2.49-0.41-4.19-1.22-5.12 c-0.82-0.92-2.17-1.38-4.06-1.38H36.77L36.77,73.85z M53.01,80.3c0-3.84,1.07-6.83,3.21-8.97c2.14-2.14,5.12-3.21,8.94-3.21 c3.92,0,6.93,1.05,9.05,3.15c2.12,2.1,3.18,5.05,3.18,8.83c0,2.75-0.46,5-1.39,6.76c-0.93,1.76-2.27,3.13-4.01,4.11 c-1.75,0.98-3.93,1.47-6.54,1.47c-2.65,0-4.85-0.42-6.59-1.27c-1.74-0.85-3.15-2.19-4.23-4.01C53.55,85.34,53.01,83.05,53.01,80.3 L53.01,80.3z M60.27,80.32c0,2.37,0.44,4.08,1.33,5.12c0.89,1.04,2.09,1.56,3.61,1.56c1.56,0,2.78-0.51,3.63-1.52 c0.86-1.02,1.29-2.84,1.29-5.47c0-2.21-0.45-3.83-1.34-4.85c-0.9-1.02-2.11-1.53-3.64-1.53c-1.47,0-2.65,0.52-3.54,1.56 C60.72,76.21,60.27,77.93,60.27,80.32L60.27,80.32z M95.94,82.42l6.38,1.92c-0.43,1.79-1.1,3.28-2.03,4.47 c-0.92,1.2-2.06,2.1-3.43,2.71c-1.36,0.61-3.1,0.91-5.21,0.91c-2.56,0-4.65-0.37-6.28-1.11c-1.62-0.74-3.02-2.05-4.2-3.92 c-1.18-1.87-1.77-4.27-1.77-7.18c0-3.89,1.04-6.88,3.11-8.97c2.08-2.09,5.01-3.13,8.8-3.13c2.96,0,5.3,0.6,6.99,1.8 c1.69,1.2,2.96,3.04,3.78,5.53l-6.41,1.42c-0.22-0.71-0.46-1.23-0.71-1.56c-0.41-0.55-0.91-0.98-1.49-1.28 c-0.59-0.3-1.25-0.45-1.98-0.45c-1.65,0-2.92,0.66-3.8,1.98c-0.66,0.98-1,2.52-1,4.62c0,2.61,0.39,4.39,1.19,5.36 c0.79,0.96,1.91,1.45,3.34,1.45c1.39,0,2.44-0.39,3.15-1.17C95.11,85.04,95.62,83.91,95.94,82.42L95.94,82.42z M97.79,57h9.93 c4.16,0,7.56,3.41,7.56,7.56v31.42c0,4.15-3.41,7.56-7.56,7.56h-9.93v13.55c0,1.61-0.65,3.04-1.7,4.1c-1.06,1.06-2.49,1.7-4.1,1.7 c-29.44,0-56.59,0-86.18,0c-1.61,0-3.04-0.64-4.1-1.7c-1.06-1.06-1.7-2.49-1.7-4.1V5.85c0-1.61,0.65-3.04,1.7-4.1 c1.06-1.06,2.53-1.7,4.1-1.7h58.72C64.66,0,64.8,0,64.94,0c0.64,0,1.29,0.28,1.75,0.69h0.09c0.09,0.05,0.14,0.09,0.23,0.18 l29.99,30.36c0.51,0.51,0.88,1.2,0.88,1.98c0,0.23-0.05,0.41-0.09,0.65V57L97.79,57z M67.52,27.97V8.94l21.43,21.7H70.19 c-0.74,0-1.38-0.32-1.89-0.78C67.84,29.4,67.52,28.71,67.52,27.97L67.52,27.97z" />
                                        </g>
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Documents
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('contactus.index') }}"
                                class="group {{ request()->is('contactus*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 122.88 118.1">
                                        <title>newsletter</title>
                                        <path
                                            d="M115.17,33.29a3.8,3.8,0,0,1,2.49-.92,4.19,4.19,0,0,1,2.14.62,5.82,5.82,0,0,1,1.32,1.12,7.37,7.37,0,0,1,1.76,4.44v73.64a5.87,5.87,0,0,1-1.73,4.16h0A5.9,5.9,0,0,1,117,118.1H5.91a5.91,5.91,0,0,1-4.17-1.73h0A5.9,5.9,0,0,1,0,112.19V38.55a7.41,7.41,0,0,1,1.8-4.5A5.52,5.52,0,0,1,3.12,33a4.05,4.05,0,0,1,2.1-.6,3.68,3.68,0,0,1,2,.59l.2.17v-26a7.1,7.1,0,0,1,2.08-5h0a7.1,7.1,0,0,1,5-2.08h93.54a7.08,7.08,0,0,1,5,2.08,2.25,2.25,0,0,1,.21.24,7,7,0,0,1,1.87,4.77v26.2ZM70.85,43a3,3,0,0,1,0-6H83.64a3,3,0,0,1,0,6ZM39,43a3,3,0,0,1,0-6H51.77a3,3,0,0,1,0,6ZM54.2,60a3,3,0,0,1,0-6.05H68.42a3,3,0,0,1,0,6.05ZM27.86,26.07a3,3,0,0,1,0-6.05H42.29a3,3,0,0,1,0,6.05Zm52.48,0a3,3,0,0,1,0-6.05H94.77a3,3,0,0,1,0,6.05Zm-24.11,0a3,3,0,0,1,0-6.05h10a3,3,0,0,1,0,6.05ZM13.71,38.65,48.64,69.86l.15.14L60.84,80.76l48.08-42V7.09a.89.89,0,0,0-.17-.51l-.08-.08a.84.84,0,0,0-.59-.25H14.54A.84.84,0,0,0,14,6.5a.83.83,0,0,0-.24.59V38.65ZM114.56,41.4a3.09,3.09,0,0,1-1,.87L79.85,71.72l37.31,32.7h0V39.12l-2.6,2.28ZM58.92,86.68,46.81,75.86l-41.09,36v.33a.17.17,0,0,0,0,.13h0a.17.17,0,0,0,.13,0H117a.17.17,0,0,0,.13,0h0a.17.17,0,0,0,0-.13V112L75.52,75.5,62.7,86.7h0a2.85,2.85,0,0,1-3.78,0ZM42.52,72,5.72,39.15v65.13L42.52,72Z" />
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Contact-Us
                                        List
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('prize.index') }}"
                                class="group {{ request()->is('prize*') ? 'active' : '' }}">
                                <div class="flex items-center">
                                    <?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 114.46 122.88"
                                        style="enable-background:new 0 0 114.46 122.88" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M24.72,31.9H3.28C2.7,43.69,3.45,54.95,8.07,63.17c4.36,7.76,12.33,12.89,26.2,13.28c-1.58-1.94-3.09-4.23-4.42-6.99 c-8.33-1.12-13.16-4.57-15.93-9.75c-2.77-5.17-3.36-11.93-3.36-19.78c0-0.86,0.7-1.56,1.56-1.56h12.6V31.9L24.72,31.9z M56.88,33.47l3.88,9.45l10.2,0.77l-7.81,6.62l2.42,9.92l-8.69-5.38l-8.69,5.38l2.42-9.92l-7.81-6.62L53,42.92L56.88,33.47 L56.88,33.47z M1.8,28.78h22.91v-5.84h-3.14c-1.9,0-3.62-0.78-4.87-2.03l-0.01-0.01c-1.25-1.25-2.03-2.97-2.03-4.87v-3.89 c-1.22-0.24-2.32-0.84-3.17-1.69c-1.11-1.11-1.8-2.64-1.8-4.33V3.38c0-0.93,0.38-1.78,0.99-2.39C11.31,0.38,12.16,0,13.09,0h87.58 c0.93,0,1.78,0.38,2.39,0.99c0.61,0.61,0.99,1.46,0.99,2.39v2.75c0,1.69-0.69,3.22-1.8,4.33c-0.85,0.85-1.95,1.45-3.17,1.69v3.89 c0,1.89-0.78,3.62-2.03,4.87l-0.01,0.01c-1.25,1.25-2.97,2.03-4.87,2.03h-3.14v5.84h23.62c0.86,0,1.56,0.7,1.56,1.56l0,0.02 c0.72,12.8,0,25.18-5.21,34.35c-5.28,9.3-15.01,15.22-32.18,14.85c-1.63,1.47-3.28,2.72-4.83,3.91c-5.42,4.14-9.5,7.26-6.33,16.82 h4.74c1.89,0,3.61,0.77,4.86,2.02c1.25,1.25,2.02,2.97,2.02,4.86v2.77h0.68c1.86,0,3.56,0.76,4.79,1.99l0,0 c1.23,1.23,1.99,2.92,1.99,4.79v4.6c0,0.86-0.7,1.56-1.56,1.56H30.57c-0.86,0-1.56-0.7-1.56-1.56v-4.6c0-1.86,0.76-3.55,1.99-4.78 l0.01-0.01c1.23-1.23,2.92-1.99,4.78-1.99h0.68v-2.77c0-1.89,0.77-3.61,2.02-4.85l0.01-0.01c1.25-1.24,2.97-2.02,4.85-2.02h5.33 c2.83-9.02-0.88-11.94-6.01-15.98c-1.74-1.37-3.64-2.86-5.55-4.74c-16.97,0.28-26.57-5.63-31.77-14.88 C0.2,55.52-0.48,43.16,0.24,30.37l0-0.02C0.24,29.48,0.94,28.78,1.8,28.78L1.8,28.78z M51.93,100.29H62.4 c-3.32-10.87,1.44-14.5,7.71-19.29c6.88-5.25,15.82-12.07,15.82-34.51V22.98H27.83v22.9c0.38,8.71,1.84,15.16,3.85,20.1 c1.99,4.88,4.55,8.29,7.17,10.94c1.91,1.94,3.91,3.51,5.74,4.95C50.56,86.56,54.91,89.99,51.93,100.29L51.93,100.29z M64.57,103.41 H43.35c-1.04,0-1.98,0.42-2.66,1.1l-0.01,0.01c-0.68,0.68-1.1,1.62-1.1,2.66v2.77h34.58v-2.77c0-1.03-0.42-1.97-1.11-2.66 c-0.68-0.68-1.62-1.11-2.66-1.11H64.57L64.57,103.41z M75.73,113.06H35.79c-1.01,0-1.93,0.41-2.59,1.07l-0.01,0.01 c-0.66,0.66-1.07,1.58-1.07,2.59v3.04h49.5v-3.04c0-1.01-0.41-1.92-1.08-2.59v-0.01c-0.66-0.66-1.58-1.07-2.59-1.07H75.73 L75.73,113.06z M89.05,38.37h13.75c0.86,0,1.56,0.7,1.56,1.56c0,7.87-0.71,14.65-3.61,19.82c-2.89,5.14-7.82,8.57-16.14,9.7 c-1.38,2.79-2.98,5.07-4.68,7.01c13.93-0.37,21.96-5.5,26.38-13.27c4.67-8.22,5.45-19.49,4.88-31.28H89.05V38.37L89.05,38.37z M101.23,41.49H89.05v5c0,8.31-1.16,14.62-2.98,19.56c6.08-1.2,9.78-3.93,11.97-7.83C100.42,53.96,101.14,48.21,101.23,41.49 L101.23,41.49z M24.72,41.49H13.68c0.08,6.73,0.69,12.49,2.97,16.75c2.09,3.9,5.68,6.63,11.73,7.82 c-1.93-5.11-3.3-11.61-3.67-20.12l0-0.07h-0.01V41.49L24.72,41.49z M100.67,3.12H13.09c-0.07,0-0.14,0.03-0.19,0.08 c-0.05,0.05-0.08,0.11-0.08,0.19v2.75c0,0.83,0.34,1.58,0.89,2.13c0.55,0.55,1.3,0.89,2.13,0.89h82.08c0.83,0,1.58-0.34,2.13-0.89 c0.55-0.55,0.89-1.3,0.89-2.13V3.38c0-0.07-0.03-0.14-0.08-0.19C100.81,3.15,100.74,3.12,100.67,3.12L100.67,3.12z M95.97,12.27 H17.8v3.78c0,1.04,0.42,1.99,1.11,2.67c0.69,0.68,1.63,1.11,2.67,1.11h70.61c1.04,0,1.99-0.42,2.67-1.11 c0.68-0.69,1.11-1.63,1.11-2.67V12.27L95.97,12.27z" />
                                        </g>
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">Prize
                                        List
                                    </span>
                                </div>
                            </a>
                        </li>

                        <li class="menu nav-item">

                            <button type="button"
                                class="expnadClass nav-link group {{ request()->is('notice*') ? 'active' : '' }}"
                                :class="{}" @click="activeDropdown === 'notice' ? activeDropdown = null : activeDropdown =
                                'notice'">
                                <div class="flex items-center">
                                    <?xml version="1.0" encoding="utf-8"?><svg version="1.1" id="Layer_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" width="122.88px" height="121.135px" viewBox="0 0 122.88 121.135"
                                        enable-background="new 0 0 122.88 121.135" xml:space="preserve">
                                        <g>
                                            <path
                                                d="M74.401,65.787h41.427c1.943,0,3.707,0.791,4.982,2.068c1.276,1.275,2.069,3.039,2.069,4.982v41.246 c0,1.941-0.793,3.707-2.069,4.98c-1.275,1.277-3.039,2.07-4.982,2.07H74.401c-1.942,0-3.706-0.793-4.982-2.07 c-1.275-1.273-2.068-3.039-2.068-4.98V72.838c0-1.943,0.793-3.707,2.068-4.982C70.695,66.578,72.459,65.787,74.401,65.787 L74.401,65.787z M7.052,0h41.426c1.942,0,3.707,0.792,4.983,2.069s2.068,3.04,2.068,4.983v41.245c0,1.943-0.792,3.707-2.068,4.982 c-1.276,1.276-3.041,2.069-4.983,2.069H7.052c-1.934,0-3.692-0.793-4.969-2.069l-0.007-0.006l-0.007,0.006 C0.792,52.003,0,50.239,0,48.296V7.052c0-1.943,0.792-3.707,2.069-4.983C2.162,1.976,2.26,1.888,2.359,1.807 C3.607,0.685,5.255,0,7.052,0L7.052,0z M48.131,7.397H7.397V47.95h40.733V7.397L48.131,7.397z M74.401,0h41.427 c1.943,0,3.707,0.792,4.982,2.069c1.276,1.276,2.069,3.04,2.069,4.983v41.245c0,1.943-0.793,3.707-2.069,4.982 c-1.275,1.276-3.039,2.069-4.982,2.069H74.401c-1.942,0-3.706-0.793-4.982-2.069c-1.275-1.275-2.068-3.04-2.068-4.982V7.052 c0-1.943,0.793-3.707,2.068-4.983C70.695,0.792,72.459,0,74.401,0L74.401,0z M115.482,7.397H74.748V47.95h40.734V7.397 L115.482,7.397z M7.052,65.787h41.426c1.942,0,3.707,0.791,4.983,2.068c1.276,1.275,2.068,3.039,2.068,4.982v41.246 c0,1.941-0.792,3.707-2.068,4.98c-1.276,1.277-3.041,2.07-4.983,2.07H7.052c-1.934,0-3.692-0.793-4.969-2.07l-0.007-0.006 l-0.007,0.006C0.792,117.791,0,116.025,0,114.084V72.838c0-1.943,0.792-3.707,2.069-4.982c0.093-0.094,0.191-0.182,0.291-0.264 C3.607,66.471,5.255,65.787,7.052,65.787L7.052,65.787z M48.131,73.184H7.397v40.553h40.733V73.184L48.131,73.184z M115.482,73.184 H74.748v40.553h40.734V73.184L115.482,73.184z" />
                                        </g>
                                    </svg>
                                    <span
                                        class="text-black ltr:pl-3 rtl:pr-3 dark:text-[#506690] dark:group-hover:text-white-dark">General</span>
                                </div>
                                <?php 
                                $expnad = (request()->is('notice*') == 1) ? '!rotate-90' : 'rtl:rotate-180';  
                                ?>
                                <div class="{{ $expnad }}" :class="{'!rotate-90' : activeDropdown === 'notice'}">
                                    <svg width="16" height="16" viewbox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak="" x-show="activeDropdown === 'notice'" x-collapse=""
                                class="sub-menu text-gray-500">
                                <li>
                                    <a class="group {{ request()->is('notice*') ? 'active' : '' }}"
                                        href="{{ route('notice.index') }}">Notice</a>
                                </li>
                                <li>
                                    <a class="group {{ request()->is('social*') ? 'active' : '' }}"
                                        href="{{ route('social.index') }}">Social Links</a>
                                </li>
                                <li>
                                    <a class="group {{ request()->is('gallery*') ? 'active' : '' }}"
                                        href="{{ route('gallery.index') }}">Gallery</a>
                                </li>
                                <li>
                                    <a class="group {{ request()->is('cms*') ? 'active' : '' }}"
                                        href="{{ route('cms.index') }}">CMS</a>
                                </li>

                                <li>
                                    <a class="group {{ request()->is('broadcast*') ? 'active' : '' }}"
                                        href="{{ route('broadcast.index') }}">Broadcast Message</a>
                                </li>
                            </ul>
                        </li>

                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</div>
<?php if(request()->is('notice*') == 1 || request()->is('social*') == 1 || request()->is('cms*') == 1 || request()->is('gallery*') == 1) { ?>
<script>
    setTimeout(() => {
        $(".expnadClass").click()
    }, 900);
</script>
<?php } ?>