<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Block_Adminhtml_Slideshow_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'slideshow';
        $this->_controller = 'adminhtml_slideshow';
        
        $this->_updateButton('save', 'label', Mage::helper('slideshow')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('slideshow')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('slideshow_data') && Mage::registry('slideshow_data')->getId() ) {
            return Mage::helper('slideshow')->__("Edit Item '%s'", $this->escapeHtml(Mage::registry('slideshow_data')->getTitle()));
        } else {
            return Mage::helper('slideshow')->__('Add Item');
        }
    }
}