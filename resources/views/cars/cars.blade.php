@extends('layouts.myapp')
@section('content')
    <div class="bg-gray-200 mx-auto max-w-screen-xl mt-10 p-3 rounded-md shadow-xl">
        <form action="{{route('carSearch')}}">
            <div class="flex justify-center md:flex-row flex-col md:gap-28 gap-4">
                <div class="flex justify-evenly md:flex-row flex-col md:gap-16 gap-2">
                    <input type="text" placeholder="brand" name="brand"
                    class="block  rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6"
                >
                <input type="text" placeholder="model" name="model"
                    class="block  rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6"
                >
                <input type="number" placeholder="$ minimum price " name="min_price"
                    class="block  rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6"
                >
                <input type="number" placeholder="$ maximum price " name="max_price"
                    class="block  rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-pr-400 sm:text-sm sm:leading-6"
                >
                </div>
                <button class="bg-pr-400 rounded-md text-white p-2 w-20 font-medium hover:bg-pr-500" type="submit" placeholder="brand"> Search</button>
            </div>
        </form>
    </div>
    <div class="mt-6 mb-2 grid md:grid-cols-3  justify-center items-center mx-auto max-w-screen-xl">
        @foreach ($cars as $car)
            <div
                        class="relative md:m-10 flex w-full max-w-xs flex-col overflow-hidden rounded-lg border border-gray-100 bg-white shadow-md">
                        <a class="relative mx-3 mt-3 flex h-60 overflow-hidden rounded-xl" href="{{ route('car.reservation', ['car' => $car->id]) }}">
                            <img loading="lazy" class="object-cover" src="{{ $car->image }}" alt="product image" />
                            <span
                                class="absolute top-0 left-0 m-2 rounded-full bg-red-700 px-2 text-center text-sm font-medium text-white">{{ $car->reduce }}
                                %
                                OFF</span>
                        </a>
                        <div class="mt-4 px-5 pb-5">
                            <div >
                                <h5 class=" font-bold text-xl tracking-tight text-slate-900">{{ $car->brand }}
                                    {{ $car->model }}</h5>
                            </div>
                            <div class="mt-2 mb-5 flex flex-col items-start justify-between">
                                <p>
                                    <span class="text-3xl font-bold text-slate-900">Rp.{{ number_format($car->price_per_day, 0, ',', '.') }}</span>
                                    <span
                                        class="text-sm text-slate-900 line-through"> Rp.{{ number_format(($car->price_per_day * 100) / (100 - $car->reduce), 0, ',', '.') }}
                                    </span>
                                </p>

                                <div class="flex items-center">
                                    @for ($i = 0; $i < $car->stars; $i++)
                                        <svg aria-hidden="true" class="h-5 w-5 text-red-700" fill="currentColor"
                                            viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                            </path>
                                        </svg>
                                    @endfor
                                    <span
                                        class="mr-2 ml-3 rounded bg-red-700 px-2.5 py-0.5 text-xs font-semibold">{{ $car->stars }}.0</span>
                                </div>
                                <div class="flex items-center">
                                    <div class="col-3 me-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                                        </svg>{{ $car->passenger }}
                                    </div>
                                    <div class="col-3 me-2 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-door-closed" viewBox="0 0 16 16">
                                            <path d="M3 2a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v13h1.5a.5.5 0 0 1 0 1h-13a.5.5 0 0 1 0-1H3zm1 13h8V2H4z"/>
                                            <path d="M9 9a1 1 0 1 0 2 0 1 1 0 0 0-2 0"/>
                                        </svg>{{ $car->door }}
                                    </div>
                                    <div class="col-3 me-2 flex items-center">
                                        <svg fill="#ff0000" height="16" width="16" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve" stroke="#ff0000"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <g> <polygon points="360.55,131.774 360.55,239.304 272.696,239.304 272.696,131.774 239.304,131.774 239.304,239.304 151.45,239.304 151.45,131.774 118.058,131.774 118.058,380.227 151.45,380.227 151.45,272.696 239.304,272.696 239.304,380.227 272.696,380.227 272.696,272.696 393.942,272.696 393.942,131.774 "></polygon> </g> </g> <g> <g> <path d="M437.02,74.98C388.667,26.628,324.38,0,256,0S123.333,26.628,74.98,74.98C26.628,123.333,0,187.619,0,256 s26.628,132.667,74.98,181.02C123.333,485.372,187.62,512,256,512s132.667-26.628,181.02-74.98 C485.372,388.667,512,324.381,512,256S485.372,123.333,437.02,74.98z M256,478.609c-122.746,0-222.609-99.862-222.609-222.609 S133.254,33.391,256,33.391S478.609,133.254,478.609,256S378.746,478.609,256,478.609z"></path> </g> </g> <g> <g> <path d="M393.775,351.234c5.845-4.11,9.315-11.05,9.315-18.539c0-11.05-9.133-22.466-21.826-22.466h-29.771v64.838h17.808v-19.907 h6.666l12.329,19.908h20.09L393.775,351.234z M380.534,339.636h-11.233v-13.881h10.776c2.192,0,4.932,2.465,4.932,6.94 C385.008,337.079,382.726,339.636,380.534,339.636z"></path> </g> </g> </g></svg>

                                          {{ $car->gear }}
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('car.reservation', ['car' => $car->id]) }}"
                                class="flex items-center justify-center rounded-md bg-slate-900 hover:bg-red-700 px-5 py-2.5 text-center text-sm font-medium text-white  focus:outline-none focus:ring-4 focus:ring-blue-300">
                                <svg id="thisicon" class="mr-4 h-6 w-6" xmlns="http://www.w3.org/2000/svg" height="1em"
                                    viewBox="0 0 512 512">
                                    <style>
                                        #thisicon {
                                            fill: #ffffff
                                        }
                                    </style>
                                    <path
                                        d="M184 24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H96c-35.3 0-64 28.7-64 64v16 48V448c0 35.3 28.7 64 64 64H416c35.3 0 64-28.7 64-64V192 144 128c0-35.3-28.7-64-64-64H376V24c0-13.3-10.7-24-24-24s-24 10.7-24 24V64H184V24zM80 192H432V448c0 8.8-7.2 16-16 16H96c-8.8 0-16-7.2-16-16V192zm176 40c-13.3 0-24 10.7-24 24v48H184c-13.3 0-24 10.7-24 24s10.7 24 24 24h48v48c0 13.3 10.7 24 24 24s24-10.7 24-24V352h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H280V256c0-13.3-10.7-24-24-24z" />
                                </svg>
                                Reserve</a>
                        </div>
                    </div>
        @endforeach
    </div>


    <div class="flex justify-center mb-12 w-full">
        {{ $cars->links('pagination::tailwind') }}
    </div>
@endsection
