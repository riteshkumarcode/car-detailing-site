<?php

namespace App\Domain\Normalizers;

class RegistrationNormalizer
{
    /**
     * Normalise an Indian vehicle registration plate string.
     * Examples:
     * - "jk-02-ab-1234" => "JK02AB1234"
     * - "DL 01 C AA 1111" => "DL01CAA1111"
     * - "hr26 dk 8337" => "HR26DK8337"
     */
    public static function normalize(?string $plate): string
    {
        if (!$plate) {
            return '';
        }

        // Remove all non-alphanumeric characters and convert to uppercase
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $plate));
    }

    /**
     * Format a normalised plate for display if it matches standard Indian format.
     */
    public static function format(?string $plate): string
    {
        $normalized = self::normalize($plate);
        if (strlen($normalized) >= 8 && preg_match('/^([A-Z]{2})([0-9]{1,2})([A-Z]{1,3})([0-9]{4})$/', $normalized, $matches)) {
            return "{$matches[1]} {$matches[2]} {$matches[3]} {$matches[4]}";
        }

        return $normalized;
    }
}
