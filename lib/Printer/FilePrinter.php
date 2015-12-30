<?php
namespace Shantilab\BxEcho\Printer;

use \Bitrix\Main\Diag\Debug;
use \Bitrix\Main\Application;
use \Bitrix\Main\IO\File;

/**
 * Class FilePrinter
 * @package Shantilab\BxEcho\Printer
 */
class FilePrinter extends BasePrinter implements PrinterInterface
{
     /**
     * @param null $data
     */
    public function fire($data = null)
    {
        if (!isset($this->options['path'])){
            throw new \Shantilab\BxEcho\Exceptions\EmptyFilePathException();
        }

        $file = new File(Application::getDocumentRoot() . $this->options['path']);
        if ($file->isFile())
            throw new \Shantilab\BxEcho\Exceptions\InvalidFilePathException(Application::getDocumentRoot() . $this->options['path']);

        if (!$this->options['append'] && $file->isExists())
            $file->delete();

        if ($data)
            $this->printToFile($data);
        else
            $this->printToFile($this->data);
    }

    /**
     * @param $data
     * @return bool
     */
    public function printToFile($data){
        if (!isset($this->options['path'])){
            throw new \Shantilab\BxEcho\Exceptions\EmptyFilePathException();
        }

        if ($this->options['type'] == 'dump')
            Debug::dumpToFile($data, null, $this->options['path']);
        if ($this->options['type'] == 'write')
            Debug::writeToFile($data, null, $this->options['path']);
    }
}
