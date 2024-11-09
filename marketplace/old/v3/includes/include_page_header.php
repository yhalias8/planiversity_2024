<section class="header_slider_sec category">
    <?php include_once("includes/include_navbar.php"); ?>

    <img src="<?= SITE; ?>/marketplace/images/background.png" class="header_banner_plus">

    <div class="top_header_heading category">

        <div class="container">

            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">


                    <div class="banner-search category">
                        <div class="s002">
                            <form id="search_form">

                                <div class="inner-form">
                                    <div class="input-field first-wrap">
                                        <div class="icon-wrap">
                                            <i class="fa fa-search"></i>
                                        </div>
                                        <input id="search" name="search" type="text" placeholder="What are you looking for?" />
                                    </div>

                                    <div class="input-field fouth-wrap">

                                        <select id="category_field" name="category_field" class="form-control form_category">
                                            <option value="" selected>Choose Category</option>
                                            <option value="0">All</option>
                                            <option value="1">Courses & Learning</option>
                                            <option value="2"> E-books </option>
                                            <option value="3"> Travel Services </option>
                                            <option value="4"> Event Services </option>
                                        </select>

                                    </div>
                                    <div class="input-field fifth-wrap">
                                        <button class="btn-search e_button" type="submit">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>


            </div>

        </div>

    </div>




</section>