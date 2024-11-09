// see employee details
function see_detail(id)
   { $('#details'+id).toggle('slow');
   }

// delete a note
function del_element(id)
    { var tmp = 'Are you sure you want to delete this Timeline?';
      $('#loading_list').hide('fast');
      $('#error_list').hide('fast');
      if (confirm(tmp))
         { setTimeout(function() {  
              $.post(SITE+"ajaxfiles/add_notes.php", {id : id},    
              function(data)
                 { if(data['error'])
                     { $('#error_list').html(data['error']);
                       $('#error_list').fadeIn(500);
                     }
                   else
                     { $('#note_'+id).fadeOut(1000);
                     }                     
                 }, "json"); 
           }, 0 );  
         }
      else
         return false;
 }

$(document).ready(function() { 
 
 $('#notes_add').click(function(event){ // add a note to DB  
   //setTimeout(function() {
    $('#loading_list').show('fast');	
    $('#error_list').hide('fast');
	$('#error_list').html('');
    $.post(SITE+"ajaxfiles/add_notes.php", {name : $('#notes_text').val(), trip : $('#notes_idtrip').val()},
    function(data)
       { if(data['error'])
           { $('#loading_list').hide('fast');              
             $('#error_list').html(data['error']);
             $('#error_list').fadeIn(500);
           }
         else
           { $('#loading_list').hide('fast');
             $('#data_list').append(data['txt']);      
             $('#data_list').fadeIn(1000);
             $('#notes_text').val('');
           }
       }, "json"); 
    });                     
         
});