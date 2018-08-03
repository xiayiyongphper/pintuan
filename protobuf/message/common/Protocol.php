<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * Protocol enum
 */
final class Protocol
{
    const PB = 1;
    const JSON = 2;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'PB' => self::PB,
            'JSON' => self::JSON,
        );
    }
}