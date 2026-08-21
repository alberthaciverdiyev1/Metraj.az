@extends('layouts.app')

@section('title', ($agent->user?->name ?? 'Agent') . ' - Rieltor Profili - Metraj.az')

@section('content')
<div class="bg-slate-50 py-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center text-xs text-slate-500 mb-6 gap-2">
            <a href="/" class="hover:text-blue-600">Ana səhifə</a>
            @if($agent->agency)
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
                <a href="/agentlik/{{ $agent->agency->slug }}" class="hover:text-blue-600">{{ $agent->agency->name }}</a>
            @endif
            <i class="fa-solid fa-chevron-right text-[10px] text-slate-300"></i>
            <span class="text-slate-800 font-medium">{{ $agent->user?->name }}</span>
        </nav>

        <!-- Agent Profile Card -->
        <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-sm mb-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-6">
                    <div class="w-24 h-24 rounded-3xl bg-slate-900 text-white font-black text-4xl flex items-center justify-center shadow-lg">
                        <i class="fa-solid fa-user-tie text-blue-500"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $agent->user?->name }}</h1>
                        <p class="text-sm font-medium text-slate-500 mt-1">
                            {{ $agent->position ?? 'Peşəkar Rieltor' }}
                            @if($agent->agency)
                                • <a href="/agentlik/{{ $agent->agency->slug }}" class="text-blue-600 font-semibold hover:underline">{{ $agent->agency->name }}</a>
                            @endif
                        </p>
                        <div class="flex items-center gap-3 mt-3">
                            <span class="bg-slate-100 text-slate-700 text-xs px-3 py-1 rounded-xl font-medium">
                                {{ $properties->total() }} aktiv elan
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Direct Contact Buttons -->
                <div class="flex flex-wrap items-center gap-3">
                    @if($agent->phone)
                        <a href="tel:{{ $agent->phone }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-6 py-3 rounded-2xl transition flex items-center gap-2 shadow-md">
                            <i class="fa-solid fa-phone"></i> {{ $agent->phone }}
                        </a>
                    @endif
                    @if($agent->whatsapp)
                        @php
                            $wa = preg_replace('/[^0-9]/', '', $agent->whatsapp);
                        @endphp
                        <a href="https://wa.me/{{ $wa }}" target="_blank" class="bg-emerald-500 hover:bg-emerald-600 text-white font-bold px-6 py-3 rounded-2xl transition flex items-center gap-2 shadow-md">
                            <i class="fa-brands fa-whatsapp text-lg"></i> WhatsApp
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Agent Listings -->
        <div>
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-slate-900">{{ $agent->user?->name }} tərəfindən idarə olunan elanlar ({{ $properties->total() }})</h2>
            </div>

            @if($properties->isEmpty())
                <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 text-slate-500">
                    Bu agentə aid heç bir aktiv elan tapılmadı.
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($properties as $property)
                        <div class="bg-white rounded-3xl overflow-hidden border border-slate-200 hover:shadow-xl transition flex flex-col group">
                            <a href="/elan/{{ $property->slug }}" class="relative h-56 bg-slate-100 block overflow-hidden">
                                <img src="{{ !empty($property->images) ? $property->images[0] : 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?auto=format&fit=crop&w=800&q=80' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute bottom-3 right-3 bg-slate-900/80 backdrop-blur text-white text-xs px-2.5 py-1 rounded-lg font-mono">
                                    #{{ $property->code }}
                                </div>
                            </a>
                            <div class="p-6 flex-grow flex flex-col justify-between">
                                <div>
                                    <span class="text-2xl font-black text-blue-600 block mb-1">
                                        {{ number_format($property->price, 0, '.', ' ') }} {{ $property->currency }}
                                    </span>
                                    <a href="/elan/{{ $property->slug }}" class="font-bold text-slate-800 text-base hover:text-blue-600 transition line-clamp-1">
                                        {{ $property->title }}
                                    </a>
                                </div>
                                <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                    <span>{{ $property->rooms }} otaq • {{ $property->area }} m²</span>
                                    <a href="/elan/{{ $property->slug }}" class="text-blue-600 font-semibold hover:underline">Baxış</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $properties->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
