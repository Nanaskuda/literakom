<?php

namespace App\Observers;

use App\Models\Borrowing;
use Illuminate\Support\Facades\DB;

class BorrowingObserver
{
    /**
     * Handle the Borrowing "created" event.
     */
    public function created(Borrowing $borrowing): void
    {
        if ($borrowing->status === 'dipinjam') {
            $borrowing->user()->increment('book_count');
        }
    }

    /**
     * Handle the Borrowing "updated" event.
     */
    public function updated(Borrowing $borrowing): void
    {
        if ($borrowing->isDirty('status')) {
            $oldStatus = $borrowing->getOriginal('status');
            $newStatus = $borrowing->status;

            DB::transaction(function () use ($oldStatus, $newStatus, $borrowing) {
                // Jika berubah jadi dipinjam, tambah count
                if ($newStatus === 'dipinjam') {
                    $borrowing->user()->increment('book_count');
                }
                // Jika sebelumnya dipinjam dan sekarang dikembalikan, kurangi count
                if ($oldStatus === 'dipinjam' && $newStatus === 'dikembalikan') {
                    $borrowing->user()->decrement('book_count');
                }
            });
        }
    }

    /**
     * Handle the Borrowing "deleted" event.
     */
    public function deleted(Borrowing $borrowing): void
    {
        if ($borrowing->status === 'dipinjam') {
            $borrowing->user()->decrement('book_count');
        }
    }

    /**
     * Handle the Borrowing "restored" event.
     */
    public function restored(Borrowing $borrowing): void
    {
        //
    }

    /**
     * Handle the Borrowing "force deleted" event.
     */
    public function forceDeleted(Borrowing $borrowing): void
    {
        //
    }
}
