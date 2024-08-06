@extends('eventsaaspro::layouts.app')

@if (!empty($page))
    <!-- {{-- Page title --}}
    @section('title', $page->title)

    {{-- breadcrumb --}}
    @section('heading', $page->title) -->

    @section('content')
        <main>
            <!--Hero Banner-->
            <section class="cb_event-listing-banner" style="background-image: url({{config('filesystems.disks.s3.url').$page->image}});">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-12 col-12">
                            <h1> {{ $page->title }} </h1>
                            <p style="max-width: 750px;"> {{ $page->excerpt }} </p>
                        </div>
                    </div>
                </div>
            </section>
            <!--Hero Banner-->
            <!--ABOUT-->
            <section class="comic-book-pages">
                <div class="py-6 py-lg-8 ">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12  col-12">
                                {!! $page->body !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!--ABOUT END-->
        </main>
    @endsection
@endif
