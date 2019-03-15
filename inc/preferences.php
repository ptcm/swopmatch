
	  
<?phpif ($_GET["select_by"] == "province_name"){ ?>
		  <a name="province_name" hidden></a>
		  <label for ="preferred_province">Select Your Preferred Province</label>
		  <select id="preferred_province" name="preferred_province">
		  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php
				  all_provinces($provinces);
				  ?>
<?php}else if($_GET["select_by"] == "district_name"){?>
		  <a name="district_name" hidden></a>
		  <label for = "preferred_district">Select Your Preferred District - Up To Two Options</label>
		  <ol>
			  <li>
				  <select id="preferred_district1" name="preferred_district1">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_districts($districts);
				  ?>
			  </li>
			  <li>
				  <select id="preferred_district2" name="preferred_district2">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php				  
				  all_districts($districts);				  
				  ?>
			  </li>
		  </ol>
<?php}else if($_GET["select_by"] == "town_name"){?>
		  <a name="town_name" hidden></a>
		  <?php
			all_towns($towns);
		  ?>
<?php}else if($_GET["select_by"] == "location_name"){?>
		  <a name="location_name" hidden></a>
		  <label for = "location_name">Select Your Preferred Location - Up To Three Options</label>
		  <ol>
			  <li>
				  <select id="loc_name1" name="preferred_location1">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
			  </li>
			  <li>
				  <select id="loc_name2" name="preferred_location2">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
			  </li>
			  <li>
				  <select id="loc_name3" name="preferred_location3">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
			  </li>
		  </ol>
<?php}else if($_GET["select_by"] == "specific_schools"){?>
		  <a name="specific_schools"></a>
		  <label for = "preferred_schools">Select Your Preferred Schools Maximum of 10 Schools</label>
		  <ol>
			  <li>
				  <select id="preferred_schools1" name="preferred_schools1">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools2" name="preferred_schools2">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools3" name="preferred_schools3">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools4" name="preferred_schools4">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools5" name="preferred_schools5">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools6" name="preferred_schools6">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools7" name="preferred_schools7">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools8" name="preferred_schools8">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools9" name="preferred_schools9">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
			  <li>
				  <select id="preferred_schools10" name="preferred_schools10">
				  <option selected disabled>Please select one option -- (only if applicable)</option>
						<?php
				all_schools($schools);
				?>
			  </li>
		  </ol>
<?php}?>