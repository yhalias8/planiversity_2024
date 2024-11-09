<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    let blogList = [];
    let next_page = null;
    let pagination;
    let category_slug = "<?= $category_slug ?>";
    let category = "<?= $category ?>";
    let author = "<?= $author ?>";


    function getCategoryList(category) {

        var myObject = {
            category: category,
        };

        var items = "";
        $.getJSON(SITE + "root/blog/category", myObject, function(response) {
            $("#blog-category").html(response.data.list);
        });

    }


    function blogListProcess(dataSet, cat_mode = null, load_more = true, data_empty = null) {

        if (data_empty) {
            $("#service_content").html("");
        }

        $(".loading_section").show();

        $('.e_button').css('cursor', 'wait');
        $('.e_button').attr('disabled', true);

        // if (cat_mode) {
        //     $('.button-class').css('cursor', 'wait');
        //     $('.button-class').attr('disabled', true);
        // }

        $.ajax({
            url: SITE + "root/blog/list",
            type: "GET",
            data: dataSet,
            dataType: "json",
            cache: false,
            success: function(response) {

                console.log('response', response);

                $(".loading_section").hide();

                $(".blog-row").html(response.data.responseList);
                blogList = [...response.data.results.data];

                //console.log('total_pages Outside', response.data.total_count);

                paginationLoad(response.data.total_count, cat_mode);
                $('.service_count').html(response.data.total_count);

                //next_page = response.data.next_page;                
                console.log('blogList', blogList);

                //$(".view_more").val(response.data.next_page);

                // if (cat_mode) {
                //     $('.button-class').css('cursor', 'pointer');
                //     $('.button-class').removeAttr('disabled');
                // }

                $('.e_button').css('cursor', 'pointer');
                $('.e_button').removeAttr('disabled');


            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


            }

        });

    }


    function paginationLoad(total_pages, reload) {

        if (reload) {
            $('#pagination-step').twbsPagination('destroy');
            $('#page-content').text('Page 1') + ' content here';
        }

        var numPages = Math.ceil(total_pages / 4);

        console.log('numPages number', numPages);

        if (numPages != 0) {

            console.log('Logic Cross');

            pagination = $('#pagination-step').twbsPagination({
                totalPages: numPages,
                visiblePages: 5,
                first: '',
                last: '',
                next: 'Next',
                prev: 'Previous',
                initiateStartPageClick: false,
                //startPage: page,
                hideOnlyOnePage: true,
                onPageClick: function(event, page) {
                    //fetch content and render here
                    $('#page-content').text('Page ' + page) + ' content here';
                    var dataSet = 'category=' + category + '&author=' + author + '&page=' + page;
                    blogListProcess(dataSet);

                    console.log('total_pages Inside', total_pages);
                }
            });
        }
    }


    $("#search_form").on("submit", function(event) {

        event.preventDefault(); // Prevent form submission

        var nameInput = $("#keyword-input");

        if ($.trim(nameInput.val()) === "") {
            //showError(nameInput, "Please enter your name");
            return;
        }


        var search = $('#keyword-input').val();

        var dataSet = 'category=' + category + '&author=' + author + '&search=' + search + '&page=' + 1;
        blogListProcess(dataSet, null, false);
        // $('html, body').animate({
        //     scrollTop: $("#marketplace-content").offset().top - 70
        // }, 1000);

    });
</script>