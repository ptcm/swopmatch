$(document).ready(function(){
        //declare variables for repeatedly used queries
        var $level_taught = $('#level_taught'), $subjects = $('#subjects'), $referrer_agent = $('#referrer_agent'), $current_province = $("#current_province"), $current_district = $("#current_district"), $current_school = $("#current_school"), $current_town = $('#current_town'), $current_location = $('#current_location'), $prefButtons = $('.prefButtons');
        
    $level_taught.change(function (){
      //the below hides or displays the subjects based on level selected
      if($level_taught.val() == 'High School - Up To O Level' || 
         $level_taught.val() == 'High School - Up To A Level'){
         $subjects.css({display: 'block'})
         }else{
          $subjects.css({display: 'none'}) 
        }
      //until the next comment the below empties the drop down lists for the current section and preferred schools section
      $("#current_province").val('');
      $("#current_district").empty();
      $("#current_school").empty();
      $("#preferred_schools1_distr").val('');
      $("#preferred_schools1").val('');
      $("#preferred_schools2_distr").val('');
      $("#preferred_schools2").empty();
      $("#preferred_schools3_distr").val('');
      $("#preferred_schools3").empty();
      $("#preferred_schools4_distr").val('');
      $("#preferred_schools4").empty();
      $("#preferred_schools5_distr").val('');
      $("#preferred_schools5").empty();
      $("#preferred_schools6_distr").val('');
      $("#preferred_schools6").empty();
      $("#preferred_schools7_distr").val('');
      $("#preferred_schools7").empty();
      $("#preferred_schools8_distr").val('');
      $("#preferred_schools8").empty();
      $("#preferred_schools9_distr").val('');
      $("#preferred_schools9").empty();
      $("#preferred_schools10_distr").val('');
      $("#preferred_schools10").empty(); 
      });
    
    $current_province.click(function (){ //passes the province ID to PHP to be used in the selection of relevant district names
      var provinceID = $(this).val();
      if(provinceID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:'distr_province_id='+provinceID,
              success:function(data){
                  $current_district.html(data);/*
                  $('#current_school').html('<option value="">Select district first</option>'); */
              }
          }); 
      }else{
          $current_district.html('<option value="">Select province first</option>');
          $current_town.html('<option value="">Select province first</option>');
          $current_location.html('<option value="">Select province first</option>');
      }
    });
    
    $current_province.click(function (){ //passes the province ID to PHP to be used in the selection of relevant town names
      var provinceID = $(this).val();
      if(provinceID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:'town_province_id='+provinceID,
              success:function(data){
                  $current_town.html(data);
              }
          }); 
      }
    });
    
    $current_province.click(function (){ //passes the province ID to PHP to be used in the selection of relevant location names if current_town is not selected
      var provinceID = $(this).val();
      if(provinceID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:'loc_province_id='+provinceID,
              success:function(data){
                  $current_location.html(data);
              }
          }); 
      }
    });

  $current_district.click(function (){ //passes the district and level to PHP to be used in the filtering of schools
      var districtID = $(this).val();
      if($level_taught.val() == 'Primary - ECD' || 
         $level_taught.val() == 'Primary - General'){
        var level = 'Primary';
      }else{
        var level = 'Secondary';
      }
      if(districtID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{distr_id: districtID, level_taught: level},
              success:function(html){
                  $current_school.html(html);
              }
          }); 
      }else{
          $current_school.html('<option value="">Select district first</option>'); 
      }
  });

  $current_district.click(function (){ //passes the district to PHP to be used in the filtering of agents
      var districtID = $(this).val();
      if(districtID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:'agent_territory='+districtID,
              success:function(data){
                  $referrer_agent.html(data);
              }
          }); 
      }else{
          $referrer_agent.html('<option value="">Select Current Distr. First</option>'); 
      }
  });
 
 $current_town.click(function (){ //passes the town ID to PHP to be used in the selection of relevant location names if current town is selected
      var townID = $(this).val();
      if(townID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:'loc_town_id='+townID,
              success:function(data){
                  $current_location.html(data);
              }
          }); 
      }
    });
 
 $('#preferred_schools1_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 1
          var pref1_districtID = $('#preferred_schools1_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref1 = 'Primary';
          }else{
            var level_pref1 = 'Secondary';
          }
          if(pref1_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref1_distr_id: pref1_districtID, pref1_level_taught: level_pref1},
                  success:function(html){
                      $('#preferred_schools1').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools1').html('<option value="">Select district first</option>'); 
          }
      });
      
         $('#preferred_schools2_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 2
          var pref2_districtID = $('#preferred_schools2_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref2 = 'Primary';
          }else{
            var level_pref2 = 'Secondary';
          }
          if(pref2_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref2_distr_id: pref2_districtID, pref2_level_taught: level_pref2},
                  success:function(html){
                      $('#preferred_schools2').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools2').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools3_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 3
          var pref3_districtID = $('#preferred_schools3_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref3 = 'Primary';
          }else{
            var level_pref3 = 'Secondary';
          }
          if(pref3_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref3_distr_id: pref3_districtID, pref3_level_taught: level_pref3},
                  success:function(html){
                      $('#preferred_schools3').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools3').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools4_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 4
          var pref4_districtID = $('#preferred_schools4_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref4 = 'Primary';
          }else{
            var level_pref4 = 'Secondary';
          }
          if(pref4_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref4_distr_id: pref4_districtID, pref4_level_taught: level_pref4},
                  success:function(html){
                      $('#preferred_schools4').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools4').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools5_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 5
          var pref5_districtID = $('#preferred_schools5_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref5 = 'Primary';
          }else{
            var level_pref5 = 'Secondary';
          }
          if(pref5_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref5_distr_id: pref5_districtID, pref5_level_taught: level_pref5},
                  success:function(html){
                      $('#preferred_schools5').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools5').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools6_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 6
          var pref6_districtID = $('#preferred_schools6_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref6 = 'Primary';
          }else{
            var level_pref6 = 'Secondary';
          }
          if(pref6_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref6_distr_id: pref6_districtID, pref6_level_taught: level_pref6},
                  success:function(html){
                      $('#preferred_schools6').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools6').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools7_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 7
          var pref7_districtID = $('#preferred_schools7_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref7 = 'Primary';
          }else{
            var level_pref7 = 'Secondary';
          }
          if(pref7_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref7_distr_id: pref7_districtID, pref7_level_taught: level_pref7},
                  success:function(html){
                      $('#preferred_schools7').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools7').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools8_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 8
          var pref8_districtID = $('#preferred_schools8_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref8 = 'Primary';
          }else{
            var level_pref8 = 'Secondary';
          }
          if(pref8_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref8_distr_id: pref8_districtID, pref8_level_taught: level_pref8},
                  success:function(html){
                      $('#preferred_schools8').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools8').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools9_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 9
          var pref9_districtID = $('#preferred_schools9_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref9 = 'Primary';
          }else{
            var level_pref9 = 'Secondary';
          }
          if(pref9_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref9_distr_id: pref9_districtID, pref9_level_taught: level_pref9},
                  success:function(html){
                      $('#preferred_schools9').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools9').html('<option value="">Select district first</option>'); 
          }
      });
      
      $('#preferred_schools10_distr').click(function (){ //passes the district and level to PHP to be used in the filtering of preferred schools option 10
          var pref10_districtID = $('#preferred_schools10_distr').val();
          if($('#level_taught').val() == 'Primary - ECD' || 
             $('#level_taught').val() == 'Primary - General'){
            var level_pref10 = 'Primary';
          }else{
            var level_pref10 = 'Secondary';
          }
          if(pref10_districtID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref10_distr_id: pref10_districtID, pref10_level_taught: level_pref10},
                  success:function(html){
                      $('#preferred_schools10').html(html);
                  }
              }); 
          }else{
              $('#preferred_schools10').html('<option value="">Select district first</option>'); 
          }
      });

  $current_district.click(function (){ //passes the district PHP to be used in the filtering of agents
      var districtID = $(this).val();
      if(districtID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{distr_id: districtID},
              success:function(html){
                  $referrer_agent.html(html);
              }
          }); 
      }else{
          $referrer_agent.html('<option value=""></option>'); 
      }
  });
   
        
      $prefButtons.on('click', function(){
        	$('.mySelect').val("");
        	$('.schools').val("");
        	$('#provinces').hide();
        	$('#districts').hide();
        	$('#towns').hide();
        	$('#locations').hide();
        	$('#specific_schs').hide();
        });
        
        $('#province').on('click', function(){
            $('#provinces').show();
        });
        
        $('#district').on('click', function(){
            $('#districts').show();
        });
        
        $('#location').on('click', function(){
            $('#locations').show();
        });
        
        $('#town').on('click', function(){
            $('#towns').show();
        });
        
        $('#school').on('click', function(){
            $('#specific_schs').show();
        });
        
        $('#loc_name1_distr').click(function (){ //passes the district to PHP to be used in the filtering of preferred locations option 1
      var pref1_distrID = $('#loc_name1_distr').val();
      if(pref1_distrID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{pref1_distrID: pref1_distrID},
              success:function(html){
                  $('#loc_name1').html(html);
              }
          }); 
      }else{
          $('#loc_name1').html('<option value="">Select district first</option>'); 
      }
    });
    
    $('#loc_name2_distr').click(function (){ //passes the district to PHP to be used in the filtering of preferred locations option 2
      var pref2_distrID = $('#loc_name2_distr').val();
      if(pref2_distrID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{pref2_distrID: pref2_distrID},
              success:function(html){
                  $('#loc_name2').html(html);
              }
          }); 
      }else{
          $('#loc_name2').html('<option value="">Select district first</option>'); 
      }
    });
    
    $('#loc_name3_distr').click(function (){ //passes the district to PHP to be used in the filtering of preferred locations option 3
      var pref3_distrID = $('#loc_name3_distr').val();
      if(pref3_distrID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{pref3_distrID: pref3_distrID},
              success:function(html){
                  $('#loc_name3').html(html);
              }
          }); 
      }else{
          $('#loc_name3').html('<option value="">Select district first</option>'); 
      }
    });
    
    $('#loc_name4_distr').click(function (){ //passes the district to PHP to be used in the filtering of preferred locations option 4
      var pref4_distrID = $('#loc_name4_distr').val();
      if(pref4_distrID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{pref4_distrID: pref4_distrID},
              success:function(html){
                  $('#loc_name4').html(html);
              }
          }); 
      }else{
          $('#loc_name4').html('<option value="">Select district first</option>'); 
      }
    });
    
    $('#loc_name5_distr').click(function (){ //passes the district to PHP to be used in the filtering of preferred locations option 5
      var pref5_distrID = $('#loc_name5_distr').val();
      if(pref5_distrID){
          $.ajax({
              type:'POST',
              url:'inc/functions.php',
              data:{pref5_distrID: pref5_distrID},
              success:function(html){
                  $('#loc_name5').html(html);
              }
          }); 
      }else{
          $('#loc_name5').html('<option value="">Select district first</option>'); 
      }
    });
    
    
      $('#distr_name1_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred districts option 1
          var pref1_provinceID = $('#distr_name1_province').val();
          if(pref1_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref1_provinceID: pref1_provinceID},
                  success:function(html){
                      $('#preferred_district1').html(html);
                  }
              }); 
          }else{
              $('#preferred_district1').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#distr_name2_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred districts option 2
          var pref2_provinceID = $('#distr_name2_province').val();
          if(pref2_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref2_provinceID: pref2_provinceID},
                  success:function(html){
                      $('#preferred_district2').html(html);
                  }
              }); 
          }else{
              $('#preferred_district2').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#distr_name3_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred districts option 3
          var pref3_provinceID = $('#distr_name3_province').val();
          if(pref3_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref3_provinceID: pref3_provinceID},
                  success:function(html){
                      $('#preferred_district3').html(html);
                  }
              }); 
          }else{
              $('#preferred_district3').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#distr_name4_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred districts option 2
          var pref4_provinceID = $('#distr_name4_province').val();
          if(pref4_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref4_provinceID: pref4_provinceID},
                  success:function(html){
                      $('#preferred_district4').html(html);
                  }
              }); 
          }else{
              $('#preferred_district4').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#town_name1_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred towns option 1
          var pref1_town_provinceID = $('#town_name1_province').val();
          if(pref1_town_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref1_town_provinceID: pref1_town_provinceID},
                  success:function(html){
                      $('#town_name').html(html);
                  }
              }); 
          }else{
              $('#town_name').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#town_name2_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred towns option 2
          var pref2_town_provinceID = $('#town_name2_province').val();
          if(pref2_town_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref2_town_provinceID: pref2_town_provinceID},
                  success:function(html){
                      $('#town_name2').html(html);
                  }
              }); 
          }else{
              $('#town_name2').html('<option value="">Select province first</option>'); 
          }
      });
      
      $('#town_name3_province').click(function (){ //passes the province to PHP to be used in the filtering of preferred towns option 3
          var pref3_town_provinceID = $('#town_name3_province').val();
          if(pref3_town_provinceID){
              $.ajax({
                  type:'POST',
                  url:'inc/functions.php',
                  data:{pref3_town_provinceID: pref3_town_provinceID},
                  success:function(html){
                      $('#town_name3').html(html);
                  }
              }); 
          }else{
              $('#town_name3').html('<option value="">Select province first</option>'); 
          }
      });
      
      //change the screen layout for the index page
      if($('#infoDiv').hasClass('col-6')){
          $('#leftScrDiv').remove();
          $('#rightScrDiv').remove();
          $('#leftBodDiv').remove();
          $('#rightBodDiv').remove();
          $('#headerDiv').removeClass('col-10');
          $('#infoDiv').removeClass('col-6').addClass('col-8');
          $('#jumbo-row').addClass('flex-nowrap').css("margin-right", "12px");
          $('#wrapper-container').removeClass('container').addClass('container-fluid').css({'padding-left': "0px !important", 'padding-right': "0px !important"});
      }  
      //rearrangement started from here!
      // Activate first tab if it's an already signed up client
      if ($('#welcome').length>0)
        {   
            $('#view_status').addClass('active');
            $('#info').hide();
            $('#profile').hide();
            $('#hide-group').hide();
            $('#reset-button').hide();
            $('#status').show();
        }
            
    // On clicking first tab
    $('#view_status').on('click', function(){
        $(this).addClass('active');
        $('#view_update').removeClass('active');
        $('#info').hide();
        $('#hide-group').hide();
        $('#logOut').show();
        $('#reset-button').hide();
        $('#profile').hide();
        $('#status').show();
    });
    
    // On clicking second tab
    $('#view_update').on('click', function(){
        $('#view_status').removeClass('active');
        $(this).addClass('active');
        $('#info').show();
        $('#reset-button').show();
        $('#status').hide();
        $('#profile').show();
    });
    
    // On clicking new location tab
    $('#new_loc').on('click', function(){
        $(this).addClass('active');
        $('#update_loc').removeClass('active');
        $('#update_location').hide();
        $('#new_location').show();
    });
    
    // On clicking update location tab
    $('#update_loc').on('click', function(){
        $('#new_loc').removeClass('active');
        $(this).addClass('active');
        $('#new_location').hide();
        $('#update_location').show();
    });
    
    
    $('#update_loc').on('click', function(){
        	$('#location-name').show();
        	$('#loc-butts').hide();
        });
    
    //hide overview on home page
    $('#hide_how').on('click', function(){
        	$('#how').hide();
        	$('#show_how').show();
        	$('#hide_how').hide();
        });
    
    //show overview on home page
    $('#show_how').on('click', function(){
        	$('#how').show();
        	$('#show_how').hide();
        	$('#hide_how').show();
        });
        
    //login popover js 
    $(function () {
          $('[data-toggle="popover"]').popover()
        })
        
    
   if($(this).val()== "c_name"){
	$('#by_name').show();
	$('#date_range').hide();
   }
   
   else {
       if($(this).val()== "entry_date"){
        	$('#by_name').hide();
        	$('#date_range').show();
       }
   }
       
   if($(this).val()== "agent_report"){
	$('#agent_code').show();
   }
   
   else {
       if($(this).val()!= "agent_report"){
	$('#agent_code').hide();
       }
   }
}); 
  /*    
      // Activate first tab if it's an already signed up client
      if ($('#welcome').length>0)
        {   
            $('#view_status').addClass('active');
            $('#info').hide();
            $('#profile').hide();
            $('#hide-group').hide();
            $('#reset-button').hide();
            $('#status').show();
        }
            
    // On clicking first tab
    $('#view_status').on('click', function(){
        $(this).addClass('active');
        $('#view_update').removeClass('active');
        $('#info').hide();
        $('#hide-group').hide();
        $('#logOut').show();
        $('#reset-button').hide();
        $('#profile').hide();
        $('#status').show();
    });
    
    // On clicking second tab
    $('#view_update').on('click', function(){
        $('#view_status').removeClass('active');
        $(this).addClass('active');
        $('#info').show();
        $('#reset-button').show();
        $('#status').hide();
        $('#profile').show();
    });
    
    // On clicking new location tab
    $('#new_loc').on('click', function(){
        $(this).addClass('active');
        $('#update_loc').removeClass('active');
        $('#update_location').hide();
        $('#new_location').show();
    });
    
    // On clicking update location tab
    $('#update_loc').on('click', function(){
        $('#new_loc').removeClass('active');
        $(this).addClass('active');
        $('#new_location').hide();
        $('#update_location').show();
    });
    
    
    $('#update_loc').on('click', function(){
        	$('#location-name').show();
        	$('#loc-butts').hide();
        });
    
    //hide overview on home page
    $('#hide_how').on('click', function(){
        	$('#how').hide();
        	$('#show_how').show();
        	$('#hide_how').hide();
        });
    
    //show overview on home page
    $('#show_how').on('click', function(){
        	$('#how').show();
        	$('#show_how').hide();
        	$('#hide_how').show();
        });
        
    //login popover js 
    $(function () {
          $('[data-toggle="popover"]').popover()
        })
    *//*
   $(document).on('change','#search_type',function(){
       if($(this).val()== "c_name"){
    	$('#by_name').show();
    	$('#date_range').hide();
       }
       
       else {
           if($(this).val()== "entry_date"){
            	$('#by_name').hide();
            	$('#date_range').show();
           }
       }
    });*//*

  //show or hide agent code field in reports
   $(document).on('change','#rep_type',function(){
       if($(this).val()== "agent_report"){
    	$('#agent_code').show();
       }
       
       else {
           if($(this).val()!= "agent_report"){
    	$('#agent_code').hide();
           }
       }
    
    });*/