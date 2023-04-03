{{-- {{ dd($coupons) }}  --}}
<div class="grid grid-cols-1 place-items-start  px-4 pt-0 pb-4 lg:py-0 lg:pt-0   w-auto h-auto g-6 text-gray-800">
    <div class="grid  grid-cols-1 w-full">
        <div class="grid grid-cols-1">
            <div class="grid  grid-cols-1 gap-4 place-items-start  pt-0 pb-2">
                <h2 class="lg:text-lg text-lg text-gray-700 font-semibold">Coupons</h2>
            </div>
        </div>
    </div>
</div>
<div class="grid lg:grid-cols-1 sm:grid-cols-2 sm:gap-6 grid-cols-1  px-4  pb-10  w-full h-auto  ">
    @if(isset($coupons) && is_array($coupons) && $coupons != [])    
        @foreach ($coupons as $coupon)
        <div class="flex app-bg-color lg:w-2/4 w-full h-40 rounded-md py-2 shadow-md shadow-gray-400">
            <div class="flex items-center justify-center w-48  pl-3  ">
                <img src="{{ isset($coupon['standard_image_1']) && $coupon['standard_image_1'] !='' ? $coupon['standard_image_1'] : asset('images/undraw_gifts_0ceh.svg') }}" class="w-44 h-40 rounded" alt="Coupen_img" srcset="">
            </div>
            <div class="text-white lg:w-80 md:w-52   px-3">
                 <div class="flex flex-col justify-left">
                    <h1 class="text-3xl font-bold ">
                        {{ $coupon['series_name'] ?? '' }}
                    </h1>
                    <h2 class="text-md font-thin   ml-0.5" title="{{ $coupon['standard_description'] ??'' }}" style="display: -webkit-box;max-width: 250px;height:50px;-webkit-line-clamp: 2;-webkit-box-orient: vertical;overflow: hidden;text-overflow: ellipsis;">
                        {{ $coupon['standard_description'] ?? ''}} 
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit. Temporibus natus repudiandae dignissimos commodi quaerat nulla cumque, quibusdam, alias, ullam maiores ipsa saepe. Facere, placeat molestias! Reprehenderit incidunt facilis natus! A?
                    </h2>
                        @php
                            $generator = new Picqer\Barcode\BarcodeGeneratorHTML();
                        @endphp
                    <div class="bg-white  py-1.5 lg:px-2  w-44 md:px-1   text-center">
                        <div class="grid grid-cols-1 place-items-center">
                            <h1 class="text-gray-800 font-bold">{!! $generator->getBarcode($coupon['code'] ?? '', $generator::TYPE_CODE_128) !!}  </h1>
                        </div>
                    </div>
                 </div>
            </div>
        </div>
        @endforeach
    @else
       No Coupen Found For This Account
    @endif
</div>