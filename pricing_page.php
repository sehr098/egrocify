<?php 
include("inc/header.php"); 
include("function.php"); 


  // if(!isset($_SESSION['loggedin_id'])){
  //     header("location:user/login.php");
  // }
  
  $packages = get_all_packages('approved');
  if(isset($_SESSION['loggedin_id'])){
    $current_package = get_current_user_det()['package'];
  }else{
      $current_package= 0;
  }
      
  
//   var_dump(gettype($current_package));
  // var_dump($packages);

  ?>

  <body>
    <script src="assets/vendor/js/dropdown-hover.js"></script>
    <script src="assets/vendor/js/mega-dropdown.js"></script>

    <?php include("inc/topbar.php"); ?>


    <!-- Sections:Start -->

    <!-- Pricing Plans -->
    <section class="section-py first-section-pt">
      <div class="container">
        <h2 class="text-center mb-2">Pricing Plans</h2>
        <p class="text-center mb-0">
          All plans include 40+ advanced tools and features to boost your product.<br />
          Choose the best plan to fit your needs.
        </p>
        <!-- <div class="d-flex align-items-center justify-content-center flex-wrap gap-2 pt-9 pb-3 mb-50">
          <label class="switch switch-sm ms-sm-12 ps-sm-12 me-0">
            <span class="switch-label fs-6 text-body">Monthly</span>
            <input type="checkbox" class="switch-input price-duration-toggler" checked />
            <span class="switch-toggle-slider">
              <span class="switch-on"></span>
              <span class="switch-off"></span>
            </span>
            <span class="switch-label fs-6 text-body">Annually</span>
          </label>
          <div class="mt-n5 ms-n10 ml-2 mb-10 d-none d-sm-flex align-items-center gap-1">
            <i class="ti ti-corner-left-down ti-lg text-muted scaleX-n1-rtl"></i>
            <span class="badge badge-sm bg-label-primary rounded-1 mb-3">Save up to 10%</span>
          </div>
        </div> -->

        <div class="row g-6">
              <!-- Basic -->
              <?php
              while($row=mysqli_fetch_assoc($packages)){
                $buttonLabel = (($row['id'] == $current_package) ? ("btn-label-success") : ("btn-primary")) ;
                $buttonText = (($row['id'] == $current_package) ? ("Your Current Plan") : ("Subscribe")) ;
                $upgrade_class = (($row['id'] == $current_package) ? ("upgrade_redirection") : ("")) ;

                $dnone = " ";
                if($row['sorting'] == 1){
                  $dnone = " d-none ";
                }
              ?>

              <div class="col-md-3 col-sm-3 col-xs-3">
                <div class="card border rounded shadow-none">
                  <div class="card-body pt-12 px-5">
                    <div class="mt-3 mb-5 text-center">
                      <img src="assets/img/illustrations/page-pricing-basic.png" alt="Basic Image" height="120" />
                    </div>
                    <h4 class="card-title text-center text-capitalize mb-1"><?php echo $row['name'] ? $row['name'] : "-"; ?></h4>
                    <p class="text-center mb-5"><?php echo $row['short_description'] ? $row['short_description'] : "-"; ?></p>
                    <div class="text-center h-px-50">
                      <div class="d-flex justify-content-center">
                        <sup class="h6 text-body pricing-currency mt-2 mb-0 me-1">$</sup>
                        <h1 class="mb-0 text-primary">$<?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0.00"; ?></h1>
                        <sub class="h6 text-body pricing-duration mt-auto mb-1 ms-1">/month</sub>
                      </div>
                    </div>
                    <div class="my-5 pt-9">
                      <?php echo $row['description'] ? $row['description'] : "-"; ?>
                    </div>
                     <?php if(!isset($_SESSION['loggedin_id'])){ ?>
                    <a href="user/register.php" class="btn btn-primary <?=$dnone?>" target="_blank"
                      ><span class="tf-icons ti ti-login scaleX-n1-rtl me-md-1"></span
                      ><span class="d-none d-md-block">Create your Store</span></a
                    ><?php }else{ ?>
                    <a href="javascript:void(0);" class="btn <?=$upgrade_class;?> <?=$buttonLabel;?> d-grid w-100" id="<?=$row['id'];?> <?=$dnone?>" onclick="upgrade_class_trigger(this)"><?=$buttonText;?></a>
                  <?php } ?>
                  </div>
                </div>
              </div>
              
              <?php
              }
              ?>

          
        </div>
      </div>
    </section>
    <!--/ Pricing Plans -->
    <!-- Pricing Free Trial -->
    <section class="pricing-free-trial bg-label-primary">
      <div class="container">
        <div class="position-relative">
          <div class="d-flex justify-content-between flex-column-reverse flex-lg-row align-items-center pt-12 pb-10">
            <div class="text-center text-lg-start">
              <h4 class="text-primary mb-2">Still not convinced? Start with a 14-day FREE trial!</h4>
              <p class="text-body mb-6 mb-md-11">You will get full access to with all the features for 14 days.</p>
              <a href="user/register.php" class="btn btn-primary">Start 14-day free trial</a>
            </div>
            <!-- image -->
            <div class="text-center">
              <img
                src="assets/img/illustrations/girl-with-laptop.png"
                class="img-fluid me-lg-5 pe-lg-1 mb-3 mb-lg-0"
                alt="Api Key Image"
                width="202" />
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--/ Pricing Free Trial -->
    <!-- Plans Comparison -->
    <section class="section-py pricing-plans-comparison d-none">
      <div class="container">
        <div class="col-12 text-center mb-6">
          <h3 class="mb-2">Pick a plan that works best for you</h3>
          <p>Stay cool, we have a 48-hour money back guarantee!</p>
        </div>
        <div class="row">
          <div class="col-12">
            <div class="table-responsive border border-top-0 rounded">
              <table class="table table-striped text-center mb-0">
                <thead>
                  <tr>
                    <th scope="col">
                      <p class="mb-0">Features</p>
                      <small class="text-body fw-normal text-capitalize">Native front features</small>
                    </th>
                    <th scope="col">
                      <p class="mb-0">Starter</p>
                      <small class="text-body fw-normal text-capitalize">Free</small>
                    </th>
                    <th scope="col">
                      <p class="mb-0 position-relative">
                        Pro
                        <span class="badge badge-center rounded-pill bg-primary position-absolute mt-n2 ms-1"
                          ><i class="ti ti-star mt-n1"></i
                        ></span>
                      </p>
                      <small class="text-body fw-normal text-capitalize">$7.5/month</small>
                    </th>
                    <th scope="col">
                      <p class="mb-0">Enterprise</p>
                      <small class="text-body fw-normal text-capitalize">$16/month</small>
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="text-heading">14-days free trial</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">No user limit</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Product Support</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Email Support</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-label-primary badge-sm">Add-On-Available</span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Integrations</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Removal of Front branding</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge bg-label-primary badge-sm">Add-On-Available</span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Active maintenance & support</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-heading">Data storage for 365 days</td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-secondary p-0">
                        <i class="ti ti-x"></i>
                      </span>
                    </td>
                    <td>
                      <span class="badge badge-center rounded-pill w-px-20 h-px-20 bg-label-primary p-0">
                        <i class="ti ti-check"></i>
                      </span>
                    </td>
                  </tr>
                  <tr>
                    <td></td>
                    <td>
                      <a href="payment-page.html" class="btn text-nowrap btn-label-primary">Choose Plan</a>
                    </td>
                    <td>
                      <a href="payment-page.html" class="btn text-nowrap btn-primary">Choose Plan</a>
                    </td>
                    <td>
                      <a href="payment-page.html" class="btn text-nowrap btn-label-primary">Choose Plan</a>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!--/ Plans Comparison -->
    <!-- FAQS -->
    <section class="section-py pricing-faqs rounded-bottom bg-body">
      <div class="container">
        <div class="text-center mb-6">
          <h4 class="mb-2">FAQs</h4>
          <p>Let us help answer the most common questions you might have.</p>
        </div>
        <div class="accordion" id="pricingFaq">
          <div class="card accordion-item">
            <h2 class="accordion-header" id="headingone">
              <button
                type="button"
                class="accordion-button collapsed"
                data-bs-toggle="collapse"
                data-bs-target="#faq-1"
                aria-expanded="false"
                aria-controls="faq-1">
                What is Egrocify Reseller Center?
              </button>
            </h2>
            <div
              id="faq-1"
              class="accordion-collapse collapse show"
              aria-labelledby="headingone"
              data-bs-parent="#pricingFaq">
              <div class="accordion-body">
                Egrocify Reseller Center is a platform that allows users to resell products and earn a commission on sold products. Our platform offers a range of products with varying profit ratios, making it easy for resellers to find opportunities that suit their needs.
              </div>
            </div>
          </div>
          <div class="card accordion-item active">
            <h2 class="accordion-header" id="headingTwo">
              <button
                type="button"
                class="accordion-button"
                data-bs-toggle="collapse"
                data-bs-target="#faq-2"
                aria-expanded="false"
                aria-controls="faq-2">
                How do I get started as a reseller on Egrocify?
              </button>
            </h2>
            <div
              id="faq-2"
              class="accordion-collapse collapse "
              aria-labelledby="headingTwo"
              data-bs-parent="#pricingFaq">
              <div class="accordion-body">
                To get started, simply create an account on our platform, subscribe to a package, and verify your email address. Once you're signed in, you can browse our product catalog, create orders, and start reselling products to earn a commission.
              </div>
            </div>
          </div>
          <div class="card accordion-item">
            <h2 class="accordion-header" id="headingThree">
              <button
                type="button"
                class="accordion-button collapsed"
                data-bs-toggle="collapse"
                data-bs-target="#faq-3"
                aria-expanded="false"
                aria-controls="faq-3">
                How do I receive my earnings from reselling on Egrocify?
              </button>
            </h2>
            <div
              id="faq-3"
              class="accordion-collapse collapse"
              aria-labelledby="headingThree"
              data-bs-parent="#pricingFaq">
              <div class="accordion-body">
                Earnings from reselling on Egrocify are automatically transferred to your designated account after the withdrawal request is processed. Please note that withdrawal requests are processed on the 31st day after the sale of all products in your order.
              </div>
            </div>
          </div>
          
        </div>
      </div>
    </section>
    <!--/ FAQS -->

    <!-- / Sections:End -->

<?php include("inc/footer.php"); ?>

<script type="text/javascript">
  function upgrade_class_trigger(ee){

  // $(".upgrade_class").click(function(){



    var upgrade_class = $(ee).attr('id');
    // console.log(upgrade_class); 

    var url = 'payment_page.php';
    var form = $('<form action="' + url + '" method="POST">' +
        '<input type="hidden" name="pkg_id" value="'+upgrade_class+'" />' +
        '<input type="hidden" name="payment_mode" value="3" />' +
        '</form>');
    
    $('body').append(form);
    form.submit();
    // alert("tes" + searchbar);

    // $.post('pagination/getDataBootcamp.php', {'page' : 0, 'searchbar' : searchbar}, function(data){$('#dataContainer').html(data); }); return false; 
  // });

}
</script>

