<section class="header_slider_sec">
    <?php include_once("includes/include_navbar.php"); ?>

    <img src="<?= SITE; ?>/marketplace/images/background.png" class="header_banner">

    <div class="top_header_heading">

        <div class="container">

            <div class="row">

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                    <div class="banner-heading">

                        <h2>Find the right deal to take you or your business to the next level.</h2>

                        <p>Find courses, books, and services at a fraction of the cost, and provided by <span>the best in the business.</span></p>

                    </div>

                    <div class="banner-search">
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

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">

                    <div class="banner-image">

                        <img src="<?= SITE; ?>marketplace/images/test.png" class="into-image">

                        <div class="top-banner-info">
                            <div class="icon-info">
                                <img src="<?= SITE; ?>marketplace/images/quality-icon.png">
                            </div>

                            <div class="title-info">
                                <h4>Proof of quality</h4>
                                <p>Lorem Ipsum Dolar Amet</p>
                            </div>

                        </div>

                        <div class="right-banner-info">
                            <div class="icon-info">
                                <img src="<?= SITE; ?>marketplace/images/safe-icon.png">
                            </div>

                            <div class="title-info">
                                <h4>Safe and secure</h4>
                                <p>Lorem Ipsum Dolar Amet</p>
                            </div>

                        </div>


                        <div class="bottom-banner-info">

                            <div class="people-info">
                                <h4>300+ Courses online</h4>
                            </div>

                            <div class="icon-people">
                                <img src="<?= SITE; ?>marketplace/images/people-icon.png">
                            </div>
                        </div>
                    </div>

                </div>



            </div>

        </div>

    </div>




</section>