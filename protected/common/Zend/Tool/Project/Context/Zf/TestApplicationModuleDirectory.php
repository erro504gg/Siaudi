<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Tool
 * @subpackage Framework
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: TestApplicationControllerDirectory.php 20096 2010-01-06 02:05:09Z bkarwin $
 */

/**
 * @see Zend_Tool_Project_Context_Filesystem_Directory
 */
require_once 'Zend/Tool/Project/Context/Filesystem/Directory.php';

/**
 * This class is the front most class for utilizing Zend_Tool_Project
 *
 * A profile is a hierarchical set of resources that keep track of
 * items within a specific project.
 *
 * @category   Zend
 * @package    Zend_Tool
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Tool_Project_Context_Zf_TestApplicationModuleDirectory extends Zend_Tool_Project_Context_Filesystem_Directory
{


    /**
     * @var string
     */
    protected $_forModuleName = null;

    /**
     * @var string
     */
    protected $_filesystemName = 'moduleDirectory';

    /**
     * init()
     *
     * @return Zend_Tool_Project_Context_Zf_ControllerFile
     */
    public function init()
    {
        $this->_filesystemName = $this->_forModuleName = $this->_resource->getAttribute('forModuleName');
        parent::init();
        return $this;
    }

    /**
     * getName()
     *
     * @return string
     */
    public function getName()
    {
        return 'TestApplicationModuleDirectory';
    }

    /**
     * getPersistentAttributes
     *
     * @return array
     */
    public function getPersistentAttributes()
    {
        return array(
            'forModuleName' => $this->getForModuleName()
            );
    }

    /**
     * getModuleName()
     *
     * @return string
     */
    public function getForModuleName()
    {
        return $this->_forModuleName;
    }
    

}
