<?php
namespace Library\Queue\Adapter;

use Library\Queue\QueueAdapter;

class Files extends QueueAdapter
{

    private $tube = 'main';

    public function init()
    {
        $path = $this->getConfig('path');
        is_dir($path) or mkdir($path, 0777, true);
        return true;
    }

    public function put($tube, $data)
    {
        $this->tube = $tube;
        $filename = $this->getConfig('path') . '/' . time() . substr(microtime(), 0,
                6) . '.' . $this->tube . '.' . md5($this->tube . serialize($data)) . $this->getConfig('ext');
        return file_put_contents($filename, serialize(array('tube' => $this->tube, 'data' => $data)));
    }

    public function delete($key)
    {
        return unlink($this->getConfig('path').'/'.$key.$this->getConfig('ext'));
    }

}