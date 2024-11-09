

    var idtrip = $('#plans_idtrip').val();
    var location_to_lat = $('#location_to_lat').val();
    var location_to_lng = $('#location_to_lng').val();

    $(function() {
        getPlansList();
    });


        function getPlansList() {        

        var items = "";
        $.getJSON(SITE+"ajaxfiles/plan/get_plan.php",{ id_trip: idtrip}, function(data) {            
            $.each(data, function(index, item) {
                //items += "<div class='note-result-wrap' id='note_"+item.id_note+"'><p><span style='color:#78859A;' class='load_item'>" + item.text + "</span> <span class='button_action'><a href='#'' onclick='del_element("+item.id_note+")' data-toggle='tooltip' data-placement='top' data-original-title='Delete'><i class='fa fa-times-circle edit-icon' style='color:#058BEF;'></i></a><a href='#' onclick='edit_form("+item.id_note+");' data-toggle='tooltip' data-placement='top' data-original-title='Edit'><i class='fa fa-pencil (alias) edit-icon' style='color:#058BEF;'></i></a></span></p></div>";
                items += `<li id="plan_` + item.id_plan + `">
                <div class="your_plan_item_list_text">
                <h4 id="plan_name">` + item.plan_name + `</h4>
                <p>` + item.plan_address + `</p>                
                <h6 id="plan_address">` + item.plan_type + `</h6>
                </div>
                <div class="your_plan_item_edit">
                <button type="button" onclick="edit_plan(` + item.id_plan + `)" class="btn edit action_button"><i class="fa fa-pencil" aria-hidden="true"></i></button>
                <button type="button" onclick="remove_plan(` + item.id_plan + `)" class="btn delete action_button"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
                </li>`;
            });

            $("#plan_list").html(items);
            
        });
        

    }



function edit_plan(id){    

        var dataSet = 'id=' + id;        
        
        const matchValue = markers_list.findIndex(item => item.id == id);

        $('.action_button').css('cursor', 'not-allowed');
        $('.action_button').attr('disabled', true);        

        $.ajax({
            url: SITE+ "ajaxfiles/plan/get_plan_single.php",
            type: "GET",
            data: dataSet,
            dataType:'json',   
            success: function(response) {         
        
        if(response){ 

        $("#plan_address").prop('disabled', false);
        var icon_image = iconSelect(response['plan_type']);                
        DeleteMarker(id);
        changeMarkerPosition(response['plan_lat'], response['plan_lng'],icon_image,'bounce');        
        $('#btn-plan').html('Update');                
        $("#plan_title").val(response['plan_name']);
        $("#plan_id").val(response['id_plan']);
        $("#plan_type").val(response['plan_type']);
        $("#plan_address").val(response['plan_address']);
        $("#location_to_lat").val(response['plan_lat']);
        $("#location_to_lng").val(response['plan_lng']);
        $('body').scrollTop(0);
        $("#plan_title").focus();

        
        }
        
            }


         });



}


$("#form-plan").validate({    
        rules:{
            plan_title:{
                required:true,
            },  
            plan_type:{
                required:true,
            },
            plan_address:{
                required:true,
            }                                                                               
        },
        messages:{

            plan_title:
                {
                    required: 'Please type plan title'
                },
            plan_type:
                {
                    required: 'Please select activity type'
                },
            plan_address:
                {
                    required: 'Please type address'
                }                                                                                                                                                                               
        },


        submitHandler: function(form) {


            $('#btn-plan').css('cursor', 'wait');
            $('#btn-plan').attr('disabled', true);

            var plan_id = $("#plan_id").val();
            var plan_title = $("#plan_title").val();
            var plan_type = $("#plan_type").val();
            var plan_address = $("#plan_address").val();                        
            var loc_lat = $("#location_to_lat").val();
            var loc_lng = $("#location_to_lng").val();
           
                        
            $.ajax({
                url: SITE+"ajaxfiles/plan/add_plan.php",
                type: "POST",
                data: $(form).serialize(),
                dataType:'json',              
                success: function(response) {               
                    
                    var icon_image = iconSelect(plan_type);

                    if(response.action=='Update'){

                    const matchValue = markers_list.findIndex(item => item.id == plan_id);                    

                    let updated_state = [...markers_list];

                    let updated_element = {
                        ...updated_state[matchValue]
                    };

                    updated_element.title = plan_title;
                    updated_element.lat = loc_lat;
                    updated_element.lng = loc_lng;
                    updated_element.type = plan_type;
                    updated_state[matchValue] = updated_element;
                    markers_list = [...updated_state];

                    action_marker.setVisible(false);            

                    addMarkerProcess(plan_id,loc_lat,loc_lng,icon_image,plan_title,0,1);
                    toastr.success('Successfully Plan Updated'); 
                    $("#plan_id").val('');

                    $('.action_button').css('cursor', 'pointer');
                    $('.action_button').removeAttr('disabled');                                  

                    }else{
                        const dataS = {
                            "id" : response.id,
                            "title": plan_title,
                            "lat": loc_lat,
                            "lng": loc_lng,                            
                            "type":plan_type
                        };
                        
                        markers_list.push(dataS);                        
                        addMarkerProcess(response.id,loc_lat,loc_lng,icon_image,plan_title,0,1);
                        action_marker.setVisible(false);                        
                        toastr.success('Successfully Plan Added'); 
                        $("#plan_id").val('');

                    }  
                                                            
                    $("#plan_address").prop('disabled', true);
                    $('#btn-plan').html('Add');                    
                    getPlansList();

                    $("#form-plan").trigger("reset");                                              
                    $('#btn-plan').css('cursor', 'pointer');
                    $('#btn-plan').removeAttr('disabled');
                    

                },error: function(jqXHR, textStatus, errorThrown) { 

                    toastr.error('A system error has been encountered. Please try again');                        

                    $('#btn-plan').css('cursor', 'pointer');
                    $('#btn-plan').removeAttr('disabled');                        

                }


            });




        },                                                // Do not change code below
        errorPlacement: function(error, element)
        {
            error.insertAfter(element.parent());
        }


    });




    function remove_plan(id){    

    const matchValue = markers_list.findIndex(item => item.id == id);

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
                url: SITE+"ajaxfiles/plan/delete_plan.php",
                data: {
                    "id": id,
                },
                dataType:'json',        
                success: function(response) {                    
                                        
                    DeleteMarker(id);
                    markers_list.splice(matchValue, 1);
                    toastr.success(response.message);   
                    $("#plan_" + id + "").remove();                                         
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    toastr.error(jqXHR.responseJSON);                    
                }

            });



        });



    }