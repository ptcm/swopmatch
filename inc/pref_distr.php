<a name="district_name"></a>
		  <label for = "preferred_district">Select Your Preferred District - Up To Two Options</label>
		  <table>
			  <tr>
				  <th>District - Option 1</th>
				  <td><select id="preferred_district1" name="preferred_district1">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_districts($districts);
				  ?>
				  </select></td>
			  </tr>
			  <tr>
				  <th>District - Option 2</th>
				  <td><select id="preferred_district2" name="preferred_district2">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_districts($districts);
				  ?>
				  </select></td>
			  </tr>
		  </table>