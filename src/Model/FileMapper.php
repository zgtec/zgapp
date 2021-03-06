<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

/**
 * ZgApp File Mapper Class
 */
class FileMapper
{

    public function __construct()
    {
        $this->load();
    }

    /**
     * load() - loading data from the file
     */
    public function load()
    {
        if (!$filename = $this->getFileName())
            return false;

        if (!file_exists($filename)) {
            $this->getWriter()->toFile($filename, $this->asset->getArrayCopy(true));
        } else {
            if (!strlen(file_get_contents($filename))) {
                unlink($filename);
                $this->getWriter()->toFile($filename, $this->asset->getArrayCopy(true));
            }
        }

        $reader = $this->getReader();
        $data = $reader->fromFile($filename);

        $this->asset->setArrayCopy($data);
        return true;
    }


    /**
     * Function save
     *
     * saving data to the file
     *
     * @param array $data
     * @return bool
     */
    public function save($data = array())
    {
        if (!$filename = $this->getFileName())
            return false;

        \array_walk_recursive($data, '\ZgApp\Filter\Html::recursive');

        if (count($data) === 0) {
            return false;
        }

        $this->setArrayCopy($data);

        $writer = $this->getWriter();
        $writer->toFile($filename, $this->asset->getArrayCopy(true));
        return true;
    }

    /*
     * remove() - removing asset data file
     */
    /**
     * Function remove
     *
     *
     *
     * @return bool
     */
    public function remove()
    {
        if (!$filename = $this->getFileName())
            return false;
        unlink($filename);
        return true;
    }

    /**
     * getFileName() - returns storage filename
     */
    protected function getFileName()
    {
        return false;
    }

    /**
     * getAssetData()
     */
    public function getAssetData()
    {
        return $this->asset->getArrayCopy(true);
    }

    /**
     * getAsset()
     */
    public function getAsset()
    {
        return $this->asset;
    }

    /**
     * getReader(), getWriter() - returns reader and writer
     */
    protected function getReader()
    {
        return new \Zend\Config\Reader\Json();
    }

    /**
     * Function getWriter
     *
     *
     *
     * @return \Zend\Config\Writer\Json
     */
    protected function getWriter()
    {
        return new \Zend\Config\Writer\Json();
    }

    /**
     * Function imagesReorder
     *
     * reorder images
     *
     * @param $dir
     * @param $num
     */
    public function imagesReorder($dir, $num)
    {
        $assetData = $this->getAssetData();
        if ($num > 0 && $dir == 'up') {
            $image1 = $assetData['images'][$num];
            $image2 = $assetData['images'][$num - 1];
            $assetData['images'][$num] = $image2;
            $assetData['images'][$num - 1] = $image1;
            $this->save($assetData);
        } elseif ($num < count($assetData['images']) - 1 && $dir == 'down') {
            $image1 = $assetData['images'][$num];
            $image2 = $assetData['images'][$num + 1];
            $assetData['images'][$num] = $image2;
            $assetData['images'][$num + 1] = $image1;
            $this->save($assetData);
        }
    }

    /**
     * Function setArrayCopy
     *
     *
     *
     * @param $data
     * @return mixed
     */
    public function setArrayCopy($data)
    {
        return $this->asset->setArrayCopy($data);
    }

}
