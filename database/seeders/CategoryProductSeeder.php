<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategoryProductSeeder extends Seeder
{
    /**
     * Top every category up to five distinct products so a category click
     * has a full product list to show.
     */
    public function run(): void
    {
        $this->call(CategorySeeder::class);

        foreach ($this->catalog() as $slug => $items) {
            $this->fillCategory($slug, $items);
        }

        $this->attachFlaggedProducts('new', 'is_new');
        $this->attachFlaggedProducts('hot', 'is_hot');
    }

    /**
     * @param  list<array<string, mixed>>  $items
     */
    private function fillCategory(string $slug, array $items): void
    {
        $category = Category::where('slug', $slug)->first();

        if (! $category) {
            return;
        }

        foreach ($items as $item) {
            if ($category->products()->count() >= 5) {
                break;
            }

            $existing = Product::where('product_code', $item['code'])->first();
            if ($existing && $existing->categories()->where('categories.id', $category->id)->exists()) {
                continue;
            }

            $this->storeProduct($item, [$category->id]);
        }
    }

    /**
     * Link up to five flagged products into New / Hot so those categories list products too.
     */
    private function attachFlaggedProducts(string $slug, string $column): void
    {
        $category = Category::where('slug', $slug)->first();

        if (! $category) {
            return;
        }

        $linked = $category->products()->count();

        if ($linked >= 5) {
            return;
        }

        $products = Product::query()
            ->where($column, true)
            ->where('is_active', true)
            ->whereDoesntHave('categories', function ($query) use ($slug) {
                $query->where('categories.slug', $slug);
            })
            ->latest()
            ->take(5 - $linked)
            ->get();

        foreach ($products as $product) {
            $product->categories()->syncWithoutDetaching([$category->id]);
        }
    }

    /**
     * @param  array<string, mixed>  $item
     * @param  list<int>  $categoryIds
     */
    private function storeProduct(array $item, array $categoryIds): void
    {
        $code = $item['code'];
        $moq = (int) $item['moq'];
        $price = (float) $item['price'];

        $product = Product::updateOrCreate(
            ['product_code' => $code],
            [
                'name' => $item['name'],
                'slug' => Str::slug($item['name']).'-'.Str::lower($code),
                'short_desc' => $item['short'],
                'description' => $item['short']."\n\n- Built for logo branding on events, teams, and corporate gifts.\n- Print method: {$item['print']}.\n- Minimum order {$moq} units. Lead time {$item['lead']}.",
                'size' => $item['size'],
                'material' => $item['material'],
                'print_method' => $item['print'],
                'moq' => $moq,
                'packing_info' => $item['packing'],
                'lead_time' => $item['lead'],
                'price_includes' => $item['includes'],
                'is_new' => (bool) ($item['new'] ?? false),
                'is_hot' => (bool) ($item['hot'] ?? false),
                'is_eco' => (bool) ($item['eco'] ?? false),
                'is_active' => true,
            ]
        );

        $product->categories()->syncWithoutDetaching($categoryIds);

        $product->prices()->delete();
        $now = now();
        ProductPrice::insert([
            ['product_id' => $product->id, 'min_qty' => $moq, 'max_qty' => ($moq * 2) - 1, 'unit_price' => round($price, 3), 'currency' => 'AUD', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $product->id, 'min_qty' => $moq * 2, 'max_qty' => ($moq * 5) - 1, 'unit_price' => round($price * 0.86, 3), 'currency' => 'AUD', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $product->id, 'min_qty' => $moq * 5, 'max_qty' => ($moq * 10) - 1, 'unit_price' => round($price * 0.74, 3), 'currency' => 'AUD', 'created_at' => $now, 'updated_at' => $now],
            ['product_id' => $product->id, 'min_qty' => $moq * 10, 'max_qty' => null, 'unit_price' => round($price * 0.62, 3), 'currency' => 'AUD', 'created_at' => $now, 'updated_at' => $now],
        ]);

        $product->variants()->delete();
        $variants = [];
        foreach ($item['colors'] as $index => $color) {
            $variants[] = [
                'product_id' => $product->id,
                'variant_name' => $color[0],
                'color_code' => $color[1],
                'sku' => $code.'-'.($index + 1),
                'image_path' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }
        ProductVariant::insert($variants);

        $product->images()->delete();
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $item['image'],
            'alt_text' => $item['name'],
            'is_primary' => true,
            'sort_order' => 1,
        ]);
    }

    /**
     * Five product ideas for every catalogue category.
     * Categories that already have products only receive enough new rows to reach five.
     *
     * @return array<string, list<array<string, mixed>>>
     */
    private function catalog(): array
    {
        $navy = [['Navy', '#1e3a8a'], ['White', '#f8fafc'], ['Black', '#111827']];
        $black = [['Black', '#111827'], ['Red', '#dc2626'], ['Royal Blue', '#2563eb']];

        return [
            'lanyards' => [
                $this->item('LAN-201', 'Dye Sublimation Polyester Lanyard - 15mm', 'Narrow full-colour polyester lanyard with metal swivel hook and safety breakaway.', '900mm x 15mm', 'Polyester webbing', 'Dye Sublimation', 100, 1.45, '1000pcs/ctn', '2 - 3 weeks', 'Full colour print both sides', 'https://images.unsplash.com/photo-1586281380349-632531db7ed4?auto=format&fit=crop&w=800&q=80', $black, true, true, false),
                $this->item('LAN-202', 'Reflective Tubular Safety Lanyard - 12mm', 'High-visibility tubular lanyard with reflective yarn for site and event staff.', '900mm x 12mm', 'Tubular polyester with reflective thread', 'Screen Print', 100, 1.15, '1500pcs/ctn', '2 - 3 weeks', '1 colour print on one side', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80', [['Safety Yellow', '#facc15'], ['Orange', '#f97316'], ['Black', '#111827']]),
                $this->item('LAN-203', 'Bamboo Fibre Eco Lanyard - 20mm', 'Soft bamboo-blend lanyard with a recycled metal clip for sustainable events.', '900mm x 20mm', 'Bamboo fibre blend', 'Sublimation Heat Transfer', 100, 1.95, '1000pcs/ctn', '3 weeks', 'Full colour print both sides', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=800&q=80', [['Natural', '#d6d3d1'], ['Forest', '#166534'], ['Navy', '#1e3a8a']], true, false, true),
                $this->item('LAN-204', 'Wide Satin Event Lanyard - 25mm', 'Smooth satin lanyard with a wide print area for conferences and festivals.', '900mm x 25mm', 'Satin polyester', 'Dye Sublimation', 150, 1.65, '800pcs/ctn', '2 - 3 weeks', 'Full colour print on one side', 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80', $navy),
                $this->item('LAN-205', 'Phone-Loop Woven Lanyard - 20mm', 'Woven lanyard with a detachable phone patch so guests can carry a device hands-free.', '450mm x 20mm', 'Woven polyester', 'Woven Jacquard or Sublimation', 100, 2.35, '1000pcs/ctn', '3 - 4 weeks', 'Woven logo or full colour print', 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=800&q=80', $black),
            ],
            'id' => [
                $this->item('ID-301', 'Retractable Badge Reel - 60cm Cord', 'Round badge reel with a metal clip and 60cm retractable cord for staff passes.', '40mm reel', 'ABS plastic and metal clip', 'Epoxy Dome or Pad Print', 100, 1.85, '500pcs/ctn', '2 - 3 weeks', '1 colour logo on the reel face', 'https://images.unsplash.com/photo-1586282391129-76a6df230234?auto=format&fit=crop&w=800&q=80', $black, true, false, false),
                $this->item('ID-302', 'Rigid Landscape PVC Card Holder', 'Clear rigid card holder for landscape ID cards, with a slot for a lanyard strap.', '105mm x 74mm', 'Rigid PVC', 'Full Colour Insert Card', 100, 0.95, '1000pcs/ctn', '2 weeks', 'Printed insert card included', 'https://images.unsplash.com/photo-1554224155-6726b3ff858f?auto=format&fit=crop&w=800&q=80', [['Clear', '#e2e8f0'], ['Frosted', '#94a3b8']]),
                $this->item('ID-303', 'Aluminium Metal Staff Badge', 'Brushed aluminium name badge with a pin and a printed colour window.', '70mm x 25mm', 'Aluminium', 'UV Print', 50, 3.40, '400pcs/ctn', '3 weeks', 'Full colour print on the window', 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&w=800&q=80', [['Silver', '#cbd5e1'], ['Black', '#111827'], ['Gold', '#d4a017']]),
                $this->item('ID-304', 'Soft PVC Zipper Neck Pouch', 'Water-resistant neck pouch that keeps an ID card, cash, and a room key together.', '140mm x 120mm', 'Soft PVC', 'Screen Print', 100, 2.10, '500pcs/ctn', '3 weeks', '1 colour print on the front', 'https://images.unsplash.com/photo-1556742049-0cfed4f6a45d?auto=format&fit=crop&w=800&q=80', $navy),
                $this->item('ID-305', 'RFID Fabric Event Wristband', 'Soft fabric wristband with an RFID inlay for access control at festivals and conferences.', '350mm x 15mm', 'Polyester with RFID inlay', 'Sublimation', 200, 1.75, '1000pcs/ctn', '3 - 4 weeks', 'Full colour fabric print', 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=80', $black, false, true, false),
            ],
            'headwear' => [
                $this->item('CAP-206', 'Washed Cotton Bucket Hat', 'Packable bucket hat with an all-over or front embroidery area for outdoor crews.', 'One size, 58cm', 'Washed cotton twill', 'Embroidery', 50, 6.40, '100pcs/ctn', '3 weeks', 'Up to 8,000 stitches on the front', 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=800&q=80', [['Khaki', '#a16207'], ['Black', '#111827'], ['Navy', '#1e3a8a']], false, true, false),
                $this->item('CAP-207', 'Double-Layer Acrylic Knit Beanie', 'Warm cuff beanie with a folded embroidery panel for winter campaigns.', 'One size', 'Acrylic knit', 'Embroidery', 100, 4.20, '200pcs/ctn', '3 weeks', 'Up to 5,000 stitches on the cuff', 'https://images.unsplash.com/photo-1576871337622-98d48d1cf531?auto=format&fit=crop&w=800&q=80', [['Charcoal', '#334155'], ['Red', '#dc2626'], ['Navy', '#1e3a8a']]),
                $this->item('CAP-208', 'Foam Trucker Cap with Mesh Back', 'Structured foam-front trucker with a breathable mesh back and plastic snap.', 'Adjustable adult', 'Foam front and polyester mesh', 'Printed Patch or Embroidery', 50, 5.50, '150pcs/ctn', '3 weeks', 'Full colour patch or embroidery', 'https://images.unsplash.com/photo-1588850561407-ed78c282e89b?auto=format&fit=crop&w=800&q=80', [['Black / White', '#111827'], ['Navy / White', '#1e3a8a'], ['Red / White', '#dc2626']], true, false, false),
                $this->item('CAP-209', 'Performance Sports Sun Visor', 'Lightweight visor with a sweatband, made for golf days and fun runs.', 'Adjustable adult', 'Performance polyester', 'Embroidery or Heat Transfer', 100, 3.90, '200pcs/ctn', '3 weeks', 'Front embroidery included', 'https://images.unsplash.com/photo-1521369909029-2afed882baee?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Neon Yellow', '#facc15'], ['Black', '#111827']]),
                $this->item('CAP-210', 'Natural Straw Sun Hat with Band', 'Wide-brim straw hat with a coloured fabric band ready for a woven label.', '57cm', 'Paper straw and cotton band', 'Woven Label or Screen Print', 50, 7.80, '50pcs/ctn', '4 weeks', 'Printed band or woven label', 'https://images.unsplash.com/photo-1514327605112-b887c0e61c0a?auto=format&fit=crop&w=800&q=80', [['Natural', '#e7e5e4'], ['Navy Band', '#1e3a8a']]),
            ],
            'sports' => [
                $this->item('SPT-401', 'Microfibre Sports Towel - 30x80cm', 'Quick-dry gym towel with a hemmed branding strip along the short edge.', '300mm x 800mm', 'Microfibre', 'Sublimation or Embroidery', 100, 3.20, '200pcs/ctn', '3 weeks', 'Full colour print on one end', 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?auto=format&fit=crop&w=800&q=80', $navy, false, true, false),
                $this->item('SPT-402', 'Non-Woven Drawstring Gym Sack', 'Lightweight drawstring sack for race packs, clubs, and school sports days.', '340mm x 420mm', 'Non-woven polypropylene', 'Screen Print', 100, 1.35, '300pcs/ctn', '2 - 3 weeks', '1 colour print on one side', 'https://images.unsplash.com/photo-1556817411-31ae72fa3ea0?auto=format&fit=crop&w=800&q=80', [['Red', '#dc2626'], ['Royal', '#2563eb'], ['Black', '#111827']]),
                $this->item('SPT-403', 'Adjustable Speed Skipping Rope', 'Steel-core skipping rope with foam handles and a colour-matched printed sleeve.', '3 metre adjustable', 'Steel cable and EVA foam', 'Pad Print on handles', 50, 2.90, '200pcs/ctn', '3 weeks', '1 colour logo on both handles', 'https://images.unsplash.com/photo-1517963879433-6ad2b056d712?auto=format&fit=crop&w=800&q=80', $black),
                $this->item('SPT-404', 'Evaporative Cooling Neck Towel', 'PVA cooling towel that stays cold when wet, with a printed hang tag.', '900mm x 300mm', 'PVA cooling fabric', 'Heat Transfer', 100, 2.40, '300pcs/ctn', '3 weeks', 'Full colour print on one end', 'https://images.unsplash.com/photo-1571902943202-507ec2618e8f?auto=format&fit=crop&w=800&q=80', [['Blue', '#0284c7'], ['Lime', '#84cc16'], ['Grey', '#64748b']], true, false, false),
                $this->item('SPT-405', 'Inflatable Cheering Stick - Pair', 'A pair of bang-bang inflatable sticks for grandstands and sponsor activations.', '600mm each', 'PE inflatable film', 'Screen Print', 100, 1.10, '500pcs/ctn', '3 weeks', '1 colour print on each stick', 'https://images.unsplash.com/photo-1461896836934-ffe607ba6851?auto=format&fit=crop&w=800&q=80', [['Yellow', '#facc15'], ['Red', '#dc2626'], ['Blue', '#2563eb']]),
            ],
            'fans' => [
                $this->item('FAN-501', 'Bamboo Rib Folding Hand Fan', 'Traditional folding fan with a full-colour paper leaf and bamboo ribs.', '21cm closed', 'Bamboo and paper', 'Full Colour Paper Print', 100, 1.55, '400pcs/ctn', '3 weeks', 'Full colour artwork on the leaf', 'https://images.unsplash.com/photo-1527482797697-8795b05a13fe?auto=format&fit=crop&w=800&q=80', [['Natural Bamboo', '#d6d3d1'], ['Black Ribs', '#111827']], true, false, true),
                $this->item('FAN-502', 'USB Mini Desk Fan', 'Quiet desk fan with a USB plug and a printed hub cap for office giveaways.', '140mm diameter', 'ABS plastic', 'Pad Print or UV Print', 50, 4.80, '100pcs/ctn', '3 - 4 weeks', '1 colour logo on the hub', 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Blue', '#2563eb'], ['Black', '#111827']]),
                $this->item('FAN-503', 'Misting Sports Bottle Fan', 'Hand fan with a water-mist bottle for summer events and outdoor queues.', '90mm fan head', 'PP plastic', 'Screen Print', 50, 3.60, '100pcs/ctn', '3 - 4 weeks', '1 colour print on the bottle', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Orange', '#f97316'], ['Sky Blue', '#38bdf8']]),
                $this->item('FAN-504', 'Full Colour Paper Paddle Fan', 'Flat paddle fan on a wooden stick, printed edge to edge for festivals.', '280mm x 180mm', 'Art card and wooden stick', 'Offset Full Colour', 250, 0.72, '1000pcs/ctn', '2 - 3 weeks', 'Full colour both sides', 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=800&q=80', [['Full Colour', '#ed7100']]),
                $this->item('FAN-505', 'Rechargeable Bladeless Neck Fan', 'Hands-free neck fan with a rechargeable battery and a laser-mark panel.', 'Neckband, 3 speeds', 'ABS and silicone', 'Laser Engraving', 50, 12.90, '50pcs/ctn', '4 weeks', '1 position laser logo', 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Black', '#111827'], ['Pink', '#f472b6']], false, true, false),
            ],
            'bags' => [
                $this->item('BAG-603', 'Cotton Drawstring Shoe Bag', 'Compact cotton drawstring bag sized for shoes, bottles, or event kits.', '350mm x 420mm', 'Cotton canvas 140gsm', 'Screen Print', 100, 2.15, '200pcs/ctn', '2 - 3 weeks', '1 colour print on one side', 'https://images.unsplash.com/photo-1547949003-9792a18a2601?auto=format&fit=crop&w=800&q=80', [['Natural', '#f5f5f4'], ['Black', '#111827'], ['Navy', '#1e3a8a']]),
                $this->item('BAG-604', '15 Inch Padded Laptop Sleeve', 'Felt-look laptop sleeve with a zipper and a debossed patch area.', 'Fits 15 inch', 'Recycled felt', 'Deboss or Heat Transfer', 50, 6.90, '100pcs/ctn', '3 - 4 weeks', 'Debossed patch included', 'https://images.unsplash.com/photo-1525547719571-a2d4ac8945e2?auto=format&fit=crop&w=800&q=80', [['Grey', '#64748b'], ['Black', '#111827'], ['Navy', '#1e3a8a']], false, true, true),
                $this->item('BAG-605', 'Insulated Lunch Cooler Tote', 'Foil-lined cooler tote with a front pocket sized for a branded card.', '250mm x 220mm x 140mm', '600D polyester and PEVA lining', 'Screen Print or Transfer', 50, 5.40, '100pcs/ctn', '3 weeks', '1 colour print on the front pocket', 'https://images.unsplash.com/photo-1544816155-12df9643f363?auto=format&fit=crop&w=800&q=80', $navy),
                $this->item('BAG-606', 'Everyday 18L Day Backpack', 'Simple day pack with a laptop sleeve and a padded embroidery panel.', '18 litre', '600D polyester', 'Embroidery or Transfer', 50, 11.50, '40pcs/ctn', '4 weeks', 'Front embroidery up to 8,000 stitches', 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Navy', '#1e3a8a'], ['Olive', '#3f6212']], true, false, false),
                $this->item('BAG-607', 'Clear Stadium Security Tote', 'Transparent PVC tote that meets stadium bag rules, with colour trim.', '300mm x 280mm x 120mm', 'Clear PVC and webbing', 'Screen Print on trim', 100, 3.30, '150pcs/ctn', '3 weeks', '1 colour print on the trim', 'https://images.unsplash.com/photo-1590874103328-eac38a683ce7?auto=format&fit=crop&w=800&q=80', [['Clear / Black', '#111827'], ['Clear / Red', '#dc2626']]),
            ],
            'drinkware' => [
                $this->item('DRK-551', 'Double Wall Travel Tumbler - 400ml', 'Spill-resistant tumbler with a slider lid and a powder-coated barrel.', '400ml', '304 stainless steel', 'Laser Engraving or Rotary Print', 50, 8.90, '40pcs/ctn', '3 - 4 weeks', '1 position laser engraving', 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=800&q=80', [['Matte Black', '#1e293b'], ['White', '#f8fafc'], ['Coral', '#fb7185']], true, true, false),
                $this->item('DRK-552', '11oz Ceramic Latte Mug', 'Classic glazed mug with a C-handle and a large wrap print area.', '11oz / 325ml', 'Ceramic', 'Full Colour Wrap', 72, 3.80, '36pcs/ctn', '3 weeks', 'Full colour wrap included', 'https://images.unsplash.com/photo-1514228742587-6b1558fcca3d?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Black', '#111827'], ['Yellow', '#facc15']]),
                $this->item('DRK-553', 'Collapsible Silicone Travel Cup - 350ml', 'A cup that folds flat for carry-on kits, with a leak-resistant lid.', '350ml', 'Food-grade silicone and PP lid', 'Pad Print', 50, 4.60, '100pcs/ctn', '3 - 4 weeks', '1 colour logo on the band', 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?auto=format&fit=crop&w=800&q=80', [['Slate', '#475569'], ['Mint', '#34d399'], ['Navy', '#1e3a8a']], false, false, true),
                $this->item('DRK-554', 'Borosilicate Glass Bottle - 500ml', 'Clear glass bottle with a bamboo lid and a coloured silicone sleeve.', '500ml', 'Borosilicate glass and bamboo', 'Screen Print on sleeve', 48, 5.20, '24pcs/ctn', '4 weeks', '1 colour print on the sleeve', 'https://images.unsplash.com/photo-1523362628745-0c100150b504?auto=format&fit=crop&w=800&q=80', [['Clear / Natural', '#e7e5e4'], ['Sleeve Grey', '#64748b']], false, false, true),
                $this->item('DRK-555', 'Enamel Camp Mug - 350ml', 'Speckled enamel mug with a steel rim for markets, trails, and camps.', '350ml', 'Enamel coated steel', 'Screen Print', 48, 4.10, '48pcs/ctn', '3 - 4 weeks', '1 colour print on one side', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=800&q=80', [['Speckled White', '#f8fafc'], ['Speckled Navy', '#1e3a8a'], ['Speckled Green', '#166534']]),
            ],
            'leisure' => [
                $this->item('LES-818', 'Fleece Picnic Blanket with Carry Strap', 'Waterproof-backed fleece blanket that rolls into its own strap.', '1500mm x 1300mm', 'Polar fleece and PEVA back', 'Embroidery or Transfer', 25, 14.50, '20pcs/ctn', '4 weeks', 'Corner embroidery included', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=800&q=80', [['Red Check', '#dc2626'], ['Navy', '#1e3a8a'], ['Grey', '#64748b']], false, true, false),
                $this->item('LES-819', 'Custom Tuck Box Playing Cards', 'A linen-finish deck in a tuck box, printed with a full-colour back design.', 'Standard poker size', '300gsm linen card', 'Offset Full Colour', 100, 3.40, '200pcs/ctn', '3 - 4 weeks', 'Full colour card back and box', 'https://images.unsplash.com/photo-1529480780361-c8cb81eb5735?auto=format&fit=crop&w=800&q=80', [['Custom Print', '#ed7100']]),
                $this->item('LES-820', 'Translucent Inflatable Beach Ball', '6-panel beach ball with a valve, printed on two panels.', '30cm diameter', 'PVC', 'Screen Print', 100, 1.25, '200pcs/ctn', '3 weeks', '1 colour on 2 panels', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', [['Clear / Blue', '#38bdf8'], ['Clear / Yellow', '#facc15'], ['Clear / Pink', '#f472b6']]),
                $this->item('LES-821', 'Polarised Promo Sunglasses', 'CE polarised sunglasses with a printed arm and a soft pouch.', 'Adult', 'PC frame and polarised lens', 'Pad Print', 50, 3.70, '200pcs/ctn', '3 - 4 weeks', '1 colour logo on one arm', 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Tortoise', '#92400e'], ['Clear', '#e2e8f0']], true, false, false),
                $this->item('LES-822', '175g Flying Disc', 'Professional weight flying disc with a full-colour top print.', '175g, 27cm', 'HDPE', 'Full Colour Disc Print', 50, 2.80, '150pcs/ctn', '3 weeks', 'Full colour top print', 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Red', '#dc2626'], ['Yellow', '#facc15']]),
            ],
            'office' => [
                $this->item('OFF-311', 'A5 Hardcover Notebook - 80 Sheets', 'PU hardcover notebook with a ribbon, elastic close, and 80 lined sheets.', 'A5', 'PU cover and 80gsm paper', 'Deboss or UV Print', 50, 4.90, '50pcs/ctn', '3 weeks', 'Debossed cover logo', 'https://images.unsplash.com/photo-1517842645767-c639042777db?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Navy', '#1e3a8a'], ['Burgundy', '#9f1239']], true, false, false),
                $this->item('OFF-312', 'Bamboo Desk Organiser', 'Small bamboo caddy for pens, clips, and a phone, with a laser panel.', '180mm x 90mm x 80mm', 'Bamboo', 'Laser Engraving', 25, 8.40, '40pcs/ctn', '4 weeks', '1 position laser logo', 'https://images.unsplash.com/photo-1586075010923-2dd4570fb338?auto=format&fit=crop&w=800&q=80', [['Natural', '#d6d3d1']], false, false, true),
                $this->item('OFF-313', 'Full Colour Sticky Note Cube', '500-sheet sticky cube with a full-colour wrap on all four sides.', '75mm cube', 'Paper', 'Offset Full Colour', 50, 3.10, '100pcs/ctn', '2 - 3 weeks', 'Full colour wrap', 'https://images.unsplash.com/photo-1456327102063-fb5054efe647?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Yellow', '#fde68a']]),
                $this->item('OFF-314', 'Stainless Desk Ruler - 30cm', 'Brushed steel 30cm ruler with a cork backing so it stays put on a desk.', '300mm', 'Stainless steel and cork', 'Laser Engraving', 100, 2.60, '200pcs/ctn', '3 weeks', 'Laser logo on the steel face', 'https://images.unsplash.com/photo-1455390582262-044cdead277a?auto=format&fit=crop&w=800&q=80', [['Silver', '#cbd5e1'], ['Black', '#111827']]),
                $this->item('OFF-315', 'Pastel Highlighter Set of 4', 'Wallet of four pastel highlighters with a printed sleeve.', '4 colours', 'PP barrels', 'Pad Print on sleeve', 100, 1.90, '200pcs/ctn', '3 weeks', 'Full colour sleeve print', 'https://images.unsplash.com/photo-1513542789411-b6a5d4f31634?auto=format&fit=crop&w=800&q=80', [['Pastel Mix', '#f9a8d4']], false, true, false),
            ],
            'pets' => [
                $this->item('PET-601', 'Reversible Dog Bandana', 'Triangle dog bandana in two cotton prints with a full-colour face.', 'S / M / L', 'Cotton', 'Sublimation', 50, 3.50, '200pcs/ctn', '3 weeks', 'Full colour one side', 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?auto=format&fit=crop&w=800&q=80', [['Red', '#dc2626'], ['Navy', '#1e3a8a'], ['Green', '#166534']], true, false, false),
                $this->item('PET-602', 'Collapsible Silicone Pet Bowl', 'Carabiner travel bowl that clips to a leash, with a printed base.', '350ml', 'Food-grade silicone', 'Pad Print', 50, 2.80, '150pcs/ctn', '3 weeks', '1 colour logo inside the base', 'https://images.unsplash.com/photo-1548199973-03cce0bbc87b?auto=format&fit=crop&w=800&q=80', [['Slate', '#475569'], ['Blue', '#2563eb'], ['Pink', '#f472b6']]),
                $this->item('PET-603', 'Nylon Dog Leash - 150cm', 'Padded-handle leash with a woven label near the clip.', '150cm x 20mm', 'Nylon webbing', 'Woven Label', 50, 4.70, '100pcs/ctn', '3 - 4 weeks', 'Woven label included', 'https://images.unsplash.com/photo-1530281700549-e82e7bf110d6?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Red', '#dc2626'], ['Blue', '#2563eb']], false, true, false),
                $this->item('PET-604', 'Waste Bag Dispenser with Clip', 'Bone-shaped dispenser loaded with 15 bags and a lobster clip.', 'Includes 15 bags', 'ABS plastic', 'Pad Print', 100, 1.65, '300pcs/ctn', '3 weeks', '1 colour logo on the lid', 'https://images.unsplash.com/photo-1544568100-847a948585b9?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Orange', '#f97316'], ['Teal', '#0f766e']]),
                $this->item('PET-605', 'Cotton Rope Tug Toy', 'Knotted cotton rope toy with a woven brand keeper.', '25cm', 'Cotton rope', 'Woven Label', 50, 2.40, '150pcs/ctn', '3 weeks', 'Woven label on the loop', 'https://images.unsplash.com/photo-1583511655857-d19b40a7a54e?auto=format&fit=crop&w=800&q=80', [['Natural', '#e7e5e4'], ['Navy / Natural', '#1e3a8a']]),
            ],
            'tools' => [
                $this->item('TOL-701', '8-in-1 Stainless Multi Tool', 'Pocket multi tool with pliers, knife, and a laser panel on the handle.', 'Closed 100mm', 'Stainless steel', 'Laser Engraving', 50, 7.20, '100pcs/ctn', '3 - 4 weeks', '1 position laser logo', 'https://images.unsplash.com/photo-1504148455328-c376907d081c?auto=format&fit=crop&w=800&q=80', [['Silver', '#cbd5e1'], ['Black Oxide', '#111827']], false, true, false),
                $this->item('TOL-702', '5 Metre Rubber Grip Tape Measure', 'Auto-lock tape with a rubberised case and a belt clip.', '5 metre', 'ABS and steel tape', 'Pad Print or Dome', 50, 3.30, '100pcs/ctn', '3 weeks', '1 colour logo on the case', 'https://images.unsplash.com/photo-1530124566582-a618bc2615dc?auto=format&fit=crop&w=800&q=80', [['Black / Orange', '#f97316'], ['Yellow', '#facc15'], ['Black', '#111827']]),
                $this->item('TOL-703', 'COB Rechargeable Work Torch', 'Magnetic work light with a COB strip, a spot beam, and a USB charge port.', '120mm', 'Aluminium and ABS', 'Laser Engraving', 50, 8.60, '80pcs/ctn', '4 weeks', 'Laser logo on the body', 'https://images.unsplash.com/photo-1558611848-73f7eb4001a1?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Yellow', '#facc15']], true, false, false),
                $this->item('TOL-704', '6 Piece Precision Screwdriver Set', 'Interchangeable bits in a printed tin for desk and IT kits.', '6 bits', 'Chrome vanadium and tin', 'Full Colour Tin Print', 50, 4.40, '80pcs/ctn', '3 - 4 weeks', 'Full colour tin lid', 'https://images.unsplash.com/photo-1581147036324-c1c89c2c8b5c?auto=format&fit=crop&w=800&q=80', [['Silver Tin', '#e2e8f0']]),
                $this->item('TOL-705', 'Aluminium Carabiner with Split Ring', 'D-shape carabiner for keys, with a laser mark on the spine.', '60mm', 'Aluminium', 'Laser Engraving', 100, 1.20, '500pcs/ctn', '2 - 3 weeks', 'Laser logo on the spine', 'https://images.unsplash.com/photo-1522163182402-834f871fd851?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Blue', '#2563eb'], ['Red', '#dc2626'], ['Silver', '#cbd5e1']]),
            ],
            'technology' => [
                $this->item('TEC-421', '10000mAh Aluminium Power Bank', 'Slim power bank with a digital battery readout and dual USB output.', '10000mAh', 'Aluminium', 'Laser Engraving', 25, 16.80, '40pcs/ctn', '4 weeks', '1 position laser logo', 'https://images.unsplash.com/photo-1609091839311-d5365f9ff1c5?auto=format&fit=crop&w=800&q=80', [['Space Grey', '#334155'], ['White', '#f8fafc'], ['Black', '#111827']], false, true, false),
                $this->item('TEC-422', '4-Port USB-C Hub', 'Pocket hub with HDMI, two USB-A ports, and one USB-C port.', '110mm', 'Aluminium', 'Laser Engraving', 25, 18.50, '50pcs/ctn', '4 weeks', 'Laser logo on the top face', 'https://images.unsplash.com/photo-1625842268584-8f3296236761?auto=format&fit=crop&w=800&q=80', [['Silver', '#cbd5e1'], ['Space Grey', '#334155']]),
                $this->item('TEC-423', 'Fabric Bluetooth Mini Speaker', '3-watt speaker with a carry loop and a rubber print patch.', '3W', 'Recycled fabric and ABS', 'Deboss or Rubber Patch', 25, 11.40, '40pcs/ctn', '4 weeks', 'Debossed logo on the patch', 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?auto=format&fit=crop&w=800&q=80', [['Charcoal', '#334155'], ['Blue', '#1d4ed8'], ['Sand', '#d6d3d1']], true, false, false),
                $this->item('TEC-424', 'Braided 3-in-1 Charging Cable', 'One cable with USB-C, Lightning, and USB-A ends, in a printed pouch.', '1 metre', 'Nylon braid', 'Print on pouch', 50, 4.30, '200pcs/ctn', '3 weeks', 'Full colour pouch print', 'https://images.unsplash.com/photo-1583863788434-e58a36330cf0?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['White', '#f8fafc'], ['Red', '#dc2626']]),
                $this->item('TEC-425', 'Webcam Privacy Cover 3 Pack', 'Sliding covers for laptops and webcams, packed on a printed card.', '3 covers', 'ABS plastic', 'Full Colour Card', 100, 1.15, '500pcs/ctn', '2 - 3 weeks', 'Full colour backing card', 'https://images.unsplash.com/photo-1587825140708-dfaf72ae4b04?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['White', '#f8fafc']]),
            ],
            'umbrellas' => [
                $this->item('UMB-902', '21 Inch Auto Open Folding Umbrella', 'Compact folding umbrella that opens with one button and prints on two panels.', '21 inch, 3 fold', '190T pongee and steel shaft', 'Panel Screen Print', 50, 8.40, '50pcs/ctn', '3 - 4 weeks', '1 colour print on 2 panels', 'https://images.unsplash.com/photo-1534308143481-c55f00be8bd7?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Navy', '#1e3a8a'], ['Red', '#dc2626']], true, true, false),
                $this->item('UMB-903', 'Kids Safety Dome Umbrella', 'Rounded safety tips and a reflective rim for school and junior sports days.', '18 inch', 'Pongee and fiberglass', 'Panel Screen Print', 50, 6.20, '50pcs/ctn', '3 - 4 weeks', '1 colour print on 1 panel', 'https://images.unsplash.com/photo-1515694346937-94d85e41e6f0?auto=format&fit=crop&w=800&q=80', [['Yellow', '#facc15'], ['Blue', '#38bdf8'], ['Pink', '#f472b6']]),
                $this->item('UMB-904', '60 Inch Beach Shelter Umbrella', 'Tilt beach umbrella with an anchor screw and a valance print area.', '60 inch', 'Polyester and aluminium pole', 'Screen Print on valance', 10, 28.00, '10pcs/ctn', '4 - 5 weeks', '1 colour valance print', 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', [['Royal Stripe', '#2563eb'], ['Red Stripe', '#dc2626'], ['Natural', '#f5f5f4']]),
                $this->item('UMB-905', 'RPET Compact Windproof Umbrella', 'Folding umbrella made from recycled bottles, with a matching RPET sleeve.', '21 inch, 3 fold', 'Recycled PET pongee', 'Panel Screen Print', 50, 9.60, '50pcs/ctn', '4 weeks', '1 colour print on 2 panels and the sleeve', 'https://images.unsplash.com/photo-1473186578172-c141e67994e5?auto=format&fit=crop&w=800&q=80', [['Forest', '#166534'], ['Black', '#111827'], ['Navy', '#1e3a8a']], true, false, true),
                $this->item('UMB-906', 'Inverted Closing Storm Umbrella', 'Reverse-close canopy that keeps water off the user when getting into a car.', '23 inch', 'Double canopy pongee and fiberglass', 'Panel Screen Print', 25, 13.40, '25pcs/ctn', '4 weeks', '1 colour print on 2 panels', 'https://images.unsplash.com/photo-1501595091296-3aa970afb3ff?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['Charcoal', '#334155'], ['Red', '#dc2626']], false, true, false),
            ],
            'socks' => [
                $this->item('SCK-801', 'Cushion Crew Socks - Pair', 'Mid-calf crew socks with a knit-in logo on the cuff.', 'Adult 6-11', 'Combed cotton blend', 'Knitted Logo', 60, 4.80, '100 pairs/ctn', '4 weeks', 'Knitted logo on the cuff', 'https://images.unsplash.com/photo-1586350977771-b3b0abd50c82?auto=format&fit=crop&w=800&q=80', [['Black', '#111827'], ['White', '#f8fafc'], ['Navy', '#1e3a8a']], true, false, false),
                $this->item('SCK-802', 'Low-Cut Trainer Ankle Socks', 'No-show ankle socks with a grip heel and a small knit mark.', 'Adult 6-11', 'Cotton and elastane', 'Knitted Logo', 60, 3.90, '120 pairs/ctn', '4 weeks', 'Knitted logo on the outer ankle', 'https://images.unsplash.com/photo-1556906781-9a412961c28c?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Black', '#111827'], ['Grey', '#94a3b8']]),
                $this->item('SCK-803', 'Performance Sports Socks', 'Arch-support sports socks with moisture-wicking yarn and a contrast logo.', 'Adult 6-11', 'Polyester performance yarn', 'Knitted Logo', 60, 5.40, '100 pairs/ctn', '4 weeks', 'Contrast knitted logo', 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?auto=format&fit=crop&w=800&q=80', [['Black / Lime', '#84cc16'], ['Navy / White', '#1e3a8a']], false, true, false),
                $this->item('SCK-804', 'Bamboo Dress Socks', 'Breathable bamboo dress socks with a subtle ankle logo.', 'Adult 6-11', 'Bamboo viscose blend', 'Knitted Logo', 60, 5.10, '100 pairs/ctn', '4 weeks', 'Knitted logo', 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?auto=format&fit=crop&w=800&q=80', [['Charcoal', '#334155'], ['Navy', '#1e3a8a'], ['Burgundy', '#9f1239']], false, false, true),
                $this->item('SCK-805', 'Knee-High Team Socks', 'Knee-high socks for clubs and school teams, with a striped cuff logo.', 'Adult 6-11', 'Cotton sport blend', 'Knitted Logo', 50, 6.20, '80 pairs/ctn', '4 - 5 weeks', 'Knitted logo and stripe', 'https://images.unsplash.com/photo-1511556532299-8f662fc26c06?auto=format&fit=crop&w=800&q=80', [['Black / White', '#111827'], ['Royal / White', '#2563eb'], ['Red / White', '#dc2626']]),
            ],
            'pos' => [
                $this->item('POS-1101', '850mm Roll-Up Banner Stand', 'Aluminium base, pole, and a replaceable full-colour banner print.', '850mm x 2000mm', 'Aluminium hardware and PVC banner', 'Large Format Print', 1, 89.00, '5pcs/ctn', '1 - 2 weeks', 'Full colour banner print', 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&q=80', [['Silver Base', '#cbd5e1'], ['Black Base', '#111827']], false, true, false),
                $this->item('POS-1102', '6ft Fitted Table Throw', 'Stretch table throw with a full-colour front panel for expo tables.', '6ft table', 'Stretch polyester', 'Dye Sublimation', 1, 120.00, '5pcs/ctn', '2 weeks', 'Full colour front panel', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&q=80', [['White', '#f8fafc'], ['Black', '#111827'], ['Navy', '#1e3a8a']]),
                $this->item('POS-1103', '4.5m Feather Flag with Ground Spike', 'Single-sided feather flag, pole, and a cross base or ground spike.', '4.5 metre', 'Polyester knit flag', 'Dye Sublimation', 1, 145.00, '5pcs/ctn', '2 weeks', 'Full colour flag print', 'https://images.unsplash.com/photo-1464047736614-ffc7f0f0d0d0?auto=format&fit=crop&w=800&q=80', [['Full Colour', '#ed7100']]),
                $this->item('POS-1104', 'Tension Fabric Counter - 1m', 'Portable counter with a printed fabric graphic that zips over the frame.', '1000mm wide', 'Aluminium frame and fabric', 'Dye Sublimation', 1, 260.00, '1pc/ctn', '2 - 3 weeks', 'Full colour fabric graphic', 'https://images.unsplash.com/photo-1475721027785-f74eccf877e2?auto=format&fit=crop&w=800&q=80', [['Full Colour', '#ed7100']], true, false, false),
                $this->item('POS-1105', 'A4 Acrylic Brochure Holder', 'Two-tier acrylic holder for flyers, with a printed header card.', 'A4, 2 tier', 'Acrylic', 'UV Print on header', 10, 18.50, '20pcs/ctn', '2 - 3 weeks', 'Full colour header card', 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?auto=format&fit=crop&w=800&q=80', [['Clear', '#e2e8f0']]),
            ],
            'eco' => [
                $this->item('ECO-1001', 'Wheat Straw Lunch Pot Set', 'Microwave-safe wheat-straw lunch pot with a fork and a printed sleeve.', '700ml', 'Wheat straw composite', 'Pad Print or Laser', 50, 6.80, '50pcs/ctn', '4 weeks', '1 colour logo on the sleeve', 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=800&q=80', [['Natural', '#d6d3d1'], ['Sage', '#65a30d']], true, false, true),
                $this->item('ECO-1002', 'Cork and Kraft Notebook A5', 'Kraft notebook with a cork patch that takes a laser or debossed mark.', 'A5', 'Kraft paper and cork', 'Laser or Deboss', 50, 3.60, '80pcs/ctn', '3 weeks', 'Logo on the cork patch', 'https://images.unsplash.com/photo-1531346878377-a5be20888e57?auto=format&fit=crop&w=800&q=80', [['Natural', '#d6d3d1']], false, false, true),
                $this->item('ECO-1003', 'Recycled Cotton Shopper - 150gsm', 'Short-handle shopper woven from recycled cotton with reinforced seams.', '380mm x 420mm', 'Recycled cotton', 'Screen Print or Transfer', 100, 2.40, '150pcs/ctn', '3 weeks', '1 colour print on one side', 'https://images.unsplash.com/photo-1597484661643-2f5fef640dd1?auto=format&fit=crop&w=800&q=80', [['Natural', '#f5f5f4'], ['Black', '#111827']], false, true, true),
                $this->item('ECO-1004', 'Bamboo Cutlery Wrap', 'Knife, fork, spoon, and chopsticks in a cotton wrap with a snap closure.', 'Set of 4', 'Bamboo and cotton wrap', 'Laser on the wrap patch', 50, 4.20, '100pcs/ctn', '3 - 4 weeks', 'Laser or woven label', 'https://images.unsplash.com/photo-1516684669134-de6f7c473a8a?auto=format&fit=crop&w=800&q=80', [['Natural', '#d6d3d1']], false, false, true),
                $this->item('ECO-1005', 'Plantable Seed Paper Card', 'A card that grows wildflowers after the event, with a full-colour face.', 'A6', 'Seed paper', 'Soy Ink Print', 100, 1.10, '500pcs/ctn', '2 - 3 weeks', 'Full colour one side', 'https://images.unsplash.com/photo-1466692476866-aef0f5834438?auto=format&fit=crop&w=800&q=80', [['Seed Mix', '#65a30d']], true, false, true),
            ],
        ];
    }

    /**
     * @param  list<array{0: string, 1: string}>  $colors
     * @return array<string, mixed>
     */
    private function item(
        string $code,
        string $name,
        string $short,
        string $size,
        string $material,
        string $print,
        int $moq,
        float $price,
        string $packing,
        string $lead,
        string $includes,
        string $image,
        array $colors,
        bool $new = false,
        bool $hot = false,
        bool $eco = false,
    ): array {
        return [
            'code' => $code,
            'name' => $name,
            'short' => $short,
            'size' => $size,
            'material' => $material,
            'print' => $print,
            'moq' => $moq,
            'price' => $price,
            'packing' => $packing,
            'lead' => $lead,
            'includes' => $includes,
            'image' => $image,
            'colors' => $colors,
            'new' => $new,
            'hot' => $hot,
            'eco' => $eco,
        ];
    }
}
