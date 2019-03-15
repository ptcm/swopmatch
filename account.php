<!DOCTYPE = html>
<html>
<head>
</head>
</body>
          <div>
            <label for = "level_taught">Level <span style ="font-size: 70%">(required)</span></label>
            <select id="level_taught" name="level_taught">
              <option value="">Please select one option</option>
              <optgroup label="Primary Level" id="primary">
                <option value="Primary - ECD">Primary - ECD</option>
                <option value="Primary - General">Primary - General</option>
              </optgroup>
              <optgroup label="High School" id="secondary">
                <option value="High School - Up To O Level">High School - Up To O Level</option>
                <option value="High School - Up To A Level">High School - Up To A Level</option>
              </optgroup>
            </select>
          </div>
          <div>
				  <label for ="current_province">Province <span style ="font-size: 70%">(required)</span></label>
				  <select id="current_province" name="current_province" class="mySelect">
				  <option value="">Please select one option</option>
          <?php
          include_once 'inc/connection.php';
          $sql_prov = 'SELECT * FROM provinces ORDER BY province_name';

              try {
                  $results = $db->prepare($sql_prov);
                  $results->execute();
              } catch (Exception $e) {
                  echo "Error!: " . $e->getMessage() . "<br />";
                  return false;
              }
              
              $provinces = $results->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($provinces as $key=>$value){
              echo '<option value='.'"'.$value['province_id'].'"';
              echo '>'.$value['province_name'].'</option>';
          }
          ?>
				  </select></div>
          <div>
          <label for ="current_district">District <span style ="font-size: 70%">(required)</span></label>
          <select name="current_district" id="current_district" class="mySelect">
            
          
              <option value="">Please select one option</option>
              </select>
          </div>
          <div>
            <label for = "current_school">School <span style ="font-size: 70%">(required)</span></label></th>
            <select id="current_school" name="current_school">
              <option value="">Select district first</option>
            </select>
          </div>
              
              <script type="text/javascript" src="js/jquery-3.2.1.min.js"></script>
              <script type="text/javascript">
                 $('#current_province').change(function (){
                    var provinceID = $(this).val();
                    //alert(provinceID);
                    if(provinceID){
                        $.ajax({
                            type:'POST',
                            url:'getdata3.php',
                            data:'distr_province_id='+provinceID,
                            success:function(data){
                                $('#current_district').html(data);
                                $('#current_school').html('<option value="">Select district first</option>'); 
                            }
                        }); 
                    }else{
                        $('#current_district').html('<option value="">Select province first</option>');
                        $('#current_school').html('<option value="">Select district first</option>'); 
                    }
                });
    
                $('#current_district').change(function (){
                    var districtID = $(this).val();
                    if($('#level_taught').val() == 'Primary - ECD' || 
                       $('#level_taught').val() == 'Primary - General'){
                      var level = 'Primary';
                    }else{
                      var level = 'Secondary';
                    }
                    //alert(districtID);
                    //alert(level);
                    if(districtID){
                        $.ajax({
                            type:'POST',
                            url:'getdata3.php',
                            data:{distr_id: districtID, level_taught: level},
                            success:function(html){
                                $('#current_school').html(html);
                            }
                        }); 
                    }else{
                        $('#current_school').html('<option value="">Select district first</option>'); 
                    }
                });
               </script>
</body>        
</html>
