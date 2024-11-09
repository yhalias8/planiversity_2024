<!DOCTYPE html>
<html lang="en">

<head>
	<title>Upload Profile</title>
	<!-- Bootstrap core CSS -->
	<link href="crop/assets/css/bootstrap.css" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="crop/assets/css/main.css" rel="stylesheet">
	<link href="crop/assets/css/croppic.css" rel="stylesheet">
</head>

<body>
	<div class="container">
		<div class="row">
			<div class="col-lg-4">
				<div id="croppic"></div>
				<span class="btn" id="cropContainerHeaderButton">upload profile</span>
				<button id="btn-crop" class="btn btn-success">submit</button>
			</div>
		</div>
	</div>

	<script src=" https://code.jquery.com/jquery-2.1.3.min.js"></script>

	<script src="crop/assets/js/bootstrap.min.js"></script>
	<script src="crop/assets/js/jquery.mousewheel.min.js"></script>
	<script src="crop/croppic.min.js"></script>
	<script src="crop/assets/js/main.js"></script>

	<script>
		var croppicHeaderOptions = {
			cropData: {
				"dummyData": 1,
				"dummyData2": "asdas"
			},
			cropUrl: 'img_crop_to_file.php',
			customUploadButtonId: 'cropContainerHeaderButton',
			modal: false,
			processInline: true,
		}

		var croppic = new Croppic('croppic', croppicHeaderOptions);

		$('#btn-crop').hide();
		$('#btn-crop').click(function() {
			$('.cropControlCrop').click();
		})

		$('#cropContainerHeaderButton').click(function() {
			$('#btn-crop').show();
		})
	</script>
	<script type="text/javascript">
		setInterval(function() {
			$.ajax({
				type: 'POST',
				url: 'crop/check_session.php',
				success: function(response) {
					if (response == true) {
						window.location.href = "http://planiversity.com/staging/welcome";
					}
				},
				error: function() {
					console.log('Something went wrong');
				}
			})
		}, 1000);
	</script>
</body>

</html>