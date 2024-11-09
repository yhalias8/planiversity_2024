<style>
    /* * {
        border : 1px solid red;
    } */

    :root {
        --fc-border-color: #d8dce3;
        --fc-color: #8A888A;
        --fc-daygrid-event-dot-width: 5px;
        --fc-button-hover-bg-color: #f9fafb;
        --fc-button-active-bg-color: #f9fafb;
        --fc-button-hover-border-color: #f9fafb;
        --fc-button-active-border-color: #d8dce3;
        --fc-button-text-color: black;
        --fc-today-bg-color: #b4ddfb;
    }

    @font-face {
        font-family: 'Circular Std';
        font-style: normal;
        src: url('assets/themes/fonts/circular-std-medium-500.ttf') format('truetype');
    }

    @font-face {
        font-family: 'Circular Std book';
        font-style: normal;
        src: url('assets/themes/fonts/circular-std-book.ttf') format('truetype');
    }


    body {
        background: #FAFBFF;
        font-family: 'Circular Std', sans-serif;
    }

    .nav-link.collapsed::after,
    .nav-link::after {
        content: none !important;
    }

    #content {
        background-color: #f6f9fc;
    }

    .topbar-text {
        font-size: 13px;
    }

    .customer-text {
        font-size: 11px;
    }

    .sidebar {
        width: 20rem !important;
    }

    .sidebar-text-user {
        font-size: 14px;
        color: #ffffff;
    }

    .sidebar-text-email {
        font-size: 14px;
        color: #B4DDFB;
        margin-left: auto;
    }

    .nav-item {
        margin-left: 15px;
        margin-right: 15px;
        margin-bottom: 5px;
        border-radius: 10px;
    }

    .nav-item.active {
        background-color: #B4DDFB;
        color: #0C246B;
    }

    .nav-item.active .nav-link i,
    .nav-item.active .nav-link span {
        color: #0C246B !important;
    }

    .nav-item .nav-link i {
        color: #B4DDFB;
    }

    .sidebar .nav-item .nav-link span {
        font-size: 15px;
        margin-left: 10px;
    }

    .sidebar .nav-item .nav-link {
        padding: 5px !important;
        color: #ffffff;
        display: flex;
        align-items: center;
    }


    .arrow-icon {
        margin-left: auto;
    }

    .sidebar .sidebar-brand {
        text-transform: unset !important;
        font-size: 1.5rem;
    }

    .sidebar-bottom {
        position: absolute;
        bottom: 10px;
    }

    .sidebar .nav-item.active .nav-link {
        font-weight: unset;
    }

    .btn,
    .fc-button {
        box-shadow: unset !important;
    }

    .btn-secondary-custom {
        color: #344054;
        font-size: 14px;
    }

    .btn-primary {
        background-color: #0C246B;
        border-color: #0C246B;
        font-size: 14px;
    }

    .btn-warning {
        background-color: #F7BA4D;
        border-color: #d8dce3;
        color: #0C246B;
        font-size: 14px;
    }

    .btn-outline-dark {
        background-color: #ffffff;
        border-color: #d8dce3;
    }

    .btn-outline-secondary:not(:disabled):not(.disabled).active,
    .btn-outline-secondary:not(:disabled):not(.disabled):active,
    .show>.btn-outline-secondary.dropdown-toggle {
        background-color: #f9fafb !important;
        color: black !important;
        border-color: #d8dce3;
    }

    .btn-group>.btn-group:not(:last-child)>.btn,
    .btn-group>.btn:not(:last-child):not(.dropdown-toggle) {
        background-color: #ffffff;
        border-color: #d8dce3;
        font-size: 14px;
        color: #858796;
    }

    .btn-group>.btn-group:not(:first-child)>.btn,
    .btn-group>.btn:not(:first-child) {
        background-color: #ffffff;
        border-color: #d8dce3;
        font-size: 14px;
        color: #858796;
    }

    .text-primary {
        color: #0C246B !important;
    }

    .text-black {
        color: black;
    }

    .bg-gradient-primary {
        background-color: #0C246B !important;
        background-image: unset !important;
    }

    .badge-user {
        position: unset !important;
        border-radius: 5px;
        padding: 4px;
        font-size: 8px;
        color: #0C246B;
        text-transform: uppercase;
    }

    .fc {
        padding: 20px;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: none;
        margin-bottom: 20px;
        width: 100%;
        height: 700px;
    }

    .fc table {
        font-size: 13px;
    }

    .fc-h-event {
        background-color: unset !important;
        border: unset !important;
    }

    .fc .fc-scrollgrid-liquid {
        border-radius: 10px;
        overflow: hidden;
    }

    .fc .fc-toolbar-title {
        color: #0C246B;
        font-size: 1.5em;
    }

    .fc .fc-button-primary {
        background-color: #ffffff;
        border-color: #d8dce3;
    }

    .fc-toolbar-chunk {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .fc-toolbar-title {
        margin: 0;
        flex-grow: 1;
        text-align: center;
    }

    .fc .fc-col-header-cell-cushion,
    .fc .fc-daygrid-day-number {
        color: #8A888A;
        text-decoration: none;
        text-transform: uppercase;
    }

    .fc .fc-daygrid-day-top {
        flex-direction: unset;
    }

    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        font-size: 14px;
        color: black !important;
    }

    .fc-direction-ltr .fc-button-group>.fc-button:not(:last-child),
    .fc-direction-ltr .fc-button-group>.fc-button:not(:first-child) {
        font-size: 14px;
        color: #858796;
        text-transform: capitalize;
    }


    .full-width-event {
        padding: 5px;
        border-radius: 5px;
        margin: 0;
        width: 100%;
        box-sizing: border-box;
        height: 80px;
    }

    .toggle-sidebar {
        appearance: none;
        width: 40px;
        height: 20px;
        background: #ccc;
        border-radius: 20px;
        position: absolute;
        cursor: pointer;
        right: 0;
    }

    .toggle-sidebar:checked {
        background: #b4ddfb;
    }

    .toggle-sidebar:checked::before {
        left: 20px;
    }

    .toggle-sidebar::before {
        content: "";
        position: absolute;
        width: 20px;
        height: 18px;
        background: #0c246b;
        border-radius: 50%;
        transition: 0.2s;
    }

    .collapse-item {
        position: relative;
    }

    .slick-slider {
        /* display: inline-flex; */
        /* flex-direction: wrap;
        min-width: 100%; */
        visibility: hidden;
    }

    .two-items .slick-list>.slick-track {

        width: unset !important;
    }



    .slick-initialized {
        visibility: visible;
    }

    .slick-next {
        right: 13px;
    }

    .slick-prev {
        z-index: 1;
        left: -5px;
    }

    .slick-prev:before,
    .slick-next:before {
        color: #F7BA4D;
        font-size: 40px;
        opacity: unset;
    }

    .slick-initialized .slick-slide {
        margin: 5px;
    }


    .card-custom {
        border-radius: 15px;
        overflow: hidden;
        position: relative;
        color: white;
        margin-right: 15px;
        height: 160px;
    }

    .card-custom img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .card-body-custom {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        padding: 10px;
        background: rgba(0, 0, 0, 0.2);
        /* display: flex; */
        flex-direction: column;
        justify-content: space-between;
    }

    .card-title-custom {
        font-size: 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 5px;
    }

    .card-text-custom {
        font-size: 12px;
    }

    .card-footer-custom {
        background: none;
        border-top: none;
        padding: 0;
        position: absolute;
        bottom: 10px;
        right: 0px;
        display: flex;
        justify-content: space-between;
        width: 100%;
        /* padding: 0 15px; */
        align-items: center;
        margin-top: 10px;
        padding-left: 10px;
        padding-right: 10px;
    }

    .card-footer-custom .btn {
        border-radius: 15px;
        padding: 0.25rem 0.75rem;
        font-size: 12px;
    }

    .btn-job {
        background-color: #ffffff;
        color: #0C246B;
    }

    .btn-trip {
        background-color: #17a2b8;
        color: white;
    }

    .btn-event {
        background-color: #28a745;
        color: white;
    }

    .avatar-group-custom img {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 2px solid white;
    }

    .avatar-group-custom img:nth-child(n+2) {
        margin-left: -10px;
    }

    .avatar-group-custom {
        display: flex;
        align-items: center;
        background: #ffffff;
        padding: 0px;
        border-radius: 20px;
        width: fit-content;
        margin-top: 10px;
    }

    .avatar-group-custom .more {
        background: white;
        color: black;
        border-radius: 50%;
        padding: 0.25rem 0.5rem;
        font-size: 1rem;
        margin-left: -10px;
    }

    .action-icons-custom {
        display: flex;
        align-items: center;
        position: absolute;
        top: 15px;
        right: 15px;
    }

    .action-icons-custom .bi {
        margin-left: 10px;
        cursor: pointer;
    }

    .bi-card-custom {
        background-color: #ffffff;
        /* Warna latar belakang */
        color: black;
        /* Warna ikon */
        padding: 10px;
        /* Ruang di dalam ikon */
        border-radius: 50%;
        /* Membuat bentuk lingkaran */
        font-size: 13px;
        /* Ukuran ikon */
        width: 20px;
        /* Lebar lingkaran */
        height: 20px;
        /* Tinggi lingkaran */
        display: flex;
        /* Menggunakan flexbox */
        align-items: center;
        /* Memusatkan ikon secara vertikal */
        justify-content: center;
        /* Memusatkan ikon secara horizontal */
        text-align: center;
        /* Memusatkan teks (jika ada) */
    }

    .modal-dialog {
        max-width: 90% !important;
    }

    .modal_trip_name {
        position: relative;
        right: 15px;
        color: #0d256c;
        font-size: 24px;
    }

    .trip_close {
        width: 50px;
        height: 50px;
        background: #0c246b;
        background-color: #0c246b !important;
        color: #fff;
        opacity: 1;
        border-radius: 50px;
        font-size: 18px;
        position: relative;
        right: 15px;
    }

    .trip-content {
        padding: 20px 50px;
        background: #d7d7d7;
    }

    .trip_heading h5 {
        font-size: 16px;
        color: #1f74b7;
        text-align: left;
        padding: 15px 20px;
        border-bottom: 1px solid #c8ccd5;
        font-weight: 500;
    }

    .trip_heading.top-border h5 {
        border-top: 1px solid #c8ccd5;
    }

    .loading_screen {
        position: absolute;
        left: 48%;
        top: 95px;
        z-index: 999;
    }

    .update_details {
        min-height: 332px;
        overflow-y: auto;
        position: relative;
        bottom: 8px;
    }

    .card.other_info {
        margin-bottom: 25px;
        border-radius: 10px;
    }

    .trip_heading.document h5 {
        border-bottom: none;
    }

    .comment_details {
        height: calc(265px - 20px);
        display: flex;
        justify-content: space-between;
        flex-direction: column;
    }

    .comment_list {
        height: calc(180px - 20px);
        overflow-y: auto;
    }

    .comment-body {
        background: #f8f9fe;
        border: 1px solid #c8ccd5;
        padding: 10px;
        text-align: left;
        border-radius: 5px;
    }

    .comment-body p {
        font-size: 12px;
        color: #303030;
        margin: 0;
    }

    li.list-group-item.comment-item {
        padding: 5px 20px;
        margin-bottom: 5px;
        border: none;
    }

    div#comment_list h3.no-found {
        position: relative;
        font-size: 16px;
        font-weight: 400;
        top: 40px;
        color: #0c246b;
    }

    div#comment_list h3.no-found i {
        margin-right: 5px;
    }

    .input-group.comment-group {
        background: #f8f9fe;
        padding: 10px;
        border-top: 1px solid #d7edff;
        border-radius: 10px;
    }

    .sidebar .nav-item .collapse {
        z-index: 2;
    }

    .sidebar .nav-item .nav-link .badge-counter,
    .topbar .nav-item .nav-link .badge-counter {
        right: 0.45rem;
    }

    .update-status-textarea,
    .update-status-textarea:focus {
        font-size: 12px;
        border-radius: 5px;
        box-shadow: none;
        color: #666;
        outline: none;
        width: 50%;
        font-weight: 400;
        -webkit-box-shadow: 0 1px 1px 0 rgba(45, 44, 44, 0.05) !important;
        background: #F9FAFF;
        border: 1px solid #C8CCD5;
        padding: 2px 10px;
        resize: none;
    }

    .alert-form {
        display: none;
    }

    .attendee_details {
        padding: 20px 10px;
        min-height: 125px;
    }

    .avatar-icon {
        display: block;
        width: 44px;
        height: 44px;
        transition: all 0.2s;
        opacity: 1;
        border-radius: 50px;
        border: #fff solid 3px;
        overflow: hidden;
    }

    .avatar-icon img {
        width: 100%;
        height: 100%;
    }

    .people_left_side {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .people_img img {
        width: 70px;
        border-radius: 100px;
        border: 2px solid #1963cb;
    }

    .people_info {
        margin-left: 10px;
        text-align: left;
    }

    .people_info h4 {
        font-size: 18px;
        color: #0d256c;
        margin: 0;
    }

    .people_info p {
        font-size: 12px;
        color: #475467;
        margin: 0;
    }

    .trip_heading.top-border h5 {
        border-top: 1px solid #c8ccd5;
    }

    .trip_heading h5 {
        font-size: 16px;
        color: #1f74b7;
        text-align: left;
        padding: 15px 20px;
        border-bottom: 1px solid #c8ccd5;
        font-weight: 500;
    }

    li.list-group-item.update-item {
        display: flex;
        align-items: baseline;
        justify-content: start;
        border-top: 1px solid #c8ccd5;
        padding: 15px 40px;
        border-right: none;
        border-left: none;
        border-radius: initial;
        align-items: center;
    }

    .people_left_side.update {
        align-items: baseline;
        width: 150px;
    }

    .people_left_side {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }

    .people_left_side.update .people_img img {
        width: 40px;
    }

    button.btn.comment-action {
        background-color: #0c246b;
        color: #fff;
        padding: 8px 30px;
        font-size: 14px;
        border-radius: 5px;
    }

    .modal-content.trip-content .modal-header {
        background: transparent;
        border-color: transparent;
    }

    .trip-body {
        padding: 0 !important;
    }

    .card.trip_info {
        margin-bottom: 30px;
        border-radius: 10px;
    }



    /* .modal-dialog.modal-trip-lg * {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
    } */

    h3.no-found {
        position: relative;
        font-size: 16px;
        font-weight: 400;
        top: 40px;
        color: #0c246b;
    }

    .toggle-sidebar {
        background: #4c7fb8;
        outline: none;
        cursor: pointer;
        border-radius: 20px;
        /* box-shadow: inset 0 0 5px rgb(0 0 0 / 20%); */
        transition: background 300ms linear;
    }

    .toggle-sidebar::before {
        position: absolute;
        content: "";
        width: 20px;
        height: 20px;
        left: 0px;
        border-radius: 20px;
        /* background-color: #fff; */
        transform: scale(1.1);
        box-shadow: 0 2px 5px rgb(0 0 0 / 20%);
        transition: left 300ms linear;
        background-image: linear-gradient(to right, #fec84d, #f3a230);
    }

    .badge {
        font-size: 100%;
    }



    .segment-item {
        display: grid;
    }

    .card.trip_info {
        margin-bottom: 0px !important;
    }

    .card.other_info {
        margin-bottom: 0px !important;
    }

    .people_left_side.update .people_info h4 {
        font-size: 14px;
        color: #0d256c;
        margin: 0;
    }

    li.list-group-item.update-item .update-info {
        display: flex;
        flex-direction: column;
        width: 100%;
        align-items: flex-start;
    }

    li.list-group-item.update-item .update-info p {
        color: #0c246b;
        font-size: 10px;
        font-weight: 500;
        margin: 0;
        padding-top: 4px;
    }

    .overflow-container {
        height: 332px;
        /* Tinggi elemen */
        overflow-y: auto;
        /* Mengaktifkan scrollbar vertikal */
        border-top: 1px solid #ccc;
        /* Opsional: menambahkan border untuk visualisasi */
        padding: 10px;
        /* Opsional: menambahkan padding untuk estetika */
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        /* Opsional: menambahkan bayangan */
    }

    /* Mengubah gaya scrollbar untuk lebih terlihat (hanya untuk Webkit) */
    .overflow-container::-webkit-scrollbar {
        width: 8px;
        /* Lebar scrollbar */
    }

    .overflow-container::-webkit-scrollbar-thumb {
        background-color: #888;
        /* Warna scrollbar */
        border-radius: 10px;
        /* Membuat scrollbar bulat */
    }

    .overflow-container::-webkit-scrollbar-thumb:hover {
        background-color: #555;
        /* Warna scrollbar saat hover */
    }

    .update-status-person {
        cursor: pointer;
    }

    .avatar-icon-wrapper {
        display: inline-block;
        margin-right: 0.1rem;
        position: relative;
    }

    .avatar-icon-wrapper .badge-dot.badge-dot-lg.badge-bottom {
        top: auto;
        right: 0;
        bottom: 0;
    }

    .avatar-icon-wrapper .badge-dot.badge-dot-lg {
        border-radius: 50%;
        width: 0px;
        height: 16px;
        border: #fff solid 2px;
        top: 0;
        right: 0;
    }

    .avatar-icon-wrapper .badge {
        position: absolute;
        right: -2px;
        top: -2px;
    }

    .update-status-btn,
    .update-status-btn:focus,
    .update-status-btn:hover {
        font-size: 17px;
        color: #fff;
        outline: none;
        font-size: 14px;
        font-weight: bold;
        border: 1px solid #F39F32;
        background: #f00;
        box-shadow: 0px 4px 10px rgba(255, 255, 45, 0.0001);
        border-radius: 4px;
        cursor: pointer;
        outline: none;
        text-indent: inherit;
    }

    input.form-control.comment-field.error-comment {
        background: #ffb6b6;
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100% !important;
            position: relative !important;
        }

        #content {
            background-color: #f6f9fc !important;
        }

        .topbar-text {
            font-size: 12px !important;
        }

        .customer-text {
            font-size: 10px !important;
            color: #d1d3e2 !important;
        }

        .nav-item {
            margin-left: 10px !important;
            margin-right: 10px !important;
            margin-bottom: 5px !important;
            border-radius: 10px !important;
        }

        .nav-item.active {
            background-color: #B4DDFB !important;
            color: #0C246B !important;
        }

        .nav-item .nav-link {
            padding: 10px !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
        }

        .btn {
            font-size: 12px !important;
        }

        .card-custom {
            height: auto !important;
            margin-bottom: 15px !important;
        }

        .fc {
            height: auto !important;
            padding: 10px !important;
        }

        .fc .fc-toolbar-title {
            font-size: 16px !important;
        }

        .fc .fc-button-primary {
            font-size: 12px !important;
        }

        .modal_trip_name {
            font-size: 20px !important;
        }

        .trip_close {
            width: 40px !important;
            height: 40px !important;
            font-size: 16px !important;
        }

        .trip-content {
            padding: 15px !important;
        }

        .avatar-group-custom img {
            width: 25px !important;
            height: 25px !important;
        }

        .comment_details {
            height: auto !important;
        }

        .input-group.comment-group {
            padding: 8px !important;
        }

        .sidebar .nav-item .nav-link {
            width: unset !important;
        }
    }
</style>