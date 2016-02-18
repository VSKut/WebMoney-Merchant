<?php
namespace VSKut\WebMoney_Merchant;

/**
 * Class MerchantCallback
 *
 * @author VSKut <me@vskut.ru>
 */
class WMMerchantCallback
{
    private $payee_purse;
    private $secret_key;

    private $amount;

    private $data = array();
    private $data_custom = array();

    private $pre_request = false;

    private $error = false;
    private $error_msg = '';

    private $pre_request_params = array(
        'LMI_PAYMENT_AMOUNT',
        'LMI_PAYMENT_NO',
        'LMI_PAYEE_PURSE',
        'LMI_PAYER_WM',
        'LMI_PAYER_PURSE',
    );

    private $finally_request_params = array(
        'LMI_SYS_INVS_NO',
        'LMI_SYS_TRANS_NO',
        'LMI_SYS_TRANS_DATE',
        'LMI_HASH',
        'LMI_MODE',
    );

    /**
     * Constructor
     *
     * @param string $payee_purse
     * @param string $secret_key
     * @param array $data
     */
    public function __construct($payee_purse, $secret_key, $data) {
        $this->payee_purse = $payee_purse;
        $this->secret_key = $secret_key;
        $this->data = $data;

        foreach ($data as $key => $value) {
            if (substr($key, 0, 7) == 'CUSTOM_') {
                $this->data_custom[ str_replace('CUSTOM_', '', $key) ] = $value;
            }
        }
    }

    /**
     * Set Amount
     *
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * Get Data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get Data custom
     *
     * @return array
     */
    public function getDataCustom()
    {
        return $this->data_custom;
    }

    /**
     * @return bool|string
     */
    public function getError()
    {
        if ($this->error) {
            return $this->error_msg;
        } else {
            return false;
        }
    }

    /**
     *
     *
     * @return bool
     */
    public function isPreRequest()
    {
        return $this->pre_request;
    }

    /**
     * Check data for invalid
     *
     * @return bool
     */
    public function isInvalidData()
    {
        foreach ($this->pre_request_params as $key => $value) {
            if (empty($this->data[$value])) {
                $this->error = true;
                $this->error_msg = 'EMPTY:'.$value;
                return true;
            }
        }

        # checking user WM purse
        if ($this->data['LMI_PAYEE_PURSE'] != $this->payee_purse) {
            $this->error = true;
            $this->error_msg = 'INCORRECT:LMI_PAYEE_PURSE';
            return true;
        }

        # checking the SUM if it is set
        if (!empty($this->amount) && $this->amount != $this->data['LMI_PAYMENT_AMOUNT']) {
            $this->error = true;
            $this->error_msg = 'INCORRECT:LMI_PAYMENT_AMOUNT';
            return true;
        }

        # abort checking if it is pre-request
        if (!empty($this->data['LMI_PREREQUEST']) && $this->data['LMI_PREREQUEST'] == 1) {
            $this->pre_request = true;
            return false;
        }

        # checking data from finally request
        foreach ($this->finally_request_params as $key => $value) {
            if (empty($this->data[$value])) {
                $this->error = true;
                $this->error_msg = 'EMPTY:'.$value;
                return true;
            }
        }

        # checking hash
        if ($this->data['LMI_HASH'] != $this->generateHASH()) {
            $this->error = true;
            $this->error_msg = 'INCORRECT:LMI_HASH';
            return true;
        }

        return false;
    }

    /**
     * Generating HASH to checking
     *
     * @return string
     */
    private function generateHASH()
    {
        $string = $this->payee_purse.
            number_format($this->data['LMI_PAYMENT_AMOUNT'], 2, '.', '').
            $this->data['LMI_PAYMENT_NO'].
            $this->data['LMI_MODE'].
            $this->data['LMI_SYS_INVS_NO'].
            $this->data['LMI_SYS_TRANS_NO'].
            $this->data['LMI_SYS_TRANS_DATE'].
            $this->secret_key.
            $this->data['LMI_PAYER_PURSE'].
            $this->data['LMI_PAYER_WM'];
        return strtoupper(hash("SHA256",$string));
    }



}