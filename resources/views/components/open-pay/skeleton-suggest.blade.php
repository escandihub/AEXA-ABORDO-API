{{-- <div class="grid gap-2 max-h-48 overflow-y-auto pr-1">

    <!-- Item Skeleton -->
    @for ($i = 0; $i < 2; $i++) <div
        class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-100 animate-pulse">

        <!-- Avatar -->
        <div class="w-9 h-9 rounded-full bg-slate-200 flex-shrink-0"></div>

        <!-- Text -->
        <div class="flex-1 min-w-0 space-y-2">
            <div class="h-3 bg-slate-200 rounded w-3/4"></div>
            <div class="h-3 bg-slate-100 rounded w-1/2"></div>
        </div>

        <!-- Icon placeholder -->
        <div class="w-5 h-5 bg-slate-200 rounded-full flex-shrink-0"></div>
</div>
@endfor

<!-- Button Skeleton -->
<div class="w-full flex items-center gap-2 p-3 rounded-xl border-2 border-dashed border-slate-200 animate-pulse">
    <div class="w-4 h-4 bg-slate-200 rounded"></div>
    <div class="h-3 bg-slate-200 rounded w-2/3"></div>
</div>

</div> --}}

<style>
    @keyframes shimmer {
        0% {
            background-position: -200% 0;
        }

        100% {
            background-position: 200% 0;
        }
    }

    .shimmer {
        background: linear-gradient(90deg,
                #e5e7eb 25%,
                #f3f4f6 37%,
                #e5e7eb 63%);
        background-size: 200% 100%;
        animation: shimmer 1.4s ease infinite;
    }
</style>

<div  wire:loading.inline wire:target="phone">
    <div class="grid gap-2 max-h-48 overflow-y-auto pr-1">

        @for ($i = 0; $i < 2; $i++) <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-100">

            <!-- Avatar -->
            <div class="w-9 h-9 rounded-full shimmer flex-shrink-0"></div>

            <!-- Text -->
            <div class="flex-1 min-w-0 space-y-2">
                <div class="h-3 rounded shimmer w-3/4"></div>
                <div class="h-3 rounded shimmer w-1/2"></div>
            </div>

            <!-- Icon -->
            <div class="w-5 h-5 rounded-full shimmer flex-shrink-0"></div>
    </div>
    @endfor

    <!-- Button Skeleton -->
    <div class="w-full flex items-center gap-2 p-3 rounded-xl border-2 border-dashed border-slate-200">
        <div class="w-4 h-4 rounded shimmer"></div>
        <div class="h-3 rounded shimmer w-2/3"></div>
    </div>
</div>