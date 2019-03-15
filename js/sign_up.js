$().ready(function(){
  
  //validate form on keyup and submit
  $("#account").validate({
    rules: {
      level_taught: "required",
      current_province: "required",
      current_district: "required",
      user_password: {
        required: true,
        minlength: 5
      },
      user_pass_confirm: {
        required: true,
        minlength: 5,
        equalTo: "#password"
      },
      agent_pass_confirm: {
        required: true,
        minlength: 5,
        equalTo: "#agent_password"
      },
      email: {
        email: true
      },
      gender: "required",
      user_first_name: {
        required: true,
        minlength: 2
      },
      user_last_name: {
        required: true,
        minlength: 3
      },
      mobile_number: {
        required: true,
        minlength: 10,
        maxlength: 11
      },
      ec_number: "required",
      current_school: {
            required: true
      }
    },
    
    messages: {
      level_taught: "Please select your level.",
      user_pass_confirm: {
        equalTo: "Passwords do not match!."
      }
    }
  });
  
    $("#agents").validate({
    rules: {
      agent_pass_confirm: {
        required: true,
        minlength: 5,
        equalTo: "#agent_password"
      }
    },
    
    messages: {
      agent_pass_confirm: {
        equalTo: "Please enter the same password as above."
      }
    }
  });
        
    //check uniqueness of preferred schools option 1
    $('#preferred_schools1').change(function(){
       var selectedOpt1 = Number($(this).val());
       var selectedCurSch = Number($('#current_school').val());
        
       if( selectedOpt1 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 1 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true);//reset the first option
       }
       else if( selectedCurSch === 0) {
           alert('Whoa! Please select current school first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the first option
       }
    });
  
    //check uniqueness of preferred schools option 2
    $('#preferred_schools2').change(function(){
       var selectedOpt2 = Number($(this).val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
        
       if( selectedOpt2 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 2 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
       else if( selectedOpt2 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
       else if( selectedOpt1 === 0) {
           alert('Whoa! Please select option 1 first!');
           //$('preferred_schools2_distr').find('option[value=""]').prop("selected", true);
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }       
       
    });
  
    //check uniqueness of preferred schools option 3
    $('#preferred_schools3').change(function(){
       var selectedOpt3 = Number($(this).val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt2 === 0) {
           alert('Whoa! Please select option 2 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
        
       else if( selectedOpt3 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 3 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the second dropdown list
       }
       else if( selectedOpt3 === selectedOpt2 || 
                selectedOpt3 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the second dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 4
    $('#preferred_schools4').change(function(){
       var selectedOpt4 = Number($(this).val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt3 === 0) {
           alert('Whoa! Please select option 3 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fourth option
       }
        
       else if( selectedOpt4 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 4 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fourth dropdown list
       }
       else if( selectedOpt4 === selectedOpt3 || 
                selectedOpt4 === selectedOpt2 || 
                selectedOpt4 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fourth dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 5
    $('#preferred_schools5').change(function(){
       var selectedOpt5 = Number($(this).val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt4 === 0) {
           alert('Whoa! Please select option 4 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fifth option
       }
        
       else if( selectedOpt5 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 5 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fifth dropdown list
       }
       else if( selectedOpt5 === selectedOpt4 || 
                selectedOpt5 === selectedOpt3 || 
                selectedOpt5 === selectedOpt2 || 
                selectedOpt5 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fifth dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 6
    $('#preferred_schools6').change(function(){
       var selectedOpt6 = Number($(this).val());
       var selectedOpt5 = Number($('#preferred_schools5').val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt5 === 0) {
           alert('Whoa! Please select option 5 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the sixth option
       }
        
       else if( selectedOpt6 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 6 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the sixth dropdown list
       }
       else if( selectedOpt6 === selectedOpt5 || 
                selectedOpt6 === selectedOpt4 || 
                selectedOpt6 === selectedOpt3 || 
                selectedOpt6 === selectedOpt2 || 
                selectedOpt6 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the sixth dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 7
    $('#preferred_schools7').change(function(){
       var selectedOpt7 = Number($(this).val());
       var selectedOpt6 = Number($('#preferred_schools6').val());
       var selectedOpt5 = Number($('#preferred_schools5').val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt6 === 0) {
           alert('Whoa! Please select option 6 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the seventh option
       }
        
       else if( selectedOpt7 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 7 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the seventh dropdown list
       }
       else if( selectedOpt7 === selectedOpt6 || 
                selectedOpt7 === selectedOpt5 || 
                selectedOpt7 === selectedOpt4 || 
                selectedOpt7 === selectedOpt3 || 
                selectedOpt7 === selectedOpt2 || 
                selectedOpt7 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the seventh dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 8
    $('#preferred_schools8').change(function(){
       var selectedOpt8 = Number($(this).val());
       var selectedOpt7 = Number($('#preferred_schools7').val());
       var selectedOpt6 = Number($('#preferred_schools6').val());
       var selectedOpt5 = Number($('#preferred_schools5').val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt7 === 0) {
           alert('Whoa! Please select option 7 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the eighth option
       }
        
       else if( selectedOpt8 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 8 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the eighth dropdown list
       }
       else if( selectedOpt8 === selectedOpt7 || 
                selectedOpt8 === selectedOpt6 || 
                selectedOpt8 === selectedOpt5 || 
                selectedOpt8 === selectedOpt4 || 
                selectedOpt8 === selectedOpt3 || 
                selectedOpt8 === selectedOpt2 || 
                selectedOpt8 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the eighth dropdown list
       }
    });
  
    //check uniqueness of preferred schools option 9
    $('#preferred_schools9').change(function(){
       var selectedOpt9 = Number($(this).val());
       var selectedOpt8 = Number($('#preferred_schools8').val());
       var selectedOpt7 = Number($('#preferred_schools7').val());
       var selectedOpt6 = Number($('#preferred_schools6').val());
       var selectedOpt5 = Number($('#preferred_schools5').val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt8 === 0) {
           alert('Whoa! Please select option 8 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the nineth option
       }
        
       else if( selectedOpt9 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 9 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the nineth dropdown list
       }
       else if( selectedOpt9 === selectedOpt8 || 
                selectedOpt9 === selectedOpt7 || 
                selectedOpt9 === selectedOpt6 || 
                selectedOpt9 === selectedOpt5 || 
                selectedOpt9 === selectedOpt4 || 
                selectedOpt9 === selectedOpt3 || 
                selectedOpt9 === selectedOpt2 || 
                selectedOpt9 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the nineth dropdown list
       }        
       
    });
  
    //check uniqueness of preferred schools option 10
    $('#preferred_schools10').change(function(){
       var selectedOpt10 = Number($(this).val());
       var selectedOpt9 = Number($('#preferred_schools9').val());
       var selectedOpt8 = Number($('#preferred_schools8').val());
       var selectedOpt7 = Number($('#preferred_schools7').val());
       var selectedOpt6 = Number($('#preferred_schools6').val());
       var selectedOpt5 = Number($('#preferred_schools5').val());
       var selectedOpt4 = Number($('#preferred_schools4').val());
       var selectedOpt3 = Number($('#preferred_schools3').val());
       var selectedOpt2 = Number($('#preferred_schools2').val());
       var selectedOpt1 = Number($('#preferred_schools1').val());
       var selectedCurSch = Number($('#current_school').val());
       
       if( selectedOpt9 === 0) {
           alert('Whoa! Please select option 9 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the tenth option
       }
        
       else if( selectedOpt10 === selectedCurSch) {
           alert('Whoa! Preferred Schools Option 10 MUST not be the same as the current school!');
           $(this).find('option[value=""]').prop("selected", true); //reset the tenth dropdown list
       }
       else if( selectedOpt10 === selectedOpt9 || 
                selectedOpt10 === selectedOpt8 || 
                selectedOpt10 === selectedOpt7 || 
                selectedOpt10 === selectedOpt6 || 
                selectedOpt10 === selectedOpt5 || 
                selectedOpt10 === selectedOpt4 || 
                selectedOpt10 === selectedOpt3 || 
                selectedOpt10 === selectedOpt2 || 
                selectedOpt10 === selectedOpt1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true); //reset the tenth dropdown list
       }        
       
    });
  
    //check uniqueness of preferred provinces option 1
    $('#preferred_province').change(function(){
       var selectedProv1 = $(this).val();
       var selectedCurProv = $('#current_province').val();
       
       if( selectedCurProv === '') {
           alert('Whoa! Please select your current province first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the first option
       }
        
       else if( selectedProv1 === selectedCurProv) {
           alert('Whoa! Preferred Province Option 1 MUST not be the same as the current province!');
           $(this).find('option[value=""]').prop("selected", true);//reset the first option
       }
    });
  
    //check uniqueness of preferred districts option 1
    $('#preferred_district1').change(function(){
       var selectedDistr1 = Number($(this).val());
       var selectedCurDistr = Number($('#current_district').val());
       
       if( selectedCurDistr === 0) {
           alert('Whoa! Please select your current district first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the first option
       }
        
       else if( selectedDistr1 === selectedCurDistr) {
           alert('Whoa! Preferred Districts Option 1 MUST not be the same as the current district!');
           $(this).find('option[value=""]').prop("selected", true); //reset the first dropdown list
       }
    });
  
    //check uniqueness of preferred districts option 2
    $('#preferred_district2').change(function(){
       var selectedDistr2 = Number($(this).val());
       var selectedDistr1 = Number($('#preferred_district1').val());
       var selectedCurDistr = Number($('#current_district').val());
       
       if( selectedDistr1 === 0) {
           alert('Whoa! Please select option 1 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
        
       else if( selectedDistr2 === selectedCurDistr) {
           alert('Whoa! Preferred Districts Option 2 MUST not be the same as the current district!');
           $(this).find('option[value=""]').prop("selected", true); //reset the second dropdown list
       }
       else if( selectedDistr2 === selectedDistr1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }  
    });
  
    //check uniqueness of preferred districts option 3
    $('#preferred_district3').change(function(){
       var selectedDistr3 = Number($(this).val());
       var selectedDistr2 = Number($('#preferred_district2').val());
       var selectedDistr1 = Number($('#preferred_district1').val());
       var selectedCurDistr = Number($('#current_district').val());
       
       if( selectedDistr2 === 0) {
           alert('Whoa! Please select option 2 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }
        
       else if( selectedDistr3 === selectedCurDistr) {
           alert('Whoa! Preferred Districts Option 3 MUST not be the same as the current district!');
           $(this).find('option[value=""]').prop("selected", true); //reset the third dropdown list
       }
       else if( selectedDistr3 === selectedDistr2 || selectedDistr3 === selectedDistr1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }  
    });
  
    //check uniqueness of preferred districts option 4
    $('#preferred_district4').change(function(){
       var selectedDistr4 = Number($(this).val());
       var selectedDistr3 = Number($('#preferred_district3').val());
       var selectedDistr2 = Number($('#preferred_district2').val());
       var selectedDistr1 = Number($('#preferred_district1').val());
       var selectedCurDistr = Number($('#current_district').val());
       
       if( selectedDistr3 === 0) {
           alert('Whoa! Please select option 3 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fourth option
       }
        
       else if( selectedDistr4 === selectedCurDistr) {
           alert('Whoa! Preferred Districts Option 4 MUST not be the same as the current district!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fourth dropdown list
       }
       else if( selectedDistr4 === selectedDistr3 || 
                selectedDistr4 === selectedDistr2 || 
                selectedDistr4 === selectedDistr1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fourth option
       }  
    });
  
    //check uniqueness of preferred towns option 1
    $('#town_name').change(function(){
       var selectedTown1 = Number($(this).val());
       var selectedCurTown = Number($('#current_town').val());
        
       if( selectedTown1 === selectedCurTown && selectedCurTown != '') {
           alert('Whoa! Preferred Towns Option 1 MUST not be the same as the current town!');
           $(this).find('option[value=""]').prop("selected", true); //reset the first dropdown list
       }
    });
  
    //check uniqueness of preferred towns option 2
    $('#town_name2').change(function(){
       var selectedTown2 = Number($(this).val());
       var selectedTown1 = Number($('#town_name').val());
       var selectedCurTown = Number($('#current_town').val());
       
       if( selectedTown1 === 0) {
           alert('Whoa! Please select option 1 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
        
       else if( selectedTown2 === selectedCurTown && selectedCurTown != '') {
           alert('Whoa! Preferred Towns Option 2 MUST not be the same as the current town!');
           $(this).find('option[value=""]').prop("selected", true); //reset the second dropdown list
       }
       else if( selectedTown2 === selectedTown1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }  
    });
  
    //check uniqueness of preferred towns option 3
    $('#town_name3').change(function(){
       var selectedTown3 = Number($(this).val());
       var selectedTown2 = Number($('#town_name2').val());
       var selectedTown1 = Number($('#town_name').val());
       var selectedCurTown = Number($('#current_town').val());
       
       if( selectedTown2 === 0) {
           alert('Whoa! Please select option 2 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }
        
       else if( selectedTown3 === selectedCurTown && selectedCurTown != '') {
           alert('Whoa! Preferred Towns Option 3 MUST not be the same as the current town!');
           $(this).find('option[value=""]').prop("selected", true); //reset the third dropdown list
       }
       else if( selectedTown3 === selectedTown2 || selectedTown3 === selectedTown1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }  
    });
  
    //check uniqueness of preferred locations option 1
    $('#loc_name1').change(function(){
       var selectedLoc1 = Number($(this).val());
       var selectedCurLoc = Number($('#current_location').val());
       
       if( selectedLoc1 === selectedCurLoc && selectedCurLoc != '') {
           alert('Whoa! Preferred Locations Option 1 MUST not be the same as the current location!');
           $(this).find('option[value=""]').prop("selected", true); //reset the first dropdown list
       }
    });
  
    //check uniqueness of preferred locations option 2
    $('#loc_name2').change(function(){
       var selectedLoc2 = Number($(this).val());
       var selectedLoc1 = Number($('#loc_name1').val());
       var selectedCurLoc = Number($('#current_location').val());
       
       if( selectedLoc1 === 0) {
           alert('Whoa! Please select option 1 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }
        
       else if( selectedLoc2 === selectedCurLoc && selectedCurLoc != '') {
           alert('Whoa! Preferred Locations Option 2 MUST not be the same as the current location!');
           $(this).find('option[value=""]').prop("selected", true); //reset the second dropdown list
       }
       else if( selectedLoc2 === selectedLoc1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the second option
       }  
    });
  
    //check uniqueness of preferred locations option 3
    $('#loc_name3').change(function(){
       var selectedLoc3 = Number($(this).val());
       var selectedLoc2 = Number($('#loc_name2').val());
       var selectedLoc1 = Number($('#loc_name1').val());
       var selectedCurLoc = Number($('#current_location').val());
       
       if( selectedLoc2 === 0) {
           alert('Whoa! Please select option 2 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }
        
       else if( selectedLoc3 === selectedCurLoc && selectedCurLoc != '') {
           alert('Whoa! Preferred Locations Option 3 MUST not be the same as the current location!');
           $(this).find('option[value=""]').prop("selected", true); //reset the third dropdown list
       }
       else if( selectedLoc3 === selectedLoc2 || selectedLoc3 === selectedLoc1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the third option
       }  
    });
  
    //check uniqueness of preferred locations option 4
    $('#loc_name4').change(function(){
       var selectedLoc4 = Number($(this).val());
       var selectedLoc3 = Number($('#loc_name3').val());
       var selectedLoc2 = Number($('#loc_name2').val());
       var selectedLoc1 = Number($('#loc_name1').val());
       var selectedCurLoc = Number($('#current_location').val());
       
       if( selectedLoc3 === 0) {
           alert('Whoa! Please select option 3 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fourth option
       }
        
       else if( selectedLoc4 === selectedCurLoc && selectedCurLoc != '') {
           alert('Whoa! Preferred Locations Option 4 MUST not be the same as the current location!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fourth dropdown list
       }
       else if( selectedLoc4 === selectedLoc3 || 
                selectedLoc4 === selectedLoc2 || 
                selectedLoc4 === selectedLoc1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fourth option
       }  
    });
  
    //check uniqueness of preferred locations option 5
    $('#loc_name5').change(function(){
       var selectedLoc5 = Number($(this).val());
       var selectedLoc4 = Number($('#loc_name4').val());
       var selectedLoc3 = Number($('#loc_name3').val());
       var selectedLoc2 = Number($('#loc_name2').val());
       var selectedLoc1 = Number($('#loc_name1').val());
       var selectedCurLoc = Number($('#current_location').val());
       
       if( selectedLoc4 === 0) {
           alert('Whoa! Please select option 4 first!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fifth option
       }
        
       else if( selectedLoc5 === selectedCurLoc && selectedCurLoc != '') {
           alert('Whoa! Preferred Locations Option 5 MUST not be the same as the current location!');
           $(this).find('option[value=""]').prop("selected", true); //reset the fifth dropdown list
       }
       else if( selectedLoc5 === selectedLoc4 || 
                selectedLoc5 === selectedLoc3 || 
                selectedLoc5 === selectedLoc2 || 
                selectedLoc5 === selectedLoc1) {
           alert('Whoa! This option has already been selected!');
           $(this).find('option[value=""]').prop("selected", true);//reset the fifth option
       }
    });
  
    //disable A Level subjects for lower level teachers
    $('#level_taught').change(function(){
       var levelTaught = $(this).val();
       
      if( levelTaught !== 'High School - Up To A Level'){
          
           $('.ALevel').prop("disabled", true); //disable subjects exclusively for A Level
       }
    });
    
    //check whether terms are agreed to and that preferences for relocation are chosen
    $('#account').on('submit', function() {
        
        if($('#agree').prop('checked') == false){
           alert('Whoa! Please indicate that you have read and agree to the Terms and Conditions');
           return false;
        }
           if( $('#preferred_province').val() == null && $('#preferred_district1').val() == '' && $('#town_name').val() == null && $('#loc_name1').val() == '' && $('#preferred_schools1').val() == '') {
               alert('Whoa! Please select your preferences for relocation before proceeding!');
            return false;
           }
            return true;
    });
});