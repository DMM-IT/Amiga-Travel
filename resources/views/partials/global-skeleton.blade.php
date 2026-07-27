<div id="global-skeleton-overlay" class="pointer-events-none fixed inset-0 z-[9999] hidden bg-white/80 backdrop-blur-sm">
    <div class="absolute inset-0 bg-gradient-to-br from-white via-white/90 to-slate-200/80"></div>
    <div class="relative mx-auto mt-24 max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="space-y-4">
            <div class="h-5 w-2/5 rounded-full bg-slate-200 animate-pulse"></div>
            <div class="grid gap-4 lg:grid-cols-4">
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
            </div>
            <div class="grid gap-4 lg:grid-cols-2">
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-14 rounded-3xl bg-slate-200 animate-pulse"></div>
            </div>
            <div class="grid gap-4 lg:grid-cols-3">
                <div class="h-20 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-20 rounded-3xl bg-slate-200 animate-pulse"></div>
                <div class="h-20 rounded-3xl bg-slate-200 animate-pulse"></div>
            </div>
        </div>
    </div>
</div>

<style>
    #global-skeleton-overlay.skeleton-show {
        display: block !important;
    }
    #global-skeleton-overlay .animate-pulse {
        background-image: linear-gradient(90deg, #e2e8f0 0%, #f8fafc 50%, #e2e8f0 100%);
        background-size: 200% 100%;
        animation: skeleton-pulse 1.6s ease-in-out infinite;
    }
    @keyframes skeleton-pulse {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
</style>

<script>
    (function () {
        const overlay = document.getElementById('global-skeleton-overlay');
        let timer = null;

        if (! overlay) {
            return;
        }

        function showOverlay() {
            if (timer) {
                return;
            }
            timer = window.setTimeout(() => {
                overlay.classList.add('skeleton-show');
                timer = null;
            }, 120);
        }

        function hideOverlay() {
            if (timer) {
                window.clearTimeout(timer);
                timer = null;
            }
            overlay.classList.remove('skeleton-show');
        }

        document.addEventListener('livewire:request-start', showOverlay);
        document.addEventListener('livewire:request-error', hideOverlay);
        document.addEventListener('livewire:request-end', hideOverlay);

        document.addEventListener('DOMContentLoaded', function () {
            document.body.classList.add('global-skeleton-ready');
        });
    })();
</script>
