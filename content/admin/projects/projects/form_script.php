 <script type="text/javascript">
     var chkl_fl=1;
			$(function(){
				$("#sign_off_form").formwizard({ 
				 	formPluginEnabled: true,
				 	validationEnabled: true,
				 	focusFirstInput : true,
				 	formOptions :{	
						beforeSubmit: function(data){
                                                   if($('#sign_off_frm').val()=='ralphs_checklist.php') 
                                                       {
                                            
                                            if(chkl_fl==1){
                                              chkl_fl=0;
                                              signOffSubmit(data);  
                                            }
                                            else
                                                {
                                              chkl_fl=1;      
                                                }
                                                       }
                                                       else
                                                           {
                                                    signOffSubmit(data);
                                                           }
                                                },
						dataType: 'json',
						resetForm: true
                                              
				 	}	
				 }
				);
  		});
    if(str_stat=='yes'){
    $('#store_name_2 option:eq(0)').attr('selected', true);
    $('#store_name_2 option:eq(0)').trigger('change');
    }           
    </script>