<?php

namespace app\board\validators;


use Exception;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;

class PhoneValidator
{
    public static function validate($value)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $numberProto = $phoneUtil->parse($value, 'BY');
            if ($phoneUtil->isValidNumber($numberProto)) {
                $value = $phoneUtil->format($numberProto, PhoneNumberFormat::INTERNATIONAL);
                return $value;
            } else {
                return false;
            }
        } catch (NumberParseException $e) {
            return false;
        } catch (Exception $e) {
            return false;
        }
    }
}