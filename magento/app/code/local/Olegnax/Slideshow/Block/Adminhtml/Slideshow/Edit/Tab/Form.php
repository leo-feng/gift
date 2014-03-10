<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Slideshow_Block_Adminhtml_Slideshow_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
	protected function _prepareForm()
	{

		$model = Mage::registry('slideshow_slideshow');

		$form = new Varien_Data_Form();
		$this->setForm($form);
		$fieldset = $form->addFieldset('slideshow_form', array('legend' => Mage::helper('slideshow')->__('Item information')));

        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('slideshow')->__('Store View'),
                'title'     => Mage::helper('slideshow')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            //$model->setStoreId(Mage::app()->getStore(true)->getId());
        }

		$fieldset->addField('title', 'text', array(
			'label' => Mage::helper('slideshow')->__('Title'),
			'required' => false,
			'name' => 'title',
		));

		$fieldset->addField('text', 'textarea', array(
			'label' => Mage::helper('slideshow')->__('Text'),
			'required' => false,
			'name' => 'text',
		));

		$fieldset->addField('button_text', 'text', array(
			'label' => Mage::helper('slideshow')->__('Button Text'),
			'required' => false,
			'name' => 'button_text',
			'note' => Mage::helper('slideshow')->__('Leave empty if you do not wish to show buttom'),
		));

		$fieldset->addField('content_position', 'select', array(
			'label' => Mage::helper('slideshow')->__('Content Align'),
			'name' => 'content_position',
			'values' => array(
				array(
					'value' => 'left',
					'label' => Mage::helper('slideshow')->__('left'),
				),
				array(
					'value' => 'center',
					'label' => Mage::helper('slideshow')->__('center'),
				),
				array(
					'value' => 'right',
					'label' => Mage::helper('slideshow')->__('right'),
				),
			),
		));

		$fieldset->addField('link', 'text', array(
			'label' => Mage::helper('slideshow')->__('Link'),
			'required' => false,
			'name' => 'link',
		));

		$fieldset->addField('image', 'file', array(
			'label' => Mage::helper('slideshow')->__('Image'),
			'required' => false,
			'name' => 'image',
		));

		$fieldset->addField('status', 'select', array(
			'label' => Mage::helper('slideshow')->__('Status'),
			'name' => 'status',
			'values' => array(
				array(
					'value' => 1,
					'label' => Mage::helper('slideshow')->__('Enabled'),
				),
				array(
					'value' => 2,
					'label' => Mage::helper('slideshow')->__('Disabled'),
				),
			),
		));

        $fieldset->addField('sort_order', 'text', array(
            'label'     => Mage::helper('slideshow')->__('Sort Order'),
            'required'  => false,
            'name'      => 'sort_order',
        ));

		if (Mage::getSingleton('adminhtml/session')->getSlideshowData()) {
			$form->setValues(Mage::getSingleton('adminhtml/session')->getSlideshowData());
			Mage::getSingleton('adminhtml/session')->setSlideshowData(null);
		} elseif (Mage::registry('slideshow_data')) {
			$form->setValues(Mage::registry('slideshow_data')->getData());
		}
		return parent::_prepareForm();
	}
}