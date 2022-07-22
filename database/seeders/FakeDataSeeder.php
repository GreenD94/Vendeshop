<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\address;
use App\Models\Background;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\Comercial;
use App\Models\Icon;
use App\Models\Image;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use App\Models\Post;
use App\Models\Product;
use App\Models\PushNotificationEvent;
use App\Models\Size;
use App\Models\Stock;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
use Illuminate\Foundation\Testing\WithFaker;

class FakeDataSeeder extends Seeder
{
    use WithFaker;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->setUpFaker();

        PaymentType::factory()->create(["name" => "payu"]);
        PaymentType::factory()->create(["name" => "pago contra entrega"]);
        OrderStatus::factory()->create(["name" => "en espera"]);
        OrderStatus::factory()->create(["name" => "listo"]);
        OrderStatus::factory()->create(["name" => "cancelado"]);

        // $mainCategoryData = [
        //     [
        //         'name' => 'tienda',
        //         'is_main' => true,
        //         "color" => '#1cfc9a',
        //         'image' => 'https://images.vexels.com/media/users/3/223411/isolated/lists/7a8154be7b9b50412fc2cf63b636e370-icono-de-tienda-tienda-plana.png'
        //     ],
        //     [
        //         'name' => 'accesorios',
        //         'is_main' => true,
        //         "color" => '#c54727',
        //         'image' => 'https://cdn-icons-png.flaticon.com/512/3226/3226217.png'
        //     ],
        //     [
        //         'name' => 'computacion',
        //         'is_main' => true,
        //         "color" => '#10599a',
        //         'image' => 'https://cdn-icons-png.flaticon.com/512/1794/1794635.png'
        //     ],
        //     [
        //         'name' => 'biciletas',
        //         'is_main' => true,
        //         "color" => '#ad470d',
        //         'image' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f6/Bike-icon.svg/744px-Bike-icon.svg.png'
        //     ],
        // ];

        // $categories = collect();

        // foreach ($mainCategoryData as $key => $data) {
        //     $url = $data["image"];



        //     $image = Image::factory()->create(["url" => $data["image"]]);
        //     unset($data["image"]);
        //     $data["image_id"] = $image->id;
        //     $category = Category::factory()
        //         ->create($data);
        //     $categories->push($category);
        // }




        // $products = [
        //     [
        //         "name" => "ALCANCÍA ROBA MONEDAS PANDA AHORROS DINERO OSO",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-2-scaled.jpg",
        //                 'name' => "ALCANCÍA ROBA MONEDAS PANDA AHORROS DINERO OSO",
        //                 'color' => "#00FF00",
        //                 'size' => "mediana",
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/2-2-scaled.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/4-1.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-2.jpg"
        //                 ],
        //                 'videos' => ["https://youtu.be/3w4mm9HLTHc"]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "CAMARA DE ACCIÓN DEPORTIVA GoPro WIFI 2 LCD FULL HD 4K",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/DSC06085-scaled.jpg",
        //                 'name' => "CAMARA DE ACCIÓN DEPORTIVA GoPro WIFI 2 LCD FULL HD 4K",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/DSC06055-scaled.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/DSC06066-scaled.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "ARO DE LUZ LED 10 PULGADAS TRIPIE 1.70 M 3 TONOS DE LUZ LED",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-5.jpg",
        //                 'name' => "ARO DE LUZ LED 10 PULGADAS TRIPIE 1.70 M 3 TONOS DE LUZ LED",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/DSC05215-vende-shop.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/DSC05206-vende-shop.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "Mini ventilador USB eléctrico recargable portátil con luz LED",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/7-8.jpg",
        //                 'name' => "Mini ventilador USB eléctrico recargable verde portátil con luz LED",
        //                 'color' => "#00FF00",
        //                 'size' => null,
        //                 'colors' => ["#FFC0CB"],
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-95.jpg",

        //                 ],
        //                 'videos' => []
        //             ],
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-95.jpg",
        //                 'name' => "Mini ventilador USB eléctrico recargable rosa portátil con luz LED",
        //                 'color' => "#FFC0CB",
        //                 'size' => null,
        //                 'colors' => ["#00FF00"],
        //                 'sizes' => null,
        //                 'images' => [

        //                     "https://vende-shop.com/wp-content/uploads/2021/07/7-8.jpg"

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "RELOJ SCOTTIE PEARLS",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[1]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-5.jpg",
        //                 'name' => "RELOJ SCOTTIE PEARLS DORADO FUCSIA DAMA",
        //                 'color' => "#FFD700",
        //                 'size' => null,
        //                 'colors' => ["#000000", "#0000FF", "#0000FF"],
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-99.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-116.jpg",

