@props([
    'blog' => null,
    'slug' => null,
    'images' => [],
    'category' => null,
    'date' => '',
    'name' => '',
    'excerpt' => '',
])

@php
    $blogSlug = $blog ? $blog->slug : $slug;
    $blogTitle = $blog ? $blog->title : $name;
    $blogDate = $blog ? $blog->formatted_date : $date;
    $blogCategory = $blog ? $blog->category : (is_object($category) ? ($category->name ?? '') : $category);
    $defaultBlogImg = asset('images/section-contact.jpg');
    $blogImage = $blog ? ($blog->cover_image ?: $defaultBlogImg) : (!empty($images[0]) ? $images[0] : $defaultBlogImg);
    $blogExcerpt = $blog ? $blog->excerpt : $excerpt;
@endphp

<article onclick="window.location.href='{{ route('blog.show', $blogSlug) }}'"
         class="blog-card bg-white rounded-2xl sm:rounded-3xl border border-gray-100/90 overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col group cursor-pointer h-full">
    
    {{-- Image & Badge --}}
    <div class="blog-card-image relative overflow-hidden aspect-[16/10] bg-orange-50 shrink-0">
        <img src="{{ $blogImage }}"
             alt="{{ $blogTitle }}"
             loading="lazy"
             decoding="async"
             width="400"
             height="250"
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />
        
        @if(!empty($blogCategory))
            <span class="absolute top-3 left-3 bg-orange-500 text-white text-[11px] font-semibold px-3 py-1 rounded-full shadow-md backdrop-blur-sm">
                {{ $blogCategory }}
            </span>
        @endif
    </div>

    {{-- Info & Content --}}
    <div class="blog-card-info p-5 sm:p-6 flex flex-col flex-1">
        <div class="flex items-center gap-2 text-xs text-gray-500 mb-2.5">
            <span class="flex items-center gap-1.5 font-medium">
                <i class="bi bi-calendar3 text-[var(--primary)]"></i>
                {{ $blogDate }}
            </span>
            <span class="text-gray-300">•</span>
            <span class="flex items-center gap-1 text-gray-400">
                <i class="bi bi-clock"></i> {{ __('blog.read_time_min', ['min' => 3]) }}
            </span>
            @if(($blog && $blog->views_count > 0) || !empty($viewsCount))
                <span class="text-gray-300">•</span>
                <span class="flex items-center gap-1 text-gray-400">
                    <i class="bi bi-eye"></i> {{ number_format($blog ? $blog->views_count : $viewsCount) }}
                </span>
            @endif
        </div>

        <h3 class="font-semibold text-gray-900 text-base sm:text-lg group-hover:text-[var(--primary)] transition-colors line-clamp-2 leading-snug">
            {{ $blogTitle }}
        </h3>

        @if(!empty($blogExcerpt))
            <p class="blog-card-excerpt text-xs sm:text-sm text-gray-500 line-clamp-2 mt-2 leading-relaxed">
                {{ $blogExcerpt }}
            </p>
        @endif

        <div class="mt-auto pt-4 flex items-center justify-between border-t border-gray-100/70">
            <a href="{{ route('blog.show', $blogSlug) }}" onclick="event.stopPropagation()"
               class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-[var(--primary)] group-hover:text-orange-700 transition">
                {{ __('blog.read_more') }}
                <i class="bi bi-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </a>
            <span class="w-8 h-8 rounded-full bg-orange-50 text-[var(--primary)] group-hover:bg-[var(--primary)] group-hover:text-white flex items-center justify-center transition">
                <i class="bi bi-chevron-right text-xs"></i>
            </span>
        </div>
    </div>
</article>
