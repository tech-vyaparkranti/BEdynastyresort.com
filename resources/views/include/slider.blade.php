
<!-- banner-section -->
<section class="banner-section p_relative centred">
    <div class="banner-carousel owl-theme owl-carousel owl-dots-none owl-nav-none">
        @foreach ($sliders as $slide)
                <div class="swiper-slide swiper-slide-next">
                    <img class="img-fluid" width="" height="" alt="Image" src="{{ asset($slide->image) }}"/>
                </div>
                @endforeach
        {{-- <div class="slide-item p_relative">
            <div class="bg-layer" style="background-image: url(./assets/images/aisbanner1.jpg);"></div>
            <span class="big-text animation_text_word"></span>
            <div class="auto-container">
                <div class="content-box">
                    <span class="special-text">Discover the Planet...</span>
                    <h2>Creating memories that last...</h2>
                    <p>This statistic is based on our average personal current account online</p>
                </div> 
            </div>
        </div>
        <div class="slide-item p_relative">
            <div class="bg-layer" style="background-image: url(./assets/images/aisbanner2.jpg);"></div>
            <span class="big-text animation_text_word"></span>
            <div class="auto-container">
                <div class="content-box">
                    <span class="special-text">Discover the Planet...</span>
                    <h2>Creating memories that last...</h2>
                    <p>This statistic is based on our average personal current account online</p>
                </div> 
            </div>
        </div>
        <div class="slide-item p_relative">
            <div class="bg-layer" style="background-image: url(./assets/images/aisbanner3.jpg);"></div>
            <span class="big-text animation_text_word"></span>
            <div class="auto-container">
                <div class="content-box">
                    <span class="special-text">Discover the Planet...</span>
                    <h2>Creating memories that last...</h2>
                    <p>This statistic is based on our average personal current account online</p>
                </div> 
            </div>
        </div> --}}
    </div>
    {{-- <div class="booking-form">
        <div class="auto-container">
            <div class="booking-inner">
                <form method="post" action="https://azim.hostlin.com/Travic/index.html" class="clearfix">
                    <div class="form-group">
                        <div class="icon"><i class="icon-8"></i></div>
                        <div class="select-box">
                            <select class="wide">
                                <option data-display="Your Destination">Your Destination</option>
                                <option value="1">USA</option>
                                <option value="2">Italy</option>
                                <option value="3">China</option>
                                <option value="4">Thailand</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon"><i class="icon-9"></i></div>
                        <div class="select-box">
                            <select class="wide">
                                <option data-display="Travel Category">Travel Category</option>
                                <option value="1">Adventure travel</option>
                                <option value="2">Air travel</option>
                                <option value="3">Backpacking</option>
                                <option value="4">Bleisure travel</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="icon"><i class="icon-10"></i></div>
                        <input type="text" name="date" id="datepicker" placeholder="When to Start">
                    </div>
                    <div class="form-group d-none">
                        <div class="icon"><i class="icon-11"></i></div>
                        <div class="select-box">
                            <select class="wide">
                                <option data-display="How many Guest">How many Guest</option>
                                <option value="1">1 Child 2 Adult</option>
                                <option value="2">2 Child 2 Adult</option>
                                <option value="3">1 Child 3 Adult</option>
                                <option value="4">2 Child 3 Adult</option>
                            </select>
                        </div>
                    </div>
                    <div class="btn-box">
                        <button type="submit">Find Now</button>
                    </div>
                </form>
            </div>
        </div>
    </div> --}}
</section>
<!-- banner-section end -->