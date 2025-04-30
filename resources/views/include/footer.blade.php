<!-- footer area start -->
<!-- <footer class="main-footer bgs-cover overlay rel z-1 pb-25" style="background-image: url(assets/images/backgrounds/footer.jpg);"> -->
    <div class="container">
        <!-- <div class="footer-top pt-100 pb-30">
            <div class="row justify-content-between">

                <div class="newsletter col-xl-5 col-lg-6" data-aos="fade-up" data-aos-delay="50" data-aos-duration="1500" data-aos-offset="50">
                    <div class="section-title counter-text-wrap mb-35">
                        <h2>Subscribe Newsletter</h2>
                        <p>One site <span class="count-text plus" data-speed="3000" data-stop="34500">0</span> most popular experience you’ll remember</p>
                    </div>
                    <div class="subscribe">
                      <form class="newsletter-form mb-50" action="#">
                        <input id="news-email" type="email" placeholder="Email Address" required>
                        <button type="submit" class="theme-btn bgc-secondary style-two">
                            <span data-hover="Subscribe">Subscribe</span>
                            <i class="fal fa-arrow-right"></i>
                        </button>
                      </form>
                    </div>
                </div>
            </div>
        </div> -->
    </div>
    <div class="widget-area pt-95 pb-45">
        <div class="container">
            <div class="row cols-xl-5 cols-lg-4 cols-md-3 cols-2">
                <!-- <div class="col col-small" data-aos="fade-up" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links">
                        <div class="footer-title">
                            <h5>Services</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="destination-details.php">Best Tour Guide</a></li>
                            <li><a href="destination-details.php">Tour Booking</a></li>
                            <li><a href="destination-details.php">Hotel Booking</a></li>
                            <li><a href="destination-details.php">Ticket Booking</a></li>
                            <li><a href="destination-details.php">Rental Services</a></li>
                        </ul>
                    </div>
                </div> -->
                <div class="col-xl-5 col-lg-6"  >
                    <div class="footer-widget footer-text">
                        <div class="footer-logo mb-25">
                            <a href="{{ url('/') }}"><img src="{{ asset($Logo ?? './assets/images/radhalogo.png') }}" alt="Logo"></a>
                        </div>
                        <p>{!! $footer_heading ?? 'We make your dream more beautiful & enjoyful with lots of happiness.' !!}</p>

                    </div>
                </div>
                <div class="col col-small"  >
                    <div class="footer-widget footer-links">
                        <div class="footer-title">
                            <h5>Help Links</h5>
                        </div>
                        <ul class="list-style-three">
                        <li class="current"><a href="{{ url('/') }}">Home</a></li>
                        <li><a href="{{ route('aboutUs') }}">About</a></li>
                        <!-- <li><a href="{{ route('tourpage') }}">Tour</a></li> -->
                        <li><a href="{{ route('destinationpage') }}">Destination</a></li>
                        <li><a href="{{ route('servicePages') }}">Services</a></li>
                        <li><a href="{{ route('blogpage') }}">Blog</a></li>
                        <li><a href="{{ route('galleryPages') }}">Gallery</a></li>
                        <li><a href="{{ route(name: 'contactUs') }}">Contact Us</a></li>
                        </ul>
                    </div>
                </div>
                <!-- <div class="col col-small" data-aos="fade-up" data-aos-delay="100" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links">
                        <div class="footer-title">
                            <h5>Destinations</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="destination-details.php">African Safaris</a></li>
                            <li><a href="destination-details.php">Alaska & Canada</a></li>
                            <li><a href="destination-details.php">South America</a></li>
                            <li><a href="destination-details.php">Middle East</a></li>
                            <li><a href="destination-details.php">South America</a></li>
                        </ul>
                    </div>
                </div> -->
                <!-- <div class="col col-small" data-aos="fade-up" data-aos-delay="150" data-aos-duration="1500" data-aos-offset="50">
                    <div class="footer-widget footer-links">
                        <div class="footer-title">
                            <h5>Categories</h5>
                        </div>
                        <ul class="list-style-three">
                            <li><a href="contact.php">Adventure</a></li>
                            <li><a href="contact.php">Hiking & Trekking</a></li>
                            <li><a href="contact.php">Cycling Tours</a></li>
                            <li><a href="contact.php">Family Tours</a></li>
                            <li><a href="contact.php">Wildlife Tours</a></li>
                        </ul>
                    </div>
                </div> -->
                <div class="col col-md-6 col-10 col-small"  >
                    <div class="footer-widget footer-contact">
                        <div class="footer-title">
                            <h5>Get In Touch</h5>
                        </div>
                        <ul class="list-style-one">
                            <li><i class="fal fa-map-marked-alt"></i>{!! $Address ?? 'adc najafgarh Delhi' !!}</li>
                            <li><i class="fal fa-envelope"></i> <a href="mailto:{!! $Email ?? 'shriradharamanjee@gmail.com' !!}">{!! $Email ?? 'shriradharamanjee@gmail.com' !!}</a></li>
                            <!-- <li><i class="fal fa-clock"></i> Mon - Fri, 08am - 05pm</li> -->
                            <li><i class="fal fa-phone-volume"></i> <a href="#">{!! $Phone_number1 ?? '+91 9999111342' !!}</a></li>

                            <!-- <li><i class="fal fa-phone-volume"></i> <a href="#">+91 783 850 2000</a></li>
                            <li><i class="fal fa-phone-volume"></i> <a href="#">+91 783 860 2000</a></li>

                            <li><i class="fal fa-phone-volume"></i> <a href="#">+91 783 100 3500</a></li> -->

                            <!-- <li><i class="fal fa-phone-volume"></i> <a href="#"></a></li> -->

                        </ul>
                        <div class="gtranslate_wrapper mt-4"></div>
                    </div>
                </div>
                <div class="col col-small"  >
                    <div class="footer-widget footer-links">
                        <div class="footer-title">
                            <h5>Follow Us</h5>
                        </div>
                        <div class="social-style-one mt-15">
                            <a href="{!! $youtube_link ?? 'https://www.youtube.com' !!}"><i class="fab fa-youtube"></i></a>
                            <a href="{!! $facebook_link ?? 'https://www.facebook.com' !!}"><i class="fab fa-facebook-f"></i></a>
                            <a href="{!! $instagram_link ?? 'https://www.instagram.com' !!}"><i class="fab fa-instagram"></i></a>
                            <a href="{!! $x_link ?? 'https://www.x.com' !!}"><i class="fa-brands fa-x-twitter"></i></a>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom pt-5 pb-20">
        <div class="container">
            <div class="row">
               <div class="col-lg-5">
                    <div class="copyright-text text-center text-lg-start mb -5">
                        <p>© 2024 Shri Krishan Domodar Sewa Sansthan - Design by<a href="https://www.vyaparkranti.com/"> VyaparKranti.</a></p>
                        {{-- <p>© 2024 Shri Krishan Domodar Sewa Sansthan</p> --}}
                    </div>
               </div>
               <div class="col-lg-7 text-center text-lg-end">
                   <ul class="footer-bottom-nav">
                       <li><a href="#">Terms of services</a></li>
                       <li><a href="#">Privacy Policy</a></li>
                       <li><a href="#">Refund Policies</a></li>
                       <li><a href="#">Cookies</a></li>
                   </ul>
               </div>
            </div>
            <!-- Scroll Top Button -->
            <button class="scroll-top scroll-to-target" data-target="html"><img src="{{ asset('./assets/images/scroll-up.png') }}" alt="Scroll  Up"></button>
        </div>
    </div>
