<?php

namespace App\Services;

use RuntimeException;

/**
 * Dilempar saat payload QR gagal diverifikasi
 * (format salah, signature tidak cocok, plant/visitor tidak ditemukan).
 */
class InvalidQrException extends RuntimeException
{
}
