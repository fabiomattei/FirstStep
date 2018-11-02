<?php
/**
 * Created by IntelliJ IDEA.
 * User: fabio
 * Date: 01/11/18
 * Time: 10.15
 */

namespace Firststep\Common\Builders;

use Firststep\Common\Blocks\BaseChart;

class ChartjsBuilder {

    private $chartStructure;
    private $entities;
    private $chartdataglue;

    /**
     * @param mixed $chartStructure
     */
    public function setChartStructure($chartStructure) {
        $this->chartStructure = $chartStructure;
    }

    /**
     * @param mixed $entities
     * the $entities variable contains all values for the table
     */
    public function setEntities($entities) {
        $this->entities = $entities;
    }

    public function setChartDataGlue($chartdataglue) {
        $this->chartdataglue = $chartdataglue;
    }

    public function createChart() {
        $chartBlock = new BaseChart;
        $chartBlock->setStructure($this->chartStructure);
        $chartBlock->setChartDataGlue($this->chartdataglue);
        $chartBlock->setData($this->entities);
        return $chartBlock;
    }

}