        //                 ],
        //                 'videos' => ["https://youtu.be/N6ChHmyAdi0"]
        //             ],
        //             [
        //                 "category_id" => $categories[1]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/4-74.jpg",
        //                 'name' => "RELOJ SCOTTIE PEARLS NEGRO DAMA",
        //                 'color' => "#000000",
        //                 'size' => null,
        //                 'colors' => ["#FFD700", "#0000FF", "#0000FF"],
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-115.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-98.jpg",

        //                 ],
        //                 'videos' => ["https://youtu.be/N6ChHmyAdi0"]
        //             ],
        //             [
        //                 "category_id" => $categories[1]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/4-73.jpg",
        //                 'name' => "RELOJ SCOTTIE PEARLS AZUL DAMA",
        //                 'color' => "#0000FF",
        //                 'size' => null,
        //                 'colors' => ["#FFD700", "#FFD700"],
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-95.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-112.jpg",

        //                 ],
        //                 'videos' => ["https://youtu.be/N6ChHmyAdi0"]
        //             ],
        //             [
        //                 "category_id" => $categories[1]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/4-73.jpg",
        //                 'name' => "RELOJ SCOTTIE PEARLS AZUL DAMA",
        //                 'color' => "#0000FF",
        //                 'size' => null,
        //                 'colors' => ["#FFD700", "#FFD700"],
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-95.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-112.jpg",

        //                 ],
        //                 'videos' => ["https://youtu.be/N6ChHmyAdi0"]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "RELOJ SCOTTIE COLOMBIA CAFE DAMA",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-111.jpg",
        //                 'name' => "RELOJ SCOTTIE COLOMBIA CAFE DAMA",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/2-100.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-94.jpg"

        //                 ],
        //                 'videos' => []
        //             ],
        //         ]
        //     ],
        //     [
        //         "name" => "Reloj Inteligente Smartwatch Dz09 + Batería",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/4-51.jpg",
        //                 'name' => "Reloj Inteligente Smartwatch Dz09 + Batería",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-74.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/2-66.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "MINI CONSOLA MAQUINITA PORTATIL JUEGOS RETRO ARCADE CLASICOS",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/12/76ec677de62ab3b65cb1e30ba720970c.jpg",
        //                 'name' => "MINI CONSOLA MAQUINITA PORTATIL JUEGOS RETRO ARCADE CLASICOS",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/12/2-3.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/12/3-3.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "LAMPARA HUMIDIFICADOR DIFUSOR LUNA 3D COLORES BASE MADERA",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/humidificador_luna_1.jpg",
        //                 'name' => "LAMPARA HUMIDIFICADOR DIFUSOR LUNA 3D COLORES BASE MADERA",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-40.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/humidifcador-luna-aroma-esencias.jpg",

        //                 ],
        //                 'videos' => ["https://www.youtube.com/watch?v=YfzmUy78L7A"]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "CONSOLA DE JUEGOS MP5 X7 PANTALLA 5.1″MULTIFUNCIONAL",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1-122.jpg",
        //                 'name' => "CONSOLA DE JUEGOS MP5 X7 PANTALLA 5.1″MULTIFUNCIONAL",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-22.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3-17.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "DIADEMAS AURICULARES ESTÉREO BLUETOOTH INALÁMBRICO CON MICROFONO RADIO FM",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/Inal-mbrica-est-reo-auriculares-Bluetooth-XB450BT-sonido-Hifi-extra-bass-headphone-PARA-TODOS-Bluetooth-tablet.jpg_q50.jpg",
        //                 'name' => "DIADEMAS AURICULARES ESTÉREO BLUETOOTH INALÁMBRICO CON MICROFONO RADIO FM",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/6-3.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/5-6.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "JUEGO SABANAS GEMA HOME ROJA DOBLE 100% ALGODON",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2022/02/D_NQ_NP_616903-MCO42164774209_062020-V.jpg",
        //                 'name' => "JUEGO SABANAS GEMA HOME ROJA DOBLE 100% ALGODON",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2022/02/D_NQ_NP_605914-MCO45279565142_032021-V.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2022/02/29.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "RELOJ DAMA TORNASOL TOUCH CASIO BRILLOS",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[0]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/12/5-7.jpg",
        //                 'name' => "RELOJ DAMA TORNASOL TOUCH CASIO BRILLOS",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/12/5-2-2.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/12/5-3-1.jpg",

