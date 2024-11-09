@extends('backend.layouts.admin_backend')

@section('title')
Admin Home
@endsection

@section('content')

<div class="container-fluid">

    <div class="row ">
        <div class="col-12">
            <!-- <h3 class="page-headr ml-2">Dashboard</h3>z -->


            <div class="row mt-3">

                <div class="col-md-4 col-xl-4">
                    <div class="card mb-3 widget-content">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Active users</div>
                                    <div class="widget-subheading">Planiversity</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success" id="active_user">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xl-4">
                    <div class="card mb-3 widget-content">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Paid users</div>
                                    <div class="widget-subheading">Planiversity</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-warning" id="paid_user">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-xl-4">
                    <div class="card mb-3 widget-content">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Active coupons</div>
                                    <div class="widget-subheading">Planiversity</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-info" id="active_coupon">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="page-header">

                <div class="page-title">
                    <h3>Analytics statistics</h3>
                </div>

                <div class="filter-section">

                    <div class="filter-item">

                        <div class="form-group">
                            <select class="form-control valid" id="filter" aria-invalid="false">
                                <option value="7">7 Days</option>
                                <option value="14">14 Days</option>
                                <option value="30">30 Days</option>
                            </select>
                        </div>

                    </div>

                    <div class="filter-action">
                        <button type="submit" class="btn btn-primary process_button filter_button"> <i class="fa fa-filter" aria-hidden="true"></i> Filter</button>
                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col-md-6 col-xl-6">
                    <div class="card mb-3 widget-content">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">New Visitor</div>
                                    <div class="widget-subheading">Google Analytics</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-success" id="g_new_visitor">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-xl-6">
                    <div class="card mb-3 widget-content">
                        <div class="widget-content-outer">
                            <div class="widget-content-wrapper">
                                <div class="widget-content-left">
                                    <div class="widget-heading">Returning Visitor</div>
                                    <div class="widget-subheading">Google Analytics</div>
                                </div>
                                <div class="widget-content-right">
                                    <div class="widget-numbers text-warning" id="g_returning_visitor">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">

                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <!-- <div class="card-header-tab card-header-tab-animation card-header">
                            <div class="card-header-title">
                                <i class="header-icon lnr-apartment icon-gradient bg-love-kiss"> </i>
                                Sessions by country
                            </div>

                        </div> -->
                        <div class="card-body">
                            <h5 class="card-title">Sessions by country</h5>
                            <div class="spinner-border text-primary chart-center" id="country-loading"></div>
                            <canvas id="country-chart" class="chartjs-render-monitor">
                            </canvas>
                        </div>
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Sessions by browser</h5>
                            <div class="spinner-border text-primary chart-center" id="browser-loading"></div>
                            <canvas id="browser-chart" class="chartjs-render-monitor">
                            </canvas>
                        </div>
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Top Traffic medium</h5>
                            <div class="spinner-border text-primary chart-center" id="traffic-loading"></div>
                            <canvas id="traffic-medium-chart" class="chartjs-render-monitor">
                            </canvas>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Sessions by device</h5>
                            <div class="spinner-border text-primary chart-center" id="device-loading"></div>
                            <canvas id="device-chart" class="chartjs-render-monitor">
                            </canvas>
                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Most visited pages</h5>
                            <div class="spinner-border text-primary chart-center" id="page-loading"></div>
                            <table id="most_visited_page">
                                <thead>
                                    <tr>
                                        <th>Url</th>
                                        <th>Title</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <h5 class="card-title">Total visitors and pageviews</h5>
                            <div class="spinner-border text-primary chart-center" id="visitor-loading"></div>
                            <table id="total_visitor">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Visitors</th>
                                        <th>Views</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>

</div>

