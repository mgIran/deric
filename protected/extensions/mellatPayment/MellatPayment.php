<?php

/**
 * Extension to Payment  using Mellat Bank accounts
 * Based on Mohammad Amini code <http://www.yiiframework.com/user/99958> for yii-MellatPayment
 *
 * @author faravaghi <faravaghi@gmail.com>
 * @license MIT
 * @version 1.0.1
 */

/**
 * How to use
 *
 * You at least need PHP 5.3+ adn cURL.
 *
 * Change your config/main.php to add a new component:
 *
 * 'components' => array
 *(
 *     'Payment' => array
 *    (
 *         'class'    		=> 'ext.MellatPayment.MellatPayment',
 *         'terminalId'     => 'user to login',
 *         'userName'     	=> 'user to login',
 *         'userPassword'   => 'password to login',	// password to web login
 *     )
 * )
 *
 */
class MellatPayment extends CApplicationComponent
{
	const REQUEST_SUCCESS = true;
	const REQUEST_ERROR = false;

	/**
	 * Terminal Id
	 * @var string
	 */
	public $terminalId;

	/**
	 * User Name
	 * @var string
	 */
	public $userName;

	/**
	 * User Password
	 * @var string
	 */
	public $userPassword;

	/**
	 * Order Id
	 * @var long
	 */
	public $orderId;

	/**
	 * Amount
	 * @var long
	 */
	public $amount;

	/**
	 * Local Date
	 * @var long
	 */
	public $localDate;

	/**
	 * Local Time
	 * @var long
	 */
	public $localTime;

	/**
	 * Additional Data
	 * @var long
	 */
	public $additionalData;

	/**
	 * Call Back Url
	 * @var string
	 */
	public $callBackUrl;

	/**
	 * payerId
	 * @var long
	 */
	public $payerId;

	/**
	 * saleOrderId
	 * @var long
	 */
	public $saleOrderId;

	/**
	 * saleReferenceId
	 * @var long
	 */
	public $saleReferenceId;

	/**
	 * If you want to use SSL(HTTPS)
	 * @var boolean
	 */
	public $useSSL = false; //not working by now

	/**
	 * Api URL
	 * Api Url to call without 'http'
	 * @var string
	 */
	public $url = 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';

	/**
	 * Api URL
	 * Api Url to call without 'http'
	 * @var string
	 */
	public $urlPay = 'https://bpm.shaparak.ir/pgwchannel/startpay.mellat';

	/**
	 * namespace
	 * @var string
	 */
	public $namespace='http://interfaces.core.sw.bps.com/';

	/**
	 * Server Response CODE
	 * @var array
	 */
	protected $responseCode;

	/**
	 * Server Response CODE
	 * @var array
	 */
	protected $client;

	protected $errorCodes = array(
		'11' => 'شماره كارت نامعتبر است',
		'12' => 'موجودي كافي نيست',
		'13' => 'رمز نادرست است',
		'14' => 'تعداد دفعات وارد كردن رمز بيش از حد مجاز است',
		'15' => 'كارت نامعتبر است',
		'16' => 'دفعات برداشت وجه بيش از حد مجاز است',
		'17' => 'كاربر از انجام تراكنش منصرف شده است',
		'18' => 'تاريخ انقضاي كارت گذشته است',
		'19' => 'مبلغ برداشت وجه بيش از حد مجاز است',
		'111' => 'صادر كننده كارت نامعتبر است',
		'112' => 'خطاي سوييچ صادر كننده كارت',
		'113' => 'پاسخي از صادر كننده كارت دريافت نشد',
		'114' => 'دارنده كارت مجاز به انجام اين تراكنش نيست',
		'21' => 'پذيرنده نامعتبر است',
		'23' => 'خطاي امنيتي رخ داده است',
		'24' => 'اطلاعات كاربري پذيرنده نامعتبر است',
		'25' => 'مبلغ نامعتبر است',
		'31' => 'پاسخ نامعتبر است',
		'32' => 'فرمت اطلاعات وارد شده صحيح نمي باشد',
		'33' => 'حساب نامعتبر است',
		'34' => 'خطاي سيستمي',
		'35' => 'تاريخ نامعتبر است',
		'41' => 'شماره درخواست تكراري است',
		'42' => 'تراکنش Sale یافت نشد',
		'43' => 'قبلا درخواست Verify داده شده است',
		'44' => 'درخواست Verify يافت نشد',
		'45' => ' تراكنش Settle شده است',
		'46' => 'تراكنش Settle نشده است',
		'47' => ' تراكنش Settle يافت نشد',
		'48' => ' تراكنشReverse شده است',
		'49' => 'تراكنشRefund يافت نشد ',
		'412' => 'شناسه قبض نادرست است',
		'413' => 'شناسه پرداخت نادرست است',
		'414' => 'سازمان صادر كننده قبض نامعتبر است',
		'415' => 'زمان جلسه كاري به پايان رسيده است',
		'416' => 'خطا در ثبت اطلاعات',
		'417' => 'شناسه پرداخت كننده نامعتبر است',
		'418' => 'اشكال در تعريف اطلاعات مشتري',
		'419' => 'تعداد دفعات ورود اطلاعات از حد مجاز گذشته است',
		'421' => 'IP نامعتبر است',
		'51' => 'تراكنش تكراري است',
		'54' => 'تراكنش مرجع موجود نيست',
		'55' => 'تراكنش نامعتبر است',
		'61' => 'خطا در واريز',
	);

