<script src="{{ asset("assets/js/jquery-3.6.0.min.js") }}"></script>
<script src="{{ asset("assets/js/bootstrap.min.js") }}"></script>
<script src="{{ asset("assets/js/appear.min.js") }}"></script>
<script src="{{ asset("assets/js/slick.min.js") }}"></script>
<script src="{{ asset("assets/js/jquery.magnific-popup.min.js") }}"></script>
<script src="{{ asset("assets/js/jquery.nice-select.min.js") }}"></script>
<script src="{{ asset("assets/js/imagesloaded.pkgd.min.js") }}"></script>
<script src="{{ asset("assets/js/skill.bars.jquery.min.js") }}"></script>
<script src="{{ asset("assets/js/isotope.pkgd.min.js") }}"></script>
<script src="{{ asset("assets/js/aos.js") }}"></script>
<script src="{{ asset("assets/js/script.js") }}"></script>
<script src="assets/js/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>


<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>window.gtranslateSettings = {"default_language":"en","languages":["en","hi","ru","es","fr",],"wrapper_selector":".gtranslate_wrapper","flag_size":16,"flag_style":"3d"}</script>
<script src="https://cdn.gtranslate.net/widgets/latest/fn.js" defer></script>

<a class="footer-whatsapp" aria-label="Whatsapp Button" href="https://wa.me/+91{!! $whatsapp_number ?? '999999999' !!}?text=Let%27s+start+build+a+project"><img src="{{ asset('./assets/images/whatsapp.png') }}" alt="Whatsapp" class="img-fluid" height="" width="150"></a>
<!-- <a class="footer-whatsapp footer-call" aria-label="Phone Call Button" href="tel:+91{{ isset($phone_footer_link)?str_replace(" ","",$phone_footer_link):"999999999" }}"><img src="./assets/images/phone-call.png" alt="Phone Call" class="img-fluid" height="" width="150"></a> -->


<script>
  function errorMessage(error_message) {
      Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: error_message
      });
  }

  function successMessage(success_message, reload = false) {
      Swal.fire({
          icon: 'success',
          title: 'Success',
          text: success_message
      }).then(function() {
          if (reload) {
              window.location.reload();
          }
      });
  }
</script>

<!-- <script>
    var swiper = new Swiper(".main-slider", {
  spaceBetween: 30,
  effect: "fade",
  loop: true,
  autoplay: {
    delay: 4500,
    disableOnInteraction: false,
  },
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  fadeEffect: {
    crossFade: true, // Ensures a smooth transition between images
  },
});
</script> -->
<script>
    const sliderswiper = new Swiper(".main-slider", {
		slidesPerView: 1,
		autoplay: {
			delay: 5000, 
		},
		loop: true,
	
		effect: 'fade', 
		fadeEffect: {
			crossFade: true, 
		},
	
		speed: 2000, 
	
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
	});
</script>
<script>
  var swiper = new Swiper('.packages', {
       loop: true,
       spaceBetween: 20, 
       autoplay: {
         delay: 2500,
         disableOnInteraction: false,
       },
       breakpoints: {
           500:{slidePerView:1},
           768: { slidesPerView: 2 }, 
           1024: { slidesPerView: 4 }
       }
   });
</script>
<script>
  var swiper = new Swiper('.home-services', {
       loop: true,
       spaceBetween: 20, 
       autoplay: {
         delay: 2500,
         disableOnInteraction: false,
       },
       breakpoints: {
           500:{slidePerView:1},
           768: { slidesPerView: 2 }, 
           1024: { slidesPerView: 4 }
       }
   });
</script>
<script>
  var swiper = new Swiper('.home-gallery', {
       loop: true,
       spaceBetween: 20, 
       autoplay: {
         delay: 2500,
         disableOnInteraction: false,
       },
       breakpoints: {
           500:{slidePerView:1},
           768: { slidesPerView: 2 }, 
           1024: { slidesPerView: 4 }
       }
   });
</script>
<script>
    var swiper = new Swiper(".guest_review", {
  spaceBetween: 20,
  dynamicBullets: true,
  loop: true,
  // centeredSlides: true,
  autoplay: {delay: 3000,disableOnInteraction: false,},
  navigation: {
    nextEl: ".swiper-button-next",
    prevEl: ".swiper-button-prev",
  },
  pagination: {el: ".swiper-pagination",clickable: true,},
  breakpoints: {
    480: {slidesPerView: 1,},
    640: {slidesPerView: 1,},
    768: {slidesPerView: 2,},
    1024: {slidesPerView: 2,},
  },
});

</script>

<script>
    // Attach submit event handler to the form
    $("#enquiryForm").on("submit", function(e) {
        e.preventDefault(); // Prevent default form submission
  
        // Create FormData object
        var form = new FormData(this);
  
        // Disable the submit button to prevent multiple submissions
        $("#submitButton").attr("disabled", true);
  
        // Send AJAX POST request
        $.ajax({
            type: 'post',
            url: '{{ route('saveEnquiryFormData') }}', // Backend route for saving contact details
            data: form,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    // Display success message
                    successMessage(response.message, true);
  
                    // Reset the form after successful submission
                    $("#enquiryForm")[0].reset();
  
                    // Re-enable the submit button
                    $("#submitButton").attr("disabled", false);
                } else {
                    // Display error message
                    errorMessage(response.message ?? "Something went wrong.");
                    $("#submitButton").attr("disabled", false);
                }
            },
            failure: function(response) {
                // Handle failure response
                errorMessage(response.message ?? "Something went wrong.");
                $("#submitButton").attr("disabled", false);
            },
            error: function(response) {
                // Handle error response
                errorMessage(response.message ?? "Something went wrong.");
                $("#submitButton").attr("disabled", false);
            }
        });
    });
  
    // Function to refresh the CAPTCHA (if applicable)
    // function refreshCapthca(captchaImgId, captchaInputId) {
    //     // Replace the CAPTCHA image source to reload it
    //     $("#" + captchaImgId).attr("src", "{{ captcha_src() }}" + "?" + Math.random());
  
    //     // Clear the CAPTCHA input field
    //     $("#" + captchaInputId).val('');
    // }
</script>

<script>
  function toggleContent(id) {
    var dots = document.getElementById(`dots-${id}`);
    var hiddenContent = document.getElementById(`hiddenContent-${id}`);
    var btnText = document.getElementById(`myBtn-${id}`);

    if (dots && hiddenContent) {
        if (dots.style.display === "none") {
            dots.style.display = "inline"; 
            hiddenContent.style.display = "none"; 
            btnText.innerHTML = "Read more"; 
        } else {
            dots.style.display = "none"; 
            hiddenContent.style.display = "inline";
            btnText.innerHTML = "Read less"; 
        }
    }
}

</script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('customModal');
  const openButtons = document.querySelectorAll('.openModalButton');
  const closeModalButton = document.getElementById('closeModalButton');

  // Open the modal when any donate button is clicked
  openButtons.forEach(button => {
      button.addEventListener('click', function () {
          modal.style.display = "block";
      });
  });

  // Close the modal
  closeModalButton.addEventListener('click', function () {
      modal.style.display = "none";
  });

  // Close the modal if user clicks outside the modal content
  window.addEventListener('click', function (event) {
      if (event.target === modal) {
          modal.style.display = "none";
      }
  });
});

</script>