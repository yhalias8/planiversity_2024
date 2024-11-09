<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    let blogList = [];
    let next_page = null;
    let category = 0;
    let pagination;

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

                //paginationLoad(response.data.total_count, cat_mode);
                //$('.service_count').html(response.data.total_count);

                //next_page = response.data.next_page;                
                console.log('blogList', blogList);

                //$(".view_more").val(response.data.next_page);

                // if (cat_mode) {
                //     $('.button-class').css('cursor', 'pointer');
                //     $('.button-class').removeAttr('disabled');
                // }

                // $('.e_button').css('cursor', 'pointer');
                // $('.e_button').removeAttr('disabled');


            },
            error: function(jqXHR, textStatus, errorThrown) {

                // $(".loading_screen").hide();
                // $('#search_result').html("<h2>A system error has been encountered. Please try again</h2>");


            }

        });

    }
</script>