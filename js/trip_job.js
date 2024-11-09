// see employee details
function see_detail(id)
   { $('#details'+id).toggle('slow');
   }

// delete an employee
function del_element(id)
    { var tmp = 'Are you sure you want to delete this Job?';
      $('#loading_list').hide('fast');
      $('#error_list').hide('fast');
      if (confirm(tmp))
         { setTimeout(function() {  
              $.post(SITE+"ajaxfiles/add_jobs.php", {id : id},    
              function(data)
                 { if(data['error'])
                     { $('#error_list').html(data['error']);
                       $('#error_list').fadeIn(500);
                     }
                   else
                     { $('#job_'+id).fadeOut(1000);
                     }                     
                 }, "json"); 
           }, 0 );  
         }
      else
         return false;
 }

$(document).ready(function() { 
 
 $('#job_add').click(function(event){ // add an employee to DB  
    $('#loading_list').show('fast');	
    $('#error_list').hide('fast');
	$('#error_list').html('');
	var docname1 = $('#job_docname1').val();
	var docname2 = $('#job_docname2').val();
	var docname3 = $('#job_docname3').val();
	var docname4 = $('#job_docname4').val();
	var docname5 = $('#job_docname5').val();
	var docname6 = $('#job_docname6').val();
	var docname7 = $('#job_docname7').val();
	var docname8 = $('#job_docname8').val();
	
	var emp_map = $('.emp_map').map(function() {
    return this.value;
    }).get();
  
	$.post(SITE+"ajaxfiles/add_jobs.php", {name : $('#job_name').val(), category : $('#job_category').val(), details : $('#job_details').val(), cnumbers : $('#job_cnumbers').val(), address : $('#job_address').val(), employees : emp_map, city : $('#job_city').val(), state : $('#job_state').val(), zcode : $('#job_zcode').val(), doc1 : docname1, doc2 : docname2, doc3 : docname3, doc4 : docname4, doc5 : docname5, doc6 : docname6, doc7 : docname7, doc8 : docname8 },
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
             //clear the form
            $("#job_form").trigger("reset");
            $('#added-emp-group').html('');
           }
       }, "json");
    });                     
         
});