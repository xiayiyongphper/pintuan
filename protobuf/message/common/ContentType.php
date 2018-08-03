<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * ContentType enum
 */
final class ContentType
{
    const APPLICATION_PB_STREAM = 1;
    const APPLICATION_ZIP = 2;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'APPLICATION_PB_STREAM' => self::APPLICATION_PB_STREAM,
            'APPLICATION_ZIP' => self::APPLICATION_ZIP,
        );
    }
}