<?php
include_once("config.ini.php");
include_once("include_login_php.php");
include_once("include_new_header.php")
?>
<link href="/gallery/style.css" rel="stylesheet">
<div class="what-you-will-get-main-wrapper spacer">
  <div class="container get-item-all-wrapper">
    <div class="row">
      <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
        <div class="get-item-wrap">
          <img src="images/images-illustration.png" alt="images illustration">
          <p><a href="" data-toggle="modal" data-target="#image-gallery-modal">Images</a></p>
        </div>
      </div>
      <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
        <div class="get-item-wrap">
          <img src="images/packet-illustration.png" alt="packet illustration">
          <p><a href="images/example_packet.pdf" target="pdf-frame">Sample Packet</a></p>
        </div>
      </div>
      <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
        <div class="get-item-wrap">
          <img src="images/demo-video-illustation.png" alt="demo video illustration">
          <p><a href="" class="video-btn" data-toggle="modal" data-src="https://www.youtube.com/embed/Jfrjeg26Cwk" data-target="#demoVideo">Demo Video</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Video Modal -->
<div class="modal fade" id="demoVideo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body modal-vid-body">
        <button type="button" class="close vid-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="embed-responsive embed-responsive-16by9">
          <div class="main-video-wrapper">
            <video controls id="video1" style="width: 100%; height: auto; margin:0 auto; frameborder:0;">
              <source src="/video/example_packet.mp4" type="video/mp4">
              Your browser doesn't support HTML5 video tag.
            </video>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="image-gallery-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-body modal-vid-body">
        <button type="button" class="close vid-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="embed-responsive embed-responsive">
          <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img class="d-block w-100" src="/assets/images/gallery-images/add-a-profile.png" alt="add a profile">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/add-a-trip-name.png" alt="add a trip name">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/add-documents.png" alt="add documents">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/create-a-timeline.png" alt="create a timeline">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/create-notes.png" alt="creates notes">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/how-traveling.png" alt="how traveling">
              </div>
              <div class="carousel-item">
                <img class="d-block w-100" src="/assets/images/gallery-images/to-and-from.png" alt="to and from">
              </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  $('#demoVideo').on('shown.bs.modal', function() {
    $('#video1')[0].play();
  });
  $('#demoVideo').on('hidden.bs.modal', function() {
    $('#video1')[0].pause();
  });
</script>
<script src="/gallery/jquery.min.js"></script>
<script src="/gallery/popper.min.js"></script>
<script src="/gallery/bootstrap.min.js"></script>
<script src="/gallery/custom.js"></script>
<?php include_once("include_new_footer.php") ?>