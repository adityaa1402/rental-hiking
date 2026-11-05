<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaturan extends Model
{
    protected $table = 'pengaturan';
    protected $primaryKey = 'id_pengaturan';
    public $timestamps = false;

    protected $fillable = [
        'nama_toko', 'tagline', 'alamat', 'telepon', 'whatsapp',
        'email', 'jam_operasional', 'biaya_pengiriman_flat',
        'denda_keterlambatan_per_hari', 'syarat_ketentuan',
    ];

    /**
     * Ambil pengaturan toko (singleton pattern dengan cache)
     */
    public static function getSetting(): self
    {
        static $setting = null;
        if ($setting === null) {
            $setting = self::first() ?? new self([
                'nama_toko'   => 'HIKETRIP RENTAL',
                'tagline'     => 'Gear Up, Explore Further.',
                'alamat'      => 'Jl. Lawu Raya No. 45, Karanganyar, Jawa Tengah',
                'telepon'     => '0271-889977',
                'whatsapp'    => '081234567890',
                'email'       => 'kontak@hikettrip.com',
                'jam_operasional' => 'Setiap Hari 08:00 - 21:00 WIB',
                'biaya_pengiriman_flat' => 25000,
                'denda_keterlambatan_per_hari' => 50000,
            ]);
        }
        return $setting;
    }
}

