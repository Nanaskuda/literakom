{{-- resources/views/components/book-card.blade.php --}}
<a href="{{ route('books.show', $book) }}"
   class="group bg-white rounded-2xl overflow-hidden transition-all duration-300
          hover:-translate-y-1.5 hover:shadow-xl flex flex-col"
   style="border:1px solid rgba(26,46,26,0.08); box-shadow:0 2px 8px rgba(26,46,26,0.06);">

    {{-- Cover --}}
    <div class="relative overflow-hidden aspect-[3/4]" style="background:var(--cream2);">
        @if ($book->cover)
            <img src="{{ Storage::url($book->cover) }}" alt="{{ $book->judul }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                 loading="lazy">
        @else
            <div class="w-full h-full flex items-center justify-center"
                 style="background:linear-gradient(135deg, var(--forest), #3a5a3a);">
                <svg class="w-12 h-12 opacity-20 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/>
                </svg>
            </div>
        @endif

        {{-- Badges --}}
        <div class="absolute top-2.5 left-2.5 right-2.5 flex items-start justify-between gap-1">
            <span class="text-xs font-semibold px-2.5 py-1 rounded-lg truncate max-w-[120px]"
                  style="background:rgba(247,243,237,0.95); color:var(--forest); backdrop-filter:blur(4px);">
                {{ $book->category->nama ?? '-' }}
            </span>
            @if ($book->isAvailable())
                <span class="text-xs font-bold px-2 py-1 rounded-lg flex-shrink-0"
                      style="background:rgba(34,197,94,0.9); color:white;">✓</span>
            @else
                <span class="text-xs font-bold px-2 py-1 rounded-lg flex-shrink-0"
                      style="background:rgba(239,68,68,0.9); color:white;">✗</span>
            @endif
        </div>

        {{-- Hover CTA --}}
        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity"
             style="background:rgba(26,46,26,0.7);">
            <span class="text-white text-sm font-semibold px-5 py-2.5 rounded-xl"
                  style="background:var(--copper);">Lihat Detail</span>
        </div>
    </div>

    {{-- Info --}}
    <div class="p-3.5 flex-1 flex flex-col">
        <h3 class="text-sm font-bold line-clamp-2 leading-snug mb-1" style="color:var(--forest);">
            {{ $book->judul }}
        </h3>
        <p class="text-xs truncate" style="color:var(--muted);">{{ $book->penulis }}</p>
        <p class="text-xs mt-0.5" style="color:rgba(107,112,96,0.6);">{{ $book->tahun_terbit }}</p>

        {{-- Rating --}}
        @if ($book->reviews && $book->reviews->count() > 0)
            <div class="flex items-center gap-1.5 mt-auto pt-2.5"
                 style="border-top:1px solid var(--cream2); margin-top:8px;">
                <div class="flex">
                    @for ($s = 1; $s <= 5; $s++)
                        <svg class="w-3 h-3 {{ $s <= round($book->averageRating()) ? '' : 'opacity-20' }}"
                             style="color:var(--copper);" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    @endfor
                </div>
                <span class="text-xs" style="color:var(--muted);">{{ $book->averageRating() }}</span>
            </div>
        @endif
    </div>
</a>
