<?php
/**
* Exception thrown whenever an invalid timestamp has been passed to a job.
*
* @package		ResqueScheduler
* @author		Chris Boulton <chris@bigcommerce.com>
* @copyright	(c) 2012 Chris Boulton
* @license		http://www.opensource.org/licenses/mit-license.php
*/
namespace ResqueScheduler;

class InvalidTimestampException extends Resque_Exception
{
}
