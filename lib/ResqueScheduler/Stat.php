<?php
/**
 * Resque statistic management (jobs processed, failed, etc)
 *
 * @package     ResqueScheduler
 * @subpackage  ResqueScheduler.Stat
 * @author      Chris Boulton <chris@bigcommerce.com> (Original)
 * @author      Wan Qi Chen <kami@kamisama.me>
 * @license     http://www.opensource.org/licenses/mit-license.php
 */
namespace ResqueScheduler;

class Stat extends \Resque_Stat
{
    const KEYNAME = 'schdlr';

    /**
     * Get the value of the supplied statistic counter for the specified statistic.
     *
     * @param string $stat The name of the statistic to get the stats for.
     * @return mixed Value of the statistic.
     */
    public static function get($stat = self::KEYNAME)
    {
        return parent::get($stat);
    }

    /**
     * Increment the value of the specified statistic by a certain amount (default is 1)
     *
     * @param string $stat The name of the statistic to increment.
     * @param int $by The amount to increment the statistic by.
     * @return boolean True if successful, false if not.
     */
    public static function incr($stat = self::KEYNAME, $by = 1)
    {
        return parent::incr($stat, $by);
    }

    /**
     * Decrement the value of the specified statistic by a certain amount (default is 1)
     *
     * @param string $stat The name of the statistic to decrement.
     * @param int $by The amount to decrement the statistic by.
     * @return boolean True if successful, false if not.
     */
    public static function decr($stat = self::KEYNAME, $by = 1)
    {
        return parent::decr($stat, $by);
    }

    /**
     * Delete a statistic with the given name.
     *
     * @param string $stat The name of the statistic to delete.
     * @return boolean True if successful, false if not.
     */
    public static function clear($stat = self::KEYNAME)
    {
        return parent::clear($stat);
    }
}
