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
     * @var
     */
    protected $file;

    /**
     * FilePrinter constructor.
     * @param null $data
     * @param array $options
     */
    public function __construct($data = null, array $options = [])
    {
        parent::__construct($data, $options);

        if (isset($options['path']))
            $this->setOptions(['path' => $options['path']]);
    }

    /**
     * @param null $data
     */
    public function fire($data = null)
    {
        if (!isset($this->options['path'])){
            throw new \Shantilab\BxEcho\Exceptions\EmptyFilePathException();
        }

        if (!$this->options['append'] && $this->file->isExists())
            $this->file->delete();

        if ($data)
            $this->printToFile($data);
        else
            $this->printToFile($this->data);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options){
        parent::setOptions($options);
        $this->file = new File(Application::getDocumentRoot() . $this->options['path']);
        if (!$this->file->isFile())
            throw new \Shantilab\BxEcho\Exceptions\InvalidFilePathException(Application::getDocumentRoot() . $this->options['path']);
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