        //                 ],
        //                 'videos' => ["https://www.youtube.com/watch?v=x7qb2fLly8M&feature=youtu.be"]
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "Combo Teclado y Mouse Gamer Seisa DN H7033",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[2]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/2-82.jpg",
        //                 'name' => "Combo Teclado y Mouse Gamer Seisa DN H7033",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/2-82.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/1-93.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "BICICLETA RIN 29 BTM GARONNE ROMA WHITE-BLUE",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[3]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/08/1-20.jpg",
        //                 'name' => "BICICLETA RIN 29 BTM GARONNE ROMA WHITE-BLUE",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/08/Garonne-00276-scaled.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/08/Garonne-00278-scaled.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        //     [
        //         "name" => "AURICULARES BLUETOOTH P2 TRUE WIRELESS STEREO SPEAKER UNIT 10mm CALIDAD DE SONIDO ESTÉREO",
        //         "is_available" => true,
        //         "stocks" => [
        //             [
        //                 "category_id" => $categories[1]->id,
        //                 'cover_image' => "https://vende-shop.com/wp-content/uploads/2021/07/1.jpg",
        //                 'name' => "AURICULARES BLUETOOTH P2 TRUE WIRELESS STEREO SPEAKER UNIT 10mm CALIDAD DE SONIDO ESTÉREO",
        //                 'color' => null,
        //                 'size' => null,
        //                 'colors' => null,
        //                 'sizes' => null,
        //                 'images' => [
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/3.jpg",
        //                     "https://vende-shop.com/wp-content/uploads/2021/07/2.jpg",

        //                 ],
        //                 'videos' => []
        //             ]
        //         ]
        //     ],
        // ];
        // foreach ($products as $key => $productData) {

        //     $product = Product::factory();
        //     $product =   $product->create([
        //         "name" => $productData["name"],
        //         "is_available" => $productData["is_available"]
        //     ]);
        //     $stocksData = $productData["stocks"];

        //     foreach ($stocksData as $key => $stockData) {

        //         $stock = Stock::factory()
        //             ->create([
        //                 "cover_image_id" => null,
        //                 "name" => $stockData["name"],
        //                 "color_id" => null,
        //                 "size_id" => null
        //             ]);


        //         $stock->categories()->attach($stockData["category_id"]);

        //         if ($stockData["color"]) {
        //             $color = Color::create(["hex" => $stockData["color"]]);
        //             $stock->color_id = $color->id;
        //             $stock->colors()->attach($color->id);
        //         }
        //         if ($stockData["size"]) {
        //             $size = Size::create(["size" => $stockData["size"]]);
        //             $stock->size_id = $size->id;
        //             $stock->sizes()->attach($size->id);
        //         }


        //         if ($stockData["colors"]) {
        //             $colorsData = $stockData["colors"];
        //             foreach ($colorsData as $key => $colorData) {
        //                 $color = Color::create(["hex" => $colorData]);
        //                 $stock->colors()->attach($color->id);
        //             }
        //         }
        //         if ($stockData["sizes"]) {
        //             $sizesData = $stockData["sizes"];
        //             foreach ($sizesData as $key => $sizeData) {
        //                 $size = Size::create(["size" => $sizeData]);
        //                 $stock->size_id = $size->id;
        //                 $stock->sizes()->attach($size->id);
        //             }
        //         }

        //         $stock->product_id = $product->id;

        //         $images = collect();
        //         $images->push(Image::create([
        //             "url" => $stockData["cover_image"],
        //             "name" => "fakedata" . $stock->id . "|" . uniqid()
        //         ]));
        //         $stock->cover_image_id = $images[0]->id;
        //         $imagesDatas = $stockData["images"];
        //         foreach ($imagesDatas as $key => $imagesData) {
        //             $images->push(Image::create([
        //                 "url" => $imagesData,
        //                 "name" => "fakedata" . $stock->id . "|" . uniqid()
        //             ]));
        //         }
        //         foreach ($images as $key => $image) {
        //             $stock->images()->attach($image->id);
        //         }

        //         foreach ($stockData["videos"] as $key => $videoData) {
        //             $video = Video::create([
        //                 "url" => $videoData,
        //                 "name" => "fakedata" . $stock->id . "|" . uniqid()
        //             ]);
        //             $stock->videos()->attach($video->id);
        //         }

        //         $stock->save();
        //     }
        // }


