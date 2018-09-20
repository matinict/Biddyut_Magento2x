<?php
namespace Sslwireless\Biddyut\Model\Query;

//use Magento\Framework\HTTP\ZendClientFactory;
use Magento\Framework\HTTP\ClientFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\HTTP\ZendClient;

class Api{
    const CITY_KAB = 'Kabupaten';
    const CITY_KOT = 'Kota';

    protected $_httpClient;

    protected $clientFactory;


    public function __construct( \Magento\Framework\HTTP\ZendClientFactory $clientFactory){

       $this->clientFactory = $clientFactory;
   }

    public function apiCaller($url, $method, $params, $header = null){

        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $apiCaller = $this->clientFactory->create();
        $apiCaller->setUri($url);
        $apiCaller->setMethod($method);
        // $apiCaller->setMethod($params);
        $apiCaller->setHeaders([
          'Content-Type: application/x-www-form-urlencoded',
          'Accept: application/json',
          'Key: '.$header
        ]);
        // $client = $this->_httpClientFactory->create();
        // $client->setUri($url);
        // $client->setConfig(['maxredirects' => 0, 'timeout' => 30]);
        // $result = $client->request(\Zend_Http_Client::GET)->getBody();
        $apiCaller->setParameterPost($params); //or parameter get
        // echo '<pre>'; print_r($apiCaller->request()); echo '</pre>'; die();
        return $apiCaller->request();
    }

    public function getBiddyut($storeid,$origin,$destination,$category,$quantity,$grandTotal,$width,$height,$length,$weight) {
          $params = [
              'store_id' => $storeid,
              'width' => $width,
              'height' => $height,
              'length' =>$length,
              'weight' => $weight,
              'product_category' => $category,
              'pickup_location' => $origin,
              'delivery_zone' => $destination,
              'quantity' => $quantity,
              'unit_price' => $grandTotal
          ];
          //$apiCaller = $this->_httpClient->create();
          $biddyut = $this->apiCaller('http://dev.logita.fastbazzar.com/api/v2/charge-calculator', \Zend_Http_Client::POST, $params);
          $biddyutBody = json_decode($biddyut->getBody());
          if(!empty($biddyutBody[0]->product_delivery_charge)){
            return $biddyutBody[0]->product_delivery_charge;
          }else{
            return 0;
          }

          // if ($biddyut->biddyut-->status->code == 200) {
          //     return $biddyut->biddyut-->results;
          // } else {
          //     return 0;
          // }
    }

    public function getProvince()
    {
        //
        //
    }

    public function getCity()
    {
        //
    }
}
