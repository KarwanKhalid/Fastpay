<?php

namespace KarwanKhalid\Fastpay\Services;

class Fastpay extends AbstractFastpay
{
    protected $data = [];
    protected $config = [];

    private $successUrl;
    private $cancelUrl;
    private $failedUrl;
    private $error;

    /**
     * FastpayNotification constructor.
     */
    public function __construct()
    {
        $this->config = config('fastpay');

        $this->setStoreId($this->config['apiCredentials']['merchant_mobile_no']);
        $this->setStorePassword($this->config['apiCredentials']['store_password']);
    }

    public function orderValidate($trx_id = '', $amount = 0, $post_data)
    {
        if ($post_data == '' && $trx_id == '' && !is_array($post_data)) {
            $this->error = "Please provide valid transaction ID and post request data";
            return $this->error;
        }

        $validation = $this->validate($trx_id, $amount, $post_data);

        if ($validation) {
            return true;
        } else {
            return false;
        }
    }


    # VALIDATE Fastpay TRANSACTION
    protected function validate($merchant_trans_id, $merchant_trans_amount, $post_data)
    {
        # MERCHANT SYSTEM INFO
        if ($merchant_trans_id != "" && $merchant_trans_amount != 0) {

            # CALL THE FUNCTION TO CHECK THE RESULT
            $post_data['merchant_mobile_no'] = $this->getStoreId();
            $post_data['store_pass'] = $this->getStorePassword();


                $val_id = urlencode($post_data['val_id']);
                $merchant_mobile_no = urlencode($this->getStoreId());
                $store_passwd = urlencode($this->getStorePassword());
                $requested_url = ($this->config['apiDomain'] . $this->config['apiUrl']['order_validate'] . "?val_id=" . $val_id . "&merchant_mobile_no=" . $merchant_mobile_no . "&store_passwd=" . $store_passwd . "&v=1&format=json");

                $handle = curl_init();
                curl_setopt($handle, CURLOPT_URL, $requested_url);
                curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);

                 if ($this->config['connect_from_localhost']) {
                     curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
                     curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
                 } else {
                     curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, true);
                     curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, true);
                 }


                $result = curl_exec($handle);

                $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

                if ($code == 200 && !(curl_errno($handle))) {


                    # TO CONVERT AS OBJECT
                    $result = json_decode($result);
                    $this->fastp_data = $result;

                    # TRANSACTION INFO
                    $status = $result->status;
                    $tran_date = $result->tran_date;
                    $tran_id = $result->tran_id;
                    $val_id = $result->val_id;
                    $amount = $result->amount;


                    # GIVE SERVICE
                    if ($status == "VALID") {
                            if (trim($merchant_trans_id) == trim($tran_id) && (abs($merchant_trans_amount - $amount) < 1)) {
                                return true;
                            } else {
                                # DATA TEMPERED
                                $this->error = "Data has been tempered";
                                return false;
                            }
                        
                    } else {
                        # FAILED TRANSACTION
                        $this->error = "Failed Transaction";
                        return false;
                    }
                } else {
                    # Failed to connect with Fastpay
                    $this->error = "Faile to connect with Fastpay";
                    return false;
                }
           
        } else {
            # INVALID DATA
            $this->error = "Invalid data";
            return false;
        }
    }

  

    /**
     * @param array $requestData
     * @param string $type
     * @param string $pattern
     * @return false|mixed|string
     */
    public function makePayment(array $requestData, $type = 'checkout', $pattern = 'json')
    {
        if (empty($requestData)) {
            return "Please provide a valid information list about transaction with transaction id, amount, success url, fail url, cancel url, store id and pass at least";
        }


        $this->setApiUrl($this->config['apiDomain'] . $this->config['apiUrl']['make_payment']);

        // Set the required/additional params
        $this->setParams($requestData);

        // Set the authentication information
        $this->setAuthenticationInfo();

        // Now, call the Gateway API
        $response = $this->callToApi($this->data, $this->config['connect_from_localhost']);
       // return response()->json($this->fastp);

            if (isset($this->fastp['token']) && $this->fastp['code'] == '200') {
            # THERE ARE MANY WAYS TO REDIRECT - Javascript, Meta Tag or Php Header Redirect or Other
            # echo "<script>window.location.href = '". $fastp['token'] ."';</script>";
            echo "<meta http-equiv='refresh' content='0;url=".$this->config['apiDomain'] . $this->config['apiUrl']['extra'] . $this->fastp['token']."'>";
            # header("Location: ". $fastp['token']);
            } else {
                $errorMessage = "No redirect URL found!";
                return $errorMessage;
            }
        
    }


    protected function setSuccessUrl()
    {
        $this->successUrl = url('/') . $this->config['success_url'];
    }

    protected function getSuccessUrl()
    {
        return $this->successUrl;
    }

    protected function setFailedUrl()
    {
        $this->failedUrl = url('/') . $this->config['failed_url'];
    }

    protected function getFailedUrl()
    {
        return $this->failedUrl;
    }

    protected function setCancelUrl()
    {
        $this->cancelUrl = url('/') . $this->config['cancel_url'];
    }

    protected function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    public function setParams($requestData)
    {
        ##  Integration Required Parameters
        $this->setRequiredInfo($requestData);
    }

    public function setAuthenticationInfo()
    {
        $this->data['merchant_mobile_no'] = $this->getStoreId();
        $this->data['store_password'] = $this->getStorePassword();

        return $this->data;
    }

    public function setRequiredInfo(array $info)
    {
        $this->data['bill_amount'] = $info['total_amount']; // decimal (10,2)	Mandatory - The amount which will process by fastpay. It shall be decimal value (10,2). Example : 55.40. The transaction amount must be from 10.00 BDT to 500000.00 BDT
        $this->data['order_id'] = $info['tran_id']; // string (30)	Mandatory - Unique transaction ID to identify your order in both your end and fastpay

        // Set the SUCCESS, FAIL, CANCEL Redirect URL before setting the other parameters
        $this->setSuccessUrl();
        $this->setFailedUrl();
        $this->setCancelUrl();

        $this->data['success_url'] = $this->getSuccessUrl(); // string (255)	Mandatory - It is the callback URL of your website where user will redirect after successful payment (Length: 255)
        $this->data['fail_url'] = $this->getFailedUrl(); // string (255)	Mandatory - It is the callback URL of your website where user will redirect after any failure occure during payment (Length: 255)
        $this->data['cancel_url'] = $this->getCancelUrl(); // string (255)	Mandatory - It is the callback URL of your website where user will redirect if user canceled the transaction (Length: 255)
        return $this->data;
    }

   


}