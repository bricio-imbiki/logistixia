     <aside :class="{ 'w-full md:w-64': sidebarOpen, 'w-0 md:w-16 hidden md:block': !sidebarOpen }"
         class="bg-sidebar text-sidebar-foreground border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden">
         <!-- Sidebar Content -->
         <div class="h-full flex flex-col">
             <!-- Sidebar Menu -->
             <nav class="flex-1 overflow-y-auto custom-scrollbar py-4">
                 <ul class="space-y-1 px-2">
                     <!-- Dashboard -->
                     <x-layouts.sidebar-link href="{{ route('dashboard.index') }}" icon='fas-gauge' :active="request()->routeIs('dashboard*')">
                         Tableau de bord
                     </x-layouts.sidebar-link>


                     <x-layouts.sidebar-link href="{{ route('clients.index') }}" icon="fas-users" :active="request()->routeIs('clients*')">
                         Clients
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('camions.index') }}" icon="fas-truck" :active="request()->routeIs('camions*')">
                         Camions
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('remorques.index') }}" icon="fas-trailer" :active="request()->routeIs('remorques*')">
                         Remorques
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('chauffeurs.index') }}" icon="fas-id-badge"
                         :active="request()->routeIs('chauffeurs*')">
                         Chauffeurs
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('itineraires.index') }}" icon="fas-route" :active="request()->routeIs('itineraires*')">
                         Itinéraires
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('trajets.index') }}" icon="fas-map-marked-alt"
                         :active="request()->routeIs('trajets*')">
                         Trajets
                     </x-layouts.sidebar-link>

                        <!-- Marchandises -->
                     <x-layouts.sidebar-link href="{{ route('marchandises.index') }}" icon="fas-boxes"
                         :active="request()->routeIs('marchandises*')">
                         Marchandises
                     </x-layouts.sidebar-link>

                        <!-- Commandes -->
                        <x-layouts.sidebar-link href="{{ route('marchandise-transportee.index') }}" icon="fas-clipboard-list"
                            :active="request()->routeIs('marchandise-transportee*')">
                            Commandes
                        </x-layouts.sidebar-link>
                     <!-- Carburant -->
                     <x-layouts.sidebar-link href="{{ route('carburants.index') }}" icon="fas-gas-pump"
                         :active="request()->routeIs('carburants*')">
                         Carburant
                     </x-layouts.sidebar-link>

                     <x-layouts.sidebar-link href="{{ route('suivisGps.index') }}" icon="fas-map" :active="request()->routeIs('suivisGps.index')">
                         Suivi GPS
                     </x-layouts.sidebar-link>




                     <!-- Maintenance (entretien) -->
                     {{-- <x-layouts.sidebar-link href="{{ route('maintenance.index') }}" icon="fas-tools"
                         :active="request()->routeIs('maintenance*')">
                         Maintenance
                     </x-layouts.sidebar-link> --}}
{{--
                <!-- Factures clients -->
<x-layouts.sidebar-link href="{{ route('factures.index') }}" icon="fas-file-invoice-dollar" :active="request()->routeIs('factures*')">
    Factures clients
</x-layouts.sidebar-link>

<!-- Paiements reçus -->
<x-layouts.sidebar-link href="{{ route('paiements.index') }}" icon="fas-credit-card" :active="request()->routeIs('paiements*')">
    Paiements reçus
</x-layouts.sidebar-link>

<!-- Dépenses -->
<x-layouts.sidebar-link href="{{ route('depenses.index') }}" icon="fas-money-bill-wave" :active="request()->routeIs('depenses*')">
    Dépenses
</x-layouts.sidebar-link> --}}






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
        <div class="border-t border-gray-700 p-4">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center space-x-2 text-sm text-red-400 hover:text-red-300 w-full p-2 rounded-md hover:bg-gray-700" data-tippy-content="Déconnexion">
                    <i class="fas fa-sign-out-alt w-5 mr-3"></i>
                    <span :class="{ 'hidden': !sidebarOpen }">{{ __('Déconnexion') }}</span>
                </button>
            </form>
        </div>

             </ul>
             </nav>
         </div>



    <style>
        .sidebar-transition {
            transition: width 0.3s ease;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #4B5563;
        }
        .arrow.rotate {
            transform: rotate(180deg);
        }
        #sidebar.w-0 .sidebar-text, #sidebar.w-16 .sidebar-text {
            display: none;
        }
        #sidebar.w-full, #sidebar.w-64 {
            width: 100%;
        }
        @media (min-width: 768px) {
            #sidebar.w-16 {
                width: 4rem;
            }
            #sidebar.w-64 {
                width: 16rem;
            }
        }
    </style>

    {{-- <script>
        // Sidebar Toggle
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggle-sidebar');
        let isSidebarOpen = localStorage.getItem('sidebarOpen') === 'true' || window.innerWidth >= 1024;

        function updateSidebar() {
            sidebar.className = `bg-gray-800 text-white border-r border-gray-200 dark:border-gray-700 sidebar-transition overflow-hidden ${isSidebarOpen ? 'w-full md:w-64' : 'w-0 md:w-16 hidden md:block'}`;
            updateTooltips();
        }

        toggleButton.addEventListener('click', () => {
            isSidebarOpen = !isSidebarOpen;
            localStorage.setItem('sidebarOpen', isSidebarOpen);
            updateSidebar();
        });

        // Collapsible Menu Groups
        document.querySelectorAll('.toggle-group').forEach(button => {
            button.addEventListener('click', () => {
                const group = button.getAttribute('data-group');
                const content = button.nextElementSibling;
                const arrow = button.querySelector('.arrow');
                content.classList.toggle('hidden');
                arrow.classList.toggle('rotate');
            });
        });

        // Tooltips
        document.addEventListener('DOMContentLoaded', () => {
            tippy('[data-tippy-content]', {
                placement: 'right',
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                animation: 'scale',
                trigger: isSidebarOpen ? 'manual' : 'mouseenter focus'
            });
        });

        function updateTooltips() {
            tippy('[data-tippy-content]').forEach(tooltip => {
                tooltip.setProps({ trigger: isSidebarOpen ? 'manual' : 'mouseenter focus' });
            });
        }

        // Keyboard Navigation
        document.querySelectorAll('nav a, nav button').forEach(item => {
            item.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    item.click();
                }
            });
        });

        // Initialize Sidebar State
        updateSidebar();
    </script> --}}
     </aside>









    {{-- <style>
        .sidebar-transition {
            transition: width 0.3s ease;
        }
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #6B7280;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #4B5563;
        }
    </style>

    <script>
        // Initialize tooltips for sidebar when collapsed
        document.addEventListener('DOMContentLoaded', () => {
            tippy('[data-tippy-content]', {
                placement: 'right',
                theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                animation: 'scale',
                trigger: window.innerWidth >= 768 && !localStorage.getItem('sidebarOpen') === 'true' ? 'mouseenter focus' : 'manual'
            });

            // Update tooltips on sidebar toggle
            Alpine.watch('sidebarOpen', (value) => {
                tippy('[data-tippy-content]').forEach(tooltip => {
                    tooltip.setProps({ trigger: value ? 'manual' : 'mouseenter focus' });
                });
            });
        });
    </script> --}}
