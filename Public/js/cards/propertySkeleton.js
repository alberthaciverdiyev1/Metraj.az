
export function propertySkeletonCard() {
    return `
    <div class="cursor-pointer border border-[color:var(--border-color)] rounded-2xl overflow-hidden group relative transition-all duration-300 animate-pulse">
        <div class="relative overflow-hidden bg-gray-200 h-64 w-full rounded-t-2xl"></div>
        <div class="p-5">
            <div class="h-6 bg-gray-200 rounded w-3/4 mb-2"></div>
            <div class="h-4 bg-gray-200 rounded w-5/6 mb-2"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            </div>
            <div class="flex justify-between py-2 items-center border-t border-[color:var(--border-color)] pt-4">
                <div class="h-6 bg-gray-200 rounded w-1/3"></div>
                <div class="h-4 bg-gray-200 rounded w-1/4"></div>
            </div>
        </div>
    </div>`;
}