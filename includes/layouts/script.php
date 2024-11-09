<script>
    let SITE = `<?= SITE ?>`;
</script>
<script src="assets/themes/vendor/jquery/jquery.min.js"></script>
<script src="assets/themes/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/themes/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="assets/themes/js/sb-admin-2.min.js"></script>
<script src="assets/themes/vendor/fullcalendar/index.global.js"></script>
<script src="assets/themes/vendor/slick/slick.js" type="text/javascript" charset="utf-8"></script>
<script src="<?= SITE ?>/js/dashboard_next.js?v=<?= time(); ?>"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    let data_event = `<?= json_encode($event_list) ?>`;
    let event_list = JSON.parse(data_event);
    let filteredEvents = event_list.filter(event => event.title !== null);
    console.log(filteredEvents, 'filteredEvents');
    const schoolBgColors = {
        1: 'rgba(14, 165, 233, 0.1)',
        2: 'rgba(139, 92, 246, 0.1)',
        3: 'rgba(139, 92, 246, 0.1)',
        4: 'rgba(244, 63, 94, 0.1)',
        5: 'rgba(245, 158, 11, 0.1)'
    };

    const schoolTextColors = {
        1: '#0369A1',
        2: '#6D28D9',
        3: '#6D28D9',
        4: '#BE123C',
        5: '#F59E0B'
    };

    var schoolNumber = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'title prev,next',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            themeSystem: 'bootstrap5',
            fixedWeekCount: false,
            initialDate: new Date(),
            navLinks: false,
            editable: false,
            displayEventTime: false,
            events: filteredEvents,
            dayMaxEvents: true,
            eventContent: function(arg) {
                const isVisible = schoolNumber === 'all' || arg.event.extendedProps.school === schoolNumber;
                if (isVisible) {
                    const schoolId = arg.event.extendedProps.school;
                    const bgColor = schoolBgColors[schoolId] || 'white';
                    const textColor = schoolTextColors[schoolId] || 'white';

                    let startTime = '';
                    let endTime = '';

                    if (arg.event.start) {
                        startTime = arg.event.start.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }

                    if (arg.event.end) {
                        endTime = arg.event.end.toLocaleTimeString([], {
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }

                    return {
                        html: `<div class="full-width-event" style="background-color: ${bgColor}; color: ${textColor};  border-left: 3px solid ${textColor}; padding: 5px; border-radius:5px">
                        ${startTime ? startTime + '<br>' : ''} 
                        <span>${arg.event.title}</span>
                        
                    </div>`
                    };
                } else {
                    return {
                        html: ''
                    };
                }
            }
        });


        // Fetch events from external JSON file
        // fetch('ajaxfiles/dashboard/event_list.php')
        //     .then(response => response.json())
        //     .then(data => {
        //         // Add events to calendar
        //         calendar.addEventSource(data);
        //     })
        //     .catch(error => console.error('Error fetching events:', error));

        calendar.render();

        document.querySelectorAll('input[name="calendarEvents"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                const selectedType = this.id.replace('filter', '');
                let filteredEvents;
                let selectedSchool;

                if (selectedType == 'Trips') {
                    selectedSchool = 1;
                } else if (selectedType == 'Events') {
                    selectedSchool = 2;
                } else if (selectedType == 'Jobs') {
                    selectedSchool = 4;
                } else if (selectedType == 'Appointments') {
                    selectedSchool = 5;
                } else {
                    selectedSchool = 'All';
                }

                if (selectedSchool === 'All') {
                    filteredEvents = filteredEvents;
                } else {
                    filteredEvents = filteredEvents.filter((event) => event.extendedProps.school === selectedSchool); // Filter event berdasarkan tipe
                }

                calendar.removeAllEvents();
                filteredEvents.forEach((event) => {
                    calendar.addEvent(event);
                });
            });
        });
    });


    // slick
    $(document).ready(function() {
        setTimeout(() => {

            let slick = $('.slick-slider').slick({
                dots: false, // Menampilkan titik navigasi
                infinite: true, // Mengulangi slider
                speed: 500, // Kecepatan transisi
                slidesToShow: 3, // Jumlah slide yang ditampilkan
                slidesToScroll: 1, // Jumlah slide yang digulirkan
                lazyLoad: 'ondemand',
            })

            // Cek jumlah item dan tambahkan kelas jika hanya ada dua
            if ($('.slick-slider .slick-slide').length === 2) {
                $('.slick-slider').addClass('two-items');
            }

            $('.slick-slider').slick('refresh');
        }, 200);
    });
