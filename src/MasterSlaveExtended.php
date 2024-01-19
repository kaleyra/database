<?php

/*
 * This file is part of the Speedwork package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Kaleyra\Database;

/**
 * MasterSlaveExtended class
 */
class MasterSlaveExtended extends MasterSlave
{
    protected function getConnection($query, $default = 'slave')
    {
        if (!$this->split) {
            return $default;
        }

        $sql  = explode(' ', $query, 2);
        $type = trim(strtolower($sql[0]));

        $connections = $this->params['connections'];
        $patterns    = $connections['patterns'];

        if (is_array($patterns)) {
            foreach ($patterns as $pattern => $type) {
                $pattern = '/'.$pattern.'/ui';
                if (preg_match($pattern, $query)) {
                    return $type;
                }
            }
        }

        $types = $connections['types'];

        if (isset($types[$type])) {
            return $types[$type];
        }

        return $types['other'] ?: $default;
    }
}
