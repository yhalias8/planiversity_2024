// see employee details
function see_detail(id)
   { $('#details'+id).toggle('slow');
   }

// delete an employee
function del_element(id)
    { var tmp = 'Are you sure you want to delete this Employee?';
      $('#loading_list').hide('fast');
      $('#error_list').hide('fast');
      if (confirm(tmp))
         { setTimeout(function() {  
              $.post(SITE+"ajaxfiles/add_employees.php", {id : id},    
              function(data)
                 { if(data['error'])
                     { $('#error_list').html(data['error']);
                       $('#error_list').fadeIn(500);
                     }
                   else
                     { $('#employee_'+id).fadeOut(1000);
                     }                     
                 }, "json"); 
           }, 0 );  
         }
      else
         return false;
 }


 $("#employee_form").validate({
  rules: {
      employee_fname: {
          required: true,
      },
      employee_lname: {
          required: true,
      },
      employee_id: {
          required: true,
      },
  },
  messages: {
      employee_fname: {
          required: "Please type employee first name",
      },
      employee_lname: {
          required: "Please type emplyee last name",
      },
      employee_id: {
          required: "Please type employee ID",
      },
  },

  submitHandler: function(form) {
      $("#employee_add").css("cursor", "wait");
      $("#employee_add").attr("disabled", true);


      $('#loading_list').show('fast');	
      $('#error_list').hide('fast');
      $('#error_list').html('');
      var dlexp_date = $('#employee_dldate').val();
      var birthdate = $('#employee_b').val();
      var docname1 = $('#employee_docname1').val();
      var docname2 = $('#employee_docname2').val();
      var docname3 = $('#employee_docname3').val();
      var docname4 = $('#employee_docname4').val();
      //docname1.replace('[',''); docname1.replace(']',''); docname1.replace('"',''); docname1.replace('&#34',''); 
      //docname2.replace('[',''); docname2.replace(']',''); docname2.replace('"',''); docname2.replace('&#34','');
      //docname3.replace('[',''); docname3.replace(']',''); docname3.replace('"',''); docname3.replace('&#34','');
      //docname4.replace('[',''); docname4.replace(']',''); docname4.replace('"',''); docname4.replace('&#34','');
        $.post(SITE+"ajaxfiles/add_employees.php", {fname : $('#employee_fname').val(), lname : $('#employee_lname').val(), empid : $('#employee_id').val(), address : $('#employee_address').val(), city : $('#employee_city').val(), state : $('#employee_state').val(), zipcode : $('#employee_zcode').val(), phone : $('#employee_phone').val(), dlnum : $('#employee_dlnumber').val(), dlstate : $('#employee_dlstate').val(), dldate : dlexp_date, ssn : $('#employee_ssn').val(), bdate : birthdate, email : $('#employee_email').val(), gender : $('#employee_gender').val(), race : $('#employee_race').val(), veteran : $("input[name='employee_veteran']:checked").val(), doc1 : docname1, doc2 : docname2, doc3 : docname3, doc4 : docname4 },
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
           //$("#employee_form")[0].reset();
           $("#employee_form").trigger("reset");

           $("#employee_add").css("cursor", "pointer");
           $("#employee_add").removeAttr("disabled");

           swal({
            title: "Employee Successfully Added",
            type: "success",
            timer: 2500,
            showConfirmButton: true,
            customClass: 'swal-height'

        });

           // document.getElementById("employee_form").reset(); 
               }
           }, "json");       



  }, // Do not change code below
  errorPlacement: function(error, element) {
      error.insertAfter(element.parent());
  },
});
 
 
 

$(document).ready(function() { 
 
                 
         
});