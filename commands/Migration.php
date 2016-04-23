<?php
namespace Commands;

use Common\Model;

class Migration extends Model
{
    public function addItem($filename)
    {
        $this->setData('filename', $filename);
        $this->setData('addtime', time());
        return $this->save();
    }

    public static function getByFilename($filename)
    {
        $item = Migration::findFirstSimple(array('filename'=>$filename));
        return $item ? $item->getData() : false;
    }
}