	/**
	 * Initialization
	 *
	 */
	public function init( )

    {
        $option = CJSON::decode(SiteSetting::getOption('gateway_variables'),false);
        $this->terminalId = $option && $option->terminalId?$option->terminalId:$this->terminalId;
        $this->userName = $option && $option->userName?$option->userName:$this->userName;
        $this->userPassword = $option && $option->userPassword?$option->userPassword:$this->userPassword;
        if(!$this->terminalId || !$this->userName || !$this->userPassword)
            die('Mellat Terminal Id or Username or Password is not defined in admin gateway setting or main.php file.');
        require(dirname(__FILE__) . "/lib/nusoap.php");
        $this->client = new nusoap_client($this->url, 'wsdl', '', '', '', '');
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = false;
        if ($this->client->fault) {
            trigger_error("SOAP Fault: (faultcode: {$result->faultcode}, faultstring: {$result->faulstring})", E_ERROR);
        } else {
            $error = $this->client->getError();
            if ($error) {
                throw new CException($error);
            }
        }

        parent::init( );

        Yii::trace( 'Extension initializating', 'MellatPayment' );
    }
    /**
	 * List of API calls
	 *
	 * @return array
	 */
	protected function calls( )
	{
		return array
		(
			'PayRequest'  => array(
				'required' => array(
					'amount',
					'orderId',
					'callBackUrl'
				),
				'fixed' => array(
					'localDate'=>date('Ymd'),
					'localTime'=>date('His'),
				),
				'optional' => array(
					'payerId',
					'additionalData',
				),
				'type'=>array(
					'integer',
					'string',
					'integer',
					'string',
					'string',
					'integer',
					'string'
				)
			),
			'VerifyRequest' => array(
				'required' => array(
					'orderId',
					'saleOrderId',
					'saleReferenceId'
				),
				'type'=>array(
					'integer',
					'integer',
					'integer'
				)
			),
			'SettleRequest' => array(
				'required' => array(
					'orderId',
					'saleOrderId',
					'saleReferenceId'
				),
				'type'=>array(
					'integer',
					'integer',
					'integer'
				)
			),
			
			'InquiryRequest' => array(
				'required' => array(
					'orderId',
					'saleOrderId',
					'saleReferenceId'
				),
				'type'=>array(
					'integer',
					'integer',
					'integer'
				)
			),

			'ReversalRequest' => array(
				'required' => array(
					'orderId',
					'saleOrderId',
					'saleReferenceId'
				),
				'type'=>array(
					'integer',
					'integer',
					'integer'
				)
			),
			
		);
	}

