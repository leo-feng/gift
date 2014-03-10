<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Luxurysettings_Adminhtml_ActivateController extends Mage_Adminhtml_Controller_Action
{

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('olegnax/luxury/activate')
            ->_addBreadcrumb(Mage::helper('luxurysettings')->__('Activate Luxury Theme'),
                Mage::helper('luxurysettings')->__('Activate Luxury Theme'));

        return $this;
    }

	public function indexAction()
	    {
	        $this->_initAction();
	        $this->_title($this->__('Olegnax'))
	            ->_title($this->__('Luxury'))
	            ->_title($this->__('Activate Luxury Theme'));

	        $this->_addContent($this->getLayout()->createBlock('luxurysettings/adminhtml_activate_edit'));
	        $block = $this->getLayout()->createBlock('core/text', 'activate-desc')
	                ->setText('<big><b>Activate will update following settings:</b></big>
	                        <br/><br/>
	                        <big>System > Config</big><br/><br/>
	                        <b>Web > Default pages</b>
	                        <ul>
	                            <li>CMS Home Page</li>
	                            <li>CMS No Route Page</li>
	                        </ul>
	                        <b>Design > Themes</b>
	                        <ul>
	                            <li>Default</li>
	                        </ul>
	                        <b>Design > Header</b>
	                        <ul>
	                            <li>Logo Img Src</li>
	                        </ul>
	                        <b>Design > Footer</b>
	                        <ul>
	                            <li>Copyright</li>
	                        </ul>
	                        <b>Currency Setup > Currency Options</b>
	                        <ul>
	                            <li>Allowed currencies</li>
	                        </ul>
	                        ');
	        $this->_addLeft($block);

	        $this->renderLayout();
	    }

	public function activateAction()
    {
        $stores = $this->getRequest()->getParam('stores', array(0));
        $update_currency = $this->getRequest()->getParam('update_currency', 0);
        $setup_cms = $this->getRequest()->getParam('setup_cms', 0);
        
        try {
	        foreach ($stores as $store) {
                $scope = ($store ? 'stores' : 'default');
		        //web > default pages
                Mage::getConfig()->saveConfig('web/default/cms_home_page', 'luxury_home', $scope, $store);
                Mage::getConfig()->saveConfig('web/default/cms_no_route', 'luxury_no_route', $scope, $store);
		        //design > themes
                Mage::getConfig()->saveConfig('design/theme/default', 'luxury', $scope, $store);
                //design > header
                Mage::getConfig()->saveConfig('design/header/logo_src', 'images/logo.png', $scope, $store);
                //design > footer
                Mage::getConfig()->saveConfig('design/footer/copyright', '&copy; 2012 Luxury Theme. Made by <a href="//olegnax.com">olegnax.com</a>', $scope, $store);
                //Currency Setup > Currency Options
                if ($update_currency) {
                    Mage::getConfig()->saveConfig('currency/options/allow', 'GBP,EUR,USD', $scope, $store);
                }
            }

	        if ($setup_cms) {
                Mage::getModel('luxurysettings/settings')->setupCms();
	        }

		    Mage::getSingleton('adminhtml/session')->addSuccess(
                Mage::helper('luxurysettings')->__('Luxury Theme has been activated. Please clear cache (System > Cache management) if you do not see changes in storefront'));
        }
        catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('luxurysettings')->__('An error occurred while activating theme. '.$e->getMessage()));
        }

        $this->getResponse()->setRedirect($this->getUrl("*/*/"));
    }

	private function _updateNewest()
	{

	}

	private function _updateSale()
	{

	}

}