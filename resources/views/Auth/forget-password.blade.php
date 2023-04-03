@extends('layouts.login')
@section('content')
@include('partials.session')
<div class="container  px-6 pb-12 pt-6  h-full">
  <div class="grid grid-cols-1 place-items-center h-full g-6 text-gray-800 mt-20">
    <form action="{{ route('update_forget_password') }}" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="overflow-hidden shadow rounded ">
        <div class="bg-white px-4 py-5 rounded sm:p-6">
          <div class="text-xl mb-2 ">
            <span class="font-semibold	">Reset Password</span>
          </div>
          <div class="border border-slite-50 border-dashed	  w-full mb-8"></div>
          <div class="grid grid-cols-12 ">
            <div class="lg:col-span-6 col-span-12">
              <img class="w-80 h-40" src="{{ asset('images/forget_password.svg') }}" alt="">
            </div>
            <div class="lg:col-span-6 col-span-12">
              <div class="col-span-12 ">
                <label for="phone" class="block text-sm font-medium text-gray-700">Mobile Number</label>
                <input value="{{ old('phone') }}" minlength="8" maxlength="18" autocomplete="off" id="phone" type="text" name="phone"
                class="mt-1.5 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-gray-200 sm:text-sm"
                placeholder="Mobile number with country code" />
                @error('phone')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
              <div class="col-span-12 relative">
                <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
                <input type="password" placeholder="********" name="password" value="{{ old('password') }}" id="password"
                  autocomplete="given-name"
                  class="mt-1.5 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-gray-200 sm:text-sm">
                <span class="absolute top-8 right-4">
                  <i class="fa-solid fa-eye app-text-color" onclick="showPassword(2)"></i>
                </span>
                @error('password')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
              <div class="col-span-12 ">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm New
                  Password</label>
                <input placeholder="********" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}"
                  id="password_confirmation" autocomplete="given-name"
                  class="mt-1.5 block w-full rounded-md border border-gray-300 bg-white py-2 px-3 shadow-sm focus:border-gray-500 focus:outline-none focus:ring-gray-200 sm:text-sm">
                @error('password_confirmation')
                <p class="text-red-500 text-xs mt-2">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
        </div>
        <div class="bg-gray-100 px-4 py-3 text-right sm:px-6">
          <a href="{{ route('login_page') }}"
            class="bg-gray-200 mr-4 text-black inline-flex justify-center rounded-md border border-transparent  py-2 px-10 text-sm font-medium  shadow-sm hover:bg-gray-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">Back</a>
          <button
            class="app-bg-color inline-flex justify-center rounded-md border border-transparent  py-2 px-10 text-sm font-medium text-white shadow-sm hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-gray-200 focus:ring-offset-2">Update</button>
        </div>
      </div>
    </form>
  </div>
</div>
</section>
<script>
  function showPassword(id) {
        var x = document.getElementById("password");
        if (x.type === "password") {
        x.type = "text";
        } else {
        x.type = "password";
        }
        }
</script>
@endsection