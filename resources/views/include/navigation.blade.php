<!-- main header -->
<header class="main-header header-one white-menu menu-absolute">
    <!--Header-Upper-->
    <div class="header-upper py-10 rpy-0">
        <div class="container-fluid clearfix">

            <div class="header-inner rel d-flex align-items-center">
                <div class="logo-outer">
                    <div class="logo"><a href="{{ url('/') }}"><img
                                src="{{ asset($Logo ?? './assets/images/radhalogo.png') }}" alt="Logo" title="Logo"></a>
                    </div>
                </div>

                <div class="nav-outer mx-lg-auto ps-xxl-5 clearfix">
                    <!-- Main Menu -->
                    <nav class="main-menu navbar-expand-lg">
                        <div class="navbar-header">
                            <div class="mobile-logo">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset($Logo ?? './assets/images/radhalogo.png') }}" alt="Logo"
                                        title="Logo">
                                </a>
                            </div>

                            <!-- Toggle Button -->
                            <!-- <button type="button" class="navbar-toggle" data-bs-toggle="collapse"
                                data-bs-target=".navbar-collapse" id="navbarToggleButton">
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button> -->
                            <div class="hamburger" onclick="toggleMenu()">☰</div>
                            <div class="mobile-menu" id="mobileMenu">
                                <div class="close-btn" onclick="toggleMenu()">×</div>
                                <a href="{{ url('/') }}">Home</a>
                                <a href="{{ route('aboutUs') }}">About</a>
                                <a href="{{ route('destinationpage') }}">Destinations</a>
                                <a href="{{ route('servicePages') }}">Services</a>
                                <a href="{{ route('galleryPages') }}">Gallery</a>
                                <a href="{{ route(name: 'contactUs') }}">Contact Us</a>
                                <li><a href="{{ route(name: 'getFilesData') }}"> Download </a></li>
                                  {{-- <a href="#" class="mobile-contribute">Contribute</a>                               <!-- <a href="#" class="mobile-contribute">Contribute</a> --> --}}
                                  <a href="javascript:void(0);" class="openModalButton">
                                    <p class="theme-btn style-two bgc-secondary mt-5">Contribute</p>
                                </a>
                            </div>

                            <script>
                                function toggleMenu() {
                                    document.getElementById("mobileMenu").classList.toggle("active");
                                }
                            </script>

                        </div>
                        <div class="navbar-collapse collapse clearfix" id="navbarNav">
                            <ul class="navigation clearfix">
                                <li class="current"><a href="{{ url('/') }}">Home</a></li>
                                <li><a href="{{ route('aboutUs') }}">About</a></li>
                                <!-- <li class="dropdown"><a href="{{ route('tourpage') }}">Tours</a>
                                    <ul>
                                        <li><a href="{{ route('tourpage') }}">Tour</a></li>
                                        <li><a href="{{ route('tourDetailpage') }}">Tour Details</a></li>
                                    </ul>
                                </li> -->
                                <li><a href="{{ route('destinationpage') }}">Destinations</a>
                                    {{-- <ul>
                                        <li><a href="{{ route('destinationpage') }}">Destination</a></li>
                                        <li><a href="{{ route('destinationDetailpage') }}">Destination Details</a></li>
                                    </ul> --}}
                                </li>
                                <li><a href="{{ route('servicePages') }}">Services</a></li>
                                <li><a href="{{ route('galleryPages') }}">Gallery</a></li>
                                {{-- <li class="dropdown"><a href="{{ route('blogpage') }}">Blog</a>
                                    <ul>
                                        <li><a href="{{ route('blogDetailpage') }}">Blog Details</a></li>
                                    </ul>
                                </li> --}}
                                <li><a href="{{ route(name: 'contactUs') }}">Contact Us</a></li>
                                <li><a href="{{ route(name: 'getFilesData') }}"> Download </a></li>                            </ul>
                        </div>
                    </nav>
                    <!-- Main Menu End-->
                </div>

                <!-- Nav Search -->
                <!-- <div class="nav-search">
                    <button class="far fa-search"></button>
                    <form action="#" class="hide">
                        <input type="text" placeholder="Search" class="searchbox" required="">
                        <button type="submit" class="searchbutton far fa-search"></button>
                    </form>
                </div> -->

                <!-- Menu Button -->
                <div class="menu-btns py-10">
                    <a href="javascript:void(0);" class="openModalButton">
                        <p class="theme-btn style-two bgc-secondary mt-5">Contribute</p>
                    </a>
                    <!-- menu sidbar -->
                    <!-- <div class="menu-sidebar">
                        <button class="bg-transparent">
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div> -->
                    <!-- <div class="navbar-collapse collapse clearfix">

                    </div> -->
                </div>
            </div>
        </div>
    </div>
    <!--End Header Upper-->
