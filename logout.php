<?php
$pageTitle = 'SwopMatch Handler | Logged Out';
include("inc/header.php");
session_start();
?>
<div class="row">
    <div class="col-1">
    </div>
        <div class="col-10">
            <div class="container">
                <div class="jumbotron my-1 py-3">
                    <div class="card text-center bg-info mb-3" style="max-width: 1000px;">
                      <div class="card-body bg-info">
                        <h2 class="card-title"><b>Thanks for stopping by! </b></h2>
                        <h5 class="card-text">We will get in touch with you if need be.</h5>
                          <?php 
                            session_destroy();
                            unset($logged);
                            unset($ecNumber);
                          ?>
                        <h4 class="card-text"><b>You have been logged out!</b></h4>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div class="form-group">
                                <a href="index.php" id="logout-butt" class="btn btn-primary btn-lg btn-block hover" role="button" aria-pressed="true">Home</a>
                            </div>
                        </div>
                        <div class="col-1"></div>
                      </div>
                </div>
            </div>
        </div>
    <div class="col-1"></div>
</div>

<?php include("inc/footer.php"); ?>