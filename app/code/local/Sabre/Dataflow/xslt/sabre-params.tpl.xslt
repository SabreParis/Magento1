<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:xs="http://www.w3.org/2001/XMLSchema"
				xmlns:fn="http://www.w3.org/2005/02/xpath-functions"
				xmlns:xdt="http://www.w3.org/2005/02/xpath-datatypes"
				exclude-result-prefixes="xs fn xdt">
	
	<xsl:variable name="now"></xsl:variable>

	<xsl:variable name="website.france.id">{{var website_france_id}}</xsl:variable>
	<xsl:variable name="website.france.currency">{{var website_france_currency}}</xsl:variable>
	<xsl:variable name="website.france.vat.included">{{var website_france_vat_included}}</xsl:variable>

	<xsl:variable name="tax.group.product">{{var tax_group_product}}</xsl:variable>
	<xsl:variable name="img.src.basepath"></xsl:variable>

	<xsl:variable name="categories" as="element()*">
		{{var categories_mapping}}
		<!--
		<category type="A" magento_id="5" 	attribute_set="Accessory" />
		<category type="C" magento_id="3" 	attribute_set="Cutlery" />
		<category type="D" magento_id="3" 	attribute_set="Cutlery" />
		<category type="I" magento_id="5" 	attribute_set="Accessory" />
		<category type="VA" magento_id="4" 	attribute_set="Porcelain" />
		<category type="L" magento_id="3" 	attribute_set="Cutlery" />
		-->
	</xsl:variable>

	<xsl:variable name="website.mapping" as="element()*">
		{{var websites_mapping}}
		<!--
		<website magento="code_magento" erp="code_erp">
			<locale codeLocale="fr_FR" codeLang="fr" />
			<locale codeLocale="en_US" codeLang="en" />
		</website>
		-->
	</xsl:variable>

	<xsl:variable name="attribute_codes" as="element()*">
		{{var attribute_codes_mapping}}
		<!--
		<article_attribute_code type="A" 	code_article="a_article_accessory"	code_model="a_model_accessory" 	/>
		<article_attribute_code type="C" 	code_article="a_article_cutlery" 	code_model="a_model_cutlery" 		/>
		<article_attribute_code type="D" 	code_article="a_article_cutlery"	code_model="a_model_cutlery" 		/>
		<article_attribute_code type="I" 	code_article="a_article_accessory"	code_model="a_model_accessory" 	/>
		<article_attribute_code type="VA" 	code_article="a_article_porcelain"	code_model="a_model_porcelain" 	/>
		<article_attribute_code type="L" 	code_article="a_article_cutlery"	code_model="a_model_cutlery" 		/>
		-->
	</xsl:variable>

	<xsl:variable name="language.locale.mapping" as="element()*">
		{{var language_locale_mapping}}
		<!--
		<lang locale="fr_FR">fr</lang>
		-->
	</xsl:variable>


	
	
</xsl:stylesheet>
