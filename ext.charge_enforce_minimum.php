<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * ExpressionEngine - by EllisLab
 *
 * @package     ExpressionEngine
 * @author      ExpressionEngine Dev Team
 * @copyright   Copyright (c) 2003 - 2011, EllisLab, Inc.
 * @license     http://expressionengine.com/user_guide/license.html
 * @link        http://expressionengine.com
 * @since       Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Charge Enforce Minimum Extension
 *
 * @package     ExpressionEngine
 * @subpackage  Addons
 * @category    Extension
 * @author      Nathan Pitman
 * @link        http://www.nathanpitman.com
 */

class Charge_enforce_minimum_ext {

    public $settings        = array();
    public $description     = "Enforces a minimum charge amount";
    public $docs_url        = 'http://github.com/nathanpitman/';
    public $name            = 'Charge Enforce Minimum';
    public $settings_exist  = 'n';
    public $version         = '1.0';

    private $EE;

    /**
     * Constructor
     *
     * @param   mixed   Settings array or empty string if none exist.
     */
    public function __construct($settings = '')
    {
        $this->EE =& get_instance();
        $this->settings = $settings;
    }// ----------------------------------------------------------------------

    /**
     * Activate Extension
     *
     * This function enters the extension into the exp_extensions table
     *
     * @see http://codeigniter.com/user_guide/database/index.html for
     * more information on the db class.
     *
     * @return void
     */
    public function activate_extension()
    {
        // Setup custom settings in this array.
        $this->settings = array();

        $data = array(
            'class'     => __CLASS__,
            'method'    => 'charge_enforce_minimum',
            'hook'      => 'charge_pre_payment',
            'settings'  => serialize($this->settings),
            'version'   => $this->version,
            'enabled'   => 'y'
        );

        $this->EE->db->insert('extensions', $data);

        // No hooks selected, add in your own hooks installation code here.
    }

    // ----------------------------------------------------------------------

    /**
     * charge_enforce_minimum
     *
     * @param
     * @return
     */
    public function charge_enforce_minimum(&$chargeObj)
    {

        $min_donation = 10;
        $min_donation_cents = (int)$min_donation*100;

        if(isset($chargeObj->data['plan']['amount'])
            AND ($chargeObj->data['plan']['amount']<$membership_min_donation_cents)
            ) {
            $amount = $chargeObj->data['plan']['amount'];
            $chargeObj->errors['plan_amount'] = 'Amount must be equal to or greater than £'.$min_donation;
        }

        return;

    }

    // ----------------------------------------------------------------------

    /**
     * Disable Extension
     *
     * This method removes information from the exp_extensions table
     *
     * @return void
     */
    function disable_extension()
    {
        $this->EE->db->where('class', __CLASS__);
        $this->EE->db->delete('extensions');
    }

    // ----------------------------------------------------------------------

    /**
     * Update Extension
     *
     * This function performs any necessary db updates when the extension
     * page is visited
     *
     * @return  mixed   void on update / false if none
     */
    function update_extension($current = '')
    {
        if ($current == '' OR $current == $this->version)
        {
            return FALSE;
        }
    }

    // ----------------------------------------------------------------------
}

/* End of file ext.charge_enforce_minimum.php */
/* Location: /system/expressionengine/third_party/charge_enforce_minimum/ext.charge_enforce_minimum.php */