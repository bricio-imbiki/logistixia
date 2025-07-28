     <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
                class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
                <!-- Sidebar Content -->
                <div class="h-full flex flex-col">
                    <!-- Sidebar Menu -->
                    <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                        <ul class="space-y-1 px-2">
                            <!-- Dashboard -->
                             <x-layouts.sidebar-link href="{{ route('dashboard') }}" icon='fas-gauge'
                :active="request()->routeIs('dashboard*')">
                Tableau de bord
            </x-layouts.sidebar-link>


            <x-layouts.sidebar-link href="{{ route('clients.index') }}" icon="fas-users"
                :active="request()->routeIs('clients*')">
                Clients
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('camions.index') }}" icon="fas-truck"
                :active="request()->routeIs('camions*')">
                Camions
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('remorques.index') }}" icon="fas-trailer"
                :active="request()->routeIs('remorques*')">
                Remorques
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('chauffeurs.index') }}" icon="fas-id-badge"
                :active="request()->routeIs('chauffeurs*')">
                Chauffeurs
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('itineraires.index') }}" icon="fas-route"
                :active="request()->routeIs('itineraires*')">
                Itinéraires
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('trajets.index') }}" icon="fas-map-marked-alt"
                :active="request()->routeIs('trajets*')">
                Trajets
            </x-layouts.sidebar-link>

            <x-layouts.sidebar-link href="{{ route('marchandises.index') }}" icon="fas-boxes"
                :active="request()->routeIs('marchandises*')">
                Marchandises
            </x-layouts.sidebar-link>
            {{-- <x-layouts.sidebar-link href="{{ route('tarif.index') }}" icon="fas-map"
                :active="request()->routeIs('tarif*')">
                Tarif
            </x-layouts.sidebar-link> --}}
            {{-- <x-layouts.sidebar-link href="{{ route('plannings.index') }}" icon="fas-calendar-alt"
                :active="request()->routeIs('plannings*')">
                Plannings
            </x-layouts.sidebar-link> --}}

            <x-layouts.sidebar-link href="{{ route('suivisGps.index') }}" icon="fas-map"
                :active="request()->routeIs('suivisGps.index')">
                Suivi GPS
            </x-layouts.sidebar-link>
        </nav>





                            {{-- <!-- Example two level -->
                            <x-layouts.sidebar-two-level-link-parent title="Example two level" icon="fas-house"
                                :active="request()->routeIs('two-level*')">
                                <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                                    :active="request()->routeIs('two-level*')">Child</x-layouts.sidebar-two-level-link>
                            </x-layouts.sidebar-two-level-link-parent>

                            <!-- Example three level -->
                            <x-layouts.sidebar-two-level-link-parent title="Example three level" icon="fas-house"
                                :active="request()->routeIs('three-level*')">
                                <x-layouts.sidebar-two-level-link href="#" icon='fas-house'
                                    :active="request()->routeIs('three-level*')">Single Link</x-layouts.sidebar-two-level-link>

                                <x-layouts.sidebar-three-level-parent title="Third Level" icon="fas-house"
                                    :active="request()->routeIs('three-level*')">
                                    <x-layouts.sidebar-three-level-link href="#" :active="request()->routeIs('three-level*')">
                                        Third Level Link
                                    </x-layouts.sidebar-three-level-link>
                                </x-layouts.sidebar-three-level-parent>
                            </x-layouts.sidebar-two-level-link-parent> --}}
                             <!-- Footer / Déconnexion -->
        <div class="border-t border-gray-200 dark:border-gray-700 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-2 text-sm text-red-600 dark:text-red-400 hover:dark:text-red-400">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Déconnexion</span>
                </button>
            </form>
        </div>

                        </ul>
                    </nav>
                </div>
            </aside>
