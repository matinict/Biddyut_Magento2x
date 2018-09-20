<?php

namespace Sslwireless\Address\Controller\Adminhtml\Importaddress;

class SampleCsv extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::import_address';

    public function execute()
    {
        if ($csvType = $this->getRequest()->getParam('code')){
            $fileDirectory = \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR;
            $fileName = 'sample_'.$csvType.'.csv';
            $filePath =  $this->directoryList->getPath($fileDirectory) . "/" . $fileName;

            $data = [];

            switch ($csvType) {
                case 'region':
                    $data[] = ['country_id','code','default_name'];
                    $data[] = ['ID','ID-BA','Bali'];
                    $data[] = ['ID','ID-BB','Bangka Belitung'];
                    break;
                case 'city':
                    $data[] = ['region_code','city_name'];
                    $data[] = ['ID-AC','Kota Banda Aceh'];
                    $data[] = ['ID-AC','Kota Langsa'];
                    break;
                case 'township':
                    $data[] = ['region','city','township','postcode'];
                    $data[] = ['Bali','Kab. Badung','Abiansemal','1234,2345'];
                    $data[] = ['Bali','Kab. Badung','Kuta','1234,2345,5678'];
                    break;
            }

            $this->csvProcessor->saveData($filePath ,$data);
            $this->fileFactory->create(
                $fileName,
                [
                    'type'  => "filename",
                    'value' => $fileName,
                    'rm'    => true,
                ],
                \Magento\Framework\App\Filesystem\DirectoryList::VAR_DIR,
                'text/csv',
                null
            );

            $resultRaw = $this->resultRawFactory->create();
            return $resultRaw;
        } else {
            $this->_redirect('*/*');
        }
    }
}
