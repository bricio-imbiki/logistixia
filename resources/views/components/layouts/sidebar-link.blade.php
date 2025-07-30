@props(['active' => false, 'href' => '#', 'icon' => null])
<li>
    <a href="{{ $href }}" @class([
        'flex items-center px-3 py-2 text-sm rounded-md transition-colors duration-200',
        // Supprime le bg et outline Bootstrap sur focus, hover, active
        '!bg-transparent focus:outline-none focus:ring-0 no-underline',
        'bg-sidebar-accent text-sidebar-accent-foreground font-medium' => $active,
        'hover:bg-sidebar-accent hover:text-sidebar-accent-foreground text-sidebar-foreground' => !$active,
    ])>
        @svg($icon, $active ? 'w-5 h-5 text-white dark:text-gray-800' : 'w-5 h-5 text-gray-500')
        <span :class="{ 'hidden ml-0': !sidebarOpen, 'ml-3': sidebarOpen }"
            x-transition:enter="transition-opacity duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity duration-300"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            class="transition-opacity duration-300">{{ $slot }}</span>
    </a>
</li>
{{-- <script>
    // Update link text visibility based on sidebar state
    function updateSidebarLinks() {
        const sidebar = document.getElementById('sidebar');
        const isOpen = sidebar.classList.contains('w-full') || sidebar.classList.contains('w-64');
        document.querySelectorAll('.sidebar-link .link-text').forEach(span => {
            span.style.opacity = isOpen ? '1' : '0';
            span.style.display = isOpen ? 'inline' : 'none';
        });

        // Update tooltips based on sidebar state
        tippy('[data-tippy-content]').forEach(tooltip => {
            tooltip.setProps({ trigger: isOpen ? 'manual' : 'mouseenter focus' });
        });
    }

    // Initialize links and listen for sidebar changes
    document.addEventListener('DOMContentLoaded', () => {
        updateSidebarLinks();

        // Observe sidebar class changes
        const sidebar = document.getElementById('sidebar');
        const observer = new MutationObserver(updateSidebarLinks);
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });

        // Keyboard navigation for accessibility
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('keydown', (e) => {
                if (e.key === 'Enter' || e.key === 'Space') {
                    e.preventDefault();
                    link.click();
                }
            });
        });
    });
</script> --}}

<style>
    .sidebar-link .link-text {
        transition: opacity 0.3s ease, display 0.3s ease;
    }
</style>
