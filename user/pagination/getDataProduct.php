<?php 
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    session_start();
if(isset($_POST['page'])){ 
    // Include pagination library file 
    include_once 'Pagination.class.php'; 
     
    // Include database configuration file 
    require_once '../../db_config.php'; 
    include_once '../../function.php'; 
    
    /* Filters Start*/
    $postSearch = ((isset($_POST['searchbar'])) ? ($_POST['searchbar']) : (" ")); 
    $searchbar = " ";
    if(isset($_POST['searchbar'])){
        if($_POST['searchbar'] != ""){
            $searchbar = " ";
            // $searchbar = " AND company LIKE '%".$_POST['searchbar']."%' ";
        }
    }
    $package = get_current_user_det()['package'];
    $package = !empty($_POST['package'])?$_POST['package']:$package; 

    $packageSearch = ((isset($_POST['package'])) ? ($_POST['package']) : (" ")); 
    $package_filter = " ";
    if($package <> 0){
        // if($_POST['package'] != ""){
            $package_filter = " AND package = '".$package."' ";
        // }
    }

    /* Filters End */

    // Set some useful configuration 
    $baseURL = 'pagination/getDataProduct.php'; 
    $offset = !empty($_POST['page'])?$_POST['page']:0; 
    $limit = 6; 
    
    // Count of all records 
    $query   = $conn->query("SELECT COUNT(*) as rowNum FROM product WHERE id<>0 AND active ='1' AND deleted <> 1 $searchbar $package_filter "); 
    $result  = $query->fetch_assoc(); 
    $rowCount= $result['rowNum']; 

    
    // $additionalParamCustom =", 'searchbar' : ".$_POST['searchbar'].", 'priceRange' : ".$_POST['priceRange']." ";
    // Initialize pagination class 
    $pagConfig = array( 
        'baseURL' => $baseURL, 
        'totalRows' => $rowCount, 
        'perPage' => $limit, 
        'currentPage' => $offset, 
        'contentDiv' => 'dataContainer',
        'searchbar' => $postSearch,
        'package' => $packageSearch
    ); 
    $pagination =  new Pagination($pagConfig); 
    

    // var_dump($_POST['package']);
    // Fetch records based on the offset and limit 
    $query = $conn->query("SELECT * FROM product WHERE id<>0  AND active ='1' AND deleted <> 1 $searchbar $package_filter  ORDER BY id DESC LIMIT $offset,$limit"); 
        if($query->num_rows > 0){ 
            while($row = $query->fetch_assoc()){ 
                $location = $row['location'];
                $imgLink = '../'.$location.'/'.$row['featured_image'];
    ?>
                        <div class="col-sm-6 col-lg-4">
                          <div class="card p-2 h-100 shadow-none border">
                            <div class="rounded-2 text-center mb-4">
                              <a href="javascripy:void(0)"
                                ><img
                                  class="img-fluid"
                                  src="<?=$imgLink?>"
                                  alt="tutor image 1"
                              /></a>
                            </div>
                            <div class="card-body p-4 pt-2">
                              <div class="d-flex justify-content-between align-items-center mb-4" >
                                <span class="badge bg-label-primary " > ROI:<?php echo $row['roi'] ? $row['roi'].'%' : "0%"; ?></span>
                                <p class="d-flex align-items-center justify-content-center fw-medium gap-1 mb-0">
                                  <span class="badge bg-label-primary" > ROI Days:<?php echo $row['roi_days'] ? $row['roi_days'] : "0"; ?></span>
                                </p>
                              </div>
                              <p class="curren"></p>
                              <a href="app-academy-course-details.html" class="h5"><?php echo $row['name'] ? $row['name'] : "-"; ?></a>
                              <p class="mt-1"><?php echo $row['short_description'] ? $row['short_description'] : "-"; ?></p>
                              <p class="d-flex align-items-center mb-1"><i class="ti ti-price me-1"></i><?=currency_sym();?><?php echo $row['price'] ? number_format((float)$row['price'], 2, '.', '') : "0.00"; ?></p>
                             <!--  <div class="progress rounded-pill mb-4" style="height: 8px">
                                <div
                                  class="progress-bar w-75"
                                  role="progressbar"
                                  aria-valuenow="25"
                                  aria-valuemin="0"
                                  aria-valuemax="100"></div>
                              </div> -->
                              <div
                                class="d-flex flex-column flex-md-row gap-4 text-nowrap flex-wrap flex-md-nowrap flex-lg-wrap flex-xxl-nowrap">
                                <input type="number" class="form-control form-control-sm w-px-100" value="1" min="1" max="5">
                                <a
                                  class="w-100 btn btn-label-success d-flex align-items-center" style="background: #3ce25b !important; color: #ffffff !important;"
                                  href="javascript:void(0)" onclick="resell_trigger(this)" id="<?=$row['id']?>">
                                  <i class="ti ti-rotate-clockwise-2 ti-xs align-middle scaleX-n1-rtl me-2"></i
                                  ><span>Resell Now</span>
                                </a>
                                <!-- <a
                                  class="w-100 btn btn-label-primary d-flex align-items-center"
                                  href="app-academy-course-details.html">
                                  <span class="me-2">Continue</span
                                  ><i class="ti ti-chevron-right ti-xs scaleX-n1-rtl"></i>
                                </a> -->
                              </div>
                            </div>
                          </div>
                        </div>
                            <?php
                        }
                    }
                    else
                    {
                        echo "<p> No Results ..! </p>";
                    }
                    ?>


                    <!-- Card item END -->

                    <!-- Pagination -->

                    <?php echo $pagination->createLinks(); ?>
<?php 
} 
?>