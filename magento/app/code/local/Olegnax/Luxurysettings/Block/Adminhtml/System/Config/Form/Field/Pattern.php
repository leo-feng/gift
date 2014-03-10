<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Luxurysettings_Block_Adminhtml_System_Config_Form_Field_Pattern extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Override field method to add js
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return String
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        // Get the default HTML for this option
        $html = parent::_getElementHtml($element);

	    $html .= '<br/><div id="luxurysettings_pattern_preview" style="width:200px; height:100px; margin-top:5px; background-color:wheat;"></div>
		<script>
        var patternPreviewModel = Class.create();

        patternPreviewModel.prototype = {
            initialize : function()
            {
                this.patternElement = $("luxurysettings_appearance_bg_pattern");
                this.previewElement = $("luxurysettings_pattern_preview");

                this.refreshPreview();
                this.bindChange();
            },
            bindChange : function()
            {
                Event.observe(this.patternElement, "change", this.refreshPreview.bind(this));
                Event.observe(this.patternElement, "keyup", this.refreshPreview.bind(this));
                Event.observe(this.patternElement, "keydown", this.refreshPreview.bind(this));
            },
        	refreshPreview : function()
            {
                if ( this.patternElement.value != "" ) {
                    $(this.previewElement).setStyle({ 
                        backgroundImage: "url('. Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_SKIN).'frontend/default/luxury/images/" + this.patternElement.value + ".png)",
                        backgroundColor: $("luxurysettings_appearance_bg_color").value
                    });
                } else {
                    $(this.previewElement).setStyle({ backgroundImage: "none" });
                }
            }
        }

        patternPreview = new patternPreviewModel();
		</script>
        ';
        return $html;
    }
}