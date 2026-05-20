<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Medicine;
use Illuminate\Support\Str;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            [
                'name' => 'Tablets & Capsules',
                'description' => 'Prescription and OTC tablets, capsules, and softgels.',
                'status' => true
            ],
            [
                'name' => 'Syrups & Liquids',
                'description' => 'Cough syrups, liquid antacids, suspensions, and elixirs.',
                'status' => true
            ],
            [
                'name' => 'Ayurverdic & Herbal',
                'description' => 'Natural supplements, classical ayurvedic preparations, and herbs.',
                'status' => true
            ],
            [
                'name' => 'Personal Care',
                'description' => 'Skin care, hair care, oral care, and personal hygiene products.',
                'status' => true
            ],
            [
                'name' => 'Wellness & Fitness',
                'description' => 'Multivitamins, health drinks, nutrition bars, and wellness devices.',
                'status' => true
            ]
        ];

        $categoryModels = [];
        foreach ($categories as $cat) {
            $categoryModels[$cat['name']] = Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'description' => $cat['description'],
                    'status' => $cat['status'],
                ]
            );
        }

        // 2. Brands
        $brands = [
            ['name' => 'Cipla', 'description' => 'Cipla Limited, global pharmaceutical company.', 'status' => true],
            ['name' => 'Abbott', 'description' => 'Abbott India Healthcare Solutions.', 'status' => true],
            ['name' => 'GSK', 'description' => 'GlaxoSmithKline Pharmaceuticals Ltd.', 'status' => true],
            ['name' => 'Sun Pharma', 'description' => 'Sun Pharmaceutical Industries Ltd.', 'status' => true],
            ['name' => 'Dabur', 'description' => 'Dabur India, natural consumer goods.', 'status' => true],
            ['name' => 'Himalaya', 'description' => 'The Himalaya Drug Company.', 'status' => true]
        ];

        $brandModels = [];
        foreach ($brands as $br) {
            $brandModels[$br['name']] = Brand::updateOrCreate(
                ['slug' => Str::slug($br['name'])],
                [
                    'name' => $br['name'],
                    'description' => $br['description'],
                    'status' => $br['status'],
                ]
            );
        }

        // 3. Medicines
        $medicines = [
            [
                'name' => 'Dolo 650 Tablet',
                'category' => 'Tablets & Capsules',
                'brand' => 'Cipla',
                'description' => 'Dolo 650 Tablet helps relieve pain and fever by blocking chemical messengers in the brain.',
                'mrp' => 30.90,
                'selling_price' => 26.50,
                'stock_quantity' => 150,
                'prescription_required' => false,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ],
            [
                'name' => 'Crocin Pain Relief Tablet',
                'category' => 'Tablets & Capsules',
                'brand' => 'GSK',
                'description' => 'Crocin Pain Relief is a combination medicine containing paracetamol and caffeine for fast headache relief.',
                'mrp' => 45.00,
                'selling_price' => 39.00,
                'stock_quantity' => 120,
                'prescription_required' => false,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ],
            [
                'name' => 'Benadryl Cough Syrup 150ml',
                'category' => 'Syrups & Liquids',
                'brand' => 'GSK',
                'description' => 'Benadryl Cough Syrup provides rapid relief from dry cough, throat irritation, and runny nose.',
                'mrp' => 145.00,
                'selling_price' => 119.00,
                'stock_quantity' => 85,
                'prescription_required' => false,
                'image' => 'uploads/medicines/cough_syrup.png',
                'status' => true
            ],
            [
                'name' => 'Gelusil Liquid Antacid 200ml',
                'category' => 'Syrups & Liquids',
                'brand' => 'Abbott',
                'description' => 'Gelusil Liquid Antacid provides fast relief from acidity, heartburn, and gas flatulence.',
                'mrp' => 165.00,
                'selling_price' => 139.00,
                'stock_quantity' => 65,
                'prescription_required' => false,
                'image' => 'uploads/medicines/cough_syrup.png',
                'status' => true
            ],
            [
                'name' => 'Becosules Z Capsules',
                'category' => 'Tablets & Capsules',
                'brand' => 'Abbott',
                'description' => 'Becosules Z is a multivitamin capsule enriched with Vitamin B-Complex, Vitamin C, and Zinc.',
                'mrp' => 55.00,
                'selling_price' => 48.00,
                'stock_quantity' => 200,
                'prescription_required' => false,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ],
            [
                'name' => 'Himalaya Neem Face Wash 100ml',
                'category' => 'Personal Care',
                'brand' => 'Himalaya',
                'description' => 'A soap-free, herbal formulation that clears impurities and helps clear pimples.',
                'mrp' => 120.00,
                'selling_price' => 105.00,
                'stock_quantity' => 90,
                'prescription_required' => false,
                'image' => 'uploads/medicines/ayurvedic_jar.png',
                'status' => true
            ],
            [
                'name' => 'Dabur Pudin Hara Active Liquid',
                'category' => 'Ayurverdic & Herbal',
                'brand' => 'Dabur',
                'description' => 'Pudin Hara is an ayurvedic medicine for indigestion, gas, and stomach ache containing mint extracts.',
                'mrp' => 60.00,
                'selling_price' => 52.00,
                'stock_quantity' => 110,
                'prescription_required' => false,
                'image' => 'uploads/medicines/ayurvedic_jar.png',
                'status' => true
            ],
            [
                'name' => 'Shelcal 500 Calcium Tablet',
                'category' => 'Tablets & Capsules',
                'brand' => 'Sun Pharma',
                'description' => 'Shelcal 500 is a calcium and vitamin D3 supplement for bone, joint, and tooth strength.',
                'mrp' => 119.00,
                'selling_price' => 99.00,
                'stock_quantity' => 130,
                'prescription_required' => false,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ],
            [
                'name' => 'Amoxyclav 625 Duo Tablet',
                'category' => 'Tablets & Capsules',
                'brand' => 'Cipla',
                'description' => 'Amoxyclav 625 Duo is an antibiotic tablet used to treat bacterial infections of the throat, lungs, and skin.',
                'mrp' => 204.00,
                'selling_price' => 173.00,
                'stock_quantity' => 50,
                'prescription_required' => true,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ],
            [
                'name' => 'Ascoril LS Syrup 100ml',
                'category' => 'Syrups & Liquids',
                'brand' => 'Abbott',
                'description' => 'Ascoril LS is a combination syrup used to treat cough with mucus, asthma, and bronchial congestion.',
                'mrp' => 125.00,
                'selling_price' => 109.00,
                'stock_quantity' => 75,
                'prescription_required' => true,
                'image' => 'uploads/medicines/cough_syrup.png',
                'status' => true
            ],
            [
                'name' => 'Dabur Chyawanprash 500g',
                'category' => 'Ayurverdic & Herbal',
                'brand' => 'Dabur',
                'description' => 'Dabur Chyawanprash is a traditional ayurvedic bio-mixture that boosts immunity and fights infections.',
                'mrp' => 220.00,
                'selling_price' => 195.00,
                'stock_quantity' => 45,
                'prescription_required' => false,
                'image' => 'uploads/medicines/ayurvedic_jar.png',
                'status' => true
            ],
            [
                'name' => 'Combiflam Pain Relief Tablet',
                'category' => 'Tablets & Capsules',
                'brand' => 'Abbott',
                'description' => 'Combiflam Tablet contains Ibuprofen and Paracetamol, used to treat muscle pain, headache, and fever.',
                'mrp' => 50.00,
                'selling_price' => 42.00,
                'stock_quantity' => 180,
                'prescription_required' => false,
                'image' => 'uploads/medicines/dolo_650_tablet.png',
                'status' => true
            ]
        ];

        foreach ($medicines as $med) {
            $catId = $categoryModels[$med['category']]->id;
            $brandId = $brandModels[$med['brand']]->id;

            Medicine::updateOrCreate(
                ['slug' => Str::slug($med['name'])],
                [
                    'name' => $med['name'],
                    'category_id' => $catId,
                    'brand_id' => $brandId,
                    'description' => $med['description'],
                    'mrp' => $med['mrp'],
                    'selling_price' => $med['selling_price'],
                    'stock_quantity' => $med['stock_quantity'],
                    'prescription_required' => $med['prescription_required'],
                    'image' => $med['image'] ?? null,
                    'status' => $med['status'],
                ]
            );
        }
    }
}
