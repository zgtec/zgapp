<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Frontend Layout controller plugin model
 *
 * @author vladimir
 */

namespace ZgApp\Model\Layout;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\Layout\Frontend
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Frontend extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param bool $layout
     * @return mixed
     */
    public function __invoke($layout = false)
    {
        if ($layout) {
            $this->getController()->layout($layout);
        }

        $this->navigation();
        $this->getController()->debugLayout();

        return $this->getController()->getView();
    }

    //** Navigation **/
    public function navigation()
    {
        $this->getController()->view['navigation'] = "";
    }


}
