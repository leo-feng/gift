<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Luxurysettings_Block_Navigation extends Mage_Catalog_Block_Navigation
{

	/**
	 * columns html
	 *
	 * @var array
	 */
	protected $_columnHtml;

	/*
	 * @var Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	protected $_leftCategories;

	/**
	 * top level parent category for current category
	 *
	 * @var int
	 */
	protected $_parent;

	protected function _construct()
    {
        parent::_construct();

	    $path = $this->getCurrentCategoryPath();
        $parent = $path[count($path)-1];
        if (!$parent) {
            $parent = Mage::app()->getStore()->getRootCategoryId();
        }
        $this->_parent = $parent;
    }


	/**
	 * render category html
	 *
	 * @param Mage_Catalog_Model_Category $category
	 * @param integer $level
	 * @param array $levelClass
	 * @return string
	 */
	public function drawOpenCategoryItem($category, $level = 0, array $levelClass = null)
	{
		$html = array();

		if (!$category->getIsActive()) return '';

		if (!isset($levelClass)) $levelClass = array();
		$combineClasses = array();

		$combineClasses[] = 'level' . $level;
		if ($this->_isCurrentCategory($category)) {
			$combineClasses[] = 'active';
		} else {
			$combineClasses[] = $this->isCategoryActive($category) ? 'parent' : 'inactive';
		}
		$levelClass[] = implode('-', $combineClasses);
		$levelClass = array_merge($levelClass, $combineClasses);
		$levelClass[] = $this->_getClassNameFromCategoryName($category);

		$html[1] = '<a href="' . $this->getCategoryUrl($category) . '">' . $this->escapeHtml($category->getName()) . '</a>' . "\n";

		if ( in_array($category->getId(), $this->getCurrentCategoryPath()) ) {
			$children = $this->_getLeftCategoryCollection()
					->addIdFilter($category->getChildren());

			$hasChildren = $children && ($childrenCount = count($children));
			if ($hasChildren) {
				$htmlChildren = '';

				foreach ($children as $i => $child)
				{
					$class = array();
					if ($childrenCount == 1) {
						$class[] = 'only';
					}
					else
					{
						if (!$i) $class[] = 'first';
						if ($i == $childrenCount - 1) $class[] = 'last';
					}
					$htmlChildren .= $this->drawOpenCategoryItem($child, $level + 1, $class);
				}

				if (!empty($htmlChildren)) {
					$levelClass[] = 'open';

					$html[2] = '<ul>' . "\n"
							. $htmlChildren . "\n"
							. '</ul>';
				}
			}
		}

		$html[0] = sprintf('<li class="%s">', implode(" ", $levelClass)) . "\n";
		$html[3] = "\n" . '</li>' . "\n";

		ksort($html);
		return implode('', $html);
	}

	/**
	 * Convert the category name into a string that can be used as a css class
	 *
	 * @param Mage_Catalog_Model_Category $category
	 * @return string
	 */
	protected function _getClassNameFromCategoryName($category)
	{
		$name = $category->getName();
		$name = preg_replace('/-{2,}/', '-', preg_replace('/[^a-z-]/', '-', strtolower($name)));
		while ($name && $name{0} == '-') $name = substr($name, 1);
		while ($name && substr($name, -1) == '-') $name = substr($name, 0, -1);
		return $name;
	}

	/**
	 * Check if the current category matches the passed in category
	 *
	 * @param Mage_Catalog_Model_Category $category
	 * @return bool
	 */
	protected function _isCurrentCategory($category)
	{
		return ($cat = $this->getCurrentCategory()) && $cat->getId() == $category->getId();
	}

	/**
	 * return top level category name
	 *
	 * @return string
	 */
	public function getBlockTitle() {
		if ( $this->_parent == Mage::app()->getStore()->getRootCategoryId() ) {
			return '';
		} else {
			return Mage::getModel('catalog/category')->load($this->_parent)->getName();
		}
	}

	/**
	 * Get sibling catagories
	 *
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	public function getLeftCategories()
	{
		if (!isset($this->_leftCategories)) {
			$this->_leftCategories = $this->_getLeftCategoryCollection()
				->addIdFilter(Mage::getModel('catalog/category')->load($this->_parent)->getChildren());
		}
		return $this->_leftCategories;
	}

	/**
	 * @return Mage_Catalog_Model_Resource_Eav_Mysql4_Category_Collection
	 */
	protected function _getLeftCategoryCollection()
	{
		$collection = Mage::getResourceModel('catalog/category_collection');
		$collection->addAttributeToSelect('url_key')
				->addAttributeToSelect('name')
				->addAttributeToSelect('all_children')
				->addAttributeToFilter('is_active', 1)
				->addAttributeToFilter('include_in_menu', 1)
				->setOrder('position', 'ASC')
				->joinUrlRewrite();
		return $collection;
	}

	/**
	 * Render category to html
	 *
	 * @param Mage_Catalog_Model_Category $category
	 * @param int Nesting level number
	 * @param boolean Whether ot not this item is last, affects list item class
	 * @param boolean Whether ot not this item is first, affects list item class
	 * @param boolean Whether ot not this item is outermost, affects list item class
	 * @param string Extra class of outermost list items
	 * @param string If specified wraps children list in div with this class
	 * @param boolean Whether ot not to add on* attributes to list item
	 * @return string
	 */
	protected function _renderCategoryMenuItemHtml($category, $level = 0, $isLast = false, $isFirst = false,
	                                               $isOutermost = false, $outermostItemClass = '', $childrenWrapClass = '', $noEventAttributes = false)
	{
		if (!$category->getIsActive()) {
			return '';
		}
		$html = array();

		// get all children
		if (Mage::helper('catalog/category_flat')->isEnabled()) {
			$children = (array)$category->getChildrenNodes();
			$childrenCount = count($children);
		} else {
			$children = $category->getChildren();
			$childrenCount = $children->count();
		}
		$hasChildren = ($children && $childrenCount);

		// select active children
		$activeChildren = array();
		foreach ($children as $child) {
			if ($child->getIsActive()) {
				$activeChildren[] = $child;
			}
		}
		$activeChildrenCount = count($activeChildren);
		$hasActiveChildren = ($activeChildrenCount > 0);

		// prepare list item html classes
		$classes = array();
		$classes[] = 'level' . $level;
		$classes[] = 'nav-' . $this->_getItemPosition($level);
		if ($this->isCategoryActive($category)) {
			$classes[] = 'active';
		}
		$linkClass = '';
		if ($isOutermost && $outermostItemClass) {
			$classes[] = $outermostItemClass;
			$linkClass = ' class="' . $outermostItemClass . '"';
		}
		if ($isFirst) {
			$classes[] = 'first';
		}
		if ($isLast) {
			$classes[] = 'last';
		}
		if ($hasActiveChildren) {
			$classes[] = 'parent';
		}

		// prepare list item attributes
		$attributes = array();
		if (count($classes) > 0) {
			$attributes['class'] = implode(' ', $classes);
		}
		if ($hasActiveChildren && !$noEventAttributes) {
			$attributes['onmouseover'] = 'toggleMenu(this,1)';
			$attributes['onmouseout'] = 'toggleMenu(this,0)';
		}

		// assemble list item with attributes
		$htmlLi = '<li';
		foreach ($attributes as $attrName => $attrValue) {
			$htmlLi .= ' ' . $attrName . '="' . str_replace('"', '\"', $attrValue) . '"';
		}
		$htmlLi .= '>';
		$html[] = $htmlLi;

		$html[] = '<a href="' . $this->getCategoryUrl($category) . '"' . $linkClass . '>';
		$html[] = '<span>' . $this->escapeHtml($category->getName()) . '</span>';
		$html[] = '</a>';

		if ($level == 0) {
			//get category description
			$ca = Mage::getModel('catalog/category')->load($category->getId());
			$description = $ca->getDescription();
			if (empty($description) || !Mage::getStoreConfig('luxurysettings/navigation/show_description')) {
				$columns = 4;
			} else {
				$columns = 2;
			}
			$columnItemsNum = array_fill(0, $columns, floor($activeChildrenCount / $columns));
			if ($activeChildrenCount % $columns > 0) {
				for ($i = 0; $i < ($activeChildrenCount % $columns); $i++) {
					$columnItemsNum[$i]++;
				}
			}
			$this->_columnHtml = array();
		}

		// render children
		$htmlChildren = '';
		$j = 0; //child index
		$i = 0; //column index
		$itemsCount = $activeChildrenCount;
        if (isset($columnItemsNum[$i])) {
            $itemsCount = $columnItemsNum[$i];
        }
		foreach ($activeChildren as $child) {

			if ($level == 0) {
				$isLast = (($j + 1) == $itemsCount || $j == $activeChildrenCount - 1);
				if ($isLast) {
					$i++;
                    if (isset($columnItemsNum[$i])) {
                        $itemsCount += $columnItemsNum[$i];
                    }
				}
			} else {
				$isLast = ($j == $activeChildrenCount - 1);
			}

			$childHtml = $this->_renderCategoryMenuItemHtml(
				$child,
				($level + 1),
				$isLast,
				($j == 0),
				false,
				$outermostItemClass,
				$childrenWrapClass,
				$noEventAttributes
			);
			if ($level == 0) {
				$this->_columnHtml[] = $childHtml;
			} else {
				$htmlChildren .= $childHtml;
			}
			$j++;
		}

		if ($level == 0 && $this->_columnHtml) {
			$i = 0;
			foreach ($columnItemsNum as $columnNum) {
				$chunk = array_slice($this->_columnHtml, $i, $columnNum);
				$i += $columnNum;
				$htmlChildren .= '<li ' . (count($this->_columnHtml) == $i ? 'class="last"' : '') . '><ol>';
				foreach ($chunk as $item) {
					$htmlChildren .= $item;
				}
				$htmlChildren .= '</ol></li>';
			}
		}
		if (!empty($description) && !empty($htmlChildren) && Mage::getStoreConfig('luxurysettings/navigation/show_description')) {
			$htmlChildren .= '<li class="menu-category-description clearfix">' . $description;
			if (Mage::getStoreConfig('luxurysettings/navigation/show_learn_more')) {
				$htmlChildren .= '<p><button class="button" onclick="window.location=\'' . $this->getCategoryUrl($category) . '\'"><span><span>' . $this->__('learn more') . '</span></span></button></p>';
			}
			$htmlChildren .= '</li>';
		}

		if (!empty($htmlChildren)) {
			if ($childrenWrapClass) {
				$html[] = '<div class="' . $childrenWrapClass . '">';
			}
			$html[] = '<ul class="level' . $level . '">';
			$html[] = $htmlChildren;
			$html[] = '</ul>';
			if ($childrenWrapClass) {
				$html[] = '</div>';
			}
		}

		$html[] = '</li>';

		$html = implode("\n", $html);
		return $html;
	}


}