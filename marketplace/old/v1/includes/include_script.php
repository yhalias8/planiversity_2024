<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>


<script>
    // $('.owl-carousel').owlCarousel({
    //     loop: true,
    //     margin: 0,
    //     responsiveClass: true,
    //     responsive: {
    //         0: {
    //             items: 1,
    //             nav: true
    //         },
    //         600: {
    //             items: 3,
    //             nav: false
    //         },
    //         1000: {
    //             items: 4,
    //             nav: true,
    //             loop: false
    //         }
    //     }
    // })

    $('.owl-carousel').owlCarousel({
        stagePadding: 10,
        loop: true,
        margin: 10,
        nav: true,
        autoplay: true,
        autoplayTimeout: 5000,
        navText: [
            '<i class="fa fa-angle-left" aria-hidden="true"></i>',
            '<i class="fa fa-angle-right" aria-hidden="true"></i>'
        ],
        navContainer: '.main-content .custom-nav',
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 3
            },
            1000: {
                items: 4
            }
        }
    });
</script>