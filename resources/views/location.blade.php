@extends('layouts.myapp')
@section('content')
    <div class="mx-auto max-w-screen-xl ">
        <div class="flex md:flex-row flex-col justify-around  items-center px-6 pt-6">
            <div class="md:m-12 p-6 md:w-1/2">
                <img loading="lazy" src="/images/shop1.png" alt="shop image">
            </div>
            <div class=" relative md:m-12 m-6 md:w-1/2 md:p-6">

                <p>Selamat datang di tempat penyewaan mobil kami yang berlokasi strategis. Tempat kami menyediakan akses mudah dan pusat untuk semua kebutuhan penyewaan mobil Anda. Baik Anda penduduk lokal atau pelancong yang sedang menjelajahi daerah tersebut, menemukan kami sangatlah mudah.</p>
                <br>
                {{-- <p>Toko kami berlokasi strategis di dekat pusat transportasi utama, termasuk bandara, stasiun kereta api, dan terminal bus, sehingga sangat mudah bagi Anda untuk mengambil dan mengembalikan kendaraan sewaan Anda. Saat tiba, staf kami yang ramah akan menyambut Anda dengan hangat, memastikan proses penyewaan berjalan lancar dan efisien dari awal hingga akhir.</p> --}}
            </div>

        </div>
        {{-- <div class="flex md:flex-row flex-col justify-around  items-center px-6 pt-6">

            <div class="md:m-12 p-6 md:w-1/2 md:order-last ">
                <img loading="lazy" src="/images/shop_2.jpg" alt="shop image">
            </div>

            <div class=" relative md:m-12 m-6 md:w-1/2 md:p-6">
                <p>Located in a vibrant neighborhood, our shop is surrounded by a variety of amenities and attractions.
                    You'll find an array of restaurants, cafes, and shopping centers just a short distance away, perfect for
                    grabbing a bite to eat or running errands before or after your car rental experience.</p>
                <br>
                <p>With ample parking space available at our location, you can easily drive in, park your own vehicle, and
                    drive out with your rental car seamlessly. We prioritize your convenience, and our location is designed
                    to minimize any inconvenience or delays, allowing you to focus on your journey ahead.</p>
            </div>


        </div> --}}
        <div class=" p-3 mb-8">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3464.9188930414984!2d-8.984100424763344!3d29.722108575087578!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xdb6b1b1966dcdef%3A0x2bf9c55ec4ef96f9!2sIsta%20Tafraout!5e0!3m2!1sen!2sma!4v1686498234799!5m2!1sen!2sma"
                class="w-full h-96" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>

    </div>
@endsection
