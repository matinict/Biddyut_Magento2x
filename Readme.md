
Magento 2 Biddyut Shipping (Sslwireless_Biddyut &  Sslwireless_Address)
=======================================

Goals:
=======================================

- Biddyut/iPost(A Technology Driven Logistics & Delivery Company Bangladesh/Kurdistan Magento 2.x
- Custom Shipping Module For Magento 2 Between Magento 2 Store & Carrier Site .
- Shipping Price Calculate According To Biddyut/iPost Rate Via API To Your Magento 2x Store That Finaly Show Shipping Cost.
- Order Details With Previous Shipping Cost Submit To Biddyut/iPost Via API.
- Logistics Collect Product From Pickup Location & Delivery To Destination/Customer .
- Customer Address Customized as Country->Region/State/Division->City->Township/Zone

Tested: Magento 2.2.3


Installation
-------------


**Using Zip**
* Download the [Zip File](https://github.com/sslcommerz/Biddyut_Magento2x/archive/master.zip)
* Extract & upload the files to `/path/to/magento2/app/code/Sslwireless/`

After installation by either means, enable the extension by running following commands (again from root of Magento2 installation):
```


-- Download Code and past to app/code/Sslwireless/ Directory via FTP.

      Run Command as below:

      //php bin/magento setup:upgrade --keep-generated

      php bin/magento setup:upgrade  

      php bin/magento indexer:reindex

      php bin/magento cache:flush

      php bin/magento cache:clean

      sudo chmod 777 -R generated/*  var/* pub/*

      rm var/cache/*  generated/code/* var/view_preprocessed/* var/page_cache/*  pub/static/frontend/* pub/static/adminhtml/* -R

-- More Details please check doc folder

    https://github.com/sslcommerz/Biddyut_Magento2x/blob/master/doc/Magento2x_Biddyut_Doc.docx?raw=true

-- magento2-Biddyut- Sslwireless_Biddyut

-- magento2-address- Sslwireless_Address



## magento2-Biddyut- Sslwireless_Biddyut
=======================================

 -Shipping Charge Calculator via API
 -Dependency module  SSLWireless Address(https://github.com/sslcommerz)



Refarance:
-------------

https://www.magestore.com/magento-2-tutorial/magento-2-modules/

https://cedcommerce.com/magento-2-module-creator/shipping-module

https://github.com/cedcommerce/magento-2-sample-module

https://ranasohel.me/2015/11/28/how-to-add-custom-field-to-shipping-address-form-in-magento-2-onepage-checkout/

https://devdocs.magento.com/guides/v2.2/extension-dev-guide/events-and-observers.html

https://www.mageplaza.com/magento-2-module-development/magento-2-events.html

https://marketplace.magento.com/maurisource-shipstation-liverate.html

https://www.magecloud.net/marketplace/extension/shipping-agent-for-magento/

https://www.iwdagency.com/extensions/order-manager-m2.html

https://magehit.com/blog/how-to-use-rest-api-in-magento-2/

http://www.webnexs.com/blog/kb/get-value-custom-attribute-magento-2-rest-api/

https://github.com/baddwin/magento2-ongkir

https://github.com/netresearch/dhl-module-shipping-m2

https://github.com/sohelrana09/magento2-module-checkoutadditionalfield

https://github.com/vincent2090311/magento2-address

https://github.com/sohelrana09/module-auto-invoice-shipment




Development Team:
-------------
 * @Biddyut/iPost(A technology driven Logistics & delivery company Bangladesh/) Magento 2.x
 * @Package       Biddyut Limited
 * @Developer     Abdul Matin <matinict@gmail.com>
 * @Author        Sslwireless(https://github.com/sslcommerz)
 * @Vendor    SSLWireless Address(https://github.com/sslcommerz)

---
