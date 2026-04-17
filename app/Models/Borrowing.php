<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'catatan',
        'catatan_admin',
        'approved_at',
        'rejected_at',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    // ─── STATUS ─────────────────────────────
    const STATUS_PENDING = 'pending';
    const STATUS_DIPINJAM = 'dipinjam';
    const STATUS_DITOLAK = 'ditolak';
    const STATUS_DIKEMBALIKAN = 'dikembalikan';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_DIPINJAM => 'Dipinjam',
            self::STATUS_DITOLAK => 'Ditolak',
            self::STATUS_DIKEMBALIKAN => 'Dikembalikan',
        ];
    }

    // ─── RELASI ─────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    // ─── STATUS CHECK ───────────────────────
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDipinjam(): bool
    {
        return $this->status === self::STATUS_DIPINJAM;
    }

    public function isDikembalikan(): bool
    {
        return $this->status === self::STATUS_DIKEMBALIKAN;
    }

    // ─── LOGIC TERLAMBAT (DINAMIS) ─────────
    public function isTerlambat(): bool
    {
        return $this->isDipinjam()
            && $this->tanggal_kembali
            && Carbon::today()->gt($this->tanggal_kembali);
    }

    public function sisaHari(): ?int
    {
        if (!$this->tanggal_kembali) return null;

        return Carbon::today()->diffInDays($this->tanggal_kembali, false);
    }

    public function statusLabel(): string
    {
        if ($this->isTerlambat()) {
            return 'Terlambat';
        }

        return self::statusOptions()[$this->status] ?? $this->status;
    }

    // ─── ACCESSOR BIAR ENAK DI BLADE ───────
    public function getIsLateAttribute(): bool
    {
        return $this->isTerlambat();
    }

    // ─── SCOPES ────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeDipinjam($query)
    {
        return $query->where('status', self::STATUS_DIPINJAM);
    }

    public function scopeTerlambat($query)
    {
        return $query->where('status', self::STATUS_DIPINJAM)
            ->whereDate('tanggal_kembali', '<', Carbon::today());
    }
}
