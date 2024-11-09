
            var meeting_list = $('#meeting_list').DataTable({
                "processing": true,
                "serverSide": true,
                "type": "POST",
                "ajax": SITE + "ajaxfiles/meetings_list/meetings_list_processing.php",                
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": " <td><div align='center'><a id='edit' href='#update_meeting' class='btn btn-mini btn-info' role='button' data-toggle='modal' title='Edit Meeting'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete Meeting'><i class='fa fa-trash'></i> Delete</a></div></td>"
                }]
            });


            $('#meeting_list tbody').on('click', '#edit', function() {
                var data = meeting_list.row($(this).parents('tr')).data();                

                $('#e_title').val(data[0]);
                $('#e_customer_name').val(data[4]);                
                $('#e_event_time_from').val(data[9]);
                $('#e_event_time_to').val(data[10]);

                $('#e_event_date').val(data[7]);                                
                $('#e_location').val(data[3]);

                $('#e_overview').val(data[5]);
                $('#e_instructions').val(data[6]);               

                $('#eid').val(data[12]);                

                if(data[11]){
                    meetingInviteeProcess(data[11]);
                }

            });


            $('#meeting_list tbody').on('click', '#delete', function() {
            var data = meeting_list.row($(this).parents('tr')).data();                  


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
                url: SITE+"ajaxfiles/meetings_list/delete_meeting.php",
                data: {
                    "id": data[12],
                },
                dataType:'json',        
                success: function(response) {

                meeting_list.ajax.reload();

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




        function meetingInviteeProcess(list) {

            if (list) {

                var dataSet = 'list=' + list;

                $.ajax({
                    url: SITE + "ajaxfiles/meetings_list/meeting_invitee_process.php",
                    type: "POST",
                    data: dataSet,
                    dataType: 'json',
                    success: function(response) {            
                    inviteeLoop(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        
                        $("#meeting_invitee_list").html('<h2>A system error has been encountered. Please try again</h2>');                
                    }

                });

            }

        }            

        function inviteeLoop(data){            
            var items = "";
            $.each(data, function(index, item) {                
                items += "<div class='uploaded-item'><label>" + item.full_name + "</label></div>";
            });

            $("#meeting_invitee_list").html(items);
        
        }


            $("#meeting_form").validate({
                
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
                        event_date: {
                            required: true,                
                        },
                                                     

                    },
                    messages:{

                        title:
                            {
                                required: 'Please type meeting title'
                            },     
                        event_time_from:
                            {
                                required: 'Please select meeting time from'
                            },
                        event_time_to:
                            {
                                required: 'Please select meeting time to'
                            },
                        event_date:
                            {
                                required: 'Please select meeting date'
                            },
                                                                                                                                                                                                       
                    },


                    submitHandler: function(form) {


                        $('.submit_action_button').css('cursor', 'wait');
                        $('.submit_action_button').attr('disabled', true);

                          var upload_item = $('.upload_item').map(function() {
                            return this.value;
                            }).get();

                                    
                        $.ajax({
                            url: SITE+"ajaxfiles/meetings_list/meetings_data_processing.php",
                            type: "POST",
                            data: $(form).serialize()+'&upload_item='+upload_item,
                            dataType:'json',              
                            success: function(response) {                   
                               
                                $(form).trigger("reset");
                                $('#added-number-group').html('');
                                $('#uploaded-group1').html('');
                                
                                meeting_list.ajax.reload();

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


            $("#meeting_form_update").validate({
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
                    event_date: {
                        required: true,                
                    },                         

                    },
                    messages:{

                        title:
                            {
                                required: 'Please type meeting title'
                            },     
                        event_time_from:
                            {
                                required: 'Please select meeting time from'
                            },
                        event_time_to:
                            {
                                required: 'Please select meeting time to'
                            },
                            event_date:
                            {
                                required: 'Please select meeting date'
                            },
                                                                                                                                                                             
                    },


                submitHandler: function(form) {

                    $('.update_submit_button').css('cursor', 'wait');
                    $('.update_submit_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "ajaxfiles/meetings_list/update_meeting_processing.php",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {

                                                           
                            $(form).trigger("reset");
                            $('#meeting_invitee_list').html('');
                            $('#update_meeting').modal('hide');

                            swal({
                                    title: response.message,
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                            });  

                            meeting_list.ajax.reload();

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