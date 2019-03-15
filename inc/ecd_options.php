		  <table>
			  <tr>
				  <th><label for ="current_province">Select Your Current Province</label></th>
				  <td><select id="current_province" name="current_province">
				  <option value="">Please select one option</option>
				  <?php
				  all_provinces($provinces);
				  ?>
				  </select></td>
			  </tr>
			  <tr>
				  <th><label for ="current_district">Select Your Current District</label></th>
				  <td><select id="current_district" name="current_district">
				  <option value="">Please select one option</option>
				  <?php
				 all_districts($districts);
				  ?>
				  </select></td>
			  </tr>
			  <tr>
				  <th><label for = "current_school">Select Your Current School</label></th>
				  <td><select id="current_school" name="current_school">
				  <option value="">Please select one option</option>
				  <?php
				 all_schools($schools);
				  ?>
				  </select></td>
			  </tr>
		  </table>