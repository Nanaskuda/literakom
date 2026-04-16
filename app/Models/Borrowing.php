<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Borrowing extends Model
{
    /** @use HasFactory<\Database\Factories\BorrowingFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'tanggal_pinjam',
        'tanggal_kembali',
        'tanggal_dikembalikan',
        'status',
        'catatan',
    ];

    protected $casts = [
        'tanggal_pinjam' => 'date',
        'tanggal_kembali' => 'date',
        'tanggal_dikembalikan' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function isTerlambat()
    {
        return $this->status === 'dipinjam'
        && Carbon::today()->gt($this->tanggal_kembali);
    }

    public function sisaHari(){
        return Carbon::today()->diffInDays($this->tanggal_kembali, false);
    }

    
}