</script>

<script>
    let selectedTrip;
    $(document).ready(function() {
        $('.nav-link').on('click', function() {
            var icon = $(this).find('.arrow-icon');

            $('.nav-link').not(this).each(function() {
                $(this).find('.arrow-icon').removeClass('bi-chevron-left').addClass('bi-chevron-down');
            });

            if (icon.hasClass('bi-chevron-down')) {
                icon.removeClass('bi-chevron-down').addClass('bi-chevron-left');
            } else {
                icon.removeClass('bi-chevron-left').addClass('bi-chevron-down');
            }
        });


        $("#status-update-btn").on("click", function(e) {
            e.preventDefault();
            toggleAlertForm();
        })
    });

    function capitalizeFirstLetter(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function change_scale(scale) {
        let currentScale = `<?= $userdata['scale'] ?>`;

        if (scale != currentScale) {
            $.ajax({
                url: 'ajaxfiles/dashboard/change_scale.php',
                type: 'POST',
                data: {
                    scale: scale
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.status, 'response')
                    if (response.status == "success") {
                        $(`#toggle${capitalizeFirstLetter(currentScale)}`).prop('checked', false);
                        $(`#toggle${capitalizeFirstLetter(scale)}`).prop('checked', true);
                        location.reload();
                    }
                    // $('#responseMessage').text(response.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // $('#responseMessage').text('Error: ' + textStatus);
                    console.error(textStatus)
                }
            });
        }
    }

    function google_sync(status) {
        // Konfigurasi OAuth2
        const clientId = `<?= GOOGLE_CLIENT_ID ?>`;
        const redirectUri = `<?= GOOGLE_CLIENT_REDIRECT_URL ?>`;
        const scope = 'https://www.googleapis.com/auth/calendar';



        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

        let currentGoogleCalendar = `<?= $userdata['sync_googlecalendar'] ?>`;
        let googleCalendar = currentGoogleCalendar == 1 ? 0 : 1;
        let isChecked = googleCalendar == 1 ? true : false;

        if (status == 0) {
            document.getElementById("google_calender").checked = false;
            $('#upgrade').modal('show');
            return
        }

        if (isChecked) {
            // Jika belum login, tampilkan tombol loginoauth2
            const authUrl = `https://accounts.google.com/o/oauth2/v2/auth?client_id=${clientId}&scope=${scope}&redirect_uri=${redirectUri}&response_type=code&access_type=offline`;

            // Buka jendela autentikasi
            window.open(authUrl, '_blank', 'width=500,height=600');
        } else {


            $.ajax({
                url: 'ajaxfiles/dashboard/change_sync_google_calendar.php',
                type: 'POST',
                data: {
                    sync_googlecalendar: googleCalendar,
                    timezone_offset_minutes: timezone_offset_minutes
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.status, 'response')
                    if (response.status == "success") {
                        $(`#toggleGoogleCalendar`).prop('checked', isChecked);
                        location.reload();
                    }
                    // $('#responseMessage').text(response.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // $('#responseMessage').text('Error: ' + textStatus);
                    console.error(textStatus)
                }
            });
        }
    }

    function outlook_sync(status) {
        const tenant = `<?= OUTLOOK_CLIENT_TENANT ?>`;
        const clientId = `<?= OUTLOOK_CLIENT_ID ?>`;
        const redirectUri = `<?= OUTLOOK_CLIENT_REDIRECT_URL ?>`;

        var timezone_offset_minutes = new Date().getTimezoneOffset();
        timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

        let currentOutlookCalendar = `<?= $userdata['sync_outlookcalendar'] ?>`;
        let outlookCalendar = currentOutlookCalendar == 1 ? 0 : 1;
        let isChecked = outlookCalendar == 1 ? true : false;

        if (status == 0) {
            $(`#toggleOutlookCalendar`).prop('checked', false);
            $('#upgrade').modal('show');
            return;
        }

        if (isChecked) {
            // Jika belum login, tampilkan tombol loginoauth2
            // const authUrl = `https://login.microsoftonline.com/outlook_tenant_id/oauth2/v2.0/authorize?client_id=outlook_client_id&response_type=code&redirect_uri=outlook_redirect_uri&scope=Calendars.ReadWrite%20offline_access&response_mode=query&state=12345`;

            // const authUrl = `https://login.microsoftonline.com/${tenant}/oauth2/v2.0/authorize?&client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}&response_mode=query&scope=https://graph.microsoft.com/.default&state=12345`
            const authUrl = `https://login.microsoftonline.com/common/oauth2/v2.0/authorize?&client_id=${clientId}&response_type=code&redirect_uri=${redirectUri}&response_mode=query&scope=https://graph.microsoft.com/.default&state=12345`

            // Buka jendela autentikasi
            window.open(authUrl, '_blank', 'width=500,height=600');
        } else {
            $.ajax({
                url: 'ajaxfiles/dashboard/change_sync_outlook_calendar.php',
                type: 'POST',
                data: {
                    sync_outlookcalendar: outlookCalendar,
                    timezone_offset_minutes: timezone_offset_minutes
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.status, 'response')
                    if (response.status == "success") {
                        $(`#toggleOutlookCalendar`).prop('checked', isChecked);
                        location.reload();
                    }
                    // $('#responseMessage').text(response.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // $('#responseMessage').text('Error: ' + textStatus);
                    console.error(textStatus)
                }
            });
        }





    }

    function delete_trip(id) {
        var valid = confirm('Are you sure? want to delete');
        if (valid) {
            $.ajax({
                url: 'ajaxfiles/dashboard/delete_plan.php',
                type: 'POST',
                data: {
                    id_trip: id
                },
                dataType: 'json',
                success: function(response) {
                    // console.log(response.status, 'response')
                    if (response.status == "success") {
                        location.reload();
                    }
                    // $('#responseMessage').text(response.message);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // $('#responseMessage').text('Error: ' + textStatus);
                    console.error(textStatus)
                }
            });
        }
    }

    function trip_expand(trip_ref) {
        attendeesInfoProcess(trip_ref);
        checkinUpdateInfoProcess(trip_ref);
        documentInfoProcess(trip_ref);
        commentInfoProcess(trip_ref);
        updateStatusProcess(trip_ref)
        $("#trip_details").modal("show");
        selectedTrip = trip_ref;
    }

    function copyToClipboard(element) {
        const paketNumber = element.getAttribute('data-paket');

        const onlyNumber = paketNumber.substring(paketNumber.indexOf('#'));

        const tempInput = document.createElement('input');
        tempInput.value = onlyNumber;
        document.body.appendChild(tempInput);

        tempInput.select();
        document.execCommand('copy');

        document.body.removeChild(tempInput);
        toastr.success(`Copied PACKET: ${onlyNumber}`);
    }

    function toggleAlertForm() {
        $(".alert-form").toggle();
        $("#for1").prop("checked", true);
        $("#update_status_text").val('');

        if ($(".alert-form").is(":visible")) {
            $(".attend-badge-div").show();
            $(".attend-checkbox").prop("checked", true);
            $(".update-status-person img").on('click', function() {
                $("#attend-radio-selected").prop("checked", true);
                $(this).parent().parent().find(".badge").toggle();
                var that = $(this).parent().parent().find(".checkbox");
                if (that.is(":checked")) {
                    that.prop("checked", false);
                } else {
                    that.prop("checked", true);
                }
            });
        } else {
            $(".update-status-person img").off('click');
            $(".attend-badge-div").hide();
            $(".attend-checkbox").prop("checked", false);
        }
    }

    function commentAction() {
        event.preventDefault(); // Prevent form submission

        var commentfield = $("#commentfield");
        commentfield.removeClass("error-comment");

        // Validate name field
        if ($.trim(commentfield.val()) === "") {
            commentfield.addClass("error-comment");
            return;
        }

        $(".comment-action").css("cursor", "wait");
        $(".comment-action").attr("disabled", true);

        $.ajax({
            url: SITE + "root/dashboard/comment_process",
            type: "POST",
            data: $("#commentForm").serialize() + "&id_trip=" + selectedTrip,
            dataType: "json",
            success: function(response) {
                $("#commentForm").trigger("reset");
                toastr.success("Successfully Comment Added");
                commentInfoProcess(selectedTrip);

                $(".comment-action").css("cursor", "pointer");
                $(".comment-action").removeAttr("disabled");
            },
            error: function(jqXHR, textStatus, errorThrown) {
                toastr.error("A system error has been encountered. Please try again");

                $(".comment-action").css("cursor", "pointer");
                $(".comment-action").removeAttr("disabled");
            },
        });
    }
</script>