</header>
{{-- <ul class="navigation clearfix">
    <li><a href="{{ url('/') }}">Home</a></li>
    <li><a href="{{ route('aboutUs') }}">About Us</a></li>
    <li class="dropdown"> <a href="{{ route('tourpage') }}">Tour</a>
        <ul>
            <li><a href="{{ route('tourDetailpage') }}">Tour Detail</a></li>
        </ul>
    </li>
    <li class="dropdown"><a href="{{ route('destinationpage') }}">Destinations</a>
        <ul>
            <li><a href="{{ route('destinationDetailpage') }}">Destination Details</a></li>
        </ul>
    </li>
    <li><a href="{{ route('servicePages') }}">Services</a>

    <li><a href="{{ route('galleryPages') }}">Gallery</a>
    <li class="dropdown"><a href="{{ route('blogpage') }}">Blog</a>
        <ul>
            <li><a href="{{ route('blogDetailpage') }}">Blog Details</a></li>
        </ul>
    </li>
    <li><a href="{{ route('contactUs') }}">Contact Us</a></li>

</ul> --}}
{{-- <div id="customModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close-btn">&times;</span>
        @if (!empty($donates) && count($donates))
            @foreach ($donates as $donate)
                <div class="modal-body">
                    <h3>Scan to Contribute</h3>
                    <div class="qr-code-section">

                        <img src="{{ asset($donate->image) }}" alt="QR Code" class="qr-code">
                        <h5>{!! $donate->heading_top !!}</h5>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div> --}}


{{-- new scan bar start --}}
 <!-- Modal -->
 <div id="customModal" class="modal">
    <div class="modal-content">
        <span id="closeModalButton" class="close-btn">&times;</span>

        <!-- User details form section -->
        <div class="form-section" id="detailsSection">
            <h3>Your Details</h3>
            <form id="userDetailsForm">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name">
                </div>

                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <input type="tel" id="phone" name="phone">
                </div>

                <button type="button" id="nextButton" class="btn">Next</button>
            </form>
        </div>

        <!-- QR code scan section -->
        <div class="scan-section hidden" id="scanSection">
            <!-- Use a static fallback image if there's no data -->
            <div class="modal-body">
                <h3>Scan to Contribute</h3>
                <div class="qr-code-section">
                    @if (!empty($donates) && count($donates))
                        @foreach ($donates as $donate)
                            <img
                                src="{{ asset($donate->image) }}"
                                onerror="this.onerror=null; this.src='https://shriradharaman.in/website/uploads/Slider/file_1737016922slide_2_1737016922qrcode.jpg';"
                                alt="QR Code"
                                class="qr-code">
                            <h5>{!! $donate->heading_top !!}</h5>
                        @endforeach
                    @else
                        <!-- Directly use the fallback image when no donates are available -->
                        <img
                            src="https://shriradharaman.in/website/uploads/Slider/file_1737016922slide_2_1737016922qrcode.jpg"
                            alt="QR Code"
                            class="qr-code">
                        <h5 class="contact-message">Please Contact Us to Contribute</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Styles (unchanged) -->
<style>
    /* .modal {
        display: block;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    } */

    .contact-message {
        text-align: center;
        font-size: 20px;
        margin: 80px auto;
        max-width: 600px;
    }

    .modal-content {
        background-color: #fff;
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 90%;
        max-width: 700px;
        border-radius: 8px;
    }

    .close-btn {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close-btn:hover {
        color: black;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="email"],
    input[type="tel"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        border-radius: 4px;
        font-size: 16px;
    }

    .btn:hover {
        background: #45a049;
    }

    .form-section,
    .scan-section {
        padding: 15px;
    }

    .qr-code {
        display: block;
        max-width: 250px;
        margin: 20px auto;
        border: 1px solid #ddd; /* Adding a border makes it easier to see if image is loading */
    }

    .modal-body {
        text-align: center;
    }

    .hidden {
        display: none !important;
    }
</style>

<!-- Script (updated) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nextButton = document.getElementById('nextButton');
        const detailsSection = document.getElementById('detailsSection');
        const scanSection = document.getElementById('scanSection');
        const modal = document.getElementById('customModal');
        const closeButton = document.getElementById('closeModalButton');

        // Debug - check if elements are found
        console.log('Next button:', nextButton);
        console.log('Details section:', detailsSection);
        console.log('Scan section:', scanSection);

        nextButton.addEventListener('click', function () {
            // Simply show the scan section without validation
            detailsSection.classList.add('hidden');
            scanSection.classList.remove('hidden');

            // Debug - log the visibility state after toggle
            console.log('Details visible:', !detailsSection.classList.contains('hidden'));
            console.log('Scan visible:', !scanSection.classList.contains('hidden'));

            // Force reflow/repaint to ensure visibility change
            scanSection.offsetHeight;
        });

        closeButton.addEventListener('click', function () {
            modal.style.display = 'none';
            resetModal();
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
                resetModal();
            }
        });

        function resetModal() {
            detailsSection.classList.remove('hidden');
            scanSection.classList.add('hidden');
            document.getElementById('userDetailsForm').reset();
        }
    });
