<a name="province_name"></a>
		  <label for ="preferred_province">Select Your Preferred Province</label>
		  <table>
			  <tr>
				  <th>Province Option</th>
				  <td><select id="preferred_province" name="preferred_province">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php
				  all_provinces($provinces);
				  ?>
				  </select></td>
			  </tr>
		  </table>
		  
		  