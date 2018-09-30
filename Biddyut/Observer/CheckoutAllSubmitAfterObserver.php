<?php
/**
 * Biddyut(A technology driven Logistics & delivery company Bangladesh) Magento 2.x
 *
 * @Package    Biddyut Limited
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 * @Dependency    SSLWireless Address(https://github.com/sslcommerz)
 */
namespace Sslwireless\Biddyut\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Cart as CustomerCart;

class CheckoutAllSubmitAfterObserver implements ObserverInterface {
    /**
     *
     * @var \Sslwireless\Biddyut\Helper\Data
     */
    protected $helper;
    protected $_checkoutSession;
    protected $cart;
    protected $_shippingConfig;
    protected $scopeConfig;

     const storeid = 'carriers/biddyut/storeid';
     const passwd = 'carriers/biddyut/passwd';
     const key = 'carriers/biddyut/key';
     const origin = 'carriers/biddyut/origin';

    /**
     * @param \Sslwireless\Biddyut\Helper\Data $helper
     */
    public function __construct(
        \Sslwireless\Biddyut\Helper\Data $helper,
        \Magento\Checkout\Model\Session $checkoutSession,
        CustomerCart $cart,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->helper = $helper;
        $this->_checkoutSession = $checkoutSession;
        $this->cart = $cart;
        $this->_shippingConfig=$shippingConfig;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     *
     * @param Observer $observer
     * @return $this
     */


    public function execute(Observer $observer) {

          if(!$this->helper->isEnabled()) {
              return $this;
          }
            /// get quote items array
            $items = $this->cart->getQuote()->getAllItems();

            // foreach($items as $item) {
            //   \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(json_encode($item->getName()).'=='.$item->getPrice().' Fuck Data Here' );
            //
            // }
            //

          //Shipping Methods $storeid  $passwd $key $origin
         $courier= $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
          //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(json_encode($courier).'fds Here' );
          if($courier=="biddyut_biddyut"){
              //Get Courier/Shipping Methods Data $storeid  $passwd $key $origin
              $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
              $storeid = $this->scopeConfig->getValue(self::storeid, $storeScope);
              $passwd = $this->scopeConfig->getValue(self::passwd, $storeScope);
              $key = $this->scopeConfig->getValue(self::key, $storeScope);
              $origin = $this->scopeConfig->getValue(self::origin, $storeScope);


              $order = $observer->getEvent()->getOrder();
              $orderid=sprintf('%09d',$order->getIncrementId());
              //Check Login or not
              $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
              $customerSession = $objectManager->create('Magento\Customer\Model\Session');
              if ($customerSession->isLoggedIn()) {
                  $delivery_name= $customerSession->getCustomer()->getName();  // get  Full Name
                  $delivery_email= $customerSession->getCustomer()->getEmail(); // get Email
              }
              else {
                //Guest Checkout
                //$delivery_name=$order->getCustomerName();
                //Guest Checkout getFirstName
                $firstName=$this->_checkoutSession->getQuote()->getShippingAddress()->getFirstName();
                $lastName=$this->_checkoutSession->getQuote()->getShippingAddress()->getLastName();
                $delivery_name=$firstName.' '.$lastName;
                $delivery_email=$order->getCustomerEmail();
              }

              //Session Data
            //$fuckData = $this->_checkoutSession->getQuote()->getShippingAddress()->getData();
            //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(json_encode($fuckData).' Fuck Data Here' );


              $region= $this->_checkoutSession->getQuote()->getShippingAddress()->getRegion();//Fore Zone
              $city= $this->_checkoutSession->getQuote()->getShippingAddress()->getCity();//For city
              $street= $this->_checkoutSession->getQuote()->getShippingAddress()->getStreet();//
              $township= $this->_checkoutSession->getQuote()->getShippingAddress()->getTownship();//Zone
              $address=$street[0];//.', '.$street[1];
              $telephone= $this->_checkoutSession->getQuote()->getShippingAddress()->getTelephone();

              //Cart Data
              //$subtotal = $this->cart->getQuote()->getTotals();
              //$total=$subtotal['subtotal']['value'];
              $grandTotal = intval($this->cart->getQuote()->getBaseSubtotal());
            //  $itemsCollection = $this->cart->getQuote()->getItemsCollection();
            // \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(' Final grandTotal2== '.json_encode($grandTotal));

              $destination=$township.','.$city; //'Demo Zone 2,Al-Rutba';

              //API Data
              $destination=$township.','.$city; //'Demo Zone 2,Al-Rutba';
              $category ='Bulk Product';
              $quantity=1;// Bulk Product Always 1
              $width = '0';
              $height = '0';
              $length = '0';
             // $weight = '5';
              $weight =$this->getTotalWeight();
             // \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('Final Weight-->'.$weight );

              //Extra SDO_DAS_DataObject
              $product_titles="BulkProductPack-".$orderid;
              $api_token=$this->getApiToken($storeid,$passwd,$key);

              //Api Call Here For Order
              $curl = curl_init();
              curl_setopt_array($curl, array(
              CURLOPT_URL => "http://dev.logita.fastbazzar.com/api/v2/merchant/submit-order",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
              name=\"api_token\"\r\n\r\n$api_token\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
              name=\"store_id\"\r\n\r\n$storeid\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
              name=\"orders\"\r\n\r\n[{
                  \"delivery_name\":\"$delivery_name\",
                  \"delivery_email\":\"$delivery_email\",
                  \"delivery_msisdn\":\"$telephone\",
                  \"delivery_zone\":\"$destination\",
                  \"delivery_address\":\"$address\",
                  \"merchant_order_id\":\"$orderid\",
                  \"as_package\":0,
                  \"picking_date\":\"2018-02-20\",
                  \"pickup_location\":\"$origin\",
                  \"products\":[{
                  \"product_title\":\"$product_titles\",
                  \"url\":\"\",
                  \"product_category\":\"$category\",
                  \"unit_price\":$grandTotal,
                  \"quantity\":$quantity,
                  \"width\":$width,
                  \"height\":$height,
                  \"length\":$length,
                  \"weight\":$weight,
                  \"pickup_location\":\"$origin\",
                  \"picking_date\":\"2018-02-20\"}],
                  \"delivery_pay_by_cus\":\"1\",
                  \"verified\":1
              }]\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
              CURLOPT_HTTPHEADER => array(
              "cache-control: no-cache",
              "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW"
              ),
              ));

              $response = curl_exec($curl);

              $err = curl_error($curl);
              curl_close($curl);
              
              // if ($err) {
              //   echo "cURL Error #:" . $err;
              // } else {
              //   echo $response;
              // }
              // exit;

              // End Shipping Order

              //echo "<pre>"; var_dump($order->getId());    exit;

              // if(!$order->getId()) {
              //
              //
              //
              //
              //     return $this;
              // }

              $invoice = $this->helper->createInvoice($order);
              if($invoice) {
              $this->helper->createShipment($order, $invoice);
              }
              return $this;
        }

    }


    public function getApiToken($storeid,$passwd,$key) {
        $curl = curl_init();
         curl_setopt_array($curl, array(
         CURLOPT_URL => "http://dev.logita.fastbazzar.com/api/v2/merchant/login",
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => "",
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 30,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => "POST",
         CURLOPT_POSTFIELDS => "------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
           name=\"store_user\"\r\n\r\n$storeid\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
           name=\"store_password\"\r\n\r\n$passwd\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW\r\nContent-Disposition: form-data;
            name=\"key\"\r\n\r\n$key\r\n------WebKitFormBoundary7MA4YWxkTrZu0gW--",
         CURLOPT_HTTPHEADER => array(
           "cache-control: no-cache",
           "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
           "postman-token: 4d659223-228e-40c6-8823-dfb70bb25c20"
         ),
         ));

         $response = curl_exec($curl);
         $err = curl_error($curl);
         curl_close($curl);
         $apiData = json_decode($response);
         $apiToken=$apiData->response->api_token;
       return $apiToken;
   }
    
    public function getTotalWeight(){
       $items = $this->cart->getQuote()->getAllItems();
       $weight = 0;
       foreach($items as $item) {
           $weight += ($item->getWeight() * $item->getQty()) ;
       }
        return $weight/1000; //Gm to Kg
     }


}
