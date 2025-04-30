 
$.ajax({
    type: 'get',
    url: 'get-home-page-services',
    data: {
    },
    success: function (response) {
        if (response.length) {
            let data = "";
            response.forEach(element => {
                data += `<div class="swiper-slide mb-4">
            <div class="destinations-new">
                <div class="destinations-inner">
                    <img src="${element.service_image}" class="img-fluid" width="300" height="400" alt="${element.service_name}">
                </div>
                <p class="text-center">${element.service_name}</p>
            </div>
        </div>`; 
            });
            if (data) {
                $("#ourServicesDiv").html(data);
            }
        }
    },
    failure: function (response) {

    }
});