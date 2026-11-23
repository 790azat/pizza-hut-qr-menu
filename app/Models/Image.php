<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = ['mime', 'data'];

    protected $hidden = ['data'];

    public const MAX_BYTES = 1_500_000;

    /** Detect mime by magic bytes; null when the bytes are not a supported image. */
    public static function sniff(string $bytes): ?string
    {
        return match (true) {
            str_starts_with($bytes, 'RIFF') && substr($bytes, 8, 4) === 'WEBP' => 'image/webp',
            str_starts_with($bytes, "\xFF\xD8\xFF") => 'image/jpeg',
            str_starts_with($bytes, "\x89PNG") => 'image/png',
            default => null,
        };
    }

    /** Store raw bytes, returns null if they are not a supported image. */
    public static function fromBytes(string $bytes): ?self
    {
        $mime = self::sniff($bytes);
        if (! $mime || strlen($bytes) > self::MAX_BYTES) {
            return null;
        }

        return self::create(['mime' => $mime, 'data' => base64_encode($bytes)]);
    }

    /** Accepts "data:image/webp;base64,...." produced by the admin's in-browser resizer. */
    public static function fromDataUrl(string $dataUrl): ?self
    {
        if (! preg_match('#^data:image/[a-z]+;base64,([A-Za-z0-9+/=]+)$#', $dataUrl, $m)) {
            return null;
        }

        $bytes = base64_decode($m[1], true);

        return $bytes === false ? null : self::fromBytes($bytes);
    }

    public function url(): string
    {
        return url("/img/{$this->id}");
    }
}
