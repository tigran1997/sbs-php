<?php
namespace Gxela\CreditcardNumberGenerator;
/*
PHP credit card number generator
Copyright (C) 2006 Graham King graham@darkcoding.net
Copyright (C) 2013 Alex Goretoy alex@goretoy.com

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

class CreditCardGenerator{
    protected static $visaPrefixList = array("4539","4556","4916","4532","4929","40240071","4485","4716","4");
    protected static $mastercardPrefixList = array("51","52","53","54", "55");
    protected static $amexPrefixList = array("34", "37");
    protected static $discoverPrefixList = array("6011");
    protected static $dinersPrefixList = array("300","301","302","303","36","38");
    protected static $enRoutePrefixList = array("2014","2149");
    protected static $jcbPrefixList = array("35");
    protected static $voyagerPrefixList = array("8699");
    /*
    'prefix' is the start of the CC number as a string, any number of digits.
    'length' is the length of the CC number to generate. Typically 13 or 16
    */
    private static function completed_number($prefix, $length) {

        $ccnumber = $prefix;

        # generate digits

        while ( strlen($ccnumber) < ($length - 1) ) {
            $ccnumber .= rand(0,9);
        }

        # Calculate sum

        $sum = 0;
        $pos = 0;

        $reversedCCnumber = strrev( $ccnumber );

        while ( $pos < $length - 1 ) {

            $odd = $reversedCCnumber[ $pos ] * 2;
            if ( $odd > 9 ) {
                $odd -= 9;
            }

            $sum += $odd;

            if ( $pos != ($length - 2) ) {

                $sum += $reversedCCnumber[ $pos +1 ];
            }
            $pos += 2;
        }

        # Calculate check digit

        $checkdigit = (( floor($sum/10) + 1) * 10 - $sum) % 10;
        $ccnumber .= $checkdigit;

        return $ccnumber;
    }

    public static function credit_card_number($prefixList, $length, $howMany) {

        for ($i = 0; $i < $howMany; $i++) {
            $ccnumber = $prefixList[ array_rand($prefixList) ];
            $result[] = self::completed_number($ccnumber, $length);
        }
        if($howMany == 1){
            return array_pop($result);
        }else{
            return $result;
        }

    }
    public static function get_mastercard($count = 1){
        return self::credit_card_number(self::$mastercardPrefixList, 16, $count);
    }
    public static function get_visa16($count = 1){
        return self::credit_card_number(self::$visaPrefixList, 16, $count);
    }
    public static function get_visa13($count = 1){
        return self::credit_card_number(self::$visaPrefixList, 13, $count);
    }
    public static function get_amex($count = 1){
        return self::credit_card_number(self::$amexPrefixList, 15, $count);
    }
}
