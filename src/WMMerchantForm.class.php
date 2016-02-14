<?php
/**
 * Class MerchantForm
 *
 * @author VSKut <me@vskut.ru>
 * @date
 */
class WMMerchantForm
{
    private $payee_purse;

    private $amount;
    private $description;
    private $payment_number;

    private $custom = array();

    private $send_form = false;

    private $merchant_url = 'https://merchant.webmoney.ru/lmi/payment.asp';

    private $result_url;
    private $success_url;
    private $success_method; # 0 - GET, 1 - POST, 2 - LINK
    private $fail_url;
    private $fail_method; # 0 - GET, 1 - POST, 2 - LINK

    /**
     * Constructor
     *
     * @param string $payee_purse
     */
    public function __construct($payee_purse) {

        $this->payee_purse = $payee_purse;

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
     * Set Description
     *
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set Payment Number
     *
     * @param int $payment_number
     */
    public function setPaymentNumber($payment_number)
    {
        $this->payment_number = $payment_number;
    }

    /**
     * Set auto sending form
     */
    public function setAutoSendForm()
    {
        $this->send_form = true;
    }

    /**
     * Set custom values
     *
     * @param array $array
     */
    public function setCustom($array)
    {
        $this->custom = $array;
    }

    /**
     * Set Result URL
     *
     * @param string $url
     */
    public function setResultUrl($url)
    {
        $this->result_url = $url;
    }

    /**
     * Set Success URL
     *
     * @param string $url
     * @param int $method
     */
    public function setSuccessUrl($url, $method = 1)
    {
        $this->success_url = $url;
        $this->success_method = $method;
    }

    /**
     * Set Fail URL
     *
     * @param string $url
     * @param int $method
     */
    public function setFailUrl($url, $method = 1)
    {
        $this->fail_url = $url;
        $this->fail_method = $method;
    }

    /**
     * Generate HTML form
     *
     * @return string
     */
    public function getHTML()
    {
        $html = '<form name="WMMerchant_form" method="post" action="'.$this->merchant_url.'">'.
                '<input type="hidden" name="LMI_PAYMENT_AMOUNT" value="'.floatval($this->amount).'">'.
                '<input type="hidden" name="LMI_PAYEE_PURSE" value="'.$this->payee_purse.'">'.
                '<input type="hidden" name="LMI_PAYMENT_DESC_BASE64" value="'.base64_encode($this->description).'">'.
                '<input type="hidden" name="LMI_PAYMENT_NO" value="' . $this->payment_number . '">';

        # Result URL
        if ($this->result_url) {
            $html.= '<input type="hidden" name="LMI_RESULT_URL" value="'.$this->result_url.'">';
        }
        # Success URL & Method
        if ($this->success_url) {
            $html.= '<input type="hidden" name="LMI_SUCCESS_URL" value="'.$this->success_url.'">'.
                    '<input type="hidden" name="LMI_SUCCESS_METHOD" value="'.$this->success_method.'">';
        }
        # Fail URL & Method
        if ($this->fail_url) {
            $html.= '<input type="hidden" name="LMI_FAIL_URL" value="'.$this->fail_url.'">'.
                    '<input type="hidden" name="LMI_FAIL_METHOD" value="'.$this->fail_method.'">';
        }
        # Custom fields
        foreach ($this->custom as $key => $value) {
            $html.= '<input type="hidden" name="CUSTOM_'.$key.'" value="'.$value.'">';
        }

        $html.= '<input type="submit" value="Submit">'.
                '</form>';

        if ($this->send_form) $html.= '<script>document.forms["WMMerchant_form"].submit()</script>';

        return $html;
    }



}