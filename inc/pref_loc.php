<a name="location_name" hidden></a>
		  <label for = "location_name">Select Your Preferred Location - Up To Three Options</label>
		  <table>
			  <tr>
				  <th>Location - Option 1</th>
				  <td><select id="loc_name1" name="preferred_location1">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
				  </select></td>
			  </tr>
			  <tr>
				  <th>Location - Option 2</th>
				  <td><select id="loc_name2" name="preferred_location2">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
				  </select></td>
			  </tr>
			  <tr>
				  <th>Location - Option 3</th>
				  <td><select id="loc_name3" name="preferred_location3">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php				  				  
				  all_locations($locations);
				  ?>
				  </select></td>
			  </tr>
		  </table>