</footer>
<style>
    .footer-logo img {
        max-width:150px;
    }
    @media (min-width: 1200px) {
    .col-xl-5 {
        flex: 0 0 auto;
        width: 32.666667%;
    }
}
.newsletter {
    display:flex;
}
@media (max-width: 768px) {
    .row-cols-md-3>* {
        flex: 0 0 auto;
        width: 48.333333%;
    }
}
</style>
<!-- footer area end -->
<input id="enquiry" hidden="" type="checkbox">
<section class="enquiry-form">
    <label for="enquiry" class="enquiry-button">Enquire now</label>
    <div class="enquiry-container">
        <div class="contact-box_right_box">
            <div class="contact-box_form_box">

                <div id="success"></div>
                <form id="enquiryForm">
                    @csrf
                    <div class="control-group mt-25">
                        <input type="text" class="form-control bg-transparent p-4" id="name" name="name" placeholder="Your Name" required title="Please enter your name">
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="control-group">
                        <input type="tel" id="phone_number" name="phone_number" placeholder="Enter your phone number" required>
                        <p class="help-block text-danger"></p>
                    </div>
                    <div class="control-group">
                        <textarea class="form-control bg-transparent py-3 px-4" rows="2" id="message" name="message"  placeholder="Message" required="true" data-validation-required-message="Please enter your message"></textarea>
                        <p class="help-block text-danger"></p>
                    </div>
                    <div>
                        <button class="btn btn-primary font-weight-bold py-3 px-5" id="submitButton" type="submit">Send Message</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>

<style>
section.enquiry-form {
  position: fixed;
  top: 50%;
  right: -0%;
  z-index: 99;
  transition: var(--transition);
  margin-left: 40px;
  transform: translateY(-50%);
  margin-right: -400px;
}
.enquiry-button {
    transform: rotate(-90deg);
    top: 50%;
    left: calc(0% - 40px - 40px);
    cursor: pointer;
    margin: 0 0;
    color: #ffffff;
    height: 40px;
    user-select: none;
    -moz-user-select: none;
    line-height: 40px;
    padding: 0 15px;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    /* background-color: rgb(var(--blue-color)); */
    background: var(--primary-color);
    -webkit-animation: liner_bg 5s ease infinite;
    animation: liner_bg 5s ease-in-out infinite;
    background-size: 300% 300%;
    position: absolute;
    border: 1px solid rgb(var(--black-color));
}
.contact-box_form_box {
    background-color: #fff;
    padding: 15px;
    border-radius: 10px;
    max-width: 400px;
    min-width: 335px;
    margin: 1rem auto;
    width: 100%;
}
.btn-primary {
    color: #fff;
    border-color:var(--primary-color) !important;
    background-color:var(--primary-color) !important;
    text-align:center;

}
#enquiry:checked + .enquiry-form {margin-right: 0px;}
.enquiry-container {box-shadow: 0px 0px 15px -5px rgba(0 0 0 / 0.5);}
@-webkit-keyframes liner_bg {
  0% {
		background-position: 0% 50%;
	}
	50% {
		background-position: 100% 50%;
	}
	100% {
		background-position: 0% 50%;
	}
}
@keyframes liner_bg {
  0% {
		background-position: 0% 50%;
	}
	50% {
		background-position: 100% 50%;
	}
	100% {
		background-position: 0% 50%;
	}
}
input, select, textarea, .nice-select, .form-control {
    width: 100%;
    height: auto;
    font-weight: 400;
    border-radius: 0;
    font-size: 16px;
    padding: 15px 30px;
    /* background-color: grey; */
    border: 1px solid #757070;
    font-family: var(--heading-font);
    -webkit-transition: 0.3s;
    -o-transition: 0.3s;
    transition: 0.3s;
}
</style>
<style>
    @media (max-width: 768px) {
    .row-cols-md-3>* {
        flex: 0 0 auto;
        width: 64.333333%;
        padding-left:20px;
    }
}
</style>




