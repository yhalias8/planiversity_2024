<html>

<head>
    <title>Planiversity Holiday Landing Page</title>
    <link rel="icon" type="image/png" sizes="16x16" href="https://www.planiversity.com/images/favicon.png">
</head>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script>
    alert('123123');
    $(window).load(function() {
        $("a").click(function() {
            top.window.location.href = $(this).attr("href");
            alert('123');
            return true;
        })
    })
</script>

<body>

    <iframe id="frame" width="100%" height="100%" src="https://elu1qoujgz5d.swipepages.net/planiversity" frameborder=" 0" allowfullscreen="" style="position:absolute; top:0; left: 0">
    </iframe>

</body>

</html>