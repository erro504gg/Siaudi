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
 * @package    Zend_Service_WindowsAzure
 * @subpackage RetryPolicy
 * @version    $Id: NoRetry.php 23775 2011-03-01 17:25:24Z ralph $
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Service_WindowsAzure_RetryPolicy_RetryPolicyAbstract
 */
require_once 'Zend/Service/WindowsAzure/RetryPolicy/RetryPolicyAbstract.php';

/**
 * @category   Zend
 * @package    Zend_Service_WindowsAzure
 * @subpackage RetryPolicy
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Service_WindowsAzure_RetryPolicy_NoRetry extends Zend_Service_WindowsAzure_RetryPolicy_RetryPolicyAbstract
{
    /**
     * Execute function under retry policy
     *
     * @param string|array $function       Function to execute
     * @param array        $parameters     Parameters for function call
     * @return mixed
     */
    public function execute($function, $parameters = array())
    {
        $returnValue = null;

        try
        {
            $returnValue = call_user_func_array($function, $parameters);
            return $returnValue;
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
    }
}
