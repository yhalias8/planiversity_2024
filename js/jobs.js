
            var job_list = $('#job_list').DataTable({
                "processing": true,
                "serverSide": true,
                "type": "POST",
                "ajax": SITE + "ajaxfiles/jobs_list/jobs_list_processing.php",                
                "columnDefs": [{
                    "targets": -1,
                    "data": null,
                    "defaultContent": " <td><div align='center'><a id='edit' href='#update_job' class='btn btn-mini btn-info' role='button' data-toggle='modal' title='Edit Job'><i class='fa fa-edit'></i> Edit</a>  <button id='delete' class='btn btn-mini btn-danger' title='Delete Job'><i class='fa fa-trash'></i> Delete</a></div></td>"
                }]
            });


            $('#job_list tbody').on('click', '#edit', function() {
                var data = job_list.row($(this).parents('tr')).data();                

                $('#e_job_name').val(data[0]);
                $('#e_job_category').val(data[1]);                
                $('#e_job_cnumbers').val(data[4]);
                $('#e_job_details').val(data[3]);

                $('#e_job_address').val(data[2]);                                
                $('#e_job_city').val(data[5]);

                $('#job_state').val(data[6]);
                $('#e_job_zcode').val(data[7]);               

                $('#eid').val(data[8]);    

                employeesProcess(data[8]);

            });


            $('#job_list tbody').on('click', '#delete', function() {
            var data = job_list.row($(this).parents('tr')).data();                  

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
                url: SITE+"ajaxfiles/jobs_list/delete_job.php",
                data: {
                    "id": data[8],
                },
                dataType:'json',        
                success: function(response) {

                job_list.ajax.reload();

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




        function employeesProcess(ID) {

            if (ID) {

                var dataSet = 'ID=' + ID;

                $.ajax({
                    url: SITE + "ajaxfiles/jobs_list/job_employee_process.php",
                    type: "POST",
                    data: dataSet,
                    dataType: 'json',
                    success: function(response) {            
                        employeeLoop(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        
                        $("#employee_list").html('<h2>A system error has been encountered. Please try again</h2>');                
                    }

                });

            }

        }            

        function employeeLoop(data){            
            var items = "";
            $.each(data, function(index, item) {                
                items += "<div class='uploaded-item'><label>" + item.full_name + "</label></div>";
            });

            $("#employee_list").html(items);
        
        }


            $("#job_form").validate({
                
                    rules:{

                        job_name:{
                            required:true,
                        },
                        job_category:{
                            required:true,                
                        }, 
                        job_details: {
                            required: true,                
                        },
                                                     

                    },
                    messages:{

                        job_name:
                            {
                                required: 'Please type job title'
                            },     
                            job_category:
                            {
                                required: 'Please select a category'
                            },
                            job_details:
                            {
                                required: 'Please type job details'
                            },
                                                                                                                                                                                                       
                    },


                    submitHandler: function(form) {


                        $('.submit_action_button').css('cursor', 'wait');
                        $('.submit_action_button').attr('disabled', true);

                          var upload_item = $('.upload_item').map(function() {
                            return this.value;
                            }).get();

                                    
                        $.ajax({
                            url: SITE+"ajaxfiles/jobs_list/jobs_data_processing.php",
                            type: "POST",
                            data: $(form).serialize()+'&upload_item='+upload_item,
                            dataType:'json',              
                            success: function(response) {                   
                               
                                $(form).trigger("reset");
                                $('#added-number-group').html('');
                                $('#uploaded-group1').html('');
                                
                                job_list.ajax.reload();

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


            $("#job_form_update").validate({
                rules:{

                    job_name:{
                        required:true,
                    },
                    job_category:{
                        required:true,                
                    }, 
                    job_details: {
                        required: true,                
                    },                       

                    },
                    messages:{

                        job_name:
                            {
                                required: 'Please type job title'
                            },     
                            job_category:
                            {
                                required: 'Please select a category'
                            },
                            job_details:
                            {
                                required: 'Please type job details'
                            },
                                                                                                                                                                             
                    },


                submitHandler: function(form) {

                    $('.update_submit_button').css('cursor', 'wait');
                    $('.update_submit_button').attr('disabled', true);

                    $.ajax({
                        url: SITE + "ajaxfiles/jobs_list/update_job_processing.php",
                        type: "POST",
                        data: $(form).serialize(),
                        dataType: 'json',
                        success: function(response) {

                                                           
                            $(form).trigger("reset");
                            $('#employee_list').html('');
                            $('#update_job').modal('hide');

                            swal({
                                    title: response.message,
                                    type: "success",
                                    timer: 2500,
                                    showConfirmButton: true,
                                    customClass: 'swal-height'
                            });  

                            job_list.ajax.reload();

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