<?php

/**
 * Created by Fabio Mattei
 * Date: 02/11/2018
 * Time: 04:34
 */

namespace Firststep\Common\Blocks;

use Firststep\Common\Blocks\BaseBlock;

class RowBlock extends BaseBlock {

    private $blocks;

    /**
     * RowBlock constructor.
     * @param $blocks
     */
    public function __construct() {
        $this->blocks = array();
    }

    function show(): string {
        return parent::show();
    }

    function addToHead(): string {
        $globalAddToHead = '';
        foreach ($this->blocks as $bl) {
            $globalAddToHead .= $bl->addToHead();
        }
        return $globalAddToHead;
    }

    function addToFoot(): string {
        $globalAddToFoot = '';
        foreach ($this->blocks as $bl) {
            $globalAddToFoot .= $bl->addToFoot();
        }
        return $globalAddToFoot;
    }

}