<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:xs="http://www.w3.org/2001/XMLSchema"
				xmlns:fn="http://www.w3.org/2005/02/xpath-functions"
				xmlns:xdt="http://www.w3.org/2005/02/xpath-datatypes"
				exclude-result-prefixes="xs fn xdt">


	<xsl:variable name="allWebsites" select="document('../../../../../../var/xslt/websites.xml')" />

	<!-- affichage des websites -->
	<xsl:template name="websites">
		<xsl:param name="enabled" />
		<xsl:param name="associatedWebsites" />
		<websites>
			<xsl:for-each select="$associatedWebsites/website">
				<xsl:variable name="mageWebsite" select="$allWebsites/websites/website[@code=$website.mapping[@erp=current()]/@magento]" />
				<xsl:element name="website">
					<xsl:attribute name="identifier" select="$mageWebsite/@code" />
					<xsl:for-each select="$mageWebsite/locale">
						<language>
							<xsl:attribute name="idref" select="." />
						</language>
					</xsl:for-each>
					<xsl:element name="enabled">
						<xsl:choose>
							<xsl:when test="$enabled='0'">0</xsl:when>
							<xsl:otherwise>1</xsl:otherwise>
						</xsl:choose>
					</xsl:element>
					<xsl:element name="currency">
						<xsl:attribute name="vat_included" select="$mageWebsite/@vat_included" />
						<xsl:value-of select="$mageWebsite/@currency" />
					</xsl:element>
				</xsl:element>
			</xsl:for-each>
		</websites>
	</xsl:template>
	
	<!-- formatage du prix -->
	<xsl:template name="formatPrice">
		<xsl:param name="price" />
		<xsl:value-of select="replace($price,',', '.')" />
	</xsl:template>
	
	<!-- normalisation d'une chaine de caractère -->
	<xsl:template name="normalizeString">
		<xsl:param name="string" />
		<xsl:value-of select="replace(lower-case(replace(normalize-unicode(normalize-space($string), 'NFD'), '[\p{M}]', '')), ' ', '-')" />		
	</xsl:template>
	
	<xsl:template name="applyCategory">
		<xsl:param name="category" />
		<xsl:if test="$category">
			<categories>
				<category>
					<xsl:attribute name="merchant_code"><xsl:value-of select="$category" /></xsl:attribute>
					<xsl:attribute name="destination_id"><xsl:value-of select="$categories[@type=$category]/@magento_id" /></xsl:attribute>
				</category>
			</categories>
		</xsl:if>
	</xsl:template>

	<!-- template de création des attributs article en fonction de leur catégorie -->
	<xsl:template name="applyTypeArticle">
		<xsl:param name="currentItem" />
		<xsl:param name="productCategory" />
		<xsl:variable name="attributeCode">
			<xsl:value-of select="$attribute_codes[@type=$productCategory]/@code_article" />
		</xsl:variable>
		<attribute>
			<xsl:attribute name="code" select="$attributeCode" />
			<xsl:call-template name="normalizeString">
				<xsl:with-param name="string" select="$currentItem/name/lang[@code='fr']" />
			</xsl:call-template>
		</attribute>
	</xsl:template>

	<!-- template de création des attributs modèle en fonction de leur catégorie -->
	<xsl:template name="applyModel">
		<xsl:param name="currentModel" />
		<xsl:param name="productCategory" />
		<xsl:variable name="attributeCode">
			<xsl:value-of select="$attribute_codes[@type=$productCategory]/@code_model" />
		</xsl:variable>
		<attribute>
			<xsl:attribute name="code" select="$attributeCode" />
			<xsl:call-template name="normalizeString">
				<xsl:with-param name="string" select="$currentModel/name/lang[@code='fr']" />
			</xsl:call-template>
		</attribute>
	</xsl:template>

	<!-- template de traduction des attributs article en fonction de leur catégorie -->
	<xsl:template name="translateTypeArticle">
		<xsl:param name="currentItem" />
		<xsl:param name="productCategory" />
		<xsl:variable name="attributeCode">
			<xsl:value-of select="$attribute_codes[@type=$productCategory]/@code_article" />
		</xsl:variable>
		<attribute>
			<xsl:attribute name="code" select="$attributeCode" />
			<xsl:attribute name="default">
				<xsl:call-template name="normalizeString">
					<xsl:with-param name="string" select="$currentItem/name/lang[@code='fr']" />
				</xsl:call-template>
			</xsl:attribute>
			<xsl:for-each select="$language.locale.mapping">
				<xsl:variable name="_translatedLabel" select="$currentItem/name/lang[@code=current()]" />
				<xsl:if test="$_translatedLabel">
					<xsl:element name="translation">
						<xsl:attribute name="locale"><xsl:value-of select="@locale" /></xsl:attribute>
						<xsl:value-of select="$_translatedLabel" />
					</xsl:element>
				</xsl:if>
			</xsl:for-each>
		</attribute>
	</xsl:template>

	<!-- template de traduction des attributs modele en fonction de leur catégorie -->
	<xsl:template name="translateModel">
		<xsl:param name="currentModel" />
		<xsl:param name="productCategory" />
		<xsl:variable name="attributeCode">
			<xsl:value-of select="$attribute_codes[@type=$productCategory]/@code_model" />
		</xsl:variable>
		<attribute>
			<xsl:attribute name="code" select="$attributeCode" />
			<xsl:attribute name="default">
				<xsl:call-template name="normalizeString">
					<xsl:with-param name="string" select="$currentModel/name/lang[@code='fr']" />
				</xsl:call-template>
			</xsl:attribute>
			<xsl:for-each select="$language.locale.mapping">
				<xsl:variable name="_translatedLabel" select="$currentModel/name/lang[@code=current()]" />
				<xsl:if test="$_translatedLabel">
					<xsl:element name="translation">
						<xsl:attribute name="locale"><xsl:value-of select="@locale" /></xsl:attribute>
						<xsl:value-of select="$_translatedLabel" />
					</xsl:element>
				</xsl:if>
			</xsl:for-each>
		</attribute>
	</xsl:template>

</xsl:stylesheet>
