(function ( $ ) {

  
  $.fn.multiStepForm = function(args) {
      if(args === null || typeof args !== 'object' || $.isArray(args))
        throw  " : Called with Invalid argument";
      var form = this;
      var tabs = form.find('.itinerary_tab');
      var steps = form.find('.step');
      var steps_length = $(".itinerary_tab").length-1;

      steps.each(function(i, e){
        $(e).on('click', function(ev){
        });
      });

      form.navigateTo = function (i) {/*index*/
        /*Mark the current section with the class 'current'*/
        tabs.removeClass('current').eq(i).addClass('current');
        // Show only the navigation buttons that make sense for the current section:
        form.find('.previous').toggle(i > 0);
        atTheEnd = i >= tabs.length - 1;
        form.find('.next').toggle(!atTheEnd);
        // console.log('atTheEnd='+atTheEnd);
        form.find('.submit').toggle(atTheEnd);
        fixStepIndicator(curIndex());
        return form;
      }

      function curIndex() {
        /*Return the current index by looking at which section has the class 'current'*/
        return tabs.index(tabs.filter('.current'));
      }

      function fixStepIndicator(n) {
        steps.each(function(i, e){
          i == n ? $(e).addClass('active') : $(e).removeClass('active');
        });
      }

      function startover(){
          $('.input_reset').val('');
          $("#myForm").trigger("reset");
          $(".booking_section").hide();
          form.navigateTo(0);
          setProgressBar(0);
      }



      /* Start Over button */
      form.find('.start_over').click(function() {

      swal({
            title: "Are you sure?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#058BEF",
            confirmButtonText: "Yes, start over",
            closeOnConfirm: true
        }, function() {
            
          startover();

        });
        

      });


      /* Previous button is easy, just go back */
      form.find('.previous').click(function() {
        setProgressBar(curIndex() - 1);
        form.navigateTo(curIndex() - 1);
      });

      /* Next button goes forward iff current block validates */
      form.find('.next').click(function() {
        
        if('validations' in args && typeof args.validations === 'object' && !$.isArray(args.validations)){
          if(!('noValidate' in args) || (typeof args.noValidate === 'boolean' && !args.noValidate)){
            form.validate(args.validations);
            if(form.valid() == true){
              
              setProgressBar(curIndex() + 1);
              form.navigateTo(curIndex() + 1);              
              return true;
            }
            return false;
          }
        }
        form.navigateTo(curIndex() + 1);
        
      });


    // Change progress bar action
    function setProgressBar(curStep) {
      var percent = parseFloat(100 / steps_length) * curStep;
      percent = percent.toFixed();
      $(".progress-bar")
        .css("width", percent + "%")
        .html(percent + "%");
    }

      form.find('.submit').on('click', function(e){
        if(typeof args.beforeSubmit !== 'undefined' && typeof args.beforeSubmit !== 'function')
          args.beforeSubmit(form, this);
        /*check if args.submit is set false if not then form.submit is not gonna run, if not set then will run by default*/        
        if(typeof args.submit === 'undefined' || (typeof args.submit === 'boolean' && args.submit)){
          

            $('.submit').css('cursor', 'wait');
            $('.submit').attr('disabled', true);          

          $.ajax({
            url: SITE + "process/itinerary_process.php",
            type: "POST",
            data: $(form).serialize(),
            dataType: "json",
            success: function(response) {

              console.log('response',response);

             swal({
                      title: "Successfully created",
                      type: "success",
                      showCancelButton: true,
                      confirmButtonColor: "#f5ab3f",
                      cancelButtonColor: "#058BEF",
                      confirmButtonText: 'Continue itinerary',
                      cancelButtonText: "Start over",
                      closeOnConfirm: false,
                      closeOnCancel: true
                }, function(isConfirm) {

                  console.log('isConfirm',isConfirm);

                  if (isConfirm){                      
                      window.location.replace(SITE + "trip/connect/" + response.data);
                  }else{
                      startover();
                  }

                    $('.submit').css('cursor', 'pointer');
                    $('.submit').removeAttr('disabled');


              });



                
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var res = jqXHR.responseJSON;

                swal({
                    title: res.message,
                    type: "warning",                    
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Ok",
                    closeOnConfirm: true
                });

                $('.submit').css('cursor', 'pointer');
                $('.submit').removeAttr('disabled');

            },
        });



        }
        return form;
      });

      //form.navigateTo(0);


      /*By default navigate to the tab 0, if it is being set using defaultStep property*/
      typeof args.defaultStep === 'number' ? form.navigateTo(args.defaultStep) : null;

      form.noValidate = function() {
        console.log('Validating...');
      }
      return form;
  };
}( jQuery ));