        // $banners = [
        //     [
        //         "image" => "https://vende-shop.com/wp-content/uploads/2022/01/BANNER-ENERO-2022-NRO-1.jpg"
        //     ],
        //     [
        //         "image" => "https://vende-shop.com/wp-content/uploads/2022/01/BANNER-ENERO-2022-NRO-2.jpg"
        //     ],
        //     [
        //         "image" => "https://vende-shop.com/wp-content/uploads/2022/01/BANNER-ENERO-2022-NRO-4-1.jpg"
        //     ],
        //     [
        //         "image" => "https://vende-shop.com/wp-content/uploads/2021/08/BANNER-VENDE-SHP4.psdotro.jpg"
        //     ]
        // ];

        // foreach ($banners as $key => $banner) {
        //     $image = Image::factory()->create(["url" => $banner["image"]]);
        //     Banner::factory()->create(["is_favorite" => true, 'image_id' => $image->id, 'group_number' => 1]);
        // }
        // foreach ($banners as $key => $banner) {
        //     $image = Image::factory()->create(["url" => $banner["image"]]);
        //     Banner::factory()->create(["is_favorite" => true, 'image_id' => $image->id, 'group_number' => 2]);
        // }
        // foreach ($banners as $key => $banner) {
        //     $image = Image::factory()->create(["url" => $banner["image"]]);
        //     Banner::factory()->create(["is_favorite" => true, 'image_id' => $image->id, 'group_number' => 3]);
        // }


        // video::factory()->create([
        //     "url" => 'https://www.youtube.com/watch?v=vm3MyJJax4I&t=14s&ab_channel=VendeShop',
        //     "is_information" => true
        // ]);

        // Icon::factory()->create([
        //     "name" => $this->faker->word(),
        //     "is_favorite" => true,
        //     "color" => $this->faker->hexColor(),
        //     "image_id" => Image::factory()->create([
        //         'url' => 'https://www.vhv.rs/dpng/d/590-5909968_halloween-icon-png-free-png-download-transparent-halloween.png',
        //         'name' => $this->faker->word(),
        //     ]),
        // ]);

        // Ad::factory()->create([
        //     "name" => $this->faker->word(),
        //     "is_favorite" => true,
        //     "color" => $this->faker->hexColor(),
        //     "image_id" => Image::factory()->create([
        //         'url' => 'https://searchengineland.com/figz/wp-content/seloads/2017/02/google-adwords-green-outline-ad2-2017-1920-800x450.jpg',
        //         'name' => $this->faker->word(),
        //     ]),
        // ]);

        // Background::factory()->create([
        //     "is_favorite" => true,
        //     "color" => $this->faker->hexColor(),
        //     "image_id" => Image::factory()->create([
        //         'url' => 'https://searchengineland.com/figz/wp-content/seloads/2017/02/google-adwords-green-outline-ad2-2017-1920-800x450.jpg',
        //         'name' => $this->faker->word(),
        //     ]),
        // ]);


        // $adminUser = User::factory()->create([
        //     'first_name' => "postadmin",
        //     'last_name' => "postadmin",
        //     'email' => "postadmin@admin.com"
        // ]);
        // $adminUser->addresses()->attach(address::factory()->create(['is_favorite' => true])->id);
        // $posts = Post::factory()->count(3)->create(["is_main" => true])->each(function ($posts, $key) use ($adminUser) {
        //     $repliesId = Post::factory()->count(3)->state(new Sequence(
        //         ['user_id' =>  $adminUser->id, 'stock_id' => $posts->stock_id],
        //         ['user_id' => $posts->user_id, 'stock_id' => $posts->stock_id],
        //     ))->create()->modelKeys();
        //     $posts->replies()->attach($repliesId);
        // });

        Comercial::factory()->create(['name' => 'welcome_new',  'image_id' => null]);
        Comercial::factory()->create(['name' => 'welcome_old',  'image_id' => null]);

        PushNotificationEvent::factory()->create(['name' => 'news']);
        PushNotificationEvent::factory()->create(['name' => 'new_order']);
        PushNotificationEvent::factory()->create(['name' => 'order_state_change']);
        PushNotificationEvent::factory()->create(['name' => 'new_reply']);

        $top = Category::factory()->create(['name' => 'lo mas top']);
        $ofertas = Category::factory()->create(['name' => 'ofertas']);
        // $stock = Stock::find(2);
        // $stock->categories()->attach($top->id);

        // $stock = Stock::find(5);
        // $stock->categories()->attach($ofertas->id);
    }
}
