
var event_list = $('#event_list').DataTable({
                "processing": true,
                "serverSide": true,
                "type": "POST",
                "ajax": SITE + "ajaxfiles/events_list/events_list_processing.php",                
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": " <td><div align='center'><a id='edit' href='#update_event' class='btn btn-mini btn-info' role='button' data-toggle='modal' title='Edit Event'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete Event'><i class='fa fa-trash'></i> Delete</a></div></td>"
                }]
            });


            $('#event_list tbody').on('click', '#edit', function() {
                var data = event_list.row($(this).parents('tr')).data();                

                $('#e_title').val(data[0]);
                $('#e_customer_name').val(data[4]);
                $('#e_customer_number').val(data[5]);
                $('#e_event_time_from').val(data[13]);
                $('#e_event_time_to').val(data[14]);

                $('#e_event_date_from').val(data[11]);
                $('#e_event_date_to').val(data[12]);

                $('#e_address').val(data[6]);
                $('#e_location').val(data[3]);

                $('#e_overview').val(data[7]);
                $('#e_instructions').val(data[8]);                

                $('input[type="radio"][name=e_deposit][value='+data[9]+']').prop('checked',true);
                $('#e_deposit_amount').val(data[10]);

                $('#eid').val(data[16]);                

                if(data[15]){
                    eventInviteeProcess(data[15]);
                }


            });


            $('#event_list tbody').on('click', '#delete', function() {
            var data = event_list.row($(this).parents('tr')).data();                  


            swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: true
            }, function() {

            $.ajax({
                type: "POST",
                url: SITE+"ajaxfiles/events_list/delete_event.php",
                data: {
                    "id": data[16],
                },
                dataType:'json',        
                success: function(response) {

                event_list.ajax.reload();

               swal({
               title: response.message,
               type: "success",
               timer: 2500,
               showConfirmButton: true,
               customClass: 'swal-height'
                });                

                },
                error: function(jqXHR, textStatus, errorThrown) {
                    
                swal({
                title: "Error Occured",
                type: "warning",
                timer: 2500,
                showConfirmButton: true,
                customClass: 'swal-height'
                });   


                }

            });



        }); 

            });      




        function eventInviteeProcess(list) {

            if (list) {

                var dataSet = 'list=' + list;

                $.ajax({
                    url: SITE + "ajaxfiles/events_list/event_invitee_process.php",
                    type: "POST",
                    data: dataSet,
                    dataType: 'json',
                    success: function(response) {            
                    inviteeLoop(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        
                        $("#event_invitee_list").html('<h2>A system error has been encountered. Please try again</h2>');                
                    }

                });

            }


        }            

        function inviteeLoop(data){            
            var items = "";
            $.each(data, function(index, item) {                
                items += "<div class='uploaded-item'><label>" + item.full_name + "</label></div>";
            });

            $("#event_invitee_list").html(items);
        
        }



            $("#event_form").validate({
                
                    rules:{

                        title:{
                            required:true,
                        },
                        event_time_from:{
                            required:true,                
                        }, 
                        event_time_to: {
                            required: true,                
                        },
                        event_date_from: {
                            required: true,                
                        },
                        event_date_to: {
                            required: true,                
                        },                              

                    },
                    messages:{

                        title:
                            {
                                required: 'Please type event title'
                            },     
                        event_time_from:
                            {
                                required: 'Please select event time from'
                            },
                        event_time_to:
                            {
                                required: 'Please select event time to'
                            },
                        event_date_from:
                            {
                                required: 'Please select event date from'
                            },
                        event_date_to:
                            {
                                required: 'Please select event date to'
                            }                                                                                                                                                                                
                    },


                    submitHandler: function(form) {


                        $('.submit_action_button').css('cursor', 'wait');
                        $('.submit_action_button').attr('disabled', true);

                          var upload_item = $('.upload_item').map(function() {
                            return this.value;
                            }).get();

                                    
                        $.ajax({
                            url: SITE+"ajaxfiles/events_list/events_data_processing.php",
                            type: "POST",
                            data: $(form).serialize()+'&upload_item='+upload_item,
                            dataType:'json',              
                            success: function(response) {                   
                               
                                $(form).trigger("reset");
                                $('#added-number-group').html('');
                                $('#uploaded-group1').html('');
                                
                                event_list.ajax.reload();

                                   swal({
                                    title: response.message,
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                                    });                    
                            
                                       
                                $('.submit_action_button').css('cursor', 'pointer');
                                $('.submit_action_button').removeAttr('disabled');


                            },error: function(jqXHR, textStatus, errorThrown) { 
                                
                                swal({
                                    title: "Error Occured",
                                    type: "warning",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                                });                        

                                $('.submit_action_button').css('cursor', 'pointer');
                                $('.submit_action_button').removeAttr('disabled');                        

                            }


                        });




                    },                                                // Do not change code below
                    errorPlacement: function(error, element)
                    {
                        error.insertAfter(element.parent());
                    }


                });


            $("#event_form_update").validate({
                rules:{

                        title:{
                            required:true,
                        },
                        event_time_from:{
                            required:true,                
                        }, 
                        event_time_to: {
                            required: true,                
                        },
                        event_date_from: {
                            required: true,                
                        },
                        event_date_to: {
                            required: true,                
                        },                              

                    },
                    messages:{

                        title:
                            {
                                required: 'Please type event title'
                            },     
                        event_time_from:
                            {
                                required: 'Please select event time from'
                            },
                        event_time_to:
                            {
                                required: 'Please select event time to'
                            },
                        event_date_from:
                            {
                                required: 'Please select event date from'
                            },
                        event_date_to:
                            {
                                required: 'Please select event date to'
                            }                                                                                                                                                                                
                    },


                submitHandler: function(form) {

                    $('.update_submit_button').css('cursor', 'wait');
                    $('.update_submit_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "ajaxfiles/events_list/update_event_processing.php",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {

                                                           
                            $(form).trigger("reset");
                            $('#event_invitee_list').html('');
                            $('#update_event').modal('hide');

                            swal({
                                    title: response.message,
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                            });  

                            event_list.ajax.reload();

                            $('.update_submit_button').css('cursor', 'pointer');
                            $('.update_submit_button').removeAttr('disabled');


                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                                swal({
                                    title: "Error Occured",
                                    type: "warning",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                                }); 

                            $('.update_submit_button').css('cursor', 'pointer');
                            $('.update_submit_button').removeAttr('disabled');

                        }


                    });




                }, // Do not change code below
                errorPlacement: function(error, element) {
                    error.insertAfter(element.parent());
                }


            });