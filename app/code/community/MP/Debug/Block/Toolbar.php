<?php

/**
 * Class MP_Debug_Block_Toolbar
 *
 * @category MP
 * @package  MP_Debug
 * @license  Copyright: MP, 2016
 * @link     https://merchantprotocol.com
 */
class MP_Debug_Block_Toolbar extends MP_Debug_Block_Abstract
{
    /** @var  MP_Debug_Block_Panel[] */
    protected $visiblePanels;

    /**
     * Render toolbar only if request is allowed
     *
     * @return string
     */
    public function renderView()
    {
        // Render Debug toolbar only if allowed 
        if (!$this->helper->canShowToolbar()) {
            return '';
        }

        return parent::renderView();
    }


    /**
     * Returns MP Debug module version
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->helper->getModuleVersion();
    }


    /**
     * Returns sorted visible debug panels
     *
     * @return MP_Debug_Block_Panel[]
     */
    public function getVisiblePanels()
    {
        if ($this->visiblePanels === null) {
            $this->visiblePanels = array();

            $panels = $this->getSortedChildBlocks();
            foreach ($panels as $panel) {
                if (!$panel instanceof MP_Debug_Block_Panel) {
                    continue;
                }

                $this->visiblePanels[] = $panel;
            }
        }
        return $this->visiblePanels;
    }

}
