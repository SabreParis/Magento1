// JavaScript Document

jQuery( document ).ready(function() {
	var status_id= jQuery('a[href*="productexport/exports"]');
	jQuery(status_id).attr('target','_blank');
	jQuery(".system-download-product").attr('href',jQuery(status_id).attr('href'));
});