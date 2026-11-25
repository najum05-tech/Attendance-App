<?php

namespace App\Services;

class PhoneNumberFormatter
{
    /**
     * Normalize a phone number to E.164 format with leading +.
     * Uses `WHATSAPP_DEFAULT_COUNTRY_CODE` env (e.g., '92') when a number lacks a country code.
     * Returns null if input is empty or invalid.
     */
    public static function format(?string $phone): ?string
    {
        if (empty($phone)) {
            return null;
        }

        // Strip everything except digits and +
        $clean = preg_replace('/[^0-9+]/', '', $phone);

        // If it's already starts with + followed by digits, accept it
        if (preg_match('/^\+[0-9]{6,15}$/', $clean)) {
            return $clean;
        }

        // Remove leading + if any for easier handling
        $digits = ltrim($clean, '+');

        // If starts with 0 and length typical local (e.g., 10 or 11), replace leading 0 with country code
        $defaultCountry = env('WHATSAPP_DEFAULT_COUNTRY_CODE', '92');

        if (strlen($digits) >= 10 && $digits[0] === '0') {
            // drop leading 0 and prefix country code
            $digits = $defaultCountry . substr($digits, 1);
            return '+' . $digits;
        }

        // If number length looks like local (10 digits) add default country code
        if (strlen($digits) === 10) {
            return '+' . $defaultCountry . $digits;
        }

        // If the number already seems to contain a country code (e.g., 11+ digits), prefix +
        if (strlen($digits) >= 11 && strlen($digits) <= 15) {
            return '+' . $digits;
        }

        // Fallback: if it's short or cannot be normalized, return null
        return null;
    }

    /**
     * Ensure Twilio/WhatsApp API style prefix `whatsapp:+<number>`
     */
    public static function ensureWhatsAppPrefix(?string $phone): ?string
    {
        if (empty($phone)) return null;

        // If already has whatsapp: prefix, normalize inner part
        if (str_starts_with($phone, 'whatsapp:')) {
            $inner = substr($phone, strlen('whatsapp:'));
            $formatted = self::format($inner);
            return $formatted ? 'whatsapp:' . $formatted : null;
        }

        $formatted = self::format($phone);
        return $formatted ? 'whatsapp:' . $formatted : null;
    }
}
