@extends('base::layouts.master')
@section('title',config('dealer.name'))
@section('content')
    <h2 class="intro-y text-lg font-medium mt-10">
        @lang('$STUDLY_NAME$s')
    </h2>
    <div class="grid grid-cols-12 gap-6 mt-5">
        <div class="intro-y col-span-12 flex flex-wrap sm:flex-nowrap items-center mt-2">
            <a href="{{route('admin:dealers.create')}}" class="btn btn-primary shadow-md mr-2">@lang('Add new dealer')</a>
            @include('base::includes.header-filter',['models' => $dealers])
        </div>
        <!-- BEGIN: Data List -->
        <div class="intro-y grid grid-cols-12 gap-6 mt-5 col-span-12">
            @each('dealer::includes.cart',$dealers,'dealer')
        </div>
        <!-- END: Data List -->
        <!-- BEGIN: Pagination -->
        {{$dealers->links('pagination')}}
        <!-- END: Pagination -->
    </div>
@endsection
