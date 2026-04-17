<x-filament-widgets::widget>
    <x-filament::section>
       <div class="flex items-center gap-x-3">
            {{-- Foto Profil --}}
            <x-filament::avatar
                :src="filament()->getUserAvatarUrl(auth()->user())"
                size="lg"
                class="h-12 w-12"
            />

            <div class="flex-1">
                <h2 class="grid flex-1 text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{-- Ganti teks "Welcome" di sini --}}
                    Selamat Datang di Literakom,
                </h2>

                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ auth()->user()->name }}
                </p>
            </div>

            {{-- Tombol Logout atau aksi lainnya --}}
            <form action="{{ filament()->getLogoutUrl() }}" method="post" class="my-auto">
                @csrf
                <x-filament::button
                    color="gray"
                    icon="heroicon-m-arrow-left-on-rectangle"
                    icon-alias="widgets::account-widget.logout-button"
                    labeled-from="sm"
                    tag="button"
                    type="submit"
                >
                    Keluar
                </x-filament::button>
            </form>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
