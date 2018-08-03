<?php
/**
 *
 * message.common package
 */

namespace message\common;
/**
 * EncryptionMethod enum
 */
final class EncryptionMethod
{
    const DES = 1;
    const RSA = 2;
    const ORG = 3;

    /**
     * Returns defined enum values
     *
     * @return int[]
     */
    public function getEnumValues()
    {
        return array(
            'DES' => self::DES,
            'RSA' => self::RSA,
            'ORG' => self::ORG,
        );
    }
}