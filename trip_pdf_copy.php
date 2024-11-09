<?php
include_once("config.ini.php");
include_once("class/class.TripPlan.php");

$output   = '';
$trip     = new TripPlan();
$id_trip  = filter_var($_GET["idtrip"], FILTER_SANITIZE_STRING);
$trip->setProgressing($id_trip, 0);

if (empty($_POST["name_title"])) { // test script
    $_POST["name_title"] = "testdemo";
}

if (!empty($_POST["name_title"])) {
    $name = filter_var($_POST["name_title"], FILTER_SANITIZE_STRING);
    //$trip->edit_data_name($id_trip, $name);
    if (!$trip->error) {
    } else {
        header("Location:" . SITE . "trip/name/" . $id_trip . "/1"); //$output = 'A system error has been encountered. Please try again.';
        exit();
    }
}

if (!empty($_POST["localhost_full_path"])) {
    $full_path = $_POST["localhost_full_path"];
    $trip->edit_data_full_path($id_trip, $full_path);
    if (!$trip->error) {
        //success      
    } else {
        header("Location:" . SITE . "trip/name/" . $id_trip . "/1"); //$output = 'A system error has been encountered. Please try again.';
        exit();
    }
}

$trip->get_data($id_trip);
if (empty($_POST["name_title"]) && empty($trip->trip_title)) {
    header("Location:" . SITE . "trip/name/" . $id_trip . "/2"); //$output = 'The trip name is empty'; 
}

include("class/class.Plan.php");
$plan = new Plan();

if ($plan->check_plan($userdata['id'])) { // continue                
} else {
}

include('include_doctype.php');
?>
<html>

<head>
    <meta charset="utf-8">
    <title>PLANIVERSITY - PDF GENERATION</title>

    <link rel="shortcut icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <link rel="icon" href="<?= SITE; ?>favicon.ico" type="image/x-icon">
    <script src="<?= SITE; ?>js/jquery-1.12.4.js"></script>
    <script>
        $(document).ready(function() {
            function getLoadingFileName(percentage) {
                var fname = `<?= SITE; ?>images/loading/${percentage}.png`;
                return fname;
            }

            var count = 0;
            var post_data = "idtrip=" + "<?= $_GET['idtrip']; ?>";
            $.ajax({
                url: 'https://<?= $_SERVER['HTTP_HOST'] ?>/staging/trip_pdf_code.php',
                type: 'POST',
                data: post_data,
                dataType: 'html',
                success: function(data) {
                    var mtimer = setInterval(() => {
                        $.post("https://<?= $_SERVER['HTTP_HOST'] ?>/staging/ajaxfiles/get_progress.php", {
                                'MODE': 'progress',
                                'id': <?= $_GET['idtrip']; ?>
                            },
                            function(result) {
                                result = JSON.parse(result);
                                $("#loading_txt").text("Please wait, generating PDF..." + result.progressing + '%');
                                $("#loading_img").attr("src", getLoadingFileName(result.progressing));
                                if (result.progressing == 100) {
                                    setTimeout(function() {
                                        $('#loading').fadeOut();
                                        $('#loading_txt').fadeOut();
                                        clearInterval(mtimer);
                                        if (count == 0) {
                                            count = 1;
                                            window.open("<?= SITE . 'pdf/' . $name . '-' . $_GET['idtrip'] . '-' . $userdata['id'] . '.pdf'; ?>", '_blank')
                                        }
                                        location.href = "<?= SITE . 'welcome'; ?>";
                                    }, 1000);
                                }
                            }
                        )
                    }, 500);
                },
                error: function(data) {
                    alert("Something went wrong!" + data);
                }
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            jQuery.fn.centerElement = function() {
                this.css("position", "absolute");
                this.css("top", ($(window).height() - this.height()) / 2 + $(window).scrollTop() - 50 + "px");
                this.css("left", ($(window).width() - this.width()) / 2 + $(window).scrollLeft() + "px")
                return this;
            }
            center_cont();
        });

        function center_cont() {
            $('#center_loading').centerElement();
            setTimeout(function() {
                center_cont();
            }, 100);
        }
    </script>

    <style>
        .loader {
            width: 100%;
            height: 100%;
            margin: 0 auto 0 auto;
        }
    </style>

</head>

<body>
    <div id="center_loading">
        <div id="loading" class="loader">
            <img id="loading_img" style="width: 480px;height:480px;" src="<?= SITE; ?>images/loading/0.png">
        </div>
        <h1 id="loading_txt" style="text-align:center;color:#1973B2; font-size:14px; font-family:Tahoma, Geneva, sans-serif;">Please wait, generating PDF</h1>
    </div>

</body>

</html>