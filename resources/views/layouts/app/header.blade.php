<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:header container class="border-b border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden mr-2" icon="bars-2" inset="left" />

            <x-app-logo href="{{ route('dashboard') }}" wire:navigate />

            <flux:navbar class="-mb-px max-lg:hidden">
                <flux:navbar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                    {{ __('Dashboard') }}
                </flux:navbar.item>
            </flux:navbar>

            <flux:spacer />

            <flux:navbar class="me-1.5 space-x-0.5 rtl:space-x-reverse py-0!">
                <flux:tooltip :content="__('Search')" position="bottom">
                    <flux:navbar.item class="!h-10 [&>div>svg]:size-5" icon="magnifying-glass" href="#" :label="__('Search')" />
                </flux:tooltip>
                <flux:tooltip :content="__('Repository')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="folder-git-2"
                        href="https://github.com/laravel/livewire-starter-kit"
                        target="_blank"
                        :label="__('Repository')"
                    />
                </flux:tooltip>
                <flux:tooltip :content="__('Documentation')" position="bottom">
                    <flux:navbar.item
                        class="h-10 max-lg:hidden [&>div>svg]:size-5"
                        icon="book-open-text"
                        href="https://laravel.com/docs/starter-kits#livewire"
                        target="_blank"
                        :label="__('Documentation')"
                    />
                </flux:tooltip>
            </flux:navbar>

            <!-- Admin Language Translate Switcher -->
            <flux:dropdown position="bottom" align="end">
                <flux:button variant="subtle" class="!h-10 text-xs font-semibold flex items-center gap-1.5 px-2.5 mr-1 cursor-pointer" title="{{ __('Language') }}">
                    @if (app()->getLocale() === 'de')
                        <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                            <path fill="#000" d="M0 0h640v160H0z"/>
                            <path fill="#D00" d="M0 160h640v160H0z"/>
                            <path fill="#FFCE00" d="M0 320h640v160H0z"/>
                        </svg>
                        <span>DE</span>
                    @else
                        <svg class="size-4 shrink-0 rounded-xs shadow-2xs" viewBox="0 0 640 480">
                            <path fill="#012169" d="M0 0h640v480H0z"/>
                            <path fill="#FFF" d="m75 0 244 181L562 0h78v62L400 240l240 178v62h-78L320 301 76 480H0v-62l240-178L0 62V0h75z"/>
                            <path fill="#C8102E" d="m424 288 216 153v39h-24L392 312l32-24zM640 39 424 192l-32-24L592 0h48v39zM192 192 0 39V0h48l200 144-56 48zm0 96L0 441v39h48l200-144-56-48z"/>
                            <path fill="#FFF" d="M240 0h160v480H240zM0 160h640v160H0z"/>
                            <path fill="#C8102E" d="M267 0h106v480H267zM0 187h640v106H0z"/>
                        </svg>
                        <span>EN</span>
                    @endif
                </flux:button>
                <flux:menu>
                    <flux:menu.item :href="route('lang.switch', 'en')" :current="app()->getLocale() === 'en'">
                        English
                    </flux:menu.item>
                    <flux:menu.item :href="route('lang.switch', 'de')" :current="app()->getLocale() === 'de'">
                        Deutsch
                    </flux:menu.item>
                </flux:menu>
            </flux:dropdown>

            <x-desktop-user-menu />
        </flux:header>

        <!-- Mobile Menu -->
        <flux:sidebar collapsible="mobile" sticky class="lg:hidden border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.header>
                <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
                <flux:sidebar.collapse class="in-data-flux-sidebar-on-desktop:not-in-data-flux-sidebar-collapsed-desktop:-mr-2" />
            </flux:sidebar.header>

            <flux:sidebar.nav>
                <flux:sidebar.group :heading="__('Platform')">
                    <flux:sidebar.item icon="layout-grid" :href="route('dashboard')" :current="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard')  }}
                    </flux:sidebar.item>
                </flux:sidebar.group>
            </flux:sidebar.nav>

            <flux:spacer />

            <flux:sidebar.nav>
                <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                    {{ __('Repository') }}
                </flux:sidebar.item>
                <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire" target="_blank">
                    {{ __('Documentation') }}
                </flux:sidebar.item>
            </flux:sidebar.nav>
        </flux:sidebar>

        {{ $slot }}

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