	/**
	 * Process API calls
	 *
	 * @param string $call Call name in camelCase
	 * @param array $args Arguments to convert the API call array
	 * @return mixed Response to API call in accordance with responseCode and type MellatPayment.call()
	 */
	public function __call( $call, $args )
	{
		$calls = $this->calls();
		// call beautifer to accomply API methods
		//$callName = preg_replace_callback( '#[A-Z]#', function( $a ) { return '/' . mb_convert_case( $a[0], MB_CASE_LOWER ); }, $call );
		$callName = $call;

		if( ! isset( $calls[$callName] ) )
			throw new CException( 'Unknown call' );

		$params = $calls[$callName];

		$params['required'] = isset( $params['required'] ) ?(array) $params['required'] : array();
		$params['optional'] = isset( $params['optional'] ) ?(array) $params['optional'] : array();
		$params['fixed'] = isset( $params['fixed'] ) ? $params['fixed'] : array();
		$params['type'] = isset( $params['type'] ) ? $params['type'] : array();

		$args = $this->populate( $args, $params['required'], $params['optional'], $params['type'] );
		$args = array_merge( $args, $params['fixed'] );
		$response = $this->request( $callName, $args );
		try {
			if(!isset($response['return'])){
				return array(
					'error'=>true,
					'responseCode'=>99,
					'responseText'=>Yii::t('MellatPayment.rahbod', 99),
				);
			}
			$result = explode(',', $response['return']);
			$outPut = NULL;

			if($result[0] == 0)
			{
				return array(
					'error'=>false,
					'responseCode'=>($callName == 'PayRequest' ? $result[1] : $result[0]),
					'responseText'=>Yii::t('MellatPayment.rahbod', $result[0]),
				);
			}
			else
			{
				return array(
					'error'=>true,
					'responseCode'=>$result[0],
					'responseText'=>Yii::t('MellatPayment.rahbod', $result[0]),
				);
			}

			if( isset( $params['returnType'] ) )
				if( ! settype( $response['return'], $params['returnType'] ) )
					return array(
						'error'=>true,
						'responseCode'=>-1,
						'responseText'=>'Type is not supported',
					);

		} catch (Exception $e) {
			return array(
				'error'=>true,
				'responseCode'=>-1,
				'responseText'=>'Key not found',
			);
		}
	}

	/*
	* Formation of an array of call parameters of the arguments
	* @ Param mixed $ args The arguments passed to the method call
	* @ Param array $ required array parameters required
	* @ Param array $ optional array of additional parameters
	* @ Return array An array of call parameters
	**/
	protected function populate( $args, $required, $optional, $type )
	{
		$allParams = array_merge( $required, $optional );

		// Special case
		if( count( $args ) === 1 )
			// Check if the first argument is an array
			if( is_array( $args[0] ) )
				// ...And if all keys - line
				if( array_reduce( array_keys( $args[0] ), function( $prev, $curr ) { return $prev && is_string( $curr ); }, true ) )
				{
					foreach( $required as $requiredParam )
						if( ! isset( $args[0][$requiredParam] ) )
							throw new CException( 'It lacks some parameters' );

					return $args[0];
				}

		if( count( $args ) < count( $required ) )
			throw new CException( 'It lacks some required parameteres' );

		if( count( $args ) > count( $allParams ) )
			throw new CException( 'Too much parameteres' );

		$params = array( );

		foreach( $args as $id => $arg ){
			if(isset($tpype[$id]) && $type[$id] != 'string')
				$params[$allParams[$id]] = settype($arg, $type[$id]);
			else
				$params[$allParams[$id]] = $arg;
		}

		return $params;
	}

	/**
	 * API Call
	 * @param string $call name to call
	 * @param array $params call parameteres
	 * @return array server response
	 */
	protected function request( $call, array $params = array( ) )
	{
		//$params = array_map( function( $val ) { return join( ',',(array) $val ); }, $params );
		$params = array_merge( array( 'terminalId' => $this->terminalId, 'userName' => $this->userName, 'userPassword' => $this->userPassword ) ,$params);
		$callResponse = $this->client->call('bp'.$call, $params);

		return $callResponse;
	}

	/**
	 * Return the server response to the last request
	 * @return array
	 */
	public function getResponseCode()
	{
		return $this->responseCode;
	}

	/**
	 * Return the server response text to the last request
	 * @return string
	 */
	public function getResponseText($code)
	{
		return Yii::t('MellatPayment.rahbod', $code);
	}

	/**
	 * Return the server response to the last request
	 * @return array
	 */
	public function Transfer($reference)
	{
		print '<form name="MellatPayment" method="post" action="https://bpm.shaparak.ir/pgwchannel/startpay.mellat">
			<input type="hidden" name="RefId"    value="'.$reference.'" />
			<center>
			<noscript>
			<p>'.Yii::t('rahbod', 'After 5 seconds if you\'re not connected to the port, banking, click the button below.').'</p>
			<input type="submit" value="'.Yii::t('rahbod', 'Payment').'" class="btn btn-success" />
			</noscript>
			</center>
			</form>
			<script>document.MellatPayment.submit();</script>';
		
		exit();
	}

	public function getError($errorCode)
	{
		if(isset($this->errorCodes[$errorCode]))
			return $this->errorCodes[$errorCode];
		else
			return false;
	}

	public function getRedirectUrl()
	{
        return Yii::app()->createAbsoluteUrl('/site/mellatRedirect');
	}
}