<script>
    var charts = [];

    var country_ctx = document.getElementById("country-chart").getContext('2d');
    var browser_ctx = document.getElementById("browser-chart").getContext('2d');
    var device_ctx = document.getElementById("device-chart").getContext('2d');
    var traffic_medium_ctx = document.getElementById("traffic-medium-chart").getContext('2d');

    $(function() {

        $('.filter_button').css('cursor', 'wait');
        $('.filter_button').attr('disabled', true);

        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topCountries') }}", country_ctx, "country-loading", 'pie', true, "left");
        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topBrowsers') }}", browser_ctx, "browser-loading", 'horizontalBar', false);
        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topDevice') }}", device_ctx, "device-loading", 'doughnut', true, "left");
        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topMedium') }}", traffic_medium_ctx, "traffic-loading", 'bar', false, "left");

        var dataSet = 'days=' + 7;

        var field_array = ["g_new_visitor", "g_returning_visitor"];
        ajaxCall(dataSet, "{{ route('user.call.userType') }}", field_array);

        var dataSet = null;
        var field_array = ["active_user", "paid_user", "active_coupon"];
        ajaxCall(dataSet, "{{ route('user.call.userCalculation') }}", field_array);

        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxTableCall(dataSet, "{{ route('user.call.topVisitedPages') }}", "most_visited_page", "page-loading");
        var dataSet = 'days=' + 7 + '&length=' + 10;
        ajaxTableCall(dataSet, "{{ route('user.call.totalVisitor') }}", "total_visitor", "visitor-loading");

        //ajaxcall(dataSet, topCountriesByUser);

        // var assd = getRandomColorEach(4);
        // console.log('assd', assd);

    });


    function chartDestroy() {
        for (var i = 0; i < charts.length; i++) {
            charts[i].destroy();
        }
    }

    $('.filter_button').click(function() {

        $(this).css('cursor', 'wait');
        $(this).attr('disabled', true);

        chartDestroy();

        var filter_value = $('#filter').val();

        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topCountries') }}", country_ctx, "country-loading", 'pie', true, "left");
        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topBrowsers') }}", browser_ctx, "browser-loading", 'horizontalBar', false);
        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topDevice') }}", device_ctx, "device-loading", 'doughnut', true, "left");
        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxChartcall(dataSet, "{{ route('user.call.topMedium') }}", traffic_medium_ctx, "traffic-loading", 'bar', false, "left");
        var dataSet = 'days=' + filter_value;
        var field_array = ["g_new_visitor", "g_returning_visitor"];
        ajaxCall(dataSet, "{{ route('user.call.userType') }}", field_array);
        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxTableCall(dataSet, "{{ route('user.call.topVisitedPages') }}", "most_visited_page", "page-loading");
        var dataSet = 'days=' + filter_value + '&length=' + 10;
        ajaxTableCall(dataSet, "{{ route('user.call.totalVisitor') }}", "total_visitor", "visitor-loading");

    });

    function getRandomColor() {
        var letters = '0123456789ABCDEF'.split('');
        var color = '#';
        for (var i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    function getRandomColorEach(count) {
        var data = [];
        for (var i = 0; i < count; i++) {
            data.push(getRandomColor());
        }
        return data;
    }

    function ajaxChartcall(dataSet, url, context_bind, loading, type, legend_display, position) {

        $('#' + loading).show();

        $.ajax({
            url: url,
            type: "GET",
            data: dataSet,
            dataType: 'json',
            cache: false,
            success: function(response) {
                chartCalulation(response, context_bind, type, legend_display, position);
                $('#' + loading).hide();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#' + loading).hide();
            }
        });
    }

    function ajaxTableCall(dataSet, url, element, loading) {

        $('#' + loading).show();

        $.ajax({
            url: url,
            type: "GET",
            data: dataSet,
            dataType: 'json',
            cache: false,
            success: function(response) {
                tableCalulation(response.data, element);
                $('#' + loading).hide();

                $('.filter_button').css('cursor', 'pointer');
                $('.filter_button').removeAttr('disabled');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $('#' + loading).hide();

            }
        });

    }

    function ajaxCall(dataSet, url, field_array) {
        $.ajax({
            url: url,
            type: "GET",
            data: dataSet,
            dataType: 'json',
            cache: false,
            success: function(response) {
                valueCalulation(response.data, field_array);
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });
    }

    var options = {
        responsive: true,
        legend: {
            display: false,
            padding: 50,
            position: "top",
            labels: {
                fontColor: "#333",
                fontSize: 14
            }
        },

    };

    function tableCalulation(res, element) {

        $.each(res, function(index, slot) {

            $("#" + element + " tbody").append(
                "<tr>" +
                "<td>" + slot[0] + "</td>" +
                "<td>" + slot[1] + "</td>" +
                "<td>" + slot[2] + "</td>" +
                "</tr>"
            );
        });

        $("#" + element).tablesorter();
    }

    function valueCalulation(res, field_array) {

        $.each(field_array, function(index, value) {
            $('#' + value).html(res[index].values);
        });

    }

    function chartCalulation(res, context, type, legend_display = false, postion = "top") {

        reference = new Chart(context, {
            type: type,
            data: {
                labels: [...res.data.labels],
                datasets: [{
                    data: [...res.data.values], // Specify the data values array

                    borderColor: '#fff', // Add custom color border 
                    backgroundColor: getRandomColorEach(res.count), // Add custom color background (Points and Fill)
                    borderWidth: 1 // Specify bar border width
                }]
            },
            options: {
                responsive: true,
                legend: {
                    display: legend_display,
                    padding: 50,
                    position: postion,
                    labels: {
                        fontColor: "#333",
                        fontSize: 14
                    }
                },
            }
        });

        charts.push(reference);



    }
</script>


@stop