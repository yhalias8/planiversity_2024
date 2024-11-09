<script src="<?php echo SITE; ?>js/flexcroll.js"></script>

<script type="text/javascript">
   function show_win(id) {
      $('#icon_details' + id).toggle();
      $('#win_details' + id).toggle('slow');
   }
</script>

<!--<a onclick="show_win(1)" id="icon_details1" class="trigger filters_link blink"><img src="<?php echo SITE; ?>images/icon_filters.png" alt="" /><br />Filters</a>-->


<div class="close_cont_blue">
   <a onclick="show_win(2)" id="icon_details2" class="trigger filters_link small_style" style="display: none"><img src="<?php echo SITE; ?>images/icon_toandfrom.png" alt="" /><br />Location</a>
   <a onclick="show_win(3)" id="icon_details3" class="trigger filters_link small_style" style="display: none"><img src="<?php echo SITE; ?>images/icon_timeline.png" alt="" /><br />Timeline</a>
   <a onclick="show_win(4)" id="icon_details4" class="trigger filters_link" style="display: none"><img src="<?php echo SITE; ?>images/icon_plannotes.png" alt="" /><br />Notes</a>
   <a onclick="show_win(5)" id="icon_details5" class="trigger filters_link" style="display: none"><img src="<?php echo SITE; ?>images/icon_documents.png" alt="" /><br />Docs</a>
   <a onclick="show_win(6)" id="icon_details6" class="trigger filters_link small_style" style="display: none"><img src="<?php echo SITE; ?>images/icon_pdf.png" alt="" /><br />Employee</a>
   <a onclick="show_win(7)" id="icon_details7" class="trigger filters_link" style="display: none"><img src="<?php echo SITE; ?>images/icon_profile.png" alt="" /><br />Trip</a>
   <a onclick="show_win(8)" id="icon_details8" class="trigger filters_link" style="display: none"><img src="<?php echo SITE; ?>images/step_filters.png" alt="" /><br />Filters</a>
</div>