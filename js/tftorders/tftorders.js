// JavaScript Document

jQuery( document ).ready(function() {
	jQuery("#tftorders_tftorders_group_tftorders_create_customer").change(function() {
		
		var create_cust_val=jQuery("#tftorders_tftorders_group_tftorders_create_customer").val();
		if(create_cust_val==1) 
		{
			jQuery("#row_tftorders_tftorders_group_tftorders_customer_id").hide();	
		}
		else
		{
			jQuery("#row_tftorders_tftorders_group_tftorders_customer_id").show();		
		}
	});
	
		var create_cust_val=jQuery("#tftorders_tftorders_group_tftorders_create_customer").val();
		if(create_cust_val==1) 
		{
			jQuery("#row_tftorders_tftorders_group_tftorders_customer_id").hide();	
		}
		else
		{
			jQuery("#row_tftorders_tftorders_group_tftorders_customer_id").show();		
		}
});