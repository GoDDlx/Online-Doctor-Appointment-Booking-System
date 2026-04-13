<footer class="site-footer section-padding" id="contact">
            <div class="container">
                <div class="row">

                    <div class="col-lg-5 me-auto col-12">
                        <?php
$contactTiming = 'Mon - Fri: 9:00 AM - 6:00 PM';
$contactEmail = 'DAMS@example.com';
$clinicDescription = 'No, 23, 80 Feet Main Rd, 
Kuleshwor Mahadev, Ward No. 15, 
Kathmandu Metropolitan City, 
Kathmandu, Bagmati Province 44600, Nepal.';
try {
    $sql="SELECT * from tblpage where PageType='contactus'";
    $query = $dbh -> prepare($sql);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        $row = $results[0];
        if(isset($row->Timing)) {
            $contactTiming = $row->Timing;
        }
        if(isset($row->Email)) {
            $contactEmail = $row->Email;
        }
        if(isset($row->PageDescription)) {
            $clinicDescription = $row->PageDescription;
        }
    }
} catch (PDOException $e) {
    // Table missing or query failed; show fallback content.
}
?>
                        <h5 class="mb-lg-4 mb-3">Timing</h5>

                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex">
                                <?php echo $contactTiming; ?>
                            </li>
                        </ul>
                            <h5 class="mb-lg-4 mb-3">Email</h5>
                            <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex">
                                <?php echo $contactEmail; ?></li>
                                <br>
                        </ul>
                    </div>

                    <div class="col-lg-2 col-md-6 col-12 my-4 my-lg-0">
                        <h5 class="mb-lg-4 mb-3">Our Clinic</h5>
                        <p><?php echo $clinicDescription; ?></p>
                    </div>
<?php  ?>
                    <div class="col-lg-3 col-md-6 col-12 ms-auto">
                        <h5 class="mb-lg-4 mb-2">Socials</h5>

                        <ul class="social-icon">
                            <li><a href="#" class="social-icon-link bi-facebook"></a></li>

                            <li><a href="#" class="social-icon-link bi-twitter"></a></li>

                            <li><a href="#" class="social-icon-link bi-instagram"></a></li>

                            <li><a href="#" class="social-icon-link bi-youtube"></a></li>
                        </ul>

                        
                    </div>

                   

                </div>
            </section>
        </footer>