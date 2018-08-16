<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\File\Transfer\Adapter\Http;

/**

 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\File
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class File extends AbstractPlugin
{

    public $filename;

    /**
     * Function __invoke
     *
     *
     *
     * @param bool $name
     * @return $this
     */
    public function __invoke($name = false)
    {
        $this->filename = $name;
        return $this;
    }

    /**
     * Function size
     *
     *
     *
     * @return bool|int|string
     */
    public function size()
    {
        if (file_exists($this->filename)) {
            $filesize = $this->formatFileSize($this->filename);
            return $filesize;
        } else {
            return false;
        }
    }

    /**
     * Function dirSize
     *
     *
     *
     * @param $path
     * @return int
     */
    public function dirSize($path)
    {
        $bytestotal = 0;
        $path = realpath($path);
        if ($path !== false) {
            foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path, \FilesystemIterator::SKIP_DOTS)) as $object) {
                $bytestotal += $object->getSize();
            }
        }
        return $bytestotal;
    }

    /**
     * Function changed
     *
     *
     *
     * @return bool|false|string
     */
    public function changed()
    {
        if (file_exists($this->filename)) {
            return date("m/d/Y", filectime($this->filename));
        } else {
            return false;
        }
    }

    /**
     * Function getExtension
     *
     *
     *
     * @param bool $name
     * @return mixed
     */
    public function getExtension($name = false)
    {
        if (!$name)
            $name = $this->filename;
        $pinfo = pathinfo($name);
        return $extension = $pinfo["extension"];
    }

    /**
     * Function formatFileSize
     *
     *
     *
     * @param $name
     * @return int|string
     */
    public function formatFileSize($name)
    {
        if (!$name)
            $name = $this->filename;
        if (file_exists($name)) {
            $filesize = $this->formatSize(filesize($name));
        } else {
            $filesize = 0;
        }
        return $filesize;
    }

    /**
     * Function formatSize
     *
     *
     *
     * @param $filesize
     * @return string
     */
    public function formatSize($filesize)
    {
        $units = array('B', 'Kb', 'Mb', 'Gb', 'Tb');
        $bytes = max($filesize, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        $filesize = round($bytes, 2) . ' ' . $units[$pow];
        return $filesize;
    }

    /**
     * Function safeName
     *
     *
     *
     * @param bool $name
     * @return mixed|string
     */
    public function safeName($name = false)
    {
        if (!$name)
            $name = $this->filename;
        $firstCheck = array(".", ":", "?", "&", ",", "|", "//", "&", "$", "\"", "'", "�", " ", "&", "_", "%", "*", "/", "\\", "@", "#", "!", "(", ")", "", "+", "&amp;", '�', '�');
        $secondCheck = array("---------", "--------", "-------", "------", "-----", "----", "---", "-s-", "--");
        $sku = strtolower($name);
        $sku = str_replace($firstCheck, "-", $sku);
        $sku = str_replace($secondCheck, "-", $sku);
        $sku = ltrim($sku, "-");
        $sku = rtrim($sku, "-");
        return $sku;
    }

    /**
     * Function upload
     *
     *
     *
     * @param $key
     * @param $destination
     * @param $filename
     * @param bool $oldfile
     * @return bool|string
     */
    public function upload($key, $destination, $filename, $oldfile = false)
    {
        if (!is_dir($destination))
            mkdir($destination, 0755, true);

        $upload = new Http();
        $upload->setDestination($destination);
        if (!$upload->isUploaded($key))
            return false;
        $filename = $this->safeName($filename) . "." . $this->getExtension($upload->getFileName($key));
        $upload->addFilter("Zend\Filter\File\Rename", array("target" => $destination . "/" . $filename, "overwrite" => true), $key);
        $upload->receive($key);
        if (!$upload->isUploaded($key))
            return false;
        else {
            if ($oldfile && file_exists($destination . "/" . $oldfile) && $oldfile != $filename && strlen($oldfile))
                unlink($destination . "/" . $oldfile);
            return $filename;
        }
    }

    /**
     * Function copyDirectory
     *
     *
     *
     * @param $source
     * @param $dest
     */
    public function copyDirectory($source, $dest)
    {
        if (!is_dir($dest))
            mkdir($dest, 0755, true);

        foreach (
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            if ($item->isDir()) {
                mkdir($dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            } else {
                copy($item, $dest . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
            }
        }
    }

    /**
     * Function remove
     *
     *
     *
     * @param $path
     * @return bool
     */
    public function remove($path)
    {
        if (!is_dir($path)) {
            if (file_exists($path)) {
                unlink($path);
            } else {
                return false;
            }
        } else {
            foreach (glob($path . '/*') as $file) {
                if (is_dir($file))
                    $this->remove($file);
                else
                    unlink($file);
            }
            @rmdir($path);
        }
        return true;
    }

    /**
     * Function download
     *
     *
     *
     * @param $fileName
     * @param $downloadName
     * @param $contentType
     * @return \Zend\Http\Response\Stream
     */
    public function download($fileName, $downloadName, $contentType)
    {
        $response = new \Zend\Http\Response\Stream();
        $response->setStream(fopen($fileName, 'r'));
        $response->setStatusCode(200);

        $headers = new \Zend\Http\Headers();
        $headers->addHeaderLine('Content-Type', $contentType)
            ->addHeaderLine('Content-Disposition', 'attachment; filename="' . $downloadName . '"')
            ->addHeaderLine('Content-Length', filesize($fileName));

        $response->setHeaders($headers);
        return $response;
    }

    /**
     * Function makeZip
     *
     *
     *
     * @param $source
     * @param $destination
     * @return bool
     */
    function makeZip($source, $destination)
    {
        $source = realpath($source);

        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new \ZipArchive();
        if (!$zip->open($destination, \ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true) {
            $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($source), \RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file) {
                $file = str_replace('\\', '/', $file);

                // Ignore "." and ".." folders
                if (in_array(substr($file, strrpos($file, '/') + 1), array('.', '..')))
                    continue;

                $file = realpath($file);

                if (is_dir($file) === true) {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                } else if (is_file($file) === true) {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        } else if (is_file($source) === true) {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

        return $zip->close();
    }

    /**
     * Function extractZip
     *
     *
     *
     * @param $source
     * @param $destination
     * @param bool $infoDat
     * @return bool
     */
    public function extractZip($source, $destination, $infoDat = false)
    {
        $zip = new \ZipArchive;
        if ($zip->open($source) === TRUE) {
            if (!is_dir($destination))
                mkdir($destination, 0777, true);

            if ($infoDat) {
                $zip->extractTo($destination, array('info.dat'));
            }
            $zip->extractTo($destination);
            $zip->close();
            return true;
        } else {
            return false;
        }
    }

    /**
     * Function listFiles
     *
     *
     *
     * @param $dir
     * @param string $replace
     * @return array
     */
    public function listFiles($dir, $replace = '.php')
    {
        if (!file_exists($dir))
            return array();

        $out = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && !is_dir($dir . '/' . $entry)) {
                    $out[] = str_replace($replace, "", $entry);
                }
            }
            closedir($handle);
        }
        return $out;
    }

    /**
     * Function listDirs
     *
     *
     *
     * @param $dir
     * @param bool $ctime
     * @param bool $hasDir
     * @return array
     */
    public function listDirs($dir, $ctime = false, $hasDir = false)
    {
        if (!file_exists($dir))
            return array();

        $out = array();
        if ($handle = opendir($dir)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != ".." && is_dir($dir . '/' . $entry) && (!$hasDir || file_exists($dir . '/' . $entry . $hasDir))) {
                    $out[(string)$entry] = ($ctime) ? date("m/d/Y h:i:s", filectime($dir . '/' . $entry)) : (string)$entry;
                }
            }
            closedir($handle);
        }
        return $out;
    }

    /**
     * Function listFilesRecursive
     *
     *
     *
     * @param $dir
     * @param string $replace
     * @return array
     */
    public function listFilesRecursive($dir, $replace = '.phtml')
    {
        $files = array();
        if (is_dir($dir)) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
            $files = array();
            foreach ($iterator as $info) {
                $file = str_replace(array($dir, $replace), '', $info->getPathname());
                if (!stristr($file,'\.') && !stristr($file,'/.')) {
                    $files[] = str_replace(array($dir, $replace), '', $info->getPathname());
                }
            }
        }
        return $files;
    }

}
