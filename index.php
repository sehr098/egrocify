<?php 
include("inc/header.php"); 
include("function.php"); 

?>

  <body>
    <script src="assets/vendor/js/dropdown-hover.js"></script>
    <script src="assets/vendor/js/mega-dropdown.js"></script>

    <link href="assets/css/custom_fonts.css" rel="stylesheet" type="text/css"/>

<!-- CUSTOM CSS -->
<style type="text/css">
  .easypaisa_footer_logo{ width: 140px !important; padding: 0px 10px !important; background: white !important; }
  .alfalah_footer_logo{ width: 140px !important; }
  .automateStore{ font-weight: 800; font-size: 45px;}
</style>
<!-- CUSTOM CSS -->


<article class="sp-body-container">
  <header id="hero" class="sp-hero-light sp-hero-homepage">
    <div data-collapse="medium" data-animation="default" data-duration="200" data-easing="ease-out" data-easing2="ease-out" data-no-scroll="1" role="banner" class="sp-navbar w-nav">
      <div class="sp-navbar-container header-container w-container">
        <div class="sp-navbar-content">
          <div class="div-block-421">
            <!-- <span class="app-brand-logo demo">
                <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                    fill="#7367F0" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z"
                    fill="#161616" />
                  <path
                    opacity="0.06"
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z"
                    fill="#161616" />
                  <path
                    fill-rule="evenodd"
                    clip-rule="evenodd"
                    d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                    fill="#7367F0" />
                </svg>
              </span> -->
            <a href="javascript:void(0)" aria-current="page" class="sp-navbar-logo w-nav-brand w--current">
              <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1"><img src="assets/img/branding/logo2.png" width="160"></span>

              <!-- <img src="assets/svg/logo.svg" alt="Egrocify Reseller Center Logo" width="113" height="48"> -->
            </a>
          </div>
          <nav role="navigation" class="sp-navbar-main-menu w-nav-menu">

            <a href="#" class="sp-navbar-link-dark w-nav-link">Home</a>
            <a href="pricing_page.php" class="sp-navbar-link-dark w-nav-link">Pricing</a>
          </nav>
          <div class="sp-navbar-right-menu">
             <?php
            if(!isset($_SESSION['loggedin_id'])){
            ?>
            <a href="user/login.php" id="sourcecode" rel="noopener noreferrer" target="_blank" class="sp-navbar-link-dark utm_automation w-nav-link">Login</a>
            <a id="sourcecode" href="pricing_page.php" target="_blank" class="sp-primary-button combz utm_automation w-button">Get Started</a>
              
            <?php }else{
            ?>
            <a id="sourcecode" href="user/index.php" target="_blank" class="sp-primary-button combz utm_automation w-button">Dashboard</a>
            <a href="user/logout.php" id="sourcecode" rel="noopener noreferrer" target="_blank" class="sp-navbar-link-dark utm_automation w-nav-link">Logout</a>
              
            <?php } ?>

            
          </div>
          <aside class="sp-menu-button-mobile w-nav-button">
            <div class="text-block-189 menu_hamburgerr mob_menu_link" id="menu_hamburgerr">
              Menu
            </div>
            <div class="text-block-189 menu_hamburgerr_close d-none mob_menu_link" id="menu_hamburgerr_close">
              X
            </div>
            <div class="text-block-189 d-none menu_hamburger_list mob_menu_link" >
             <ul class="tool-list w-list-unstyled ">
                <li class=""><a class="mob_menu_link mb-2" href="#">Home</a></li>
                <li class=""><a class="mob_menu_link mb-2" href="pricing_page.php">Pricing</a></li>
                <?php
                if(!isset($_SESSION['loggedin_id'])){
                ?>
                  <li class=""><a class="mob_menu_link" href="user/login.php">Login</a></li>
                  <li class=""><a class="mob_menu_link" href="user/register.php">Get Started</a></li>
                <?php }else{
                ?>
                  <li class=""><a class="mob_menu_link" href="user/index.php">Dashboard</a></li>
                  <li class=""><a class="mob_menu_link" href="user/logout.php">Logout</a></li>
                <?php } ?>

              </ul>
            </div>

          </aside>
        </div>
      </div>

      <!-- MOBILE MENU -->
      <div class="w-nav-overlay" data-wf-ignore="" id="w-nav-overlay-0" style="height: 13601.1px; display: none;">
        <nav role="navigation" class="sp-navbar-main-menu w-nav-menu" style="transform: translateY(0px) translateX(0px); transition: transform 200ms ease-out 0s;" data-nav-menu-open="">
          <a href="#" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Home</a>
          <a href="pricing_page.php" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Pricing</a>
          <?php
            if(!isset($_SESSION['loggedin_id'])){
            ?>
          <a href="user/login.php" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Login</a>
          <a href="user/register.php" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Get Started</a>
            <!-- <a href="user/login.php" id="sourcecode" rel="noopener noreferrer" target="_blank" class="sp-navbar-link-dark w-nav-link w--nav-link-open">Login</a> -->
            <!-- <a id="sourcecode" href="user/register.php" target="_blank" class="sp-primary-button combz utm_automation w-button" style="float: left;">Get Started</a> -->
              
            <?php }else{
            ?>
          <a href="user/index.php" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Dashboard</a>
          <a href="user/logout.php" class="sp-navbar-link-dark w-nav-link w--nav-link-open" style="max-width: 1200px;">Logout</a>
            <!-- <a id="sourcecode" href="user/index.php" target="_blank" class="sp-primary-button combz utm_automation w-button " style="float: left;">Dashboard</a> -->
            <!-- <a href="user/logout.php" id="sourcecode" rel="noopener noreferrer" target="_blank" class="sp-navbar-link-dark w-nav-link w--nav-link-open">Logout</a> -->
              
            <?php } ?>
        </nav>
      </div>
      <!-- MOBILE MENU -->
    </div>
        <h2 class="automateStore" style="text-align: center; margin: -20px 0px 30px 0px; color: black;">Automate your Ecommerce Business</h2>
    <div class="sp-hero-container sp-container-width hero w-container">
      <div class="sp-hero-content-img">
        <h1 class="sp-hero-heading-3 sp-hero-heading-medium">
          <strong class="sp-hero-title-5">Discover   </strong>
          <span class="text-span-66">
            <strong class="sp-hero-title-5 purple">US | EU | Asian Products </strong>
          </span>
          <strong class="sp-hero-title-5"> and resell top-selling products <br>
          </strong>
        </h1>
        <p class="sp-hero-paragraph-3">Egrocify Reseller Center allows you to choose the best products to sell from thousands of dropshipping suppliers all over the world.</p>
        <div class="hero_button-wrapper">
          <a id="sourcecode" href="user/register.php" target="_blank" class="sp-primary-button combz utm_automation is-cta w-button">Start for absolutey FREE</a>
        </div>
        <div class="sp-sinup-form-content display-none w-form">
          <form id="email-form" name="email-form" data-name="Email Form" action="https://app.spocket.co/signup?utm_source=home_page&amp;utm_medium=hero_image&amp;utm_campaign=one_step_signup" method="get" class="sp-signup-form" data-wf-page-id="65f0a5c92b99042e65337966" data-wf-element-id="622d6609-684f-4c86-cb76-5eafe05e3a7e">
            <input class="sp-input-signup w-input" utm_medium="home_page_hero" autofocus="true" maxlength="256" name="email" utm_source="spocket" data-name="email" placeholder="Your email address…" type="email" id="email-4" required="">
            <input type="submit" data-wait="Please wait..." id="btn-email-form" class="sp-primary-button-2 sp-button-big sp-form-primary-button utm_automation w-button" value="Get Started">
          </form>
          <div class="w-form-done">
            <div>Thank you! Your submission has been received!</div>
          </div>
          <div class="w-form-fail">
            <div>Oops! Something went wrong while submitting the form.</div>
          </div>
        </div>
      </div>
      <div class="sp-hero-content-img img">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491bf3d2dbd96fc12770fd6_64419109c2d22e2a9328ab9a_home-hero-img (1).webp" alt="Dropshipping trending products" height="534" loading="lazy" sizes="(max-width: 479px) 86vw, (max-width: 991px) 87vw, (max-width: 1279px) 549.9884643554688px, 572.0023193359375px" srcset="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491bf3d2dbd96fc12770fd6_64419109c2d22e2a9328ab9a_home-hero-img%20(1)-p-500.webp 500w, https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491bf3d2dbd96fc12770fd6_64419109c2d22e2a9328ab9a_home-hero-img%20(1).webp 800w" class="sp-home-hero-image">
      </div>
    </div>
    <div class="sp-hero-container sp-container-width usp-section w-container">
      <div class="w-layout-grid grid-34">
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491c2ef9b84f99bafeae15e_6256479a3b4937a9f408817c_sell-with-ease-icon.webp" alt="Sell with ease" width="150" height="150" loading="lazy" data-w-id="6dbbdeea-ba8f-0041-02e7-dd4fdd8f1827" class="usp">
          <h2 class="sp-title-grid-row-2 with-usp">Sell With <br>Ease </h2>
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/625647c95265b3e6cd01e539_no-inventory-to-manage.webp" alt="Up-to-date inventory" width="150" height="150" loading="lazy" data-w-id="6dbbdeea-ba8f-0041-02e7-dd4fdd8f182e" class="usp">
          <h2 class="sp-title-grid-row-2 with-usp">
            <strong>Up-to-Date <br>Inventory </strong>
          </h2>
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/625647e37a5e47ddd5bf12cf_best-quality-product.webp" alt="Best quality products" width="150" height="150" loading="lazy" data-w-id="6dbbdeea-ba8f-0041-02e7-dd4fdd8f1834" class="usp">
          <h2 class="sp-title-grid-row-2 with-usp">Best Quality <br>Products </h2>
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/62564811781fd13323eaf709_great-profit-margin.webp" alt="Great profit margin" width="150" height="150" loading="lazy" data-w-id="6dbbdeea-ba8f-0041-02e7-dd4fdd8f183b" class="usp">
          <h2 class="sp-title-grid-row-2 with-usp">Great Profit <br> Margin </h2>
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6256482e20ff8b3c56992ef6_fastest-delivery.webp" alt="Super-fast delivery" width="150" height="150" loading="lazy" data-w-id="6dbbdeea-ba8f-0041-02e7-dd4fdd8f1842" class="usp">
          <h2 class="sp-title-grid-row-2 with-usp">Super-Fast <br>Delivery </h2>
        </div>
      </div>
    </div>
  </header>
  <div class="sp-hero-footer-container">
    <h2 class="heading-66">
      <strong>Featured on</strong>
    </h2>
    <div class="sp-featured-logos-container w-container">
      <div class="w-layout-grid grid-26">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6126e97d17d7b87c0be5f795_betakit.webp" loading="lazy" alt="Betakit logo" width="129" height="61" class="image-355">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491f8f05a592072358b4063_Shopify-v2.svg" loading="lazy" alt="Shopify logo" width="128" height="61" class="image-356">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491cd62c0adb319f2c9882d_svgviewer-output.svg" loading="lazy" alt="Forbes logo" width="129" height="61" class="image-357">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491fc3963487875bc0bd39c_Techstars.svg" loading="lazy" alt="Techstarts logo" width="129" height="61" class="image-358">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491ff75789eef3bea9f4bc4_Geekwire.svg" loading="lazy" alt="Geekwire logo" width="129" height="61" id="w-node-_7b922b24-5f65-8a5b-dfeb-c13cee655f61-65337966" class="image-359">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6491fff737bed0388c87798d_cix-topp.webp" loading="lazy" alt="Geekwire logo" width="64" height="64" class="image-361">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6492049a8f6742dd55b4ad3b_Globe.webp" loading="lazy" alt="Geekwire logo" width="129" height="60" id="w-node-_619bb3af-f48f-cbbf-cd2d-829185f1f041-65337966" class="image-360">
      </div>
    </div>
  </div>
  <div class="sp-homepage-features-section">
    <div class="sp-container-width sp-responsive-tablet sp-container-vertical-align w-container">
      <div class="sp-container-left-column">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/625648929107cd4a772b7f60_winning-dropshipping-products.webp" alt="Find winning products" width="576" height="414" loading="lazy" class="image-120">
      </div>
      <div class="sp-container-right-column">
        <h2 class="sp-section-title">
          <strong class="bold-text-68">Discover Top-Trending Products</strong>
        </h2>
        <p class="sp-section-paragraph">Browse our intuitive dashboard to find the hottest products in demand</p>
        <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="sp-cta-text-container utm_automation w-inline-block">
          <div class="sp-text-cta">Get started</div>
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63d91a591fe77c25d54de2_right-arrow.svg" loading="lazy" width="12" height="10" alt="" class="sp-text-cta-icon">
        </a>
      </div>
    </div>
    <div class="sp-container-width sp-responsive-tablet sp-container-vertical-align w-container">
      <div class="sp-container-left-column sp-home-left-column">
        <h2 class="sp-section-title">
          <strong>Create Orders</strong>
        </h2>
        <p class="sp-section-paragraph"> Resell products effortlessly with our streamlined order creation process </p>
        <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="sp-cta-text-container utm_automation w-inline-block">
          <div class="sp-text-cta">Get started for free</div>
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63d91a591fe77c25d54de2_right-arrow.svg" loading="lazy" width="12" height="10" alt="" class="sp-text-cta-icon">
        </a>
      </div>
      <div class="sp-container-right-column">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/625648b6f20c69ff861a35d7_products-yourself.webp" alt="Stars Backpack Plus" width="500" height="335" loading="lazy" class="image-120 comb1">
      </div>
    </div>
    <div class="sp-container-width sp-responsive-tablet sp-container-vertical-align w-container">
      <div class="sp-container-left-column">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6256490200672a29c2c45b82_connect-with-ease.webp" alt="Connect your online store" width="600" height="391" loading="lazy" srcset="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6256490200672a29c2c45b82_connect-with-ease-p-500.webp 500w, https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6256490200672a29c2c45b82_connect-with-ease.webp 600w" sizes="(max-width: 479px) 100vw, (max-width: 767px) 66vw, (max-width: 1279px) 43vw, 518.3912353515625px" class="image-120 comb2">
      </div>
      <div class="sp-container-right-column">
        <h2 class="sp-section-title">
          <strong class="bold-text-69">Automate Your Store</strong>
        </h2>
        <p class="sp-section-paragraph">Let Egrocify Reseller Center handle <strong class="bold-text-31">Order fulfillment, &nbsp;Shipping and tracking</strong>, and <strong class="bold-text-32">Customer service</strong>. </p>
        <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="sp-cta-text-container utm_automation w-inline-block">
          <div class="sp-text-cta">Get started for free</div>
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63d91a591fe77c25d54de2_right-arrow.svg" loading="lazy" width="12" height="10" alt="" class="sp-text-cta-icon">
        </a>
      </div>
    </div>
    <div class="sp-container-width sp-responsive-tablet sp-container-vertical-align w-container">
      <div class="sp-container-left-column sp-home-left-column">
        <h2 class="sp-section-title">
          <strong class="bold-text-67">Enjoy seamless payouts</strong>
        </h2>
        <p class="sp-section-paragraph">Request withdrawals anytime <span class="text-span-36">from anywhere</span>, Receive payments via preferred methods and Track earnings in your dashboard. </p>
        <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="sp-cta-text-container utm_automation w-inline-block">
          <div class="sp-text-cta">Get started for free</div>
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63d91a591fe77c25d54de2_right-arrow.svg" loading="lazy" width="12" height="10" alt="" class="sp-text-cta-icon">
        </a>
      </div>
      <div class="sp-container-right-column">
        <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/625649255a98a325b39f1565_fast-shipping.webp" alt="Dropshipping suppliers with fast shipping" width="500" height="443" loading="lazy" class="image-120 comb3">
      </div>
    </div>
  </div>
  <div class="sp-section-what-dropshipping">
    <div class="sp-container-width w-container">
      <div class="sp-container-centre">
        <div class="sp-box-container-2">
          <div class="sp-box-title-container">
            <div class="sp-box-title-content">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f234c56be075e6c06e4e9ea_dots.svg" alt="" class="image-124">
              <h3 class="sp-box-title-large">
                <strong>What is AI Dropshipping?</strong>
              </h3>
            </div>
          </div>
          <div class="w-layout-grid sp-what-dropshipping-container">
            <div id="w-node-f67b03b8-2201-48bc-2a83-796cd760beb4-65337966" class="sp-dropshipping-step">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f64fde5270ce51ba1fd21c1_what-dropshipping-1.webp" loading="lazy" width="143" height="146" alt="Product price" class="sp-dropshipping-step-image">
              <h4 class="sp-dropshipping-step-label">
                <strong>Customer purchases products from your online store</strong>
              </h4>
            </div>
            <div id="w-node-_5c722b19-c1dc-20e1-92d4-4110285e878f-65337966" class="sp-dropshipping-step-container">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63e355083edc09c4ad6b27_arrow-step.svg" loading="lazy" width="32" height="32" alt="" class="sp-dropship-step-arrow">
            </div>
            <div class="sp-dropshipping-step">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f64fde44514448085ef0706_what-dropshipping-2.webp" loading="lazy" width="143" height="141" alt="Supplier price" class="sp-dropshipping-step-image">
              <h4 class="sp-dropshipping-step-label">
                <strong>Their order goes directly to your suppliers</strong>
              </h4>
            </div>
            <div id="w-node-_4ab4c2b7-13e8-21bb-2ee4-c0e86f6827f3-65337966" class="sp-dropshipping-step-container">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63e355083edc09c4ad6b27_arrow-step.svg" loading="lazy" width="32" height="32" alt="" class="sp-dropship-step-arrow">
            </div>
            <div class="sp-dropshipping-step">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f64fde682a9008a2e5c5781_what-dropshipping-3.webp" loading="lazy" width="143" height="145" alt="Supplier ship product" class="sp-dropshipping-step-image">
              <h4 class="sp-dropshipping-step-label">
                <strong>Your suppliers ship the product to your customer</strong>
              </h4>
            </div>
            <div id="w-node-_7706567b-e660-cce6-b67a-89d03a286fb7-65337966" class="sp-dropshipping-step-container">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f63e355083edc09c4ad6b27_arrow-step.svg" loading="lazy" width="32" height="32" alt="" class="sp-dropship-step-arrow">
            </div>
            <div class="sp-dropshipping-step">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f64fde5ddc2f1a758988151_what-dropshipping-4.webp" loading="lazy" width="143" height="146" alt="Retail price profit" class="sp-dropshipping-step-image">
              <h4 class="sp-dropshipping-step-label">
                <strong>You keep the profit from the retail price</strong>
              </h4>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="sp-features-section sp-features-section-home">
    <div class="sp-container-width sp-container-with-grid w-container">
      <h2 class="sp-section-title-2 sp-text-center">
        <span class="text-span-34">
          <strong class="sp-text-purple-2">10x</strong>
        </span>
        <strong class="bold-text-70"> your Dropshipping business with Egrocify Reseller Center </strong>
      </h2>
    </div>
    <div class="sp-container-width w-container justify-content-center">
      <div class="w-layout-grid sp-container-grid-row">
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6492089798215006e8202b9b_Icon-branded.svg" alt="Branded invoicing icon" width="192" height="193" loading="lazy">
          <h3 class="sp-title-grid-row-2">
            <strong>No Marketing</strong>
          </h3>
         <!-- <p class="sp-paragraph-grid-row">Make your mark with a unique brand identity- your invoice represents your brand.</p> -->
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6492eb6fa3f0d5f9e9ca7413_svgviewer-output (1).webp" alt="Branded invoicing icon" width="192" height="193" loading="lazy" class="image-377">
          <h3 class="sp-title-grid-row-2">
            <strong>No Shipping</strong>
          </h3>
         <!-- <p class="sp-paragraph-grid-row">Reliable US, EU and Global suppliers with fast shipping on high quality winning products.</p> -->
        </div>
        <div class="sp-row-element-container">
          <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6492ebcc884c2e27f2c731b1_svgviewer-output (2).svg" alt="Product deals icon" height="193" loading="lazy" class="image-378">
          <h3 class="sp-title-grid-row-2">
            <strong>No Client handles</strong>
          </h3>
         <!-- <p class="sp-paragraph-grid-row">Heavy discounts ranging from 30-40% on all products. Exclusively on Egrocify Reseller Center .</p> -->
        </div>
      </div>
    </div>
  </div>
  <div class="sp-integrations-section d-none">
    <div class="sp-container-width w-container">
      <div class="sp-container-left-column sp-column-size-1-3">
        <h2 class="sp-section-title sp-big-title">
          <strong class="bold-text-71">Available Integrations</strong>
        </h2>
        <p class="sp-section-paragraph">Egrocify Reseller Center integrates with all your favorite eCommerce platforms. Get started dropshipping today!</p>
      </div>
      <div class="sp-container-right-column sp-column-size-2-3">
        <div class="w-layout-grid sp-grid-integration-container">
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ea0-65337966" class="sp-integration-container">
            <a href="integrations/bigcommerce.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646546d68cdd5bfa803710b4_Bigcommerce.svg" alt="Big Commerce logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>BigCommerce</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ea9-65337966" class="sp-integration-container">
            <a href="integrations/shopify.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646546b53b7db8ec3368a6c2_Shopify.svg" alt="Shopify logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>Shopify</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3eb2-65337966" class="sp-integration-container hide-it">
            <a href="integrations/felex.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646546930c3131a6ff1e0229_Felex.svg" alt="Felex logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>Felex</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ebb-65337966" class="sp-integration-container">
            <a href="integrations/wix.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6465465f2b5a3907aac37d5b_Wix.svg" alt="Wix logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>Wix</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ec4-65337966" class="sp-integration-container hide-it">
            <a href="integrations/ecwid.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64654411a88c8b2b4539f5cd_Ecwid.svg" alt="Ecwid logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>Ecwid</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ecd-65337966" class="sp-integration-container hide-it">
            <a href="integrations/squarespace.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646543762a4aaddfaf1fdfdc_Sqaurespace.svg" alt="Squarespace logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>Squarespace</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ed6-65337966" class="sp-integration-container">
            <a href="integrations/woocommerce.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64654357ee1893a3ddf59bef_Wocommerce.svg" alt="WooCommerce logo icon" width="135" height="135" loading="lazy">
              <h3 class="heading-63">
                <strong>WooCommerce</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3edf-65337966" class="sp-integration-container hide-it">
            <a href="integrations/square.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64654346c19580cf4f6c467c_Sqaure.svg" alt="Square logo icon" loading="lazy" height="135" width="135">
              <h3 class="heading-63">
                <strong>Square</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium">
                <div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ee8-65337966" class="sp-integration-container">
            <a href="integrations/alibaba.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64654331c33c2fdb263bc063_Alibaba.svg" alt="Alibaba logo icon" width="135" height="135" loading="lazy" class="image-151">
              <h3 class="heading-63"><strong>Alibaba</strong>
              </h3>
              <div class="sp-secondary-button sp-button-medium"><div class="text-block-187">Connect</div>
              </div>
            </a>
          </div>
          <div id="w-node-c3c5da9b-5f03-2d80-495e-124313ac3ef1-65337966" class="sp-integration-container">
            <a href="integrations/aliscraper.html" class="sp-integration-content w-inline-block">
              <img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646548ab4c333804a0b20921_Aliexpress.svg" alt="AliScraper logo icon" width="135" height="135" loading="lazy"><h3 class="heading-63"><strong>AliExpress</strong></h3><div class="sp-secondary-button sp-button-medium"><div class="text-block-187">Connect</div></div>
            </a>
          </div>
          <div id="w-node-_3256e7f1-fdf8-9638-199b-ebaa8e4658b7-65337966" class="sp-integration-container">
            <a href="integrations/ebay.html" class="sp-integration-content w-inline-block"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/646548bd3b7db8ec336ad483_Ebay.svg" alt="eBay logo icon" width="135" height="135" loading="lazy"><h3 class="heading-63"><strong>eBay</strong></h3><div class="sp-secondary-button sp-button-medium"><div class="text-block-187">Connect</div></div></a>
          </div>
          <div id="w-node-c58f416c-fcfb-c4ac-a89d-0c2704fe97a1-65337966" class="sp-integration-container"><a href="integrations/amazon.html" class="sp-integration-content w-inline-block"><img src="../cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64653549cba66025172d5c55_Amazon-min.png" alt="eBay logo icon" width="135" height="135" loading="lazy"><h3 class="heading-63"><strong>Amazon</strong></h3><div class="sp-secondary-button sp-button-medium"><div class="text-block-187">Connect</div></div></a></div>
        </div>
      </div>
    </div>
  </div>


  <div class="sp-testimonial-section sp-testimonial-section-divider sp-testimonial-section-home">
    <div class="sp-container-width w-container">
      <div class="sp-testimonial-container">
        <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="link-block-27 utm_automation w-inline-block"><div class="w-row"><div class="sp-testimonial-col-text w-col w-col-6"><h3 class="sp-testimonial-title"><strong class="bold-text-72">How I made $178,492 in 3 months by dropshipping US and European products</strong></h3><div utm_campaign="redirection_signup" utm_source="home_page" utm_medium="case_study_marc" class="sp-primary-button sp-button-big"><div class="text-block-186"><strong>Start your FREE</strong></div><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f2463420623248e5fdf4d50_arrow.svg" width="22" height="12" alt="" class="sp-button-icon"></div></div><div class="sp-testimonial-col-image w-col w-col-6"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/6493566d35e89d3d3bd57272_Group 100000329 (1) (1) (1) (1) (1) (1) (1).webp" alt="Marc case study " height="389" loading="lazy" class="image-117"></div></div></a>
      </div>
    </div>
    <div class="sp-container-width w-container">
      <div class="sp-testimonial-container">
          <a rel="noopener noreferrer" href="user/register.php" target="_blank" class="link-block-27 utm_automation w-inline-block">
              <div class="columns-2 w-row">
                  <div class="sp-testimonial-col-image w-col w-col-6"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/64935a9c5b516ed2abeb29a0_testimonial-erin-min (1) (2)-min (2).webp" alt="Erin case study" height="374" loading="lazy" class="image-118"></div>
                  <div class="sp-testimonial-col-text w-col w-col-6">
                      <h3 class="sp-testimonial-title"><strong class="bold-text-17">How this entrepreneur earned $442,991 in just six months </strong></h3>
                      <div utm_source="home_page" utm_medium="case_study_erin" utm_campaign="redirection_signup" class="sp-primary-button sp-button-big">
                          <div class="text-block-186"><strong>Start your FREE</strong></div><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f2463420623248e5fdf4d50_arrow.svg" width="22" height="12" alt="" class="sp-button-icon"></div>
                  </div>
              </div>
          </a>
      </div>
    </div>
  </div>

  <div class="sp-section-connect-integration"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f29e19414ff3ead15b5209d_product-glasses.webp" width="226" alt="Glasses product" height="205" loading="lazy" class="sp-product-glasses"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5fe2abc4f6805926583b0498_product-paint.webp"
    width="165" alt="Frame product" height="250" loading="lazy" class="sp-product-frame"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5fe2abc4f68059e5783b04cf_product-toy.webp" width="220" alt="Toy product" height="253" loading="lazy"
    class="sp-product-toy">
    <div class="sp-container-width w-container">
        <div class="sp-container-centre">
            <div class="sp-section-main-label"><strong>Start now</strong></div>
            <h2 class="sp-section-title sp-text-center sp-white-text"><strong class="bold-text-73">Get started for FREE and watch your dropshipping business thrive today!</strong></h2><a rel="noopener noreferrer" href="user/register.php" target="_blank" class="sp-primary-button sp-button-big utm_automation w-button"><strong class="bold-text-16">Start your FREE</strong></a></div>
    </div><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f23060d397af59610d52c1d_divider-light-grey.svg" loading="lazy" alt="" class="cta_divider-line"></div>

  <footer id="footer" class="supplier-footer">
      <div class="footer-divider extra_foot_space tab_extra_foot_space"></div>
      <div class="sp-container-width w-container">
          <div class="footer-flex-container">
              <div class="sp-footer-list-container sp-list-2-6">
                  <div class="div-block-421">
                      <a href="javascript:void(0)" aria-current="page" class="sp-navbar-logo w-nav-brand w--current">
                          <span class="app-brand-text demo menu-text fw-bold ms-2 ps-1 text-white"><img src="assets/img/branding/logo2.png" width="160"></span>

                          <!-- <img src="assets/svg/logo.svg" alt="Egrocify Reseller Center Logo" width="113" height="48"> -->
                      </a>
                  </div>

                  <p class="sp-footer-text">Egrocify Reseller Center helps dropshippers around the world discover and dropship US/EU based products.</p>
                  <p class="sp-footer-text">Trusted by <span class="text-span-38">200,000+</span> entrepreneurs like you.</p>
                  <div class="div-block-265">
                      <a href="https://play.google.com/store/apps/details?id=com.spocket.app" target="_blank" class="link-block-33 w-inline-block"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/60584ec287496c25ccefc9e6_google-store.webp" loading="lazy" alt=""></a>
                      <a href="https://apps.apple.com/us/app/spocket-dropshipping/id1538627195" target="_blank" class="w-inline-block"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/60584f1972a8dc75a06920b9_ios-store.webp" loading="lazy" width="162" height="50" alt="Download App" class="app-store"></a>
                  </div>
                  <div class="sp-footer-row-column-two">
                      <a rel="noopener noreferrer" href="https://www.facebook.com/profile.php?id=61561162590959&mibextid=ZbWKwL" target="_blank" class="sp-footer-social-icon w-inline-block"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f29ecac4a9081c0ccc04c1a_icon-facebook.svg" alt="Egrocify Reseller Center Facebook" width="24" height="24" class="sp-social-icon"></a>
                      <a rel="noopener noreferrer"
                      href="https://www.instagram.com/egrocifyreseller555?igsh=OWptZWdveTV1bmRu" target="_blank" class="sp-footer-social-icon w-inline-block"><img src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/5f29ecac72b8856fb4be7d46_icon-instagram.svg" alt="Egrocify Reseller Center Instagram" width="24" height="24" class="sp-social-icon"></a>
                  </div>
                  <div class="locales-wrapper w-locales-list">
                      <div data-hover="false" data-delay="400" class="w-dropdown">
                          <div class="dropdown-toggle w-dropdown-toggle">
                              <div class="div-block-423"><img loading="lazy" src="https://cdn.prod.website-files.com/5b3213161e5234bf1cfff9e1/661c0ee4692ba43565257d27_globe-sharp-light 1.svg" alt="" class="image-421"></div>
                              <div>
                                  <div class="current-locale">English (US)</div>
                              </div>
                              <div class="arrow-icon w-icon-dropdown-toggle"></div>
                          </div>
                          <div class="dropdown-list w-dropdown-list">
                              <div role="list" class="dropdown-link-menu w-locales-items">
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="en-US" href="index2c3d.html?r=0" aria-current="page" class="link-28 w--current">English (US)</a></div>
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="pt-BR" href="br.html" class="link-28">Portuguese</a></div>
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="fr" href="fr.html" class="link-28">French</a></div>
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="de" href="de.html" class="link-28">German</a></div>
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="it" href="it.html" class="link-28">Italian</a></div>
                                  <div id="w-node-_1c420c5e-d99f-33f6-43b4-53f1de9a3127-54d6c450" role="listitem" class="locale w-locales-item"><a hreflang="es" href="es.html" class="link-28">Spanish</a></div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div class="sp-footer-list-container col1">
                  <h2 class="sp-footer-heading">Quick links</h2>
                  <ul role="list" class="w-list-unstyled">
                      <li class="list-item-4"><a href="#" class="sp-footer-list-link">Home</a></li>
                      <li class="list-item-3"><a href="pricing_page.php" class="sp-footer-list-link">Pricing</a></li>
                      <li><a href="user/login.php" class="sp-footer-list-link">login</a></li>
                      <li><a href="user/register.php" class="sp-footer-list-link">Register</a></li>
                      <li><a href="user/index.php" class="sp-footer-list-link">Dashboard</a></li>
                  </ul>
                  <!-- <li><a href="admin/login.php" class="sp-footer-list-link">Admin</a></li> -->
                  </ul>

                  <div class="sp-footer-list-container col1 child-list tool-child-list">

                  </div>
              </div>

              <div class="sp-footer-list-container col2 ">
                  <h2 class="sp-footer-heading"><strong class="footer_heading_wrap_set">Payment Partners</strong></h2>
                  
                <img src="assets/img/payments/alfalah_white.png" loading="lazy" sizes="100vw" class="alfalah_footer_logo">
                <img src="assets/img/payments/easypaisa.png" loading="lazy" sizes="100vw" class="easypaisa_footer_logo">
              </div>

              <div class="sp-footer-list-container col3 d-none">
                  <h2 class="sp-footer-heading"><strong class="footer_heading_wrap_set">Integrations</strong></h2>
                  <ul role="list" class="w-list-unstyled">
                      <li><a href="integrations/bigcommerce.html" class="sp-footer-list-link">BigCommerce</a></li>
                      <li><a href="integrations/shopify.html" class="sp-footer-list-link">Shopify</a></li>
                      <li><a href="integrations/felex.html" class="sp-footer-list-link">Felex</a></li>
                      <li><a href="integrations/wix.html" class="sp-footer-list-link">Wix</a></li>
                      <li><a href="integrations/ecwid.html" class="sp-footer-list-link">Ecwid</a></li>
                      <li><a href="integrations/squarespace.html" class="sp-footer-list-link">Squarespace</a></li>
                      <li><a href="integrations/woocommerce.html" class="sp-footer-list-link">WooCommerce</a></li>
                      <li><a href="integrations/square.html" class="sp-footer-list-link">Square</a></li>
                      <li><a href="integrations/alibaba.html" class="sp-footer-list-link">Alibaba</a></li>
                      <li><a href="integrations/aliscraper.html" class="sp-footer-list-link">AliExpress</a></li>
                      <li><a href="integrations/ebay.html" class="sp-footer-list-link">eBay</a></li>
                      <li><a href="integrations/amazon.html" class="sp-footer-list-link">Amazon</a></li>
                  </ul>
              </div>
          </div>
      </div>
      <div class="sp-container-width w-container">
          <div class="sp-footer-row-container">
              <div class="sp-footer-row-column-one">
                  <div class="text-block-188">© 2024 Egrocify Reseller Center . All rights reserved.</div>
                  <div class="div-block-424"><a rel="noopener noreferrer" href="terms_service.php" target="_blank" class="sp-footer-row-link">Terms of Service</a><a rel="noopener noreferrer" href="privacy.php" target="_blank" class="Privacy Policy">Privacy Policy</a></div>
              </div>
          </div>
      </div>
  </footer>

</article>

</body>



<?php 
// include("inc/footer.php");
?>
<script
  src="https://code.jquery.com/jquery-3.7.1.js"
  integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
  crossorigin="anonymous"></script>

<script type="text/javascript">
  $(document).ready(function(){

    $("#menu_hamburgerr").click(function(){
      $("#menu_hamburgerr").addClass("d-none");
      $("#menu_hamburgerr_close").removeClass("d-none");
      // $(".w-nav-overlay").removeClass("d-none");
      $(".w-nav-overlay").show();

    });
    $("#menu_hamburgerr_close").click(function(){
      $("#menu_hamburgerr_close").addClass("d-none");
      $("#menu_hamburgerr").removeClass("d-none");
      // $(".w-nav-overlay").addClass("d-none");
      $(".w-nav-overlay").hide();
    });

  });
</script>