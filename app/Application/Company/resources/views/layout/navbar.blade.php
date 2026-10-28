<nav class='nav-bar d-flex align-items-center'>

    <div class="container">
        <div class="row align-items-center ">
            <div class="col-lg-3 d-flex justify-content-end justify-content-lg-start mt-3">
                <h1 class="main-title fs-3 f-semibold m-0">
                    Dashboard
                </h1>
            </div>

            <div class="col-lg-9">
                <div class="row align-items-center justify-content-end">
                    <!-- Search Form -->
                    <div class="col-md-5 col-6">
                        <form class="dashboard-form search my-2 my-lg-4">
                            <div class="input-container">
                                <input type="text" placeholder="Search.." class="w-100">
                                <span class="input-icon">
                                        <svg width="22" height="22" viewBox="0 0 22 22" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.16671 16.5C10.7938 16.4997 12.3739 15.955 13.6556 14.9527L17.6853 18.9824L18.9815 17.6862L14.9518 13.6565C15.9546 12.3747 16.4996 10.7942 16.5 9.16671C16.5 5.12329 13.2101 1.83337 9.16671 1.83337C5.12329 1.83337 1.83337 5.12329 1.83337 9.16671C1.83337 13.2101 5.12329 16.5 9.16671 16.5ZM9.16671 3.66671C12.2 3.66671 14.6667 6.13346 14.6667 9.16671C14.6667 12.2 12.2 14.6667 9.16671 14.6667C6.13346 14.6667 3.66671 12.2 3.66671 9.16671C3.66671 6.13346 6.13346 3.66671 9.16671 3.66671Z"
                                                fill="#778399"/>
                                            <path
                                                d="M10.461 7.87046C10.8084 8.21879 11 8.67896 11 9.16662H12.8333C12.8342 8.68485 12.7395 8.20769 12.5547 7.76273C12.37 7.31778 12.0989 6.91385 11.7572 6.57429C10.3693 5.18829 7.96309 5.18829 6.57617 6.57429L7.87051 7.87229C8.56717 7.17746 9.76801 7.17929 10.461 7.87046Z"
                                                fill="#778399"/>
                                        </svg>
                                    </span>
                            </div>
                        </form>
                    </div>

                    <!-- -->
                    <div class="col-md-5 col-6 d-flex justify-content-end align-items-center">
                        <!-- Language -->
                        <a class="lang d-flex justify-content-center align-items-center">
                            <img src="" data-img-ar="{{ asset("assets/vendor/images/Saudi_Arabia.png") }}" data-img-en="{{ asset("assets/vendor/images/United.svg") }}" loading="lazy" decoding="async" width="18px" height="18px"
                                 class="lang-img rounded-circle">
                            <span class="lang-text f-semibold mx-1 fs-8 d-none d-lg-block">
                                </span>
                        </a>
                        <!-- End of Language -->

                        <!-- Start notification dropdown -->
                        <div class="notification mx-3">
                            <div class="btn-group">
                                <button type="button"
                                        class="border-0 d-flex justify-content-center align-items-center"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="noti d-inline-block rounded-circle">
                                        </span>
                                    <img loading="lazy" decoding="async" src="{{ asset("assets/vendor/images") }}/Notification.svg"
                                         alt="Notification icon" width="20px" height="20px">
                                </button>
                                <ul class="dropdown-menu">
                                    <div class="dropdown-header d-flex justify-content-between mb-1 p-2">
                                        <h6 class="title f-semibold">
                                            Notification
                                        </h6>
                                        <a href="" class="f-med">
                                            Mark All As Read
                                        </a>
                                    </div>
                                    <li>
                                        <!-- new notification ===> class new -->
                                        <a href="#"
                                           class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between new">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset("assets/vendor/images") }}/noti.png" class="img-fluid rounded-circle"
                                                     alt="">
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Event Name
                                                    </div>
                                                    <p class="text m-0">
                                                            <span class="green">
                                                                1,000
                                                            </span>
                                                        tickets sold
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex text-center justify-content-between flex-column align-items-center">
                                                <small class="time mb-2">2m</small>
                                                <span class="new-noti rounded-circle d-block"></span>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <!-- new notification ===> class new -->
                                        <a href="#"
                                           class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between new">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset("assets/vendor/images") }}/noti.png" class="img-fluid rounded-circle"
                                                     alt="">
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Event Name
                                                    </div>
                                                    <p class="text m-0">
                                                            <span class="green">
                                                                1,000
                                                            </span>
                                                        tickets sold
                                                    </p>
                                                </div>
                                            </div>
                                            <div
                                                class="d-flex text-center justify-content-between flex-column align-items-center">
                                                <small class="time mb-2">2m</small>
                                                <span class="new-noti rounded-circle d-block"></span>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Old notification -->
                                    <li>
                                        <a class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between"
                                           href="#">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset("assets/vendor/images") }}/noti.png" class="img-fluid rounded-circle"
                                                     alt="">
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Event Name
                                                    </div>
                                                    <p class="text m-0">
                                                            <span class="green">
                                                                1,000
                                                            </span>
                                                        tickets sold
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="">
                                                <small class="time">2m</small>
                                            </div>


                                        </a>
                                    </li>

                                    <!-- Old notification -->
                                    <li>
                                        <a class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between"
                                           href="#">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset("assets/vendor/images") }}/noti.png" class="img-fluid rounded-circle"
                                                     alt="">
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Event Name
                                                    </div>
                                                    <p class="text m-0">
                                                            <span class="green">
                                                                1,000
                                                            </span>
                                                        tickets sold
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="">
                                                <small class="time">2m</small>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Settings notification -->
                                    <li>
                                        <a class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between"
                                           href="#">
                                            <div class="d-flex align-items-center">
                                                <div
                                                    class="setting-icon d-flex justify-content-center align-items-center rounded-circle">
                                                    <img src="{{ asset("assets/vendor/images") }}/Setting_line.png"
                                                         class="m-0 img-fluid " alt="Setting_line">
                                                </div>
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Settings
                                                    </div>
                                                    <p class="text m-0">
                                                        Update Your copmany info
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="">
                                                <small class="time">2m</small>
                                            </div>
                                        </a>
                                    </li>

                                    <!-- Old notification -->
                                    <li>
                                        <a class="dropdown-item py-2 d-inline-flex align-items-center justify-content-between"
                                           href="#">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset("assets/vendor/images") }}/noti.png" class="img-fluid rounded-circle"
                                                     alt="">
                                                <div class="">
                                                    <div class="title f-med mb-1">
                                                        Event Name
                                                    </div>
                                                    <p class="text m-0">
                                                            <span class="green">
                                                                1,000
                                                            </span>
                                                        tickets sold
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="">
                                                <small class="time">2m</small>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- End of notification dropdown -->

                        <!-- Start Profile dropdown -->
                        <div class="profile-dropdown">
                            <div class="btn-group">
                                <button type="button" class="d-flex align-items-center" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                    <img src="{{ asset("assets/vendor/images") }}/user.png" class="user" width="50px" height="50px"
                                         alt="user">
                                    <div class="d-none d-lg-flex flex-column align-items-start">
                                        <h5 class="username f-med fs-8 m-0">
                                            Musfiq
                                        </h5>
                                        <div class="title fs-9">
                                            Admin
                                        </div>
                                    </div>
                                    <div
                                        class="icon d-flex align-items-center justify-content-center rounded-circle">
                                        <img src="{{ asset("assets/vendor/images") }}/chevron-down.svg" class="m-auto" alt="chevron">
                                    </div>
                                </button>
                                <ul class="dropdown-menu p-2">
                                    <li>
                                        <a class="dropdown-item f-med py-2 fs-7" href="profile.html">
                                            <img src="{{ asset("assets/vendor/images") }}/user.svg" width="15px" class="" alt="user"> My
                                            Account
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item f-med py-2 fs-7" href="#">
                                            <img src="{{ asset("assets/vendor/images") }}/shield-security.svg" width="15px" class=""
                                                 alt="shield-security">
                                            Change Password
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item logout f-med py-2 fs-7" href="#">
                                            <img loading="lazy" decoding="async"
                                                 src="{{ asset("assets/vendor/images") }}/fluent_delete.svg" width="15px" class=""
                                                 alt="fluent_delete">
                                            Log Out
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <!-- End of Profile dropdown -->
                    </div>
                </div>
            </div>
        </div>
    </div>

</nav>