</script>

{{-- new scan bar end --}}
<style>
    /* Trigger Button */
    .open-modal-btn {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
    }

    .open-modal-btn:hover {
        background-color: #218838;
    }

    /* Modal Styles */
    .modal {
        display: none;

        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        z-index: 9999;
    }

    /* Modal Content */
    /* .modal-content {
        background-color: white;
        margin: 10% auto;
        padding: 20px;
        border-radius: 10px;
        width: 50%;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s;
    } */

    /* Close Button */
    .close-btn {
        position: absolute;
        top: 1px;
        right: 20px;
        font-size: 50px;
        /* font-weight: bold; */
        cursor: pointer;
        color: #333;
    }

    .close-btn:hover {
        color: #ff0000;
    }

    /* Modal Body */
    .modal-body {
        /* display: flex; */
        gap: 20px;
    }

    .qr-code-section {
        display: flex;
    }

    .qr-code-section,
    .services-section {
        flex: 1;
        grid-gap: 26px;
    }

    .qr-code-section img.qr-code {
        max-width: 350px;
        min-width: 350px;
        height: auto;
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 5px;
    }



    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media(max-width:992px) {

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 86% !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s;
        }
    }

    @media(max-width:767px) {

        .contact-message {
        text-align: center;
        font-size: 20px;
        margin: 10px auto;
        max-width: 600px;
    }

        .modal-body {
            display: block !important;
            gap: 20px;
            padding: 0px !important;
        }

        .qr-code-section {
            display: block;
        }

        .qr-code-section img.qr-code {
            max-width: 270px !important;
            min-width: 289px !important;
            /* max-height: 323px; */
            /* min-height: 323px; */
            height: auto;
            border: 1px solid #ddd;
            padding: 0px !important;
            border-radius: 5px;
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 95% !important;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s;
        }
    }
    .hamburger {
            display: none;
            font-size: 28px;
            cursor: pointer;
        }

        .mobile-menu {
            position: fixed;
            top: 0;
            right: -100%;
            width: 80%;
            height: 90%;
            background: #658f7d;
            backdrop-filter: blur(10px);
            box-shadow: -5px 0px 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding-top:110px;
            text-align: center;
            transition: right 0.3s ease-in-out;
        }

        .mobile-menu.active {
            right: 0;
        }

        .close-btn {
            font-size: 30px;
            font-weight: bold;
            cursor: pointer;
            align-self: flex-end;
            padding-top:30px;
            color:red;
            /* border:1px solid black; */
        }

        .mobile-menu a {
            text-decoration: none;
            font-size: 20px;
            border-bottom: 1px solid white !important;
            /* border-bottom-width: 50px !important; */
            color:white;
            padding-bottom: 5px;
            /* border : 1px solid white; */
            /* border-radius: 10px; */
            /* background-color: white; */
        }

        .mobile-contribute {
            background-color: #e5ba26;
            padding: 10px 20px;
            border-radius: 20px;
            text-align: center;
            color: white !important;
            font-weight: bold;
            margin-top: 20px;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .menu {
                display: none;
            }

            .hamburger {
                display: block;
            }
        }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const navbarToggleButton = document.getElementById('navbarToggleButton');
        const navbarCollapse = document.getElementById('navbarNav');

        navbarToggleButton.addEventListener('click', function () {
            // Toggle the 'show' class on the navbar collapse
            navbarCollapse.classList.toggle('show');
        });

        // Modal functionality
        const openModalButton = document.querySelector('.openModalButton');
        const modal = document.getElementById('customModal');
        const closeModalButton = document.getElementById('closeModalButton');

        openModalButton.addEventListener('click', function () {
            modal.style.display = 'block';
        });

        closeModalButton.addEventListener('click', function () {
            modal.style.display = 'none';
        });

        window.addEventListener('click', function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    });
</script>
