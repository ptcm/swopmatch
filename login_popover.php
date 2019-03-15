<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8" />
    <title>Bootstrap, from Twitter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!-- Le styles -->
    <link data-require="bootstrap-css" data-semver="3.0.0" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <link data-require="bootstrap@*" data-semver="3.0.0" rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" />
    <style>
      body {
        padding-top: 60px;
      }
      @media (max-width: 979px) {

        /* Remove any padding from the body */
        body {
          padding-top: 0;
        }
      }
    </style>
    <link href="style.css" rel="stylesheet" />
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <!-- Le fav and touch icons -->
    <link rel="shortcut icon" href="images/favicon.ico" />
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png" />
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png" />
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png" />
    <!-- Le javascript
    ================================================== -->
    <script data-require="jquery" data-semver="2.0.3" src="http://code.jquery.com/jquery-2.0.3.min.js"></script>
    <script data-require="bootstrap" data-semver="3.0.0" src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
  </head>

  <body>
<div id="popover-content" style="display: none" >
    <div class="container" style="margin: 25px; ">
    <div class="row" style="padding-top: 10px;">
        <label id="sample">
            <form id="mainForm" name="mainForm" method="post" action="">
    <p>
        <label>Name :</label>
        <input type="text" id="txtName" name="txtName" />
    </p>
    <p>
        <label>Address 1 :</label>
        <input type="text" id="txtAddress" name="txtAddress" />
    </p>
    <p>
        <label>City :</label>
        <input type="text" id="txtCity" name="txtCity" />
    </p>
    <p>
        <input type="submit" name="Submit" value="Submit" />
    </p>
</form>

        </label>
    </div>
          </div> 
</div>

 <a href="#" style="margin: 40px 40px;" class="btn btn-large btn-primary" rel="popover" data-content='' data-placement="left" data-original-title="Fill in form">Open form</a>
  </body>
</html>
