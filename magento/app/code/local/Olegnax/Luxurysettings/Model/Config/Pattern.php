<?php
/**
 * @version   1.0 12.0.2012
 * @author    Olegnax http://www.olegnax.com <mail@olegnax.com>
 * @copyright Copyright (C) 2010 - 2012 Olegnax
 */

class Olegnax_Luxurysettings_Model_Config_Pattern
{
	/**
	 * pattern list separated by comma
	 *
	 * @var string
	 */
	private $gfonts447 = "pattern1,pattern1_white,pattern2,pattern2_white,pattern3,pattern3_white,pattern4,pattern4_white,pattern5,pattern5_white,pattern6,pattern6_white,pattern7,pattern7_white,pattern8,pattern8_white,pattern9,pattern9_white,pattern10,pattern10_white";

    public function toOptionArray()
    {
	    $fonts = explode(',', $this->gfonts447);
	    $options = array(array(
            'value' => '',
            'label' => '- none -',
        ));
	    foreach ($fonts as $f ){
		    $options[] = array(
			    'value' => $f,
			    'label' => $f,
		    );
	    }

        return $options;
    }

}
