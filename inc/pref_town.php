<a name="town_name" hidden></a>
<label for = "town_name">Select Your Preferred Town</label>
		  <table>
			  <tr>
				  <th>Town Option</th>
				  <td><select id="town_name" name="preferred_town">
				  <option value="">Please select one option -- (only if applicable)</option>
				  <?php
					all_towns($towns);
				  ?>
				  </select></td>
			  </tr>
		  </table>