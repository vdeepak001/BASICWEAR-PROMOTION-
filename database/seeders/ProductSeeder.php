<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use App\Models\QuoteItem;
use App\Models\QuoteRequest;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leisure = Category::where('slug', 'leisure')->first();
        $sports = Category::where('slug', 'sports')->first();
        $lanyards = Category::where('slug', 'lanyards')->first();
        $drinkware = Category::where('slug', 'drinkware')->first();
        $eco = Category::where('slug', 'eco')->first();
        $bags = Category::where('slug', 'bags')->first();
        $technology = Category::where('slug', 'technology')->first();
        $office = Category::where('slug', 'office')->first();
        $headwear = Category::where('slug', 'headwear')->first();
        $umbrellas = Category::where('slug', 'umbrellas')->first();

        // 1. Mini Magic Cube - 5CM (Item 817)
        $cube = Product::updateOrCreate(
            ['product_code' => '817'],
            [
                'name' => 'Mini Magic Cube - 5CM',
                'slug' => 'mini-magic-cube-5cm-817',
                'short_desc' => 'Novel custom 3D magic cube promotional puzzle with multi-dimensional dynamic branding.',
                'description' => "5cm Mini Magic Cubes are a novel way to market your special company message. These custom magic cubes open up and twist and turn to show off numerous ads for your company, while providing an interesting spectacle that will attract people to your booth.\n\n- Crafted from PS plastic and PP sticker panels.\n- Multi-dimensional brand storytelling platform with dynamic 3D exposure.\n- A delivery time of 4 - 5 weeks applies to this product by air freight.",
                'size' => '50mm x 50mm x 50mm',
                'material' => 'PS plastic, PP sticker panels',
                'print_method' => 'Digital Full Colour Print',
                'moq' => 250,
                'packing_info' => '250pcs/ctn; Carton Size: 55x30x30cm; NW/GW: 13/14KG',
                'lead_time' => '4 - 5 weeks (air freight)',
                'price_includes' => 'Price includes full colour printed on all sides',
                'is_new' => true,
                'is_hot' => true,
                'is_eco' => false,
                'is_active' => true,
            ]
        );

        if ($leisure) {
            $cube->categories()->syncWithoutDetaching([$leisure->id]);
        }

        $cube->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $cube->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 3.500, 'currency' => 'AUD'],
            ['product_id' => $cube->id, 'min_qty' => 500, 'max_qty' => 999, 'unit_price' => 3.000, 'currency' => 'AUD'],
            ['product_id' => $cube->id, 'min_qty' => 1000, 'max_qty' => 2499, 'unit_price' => 2.500, 'currency' => 'AUD'],
            ['product_id' => $cube->id, 'min_qty' => 2500, 'max_qty' => null, 'unit_price' => 2.000, 'currency' => 'AUD'],
        ]);

        $cube->images()->delete();
        ProductImage::insert([
            [
                'product_id' => $cube->id,
                'image_path' => 'https://sinovationpromotions.com/api/media/product/5cm%20Mini%20Magic%20Cube_1.jpg',
                'alt_text' => 'Mini Magic Cube Angle 1',
                'is_primary' => true,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $cube->id,
                'image_path' => 'https://sinovationpromotions.com/api/media/product/5cm%20Mini%20Magic%20Cube_2.jpg',
                'alt_text' => 'Mini Magic Cube Angle 2',
                'is_primary' => false,
                'sort_order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $cube->id,
                'image_path' => 'https://sinovationpromotions.com/api/media/product/5cm%20Mini%20Magic%20Cube_3.jpg',
                'alt_text' => 'Mini Magic Cube Angle 3',
                'is_primary' => false,
                'sort_order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => $cube->id,
                'image_path' => 'https://sinovationpromotions.com/api/media/product/5cm%20Mini%20Magic%20Cube_4.jpg',
                'alt_text' => 'Mini Magic Cube Angle 4',
                'is_primary' => false,
                'sort_order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


        // 2. RPET Eco Sublimated Lanyard (Item 701)
        $lanyard = Product::updateOrCreate(
            ['product_code' => '701'],
            [
                'name' => 'Recycled RPET Full Colour Lanyard - 20mm',
                'slug' => 'rpet-full-colour-lanyard-20mm-701',
                'short_desc' => 'Eco-friendly recycled polyester lanyard with vivid full-colour heat transfer print.',
                'description' => "Our custom RPET lanyards are manufactured using recycled plastic bottles, combining sustainable corporate messaging with vibrant dye sublimation printing.\n\n- Includes standard metal swivel clip and safety breakaway buckle.\n- Eco certified RPET webbing material.",
                'size' => '900mm x 20mm',
                'material' => '100% Recycled PET Polyester',
                'print_method' => 'Sublimation Heat Transfer',
                'moq' => 100,
                'packing_info' => '1000pcs/ctn; Carton Size: 48x32x30cm; 12KG',
                'lead_time' => '2 - 3 weeks',
                'price_includes' => 'Includes full colour print on both sides',
                'is_new' => false,
                'is_hot' => true,
                'is_eco' => true,
                'is_active' => true,
            ]
        );

        $cats = array_filter([$lanyards?->id, $eco?->id]);
        if (! empty($cats)) {
            $lanyard->categories()->syncWithoutDetaching($cats);
        }

        $lanyard->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $lanyard->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 1.850, 'currency' => 'AUD'],
            ['product_id' => $lanyard->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 1.250, 'currency' => 'AUD'],
            ['product_id' => $lanyard->id, 'min_qty' => 500, 'max_qty' => 999, 'unit_price' => 0.850, 'currency' => 'AUD'],
            ['product_id' => $lanyard->id, 'min_qty' => 1000, 'max_qty' => null, 'unit_price' => 0.620, 'currency' => 'AUD'],
        ]);

        $lanyard->variants()->delete();
        ProductVariant::insert([
            ['product_id' => $lanyard->id, 'variant_name' => 'Midnight Black', 'color_code' => '#111827', 'sku' => 'LAN-701-BLK'],
            ['product_id' => $lanyard->id, 'variant_name' => 'Royal Blue', 'color_code' => '#2563eb', 'sku' => 'LAN-701-BLU'],
            ['product_id' => $lanyard->id, 'variant_name' => 'Forest Green', 'color_code' => '#16a34a', 'sku' => 'LAN-701-GRN'],
        ]);

        $lanyard->images()->delete();
        ProductImage::create([
            'product_id' => $lanyard->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Lanyards_2025.jpg',
            'alt_text' => 'RPET Lanyard',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 3. Stainless Steel Insulated Bottle (Item 550)
        $bottle = Product::updateOrCreate(
            ['product_code' => '550'],
            [
                'name' => 'Vacuum Insulated Stainless Sports Bottle - 650ml',
                'slug' => 'vacuum-insulated-stainless-bottle-650ml-550',
                'short_desc' => 'Double-walled thermal sports bottle that keeps cold for 24h and hot for 12h.',
                'description' => "High grade 304 food-grade stainless steel bottle featuring powder coated finish and leakproof bamboo handle lid.\n\n- Laser engraving or rotary screen printing available.\n- BPA free and fully recyclable.",
                'size' => '260mm x 72mm (650ml)',
                'material' => '304 Stainless Steel & Bamboo',
                'print_method' => 'Laser Engraved or Rotary Screen Print',
                'moq' => 50,
                'packing_info' => '25pcs/ctn; Carton Size: 40x40x30cm; 10KG',
                'lead_time' => '3 - 4 weeks',
                'price_includes' => 'Includes 1 position laser engraving',
                'is_new' => true,
                'is_hot' => false,
                'is_eco' => true,
                'is_active' => true,
            ]
        );

        $bottleCats = array_filter([$drinkware?->id, $sports?->id, $eco?->id]);
        if (! empty($bottleCats)) {
            $bottle->categories()->syncWithoutDetaching($bottleCats);
        }

        $bottle->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $bottle->id, 'min_qty' => 50, 'max_qty' => 99, 'unit_price' => 12.500, 'currency' => 'AUD'],
            ['product_id' => $bottle->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 9.800, 'currency' => 'AUD'],
            ['product_id' => $bottle->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 8.200, 'currency' => 'AUD'],
            ['product_id' => $bottle->id, 'min_qty' => 500, 'max_qty' => null, 'unit_price' => 7.150, 'currency' => 'AUD'],
        ]);

        $bottle->variants()->delete();
        ProductVariant::insert([
            ['product_id' => $bottle->id, 'variant_name' => 'Matte Black', 'color_code' => '#1e293b', 'sku' => 'BOT-550-BLK'],
            ['product_id' => $bottle->id, 'variant_name' => 'Brushed Silver', 'color_code' => '#e2e8f0', 'sku' => 'BOT-550-SLV'],
            ['product_id' => $bottle->id, 'variant_name' => 'Olive Green', 'color_code' => '#3f6212', 'sku' => 'BOT-550-GRN'],
        ]);

        $bottle->images()->delete();
        ProductImage::create([
            'product_id' => $bottle->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Drinkware_2024.jpg',
            'alt_text' => 'Stainless Sports Bottle',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 4. Premium Calico Cotton Canvas Tote Bag (Item 602)
        $tote = Product::updateOrCreate(
            ['product_code' => '602'],
            [
                'name' => 'Premium Calico Cotton Canvas Tote Bag - 320gsm',
                'slug' => 'premium-calico-cotton-canvas-tote-bag-320gsm-602',
                'short_desc' => 'Durable heavy-weight natural cotton tote bag with reinforced handles.',
                'description' => "Natural 320gsm heavy canvas tote bag designed for conventions, retail events, and grocery shopping. Reinforced cross-stitched webbing handles ensure long-term usability.\n\n- 100% natural unbleached cotton.\n- Screen print or full colour digital transfer.",
                'size' => '380mm x 420mm x 100mm gusset',
                'material' => '100% Heavy Cotton Canvas (320gsm)',
                'print_method' => 'Screen Print or Direct to Film',
                'moq' => 100,
                'packing_info' => '100pcs/ctn; Carton Size: 45x40x35cm; 16KG',
                'lead_time' => '2 - 3 weeks',
                'price_includes' => 'Includes 1 colour screen print on 1 side',
                'is_new' => true,
                'is_hot' => true,
                'is_eco' => true,
                'is_active' => true,
            ]
        );

        $toteCats = array_filter([$bags?->id, $eco?->id]);
        if (! empty($toteCats)) {
            $tote->categories()->syncWithoutDetaching($toteCats);
        }

        $tote->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $tote->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 3.800, 'currency' => 'AUD'],
            ['product_id' => $tote->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 2.950, 'currency' => 'AUD'],
            ['product_id' => $tote->id, 'min_qty' => 500, 'max_qty' => 999, 'unit_price' => 2.350, 'currency' => 'AUD'],
            ['product_id' => $tote->id, 'min_qty' => 1000, 'max_qty' => null, 'unit_price' => 1.950, 'currency' => 'AUD'],
        ]);

        $tote->variants()->delete();
        ProductVariant::insert([
            ['product_id' => $tote->id, 'variant_name' => 'Natural Ecru', 'color_code' => '#fef08a', 'sku' => 'BAG-602-NAT'],
            ['product_id' => $tote->id, 'variant_name' => 'Jet Black', 'color_code' => '#0f172a', 'sku' => 'BAG-602-BLK'],
            ['product_id' => $tote->id, 'variant_name' => 'Navy Blue', 'color_code' => '#1e3a8a', 'sku' => 'BAG-602-NVY'],
        ]);

        $tote->images()->delete();
        ProductImage::create([
            'product_id' => $tote->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Bags_2025.jpg',
            'alt_text' => 'Canvas Tote Bag',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 5. Bamboo Qi Wireless Fast Charging Pad 15W (Item 420)
        $charger = Product::updateOrCreate(
            ['product_code' => '420'],
            [
                'name' => 'Natural Bamboo 15W Fast Wireless Charger',
                'slug' => 'natural-bamboo-15w-fast-wireless-charger-420',
                'short_desc' => 'Eco-chic real bamboo wireless charging pad with high speed 15W inductive coil.',
                'description' => "Crafted from renewable organic bamboo, this Qi-certified fast charging pad provides safe and rapid wireless power for iOS and Android smartphones.\n\n- Laser engraving creates a rich contrasting burnt wood finish.\n- Foreign object detection (FOD) and over-current protection.",
                'size' => '90mm Diameter x 9mm Thickness',
                'material' => 'Real Bamboo & Recycled ABS',
                'print_method' => 'Precision Laser Engraving or UV Print',
                'moq' => 50,
                'packing_info' => '100pcs/ctn; Carton Size: 42x32x22cm; 9KG',
                'lead_time' => '3 - 4 weeks',
                'price_includes' => 'Includes 1 position precision laser engraved logo',
                'is_new' => true,
                'is_hot' => true,
                'is_eco' => true,
                'is_active' => true,
            ]
        );

        $chargerCats = array_filter([$technology?->id, $eco?->id, $office?->id]);
        if (! empty($chargerCats)) {
            $charger->categories()->syncWithoutDetaching($chargerCats);
        }

        $charger->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $charger->id, 'min_qty' => 50, 'max_qty' => 99, 'unit_price' => 14.500, 'currency' => 'AUD'],
            ['product_id' => $charger->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 11.200, 'currency' => 'AUD'],
            ['product_id' => $charger->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 9.400, 'currency' => 'AUD'],
            ['product_id' => $charger->id, 'min_qty' => 500, 'max_qty' => null, 'unit_price' => 7.900, 'currency' => 'AUD'],
        ]);

        $charger->images()->delete();
        ProductImage::create([
            'product_id' => $charger->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Technology_2024.jpg',
            'alt_text' => 'Bamboo Wireless Charger',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 6. Brushed Heavy Cotton Baseball Cap (Item 205)
        $cap = Product::updateOrCreate(
            ['product_code' => '205'],
            [
                'name' => '6-Panel Brushed Heavy Cotton Promotional Cap',
                'slug' => '6-panel-brushed-heavy-cotton-promotional-cap-205',
                'short_desc' => 'Classic pro-style structured 6-panel cap with pre-curved peak and metal buckle closure.',
                'description' => "Our most popular headwear choice for corporate teams, golf days, and festivals. Made with 100% premium brushed heavy cotton twill with embroidered eyelets.\n\n- 3D embroidery or flat embroidery on front panel.\n- Padded sweatband for all-day comfort.",
                'size' => 'Standard Adult (Adjustable 58cm)',
                'material' => '100% Brushed Heavy Cotton Twill',
                'print_method' => '3D Puff Embroidery or Flat Embroidery',
                'moq' => 100,
                'packing_info' => '150pcs/ctn; Carton Size: 65x45x38cm; 14KG',
                'lead_time' => '3 weeks',
                'price_includes' => 'Includes up to 10,000 stitches custom embroidery',
                'is_new' => false,
                'is_hot' => true,
                'is_eco' => false,
                'is_active' => true,
            ]
        );

        if ($headwear) {
            $cap->categories()->syncWithoutDetaching([$headwear->id]);
        }

        $cap->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $cap->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 5.900, 'currency' => 'AUD'],
            ['product_id' => $cap->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 4.600, 'currency' => 'AUD'],
            ['product_id' => $cap->id, 'min_qty' => 500, 'max_qty' => 999, 'unit_price' => 3.750, 'currency' => 'AUD'],
            ['product_id' => $cap->id, 'min_qty' => 1000, 'max_qty' => null, 'unit_price' => 3.100, 'currency' => 'AUD'],
        ]);

        $cap->variants()->delete();
        ProductVariant::insert([
            ['product_id' => $cap->id, 'variant_name' => 'Charcoal', 'color_code' => '#334155', 'sku' => 'CAP-205-CHR'],
            ['product_id' => $cap->id, 'variant_name' => 'Navy', 'color_code' => '#1e3a8a', 'sku' => 'CAP-205-NVY'],
            ['product_id' => $cap->id, 'variant_name' => 'White', 'color_code' => '#f8fafc', 'sku' => 'CAP-205-WHT'],
            ['product_id' => $cap->id, 'variant_name' => 'Red', 'color_code' => '#dc2626', 'sku' => 'CAP-205-RED'],
        ]);

        $cap->images()->delete();
        ProductImage::create([
            'product_id' => $cap->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Bucket%20Hat.jpg',
            'alt_text' => 'Promotional Cotton Cap',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 7. Executive Windproof Auto Golf Umbrella 30" (Item 901)
        $umbrella = Product::updateOrCreate(
            ['product_code' => '901'],
            [
                'name' => 'Executive Windproof Auto Golf Umbrella - 30 Inch',
                'slug' => 'executive-windproof-auto-golf-umbrella-30-inch-901',
                'short_desc' => 'Oversized storm-proof canopy with fiberglass ribs and automatic quick release button.',
                'description' => "Engineered to withstand heavy gusts and downpours, this 30-inch double-canopy golf umbrella features flexible fiberglass ribs and an ergonomic soft EVA grip handle.\n\n- Screen printing on 1 to 8 panels.\n- High-density 190T pongee waterproof fabric.",
                'size' => '30 inch canopy radius (132cm open diameter)',
                'material' => '190T Pongee & Fiberglass Frame',
                'print_method' => 'Panel Screen Printing',
                'moq' => 50,
                'packing_info' => '25pcs/ctn; Carton Size: 102x22x20cm; 15KG',
                'lead_time' => '3 - 4 weeks',
                'price_includes' => 'Includes 1 colour print on 2 panels',
                'is_new' => true,
                'is_hot' => false,
                'is_eco' => false,
                'is_active' => true,
            ]
        );

        if ($umbrellas) {
            $umbrella->categories()->syncWithoutDetaching([$umbrellas->id]);
        }

        $umbrella->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $umbrella->id, 'min_qty' => 50, 'max_qty' => 99, 'unit_price' => 16.500, 'currency' => 'AUD'],
            ['product_id' => $umbrella->id, 'min_qty' => 100, 'max_qty' => 249, 'unit_price' => 13.800, 'currency' => 'AUD'],
            ['product_id' => $umbrella->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 11.900, 'currency' => 'AUD'],
            ['product_id' => $umbrella->id, 'min_qty' => 500, 'max_qty' => null, 'unit_price' => 9.800, 'currency' => 'AUD'],
        ]);

        $umbrella->images()->delete();
        ProductImage::create([
            'product_id' => $umbrella->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Umbrellas_2024.jpg',
            'alt_text' => 'Executive Golf Umbrella',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // 8. Soft-Touch Aluminium Stylus Ballpoint Pen (Item 310)
        $pen = Product::updateOrCreate(
            ['product_code' => '310'],
            [
                'name' => 'Soft-Touch Aluminium Stylus Ballpoint Pen',
                'slug' => 'soft-touch-aluminium-stylus-ballpoint-pen-310',
                'short_desc' => 'Rubberized matte executive metal ballpoint pen with precision touchscreen stylus tip.',
                'description' => "Our best-selling executive giveaway pen. Features a velvety soft-touch rubberized barrel, shiny gunmetal accents, and a silicone stylus for tablets and mobile devices.\n\n- Mirror-finish laser engraving reveals crisp bright chrome logo.\n- German Dokumental ink refill (approx 1,200m writing distance).",
                'size' => '142mm x 10mm',
                'material' => 'Aluminium with Soft-Feel Rubber Coating',
                'print_method' => 'Mirror Laser Engraving',
                'moq' => 250,
                'packing_info' => '500pcs/ctn; Carton Size: 34x32x18cm; 10KG',
                'lead_time' => '2 weeks',
                'price_includes' => 'Includes 1 position precision laser engraving',
                'is_new' => true,
                'is_hot' => true,
                'is_eco' => false,
                'is_active' => true,
            ]
        );

        if ($office) {
            $pen->categories()->syncWithoutDetaching([$office->id]);
        }

        $pen->prices()->delete();
        ProductPrice::insert([
            ['product_id' => $pen->id, 'min_qty' => 250, 'max_qty' => 499, 'unit_price' => 1.450, 'currency' => 'AUD'],
            ['product_id' => $pen->id, 'min_qty' => 500, 'max_qty' => 999, 'unit_price' => 1.150, 'currency' => 'AUD'],
            ['product_id' => $pen->id, 'min_qty' => 1000, 'max_qty' => 2499, 'unit_price' => 0.880, 'currency' => 'AUD'],
            ['product_id' => $pen->id, 'min_qty' => 2500, 'max_qty' => null, 'unit_price' => 0.690, 'currency' => 'AUD'],
        ]);

        $pen->variants()->delete();
        ProductVariant::insert([
            ['product_id' => $pen->id, 'variant_name' => 'Matte Black', 'color_code' => '#111827', 'sku' => 'PEN-310-BLK'],
            ['product_id' => $pen->id, 'variant_name' => 'Burgundy', 'color_code' => '#831843', 'sku' => 'PEN-310-BUR'],
            ['product_id' => $pen->id, 'variant_name' => 'Navy Blue', 'color_code' => '#1e3a8a', 'sku' => 'PEN-310-NVY'],
            ['product_id' => $pen->id, 'variant_name' => 'Emerald Green', 'color_code' => '#065f46', 'sku' => 'PEN-310-EMR'],
        ]);

        $pen->images()->delete();
        ProductImage::create([
            'product_id' => $pen->id,
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Car%20Air%20Freshener_2025.jpg',
            'alt_text' => 'Aluminium Stylus Pen',
            'is_primary' => true,
            'sort_order' => 1,
        ]);

        // Banners setup: Clear existing hero banners to build high-converting promo slides
        Banner::where('position', 'hero')->delete();

        Banner::create([
            'title' => 'Factory Direct Promotional Merchandise',
            'position' => 'hero',
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Bags_2025.jpg',
            'link_url' => '/?category=bags',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Eco-Friendly & Sustainable Giveaways',
            'position' => 'hero',
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Lanyards_2025.jpg',
            'link_url' => '/?filter=eco',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Banner::create([
            'title' => 'Custom Drinkware & Thermal Bottles',
            'position' => 'hero',
            'image_path' => 'https://sinovationpromotions.com/api/media/banner/Drinkware_2024.jpg',
            'link_url' => '/?category=drinkware',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        // Sample Quote Request
        $quote = QuoteRequest::updateOrCreate(
            ['quote_number' => 'QT-2026-0001'],
            [
                'name' => 'Sarah Jenkins',
                'company' => 'Apex Tech Global',
                'email' => 'sarah.jenkins@apextech.example.com',
                'phone' => '+61 412 345 678',
                'destination' => 'Sydney, NSW 2000, Australia',
                'message' => 'Hi team, looking for 500 mini magic cubes and 1,000 RPET lanyards for our annual conference in Melbourne. Need delivered by November 20.',
                'needed_by' => now()->addWeeks(5),
                'status' => 'new',
            ]
        );

        $quote->items()->delete();
        QuoteItem::create([
            'quote_id' => $quote->id,
            'product_id' => $cube->id,
            'quantity' => 500,
            'print_option' => 'Full Colour 6-sided print',
            'notes' => 'Conference branding artwork will follow vector specs.',
        ]);

        $blueVariant = $lanyard->variants()->where('variant_name', 'Royal Blue')->first();
        QuoteItem::create([
            'quote_id' => $quote->id,
            'product_id' => $lanyard->id,
            'variant_id' => $blueVariant?->id,
            'quantity' => 1000,
            'print_option' => 'White logo repeated on both sides',
            'notes' => 'Metal swivel clip + safety breakaway.',
        ]);
    }
}
