<?php

namespace Jframe\tool;

/**
 * The ZipClass which the PHPer can easily create the ZIP document and extract the zip archive
 */
class Zip
{

    /**
     * @var \ZIPARCHIVE The mode for the system to create the zip files
     */
    private $_overwrite = \ZIPARCHIVE::CREATE;
    /**
     * @var array The files added to the zip file
     */
    private $_fileSet = [];
    /**
     * @var boolean Download the zip file when create it or only to save it
     */
    private $_downloadZip = false;
    /**
     *
     * @var array The fileset when extract the file from the zip archive
     */
    private $_extractFilters = null;

    /**
     * @param int|\ZIPARCHIVE $mode The zip create mode
     */
    public function setOpenMode($mode = \ZIPARCHIVE::CREATE)
    {
        if (in_array($mode, [\ZIPARCHIVE::OVERWRITE, \ZIPARCHIVE::OVERWRITE, \ZIPARCHIVE::EXCL, \ZIPARCHIVE::CHECKCONS])) {
            $this->_overwrite = $mode;
        }
    }

    /**
     * @param string|array $absoluteFileNames The file or file set which need to add to the zip file
     */
    public function addFile($absoluteFileNames)
    {
        if (is_string($absoluteFileNames) && file_exists($absoluteFileNames)) {
            $this->_fileSet[] = $absoluteFileNames;
        }
        if (is_array($absoluteFileNames) && !empty($absoluteFileNames)) {
            foreach ($absoluteFileNames as $file) {
                $this->addFile($file);
            }
        }
    }

    /**
     * @param string|array $absoluteFileNames The file or filesets which you not to add to the zip file
     */
    public function minusFile($absoluteFileNames)
    {
        if (is_string($absoluteFileNames) && file_exists($absoluteFileNames)) {
            foreach ($this->_fileSet as $key => $file) {
                if (strcmp($file, $absoluteFileNames) == 0) {
                    unset($this->_fileSet[$key]);
                }
            }
        } elseif (is_array($absoluteFileNames) && !empty($absoluteFileNames)) {
            foreach ($absoluteFileNames as $file) {
                $this->minusFile($file);
            }
        }
    }

    /**
     * @param boolean $mode Set the system to force the browser to download it or save the zip file in the directory only
     */
    public function setDownloadMode($mode = false)
    {
        if (is_bool($mode)) {
            $this->_downloadZip = $mode;
        }
    }

    /**
     * @param string $destination
     * @return boolean false means fail otherwise true or the browser to download the file
     */
    public function createZip($destination = '')
    {
        if (file_exists($destination) && ($this->_overwrite !== \ZIPARCHIVE::OVERWRITE)) {
            return false;
        }
        if (!is_dir(dirname($destination))) {
            @mkdir(dirname($destination), 0777, true);
        }
        $zip = new \ZipArchive();
        if ($zip->open($destination, $this->_overwrite) !== true) {
            return false;
        }
        foreach ($this->_fileSet as $file) {
            $zip->addFile($file, basename($file));
        }
        $zip->close();
        if ($this->_downloadZip) {
            header("Cache-Control: max-age=0");
            header("Content-Description: File Transfer");
            header('Content-disposition: attachment; filename=' . basename($destination));
            header("Content-Type: application/zip");
            header("Content-Transfer-Encoding: binary");
            header('Content-Length: ' . filesize($destination));
            readfile($destination);
            unlink($destination);
        } else {
            return true;
        }
    }

    /**
     * @param array|string|null $files The file or fileset which neet to extract, if you don't know, please set it null<br>
     * to extract all the files the zip archive contains
     */
    public function setExtractFileFilter($files)
    {
        if (is_string($files) && !empty($files)) {
            $this->_extractFilters[] = $files;
        }
        if (is_array($files) && !empty($files)) {
            $this->_extractFilters = array_merge($this->_extractFilters, $files);
        }
        if (is_null($files)) {
            $this->_extractFilters = null;
        }
    }

    /**
     * @param string $zipFile The path for the user to store the files from the zip archive
     * @param string $extractPath The zip archive need to be extracted.
     * @return bool
     */
    public function extractZip($zipFile = '', $extractPath = '')
    {
        if (!is_string($extractPath)) {
            return false;
        }
        if (is_dir($extractPath)) {
            @mkdir($extractPath, 0777, true);
        }
        $zip = new \ZipArchive();
        if ($zip->open($zipFile) !== true) {
            return false;
        }
        if (is_null($this->_extractFilters)) {
            $zip->extractTo($extractPath);
        } else {
            $zip->extractTo($extractPath, $this->_extractFilters);
        }
        if ($zip->close()) {
            return true;
        }
        return false;
    }

}
