<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0"
				xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
				xmlns:xs="http://www.w3.org/2001/XMLSchema"
				xmlns:fn="http://www.w3.org/2005/02/xpath-functions"
				xmlns:xdt="http://www.w3.org/2005/02/xpath-datatypes"
				exclude-result-prefixes="xs fn xdt">

	<xsl:import href="../../../../../../var/xslt/sabre-params.xslt" />
	<xsl:import href="sabre-functions.xslt" />
    
    <xsl:character-map name="specialchars">
        <!-- change the caracter ° -->
        <xsl:output-character character="&#176;" string="&amp;#176;" />
    </xsl:character-map>

	<xsl:output method="xml" version="1.0" encoding="UTF-8" indent="yes" cdata-section-elements="" use-character-maps="specialchars" />
	
	<xsl:variable name="simpleProductType">simple</xsl:variable>

	<xsl:variable name="locale.fr">fr_FR</xsl:variable>
	<xsl:variable name="locale.us">en_US</xsl:variable>
	
	<xsl:variable name="default.visibility">both</xsl:variable>
	
	<!-- Template de base -->
	<xsl:template match="/">		
		<xsl:apply-templates select="/CATALOG/PRODUCTS" />
	</xsl:template>
	
	<!-- liste des produits -->
	<xsl:template match="PRODUCTS">
		<products>
			<!--xsl:for-each select="PRODUCT[position()&lt;5000]"-->
			<xsl:for-each select="PRODUCT">
        		<xsl:apply-templates select="." />
    		</xsl:for-each>
		</products>
	</xsl:template>

	<!-- liste des images -->
	<xsl:template match="images" mode="list">
			<images>
				<xsl:for-each select="image">
					<image>
						<xsl:attribute name="id" select="@type" />
						<!-- transformer les images en S-C-XXXX-XXX-XXXX.png -->
						<src><xsl:value-of select="$img.src.basepath" /><xsl:call-template name="constructImageName">
							<xsl:with-param name="path" select="@path" />
						</xsl:call-template><!--xsl:value-of select="@path" /--></src>
					</image>
				</xsl:for-each>
			</images>		
	</xsl:template>
	<xsl:template name="constructImageName">
		<xsl:param name="path" />
		<xsl:value-of select="$path" />
		<!--
		<xsl:for-each  select="tokenize($path, '-')">
			<xsl:choose>
				<xsl:when test="position()=1"><xsl:value-of select="." />-</xsl:when>
				<xsl:when test="position()=2"><xsl:value-of select="." />-</xsl:when>
				<xsl:when test="position()=3"><xsl:choose>
					<xsl:when test="string-length(.)=1">000</xsl:when>
					<xsl:when test="string-length(.)=2">00</xsl:when>
					<xsl:when test="string-length(.)=3">0</xsl:when>
				</xsl:choose><xsl:value-of select="." />-</xsl:when>
				<xsl:when test="position()=4"><xsl:choose>
					<xsl:when test="string-length(.)=1">00</xsl:when>
					<xsl:when test="string-length(.)=2">0</xsl:when>
				</xsl:choose><xsl:value-of select="." />-</xsl:when>
				<xsl:when test="position()=5"><xsl:choose>
					<xsl:when test="string-length(.)=1">000</xsl:when>
					<xsl:when test="string-length(.)=2">00</xsl:when>
					<xsl:when test="string-length(.)=3">0</xsl:when>
				</xsl:choose><xsl:value-of select="." /></xsl:when>
			</xsl:choose>
		</xsl:for-each>
		-->
	</xsl:template>
	<xsl:template match="images" mode="gallery">
		<xsl:param name="productName" />
		<media_gallery>
			<xsl:for-each select="image">
				<gallery_item>
					<label><xsl:value-of select='$productName' /></label>
					<type>
						<xsl:choose>
							<xsl:when test="@type='Base'">image</xsl:when>
							<xsl:otherwise>small_image,thumbnail</xsl:otherwise>
						</xsl:choose>
					</type>
					<image>
						<xsl:attribute name="ref"><xsl:value-of select="@type" /></xsl:attribute>
					</image>
				</gallery_item>
			</xsl:for-each>
		</media_gallery>	
	</xsl:template>

	<!-- affichage d'un produit -->
	<xsl:template match="PRODUCT">
		<product uniq_id="">
			<!-- variables utiles -->
			<xsl:variable name="currentProduct" select="current()" />
            <xsl:variable name="currentColorId" select="@id_color" />
			<xsl:variable name="currentModelId" select="@id_model" />
			<xsl:variable name="currentItemId" select="@id_item" />
			<xsl:variable name="currentColor" select="/CATALOG/COLORS/COLOR[@id_color=$currentColorId][1]" />
			<xsl:variable name="currentModel" select="/CATALOG/MODELS/MODEL[@id_model=$currentModelId][1]" />
			<xsl:variable name="currentItem" select="/CATALOG/ITEMS/ITEM[@id_item=$currentItemId][1]" />
			<xsl:variable name="productCategoryCode" select="@category" />
			<xsl:variable name="currentGenericColorId" select="@id_generic_color" />
			<xsl:variable name="currentGenericColor" select="/CATALOG/GENERIC_COLORS/GENERIC_COLOR[@id_color=$currentGenericColorId][1]" />

			<!-- informations principales -->
			<product_type><xsl:value-of select="$simpleProductType" /></product_type>
			<attribute_set><xsl:value-of select="$categories[@type=$productCategoryCode]/@attribute_set" /></attribute_set>
			<created_at><xsl:value-of select="$now" /></created_at>
			<updated_at><xsl:value-of select="$now" /></updated_at>
			<!-- identifiants -->
			<identifiers>
				<merchant><xsl:value-of select="@sku" /></merchant>
			</identifiers>
			<!-- gestion des websites -->
			<xsl:call-template name="websites">
				<xsl:with-param name="enabled" select="@enabled" />
				<xsl:with-param name="associatedWebsites" select="websites" />
			</xsl:call-template>
			<!-- Gestion des images -->
			<xsl:apply-templates select="images" mode="list" />
			<!-- attributs par défaut -->
			<default_attributes>
                <attribute code="tax_class_id"><xsl:value-of select="$tax.group.product" /></attribute>
				<attribute code="weight"><xsl:value-of select="@weight div 1000" /></attribute>
				<attribute code="price">
					<xsl:call-template name="formatPrice">
						<xsl:with-param name="price" select="prices/price[@web='FRA']" />
					</xsl:call-template>
				</attribute>
				<!-- Gestion de la couleur réelle -->
				<attribute code="color">
					<xsl:call-template name="normalizeString">
						<xsl:with-param name="string" select="$currentColor/name/lang[@code='fr']" />
					</xsl:call-template>
				</attribute>
				<!-- Gestion du modèle : cas général -->
				<attribute code="a_model">
					<xsl:call-template name="normalizeString">
						<xsl:with-param name="string" select="$currentModel/name/lang[@code='fr']" />
					</xsl:call-template>
				</attribute>
				<!-- Gestion du modèle : cas de l'attribute set -->
				<xsl:call-template name="applyModel">
					<xsl:with-param name="currentModel" select="$currentModel" />
					<xsl:with-param name="productCategory" select="@category" />
				</xsl:call-template>
				<!-- Gestion du type d'article : cas général -->
				<attribute code="a_article">
					<xsl:call-template name="normalizeString">
						<xsl:with-param name="string" select="$currentItem/name/lang[@code='fr']" />
					</xsl:call-template>
				</attribute>
				<!-- Gestion du type d'article : cas de l'attribute set -->
				<xsl:call-template name="applyTypeArticle">
					<xsl:with-param name="currentItem" select="$currentItem" />
					<xsl:with-param name="productCategory" select="@category" />
				</xsl:call-template>
				<attribute code="a_filter_color">
					<xsl:call-template name="normalizeString">
						<xsl:with-param name="string" select="$currentGenericColor/name/lang[@code='fr']" />
					</xsl:call-template>
				</attribute>
				<attribute code="a_is_set"><xsl:value-of select="@is_set" /></attribute>
			</default_attributes>
            <prices>
				<xsl:for-each select="prices/price">
					<xsl:element name="price">
						<xsl:attribute name="website"><xsl:value-of select="$website.mapping[@erp=current()/@web]/@magento" /></xsl:attribute>
						<xsl:call-template name="formatPrice">
							<xsl:with-param name="price" select="current()" />
						</xsl:call-template>
					</xsl:element>
				</xsl:for-each>
            </prices>
			<languages>

				<xsl:for-each select="distinct-values($allWebsites/websites/website/locale)">
					<xsl:variable name="_codeLocale" select="current()" />
					<!-- Pour chaque locale, on veut afficher une balise language si le produit est associé à un website lié à cette locale -->
					<xsl:for-each select="$website.mapping[locale/@codeLocale=$_codeLocale][position()=1]">
						<language>
							<xsl:attribute name="id" select="$_codeLocale" />
							<xsl:variable name="_codeLangueSabre" select="$language.locale.mapping[@locale=$_codeLocale]" />
							<!--<xsl:attribute name="codeSabre" select="$_codeLangueSabre" />-->

                            <xsl:variable name="_name"><xsl:value-of select='$currentModel/name/lang[@code=$_codeLangueSabre]' /> - <xsl:value-of select='$currentItem/name/lang[@code=$_codeLangueSabre]' /> - <xsl:value-of select='$currentColor/name/lang[@code=$_codeLangueSabre]' /></xsl:variable>
                            <xsl:variable name="_description"><xsl:value-of select='$currentModel/description/lang[@code=$_codeLangueSabre]' /></xsl:variable>

							<name><xsl:value-of select='$_name' /></name>
							<description><xsl:value-of select='$_description' /></description>
                            <short_description><xsl:value-of select='$_description' /></short_description>

                            <meta>
                                <title><xsl:value-of select='$_name' /></title>
                                <description><xsl:value-of select='$_description' /></description>
                                <keyword><xsl:value-of select='$currentModel/name/lang[@code=$_codeLangueSabre]' />,<xsl:value-of select='$currentItem/name/lang[@code=$_codeLangueSabre]' />,<xsl:value-of select='$currentColor/name/lang[@code=$_codeLangueSabre]' /></keyword>
                            </meta>

                            <visibility><xsl:value-of select="$default.visibility"/></visibility>

                            <xsl:apply-templates select="$currentProduct/images" mode="gallery">
                                <xsl:with-param name="productName" select='$_name' />
                            </xsl:apply-templates>

                            <attributes>
                                <attribute code="a_size"><xsl:value-of select="$currentProduct/size/lang[@code=$_codeLangueSabre]" /></attribute>
                            </attributes>

						</language>
					</xsl:for-each>

				</xsl:for-each>

			</languages>
			<!-- Categorie -->
			<xsl:call-template name="applyCategory">
				<xsl:with-param name="category" select="@category" />
			</xsl:call-template>
            <!-- traductions des options -->
            <option_translations>
                <attribute code="color">
                    <xsl:attribute name="default">
                        <xsl:call-template name="normalizeString">
                            <xsl:with-param name="string" select="$currentColor/name/lang[@code='fr']" />
                        </xsl:call-template>
                    </xsl:attribute>
                    <xsl:for-each select="$language.locale.mapping">
                        <xsl:variable name="_translatedLabel" select="$currentColor/name/lang[@code=current()]" />
                        <xsl:if test="$_translatedLabel">
                            <xsl:element name="translation">
                                <xsl:attribute name="locale"><xsl:value-of select="@locale" /></xsl:attribute>
                                <xsl:value-of select="$_translatedLabel" />
                            </xsl:element>
                        </xsl:if>
                    </xsl:for-each>
                </attribute>
				<attribute code="a_filter_color">
                    <xsl:attribute name="default">
                        <xsl:call-template name="normalizeString">
                            <xsl:with-param name="string" select="$currentGenericColor/name/lang[@code='fr']" />
                        </xsl:call-template>
                    </xsl:attribute>
                    <xsl:for-each select="$language.locale.mapping">
                        <xsl:variable name="_translatedLabel" select="$currentGenericColor/name/lang[@code=current()]" />
                        <xsl:if test="$_translatedLabel">
                            <xsl:element name="translation">
                                <xsl:attribute name="locale"><xsl:value-of select="@locale" /></xsl:attribute>
                                <xsl:value-of select="$_translatedLabel" />
                            </xsl:element>
                        </xsl:if>
                    </xsl:for-each>
				</attribute>
                <attribute code="a_model">
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

				<xsl:call-template name="translateModel">
					<xsl:with-param name="productCategory" select="@category" />
					<xsl:with-param name="currentModel" select="$currentModel" />
				</xsl:call-template>

                <attribute code="a_article">
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

				<xsl:call-template name="translateTypeArticle">
					<xsl:with-param name="productCategory" select="@category" />
					<xsl:with-param name="currentItem" select="$currentItem" />
				</xsl:call-template>

            </option_translations>
		</product>
	</xsl:template>
	
	
</xsl:stylesheet>
