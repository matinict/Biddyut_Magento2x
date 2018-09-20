<?php
/**
 * Copyright © 2017 Sslwireless. All rights reserved.
 */

namespace Sslwireless\Address\Controller\Adminhtml\Importaddress;

class Save extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::import_address';

    public function execute()
    {
        if ($postdata = $this->getRequest()->getPostValue()){
            try {
                $connection = $this->resource->getConnection();
                $regionTable = $this->resource->getTableName('directory_country_region');
                $select = $connection->select()->from(['dcr' => $regionTable], ['region_id','code'])->where('country_id = ?', $postdata['country_id']);
                $country_region = $connection->fetchAll($select);
                $regions = [];
                foreach ($country_region as $reg) {
                    $regions[$reg['code']] = $reg['region_id'];
                }
                $import_file = $this->getRequest()->getFiles('import_file');
                if (!empty($import_file['tmp_name'])) {
                    $importRawData = iterator_to_array($this->_prepareData($import_file['tmp_name']));
                    switch ($postdata['import_type']) {
                        case 0:
                            $this->_importRegion($importRawData);
                            break;
                        case 1:
                            $this->_importCity($regions, $importRawData);
                            break;
                        case 2:
                            $this->_importTownship($importRawData);
                            break;
                    }
                    $this->messageManager->addSuccess(__('Address has been imported.'));
                    $this->_redirect('*/*');
                    return;
                } else {
                    $this->messageManager->addError(__('Import file containt error. Please try again.'));
                    $this->_redirect('*/*');
                    return;
                }
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $this->messageManager->addError(
                    __('Something went wrong while saving the data. Please review the error log.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('*/*');
                return;
            }
        }
        $this->_redirect('*/*');
    }

    protected function _prepareData($file)
    {
        $importRawData = $this->csvProcessor->getData($file);
        for($i=1; $i<count($importRawData); $i++) {
            yield $importRawData[$i];
        }
    }

    protected function _importRegion($importRawData)
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('directory_country_region');
        $insert = [];
        foreach ($importRawData as $data) {
            $select = $connection->select()
                        ->from(['dcr' => $table], 'COUNT(*)')
                        ->where('country_id = ?', $data[0])
                        ->where('code = ?', $data[1]);
            $count = $connection->fetchOne($select);
            if($count > 0){
                $update = ['default_name' => $data[2]];
                $where = ['country_id = ?' => $data[0],'code = ?' => $data[1]];
                $connection->update($table, $update, $where);
            } else {
                $insert[] = ['country_id' => $data[0], 'code' => $data[1], 'default_name' => $data[2]];
            }
        }
        if(count($insert) > 0){
            $connection->insertMultiple($table, $insert);
        }
    }

    protected function _importCity($regions, $importRawData)
    {
        $connection = $this->resource->getConnection();
        $table = $this->resource->getTableName('directory_region_city');
        $insert = [];
        foreach ($importRawData as $data) {
            $select = $connection->select()->from(['drc' => $table], ['city_id'])->where('default_name = ?', $data[1]);
            $city_id = $connection->fetchOne($select);
            if($city_id > 0){
                $update = ['region_id' => $regions[$data[0]], 'default_name' => $data[1]];
                $where = ['city_id = ?' => $city_id];
                $connection->update($table, $update, $where);
            } else {
                $insert[] = ['default_name' => $data[1], 'region_id' => $regions[$data[0]]];
            }
        }
        if(count($insert) > 0){
            $connection->insertMultiple($table, $insert);
        }
    }

    protected function _importTownship($importRawData)
    {
        $connection = $this->resource->getConnection();
        $regionTable = $this->resource->getTableName('directory_country_region');
        $cityTable = $this->resource->getTableName('directory_region_city');
        $townshipTable = $this->resource->getTableName('directory_city_township');
        $insert = [];
        foreach ($importRawData as $data) {
            $select = $connection->select()
                                ->from(['drc' => $cityTable], ['city_id'])
                                ->joinLeft(
                                    ['dcr' => $regionTable],
                                    'drc.region_id = dcr.region_id',
                                    []
                                )
                                ->where('dcr.default_name = ?', $data[0])
                                ->where('drc.default_name = ?', $data[1]);

            $city_id = $connection->fetchOne($select);
            if($city_id){
                $query = $connection->select()
                            ->from(['dct' => $townshipTable], ['township_id'])
                            ->where('city_id = ?', $city_id)
                            ->where('default_name = ?', $data[2]);
                $township_id = $connection->fetchOne($query);
                if($township_id){
                    $update = ['city_id' => $city_id,'default_name' => $data[2],'postcode' => $data[3]];
                    $where = ['township_id = ?' => $township_id];
                    $connection->update($townshipTable, $update, $where);
                } else {
                    $insert[] = ['city_id' => $city_id, 'default_name' => $data[2], 'postcode' => $data[3]];
                }
            }
        }
        if(count($insert) > 0){
            $connection->insertMultiple($townshipTable, $insert);
        }
    }
}
