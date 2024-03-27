<?php

namespace Fpaipl\Prody\Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Fpaipl\Shopy\Models\Cart;
use Illuminate\Database\Seeder;
use Fpaipl\Authy\Models\Account;
use Fpaipl\Authy\Models\Address;
use Fpaipl\Authy\Models\Profile;
use Fpaipl\Prody\Models\Category;
use Fpaipl\Shopy\Models\Checkout;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Log;
use Fpaipl\Shopy\Models\PickupAddress;

class DummySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pickupAddresses = [
            [
                'name' => 'Metro Fashion Karol Bagh Shop',
                'contacts' => '9999437620',
                'line1' => 'E-16/931/32, Shop No.9',
                'line2' => 'Hemant Complex, Main Tank Road',
                'district' => 'South West Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'pincode' => '110005',
            ],
            [
                'name' => 'Metro Fashion Okhla Factory',
                'contacts' => '9999437620',
                'line1' => 'B-74 Pocket X, Okhla Phase II Okhla Industrial Estate',
                'line2' => 'Harkesh Nagar Metro Station',
                'district' => 'South Delhi',
                'state' => 'Delhi',
                'country' => 'India',
                'pincode' => '110020',
            ],
        ];

        foreach ($pickupAddresses as $pickupAddress) {
            $pAdd = PickupAddress::create([
                'name' => $pickupAddress['name'],
                'contacts' => $pickupAddress['contacts'],
                'line1' => $pickupAddress['line1'],
                'line2' => $pickupAddress['line2'],
                'district' => $pickupAddress['district'],
                'state' => $pickupAddress['state'],
                'country' => $pickupAddress['country'],
                'pincode' => $pickupAddress['pincode'],
            ]);

            $pAdd->print = $pAdd->line1 . ', ' . $pAdd->line2 . ', ' . $pAdd->district . ', ' . $pAdd->state . ', ' . $pAdd->country . ' - ' . $pAdd->pincode;
            $pAdd->save();
        }

        $newUser = User::where('email', 'apptest@wsg.in')->first();
        $newUser->utype = $newUser->usernameIsEmailId() ? 'email' : 'mobile';
        $newUser->save();

        $userAccount = Account::create([
            'user_id' => $newUser->id,
            'kycstep' => 'business',
            'name' => $newUser->name,
        ]);

        Profile::create([
            'user_id' => $newUser->id,
            'role_assigned' => true,
            'account' => $userAccount->id,
        ]);

        $userAccount->update([
            'status' => 'approved',
            'approver_name' => 'admin',
            'kycstep' => 'review',
            'type' => 'individual',
            'lifespan' => '1-3 years',
            'turnover' => 'upto 1 crore',
            'address' => '123, Test Address',
            'city' => 'Test City',
            'pincode' => '123456',
            'state' => 'Test State',
            'contact' => '1234567890',
            'other' => 'Other Document',
            'gstin' => 'GSTIN1234564785',
            'aadhar' => '222233334444',
            'pan' => 'ABCDE1234F',
            'bank' => 'TestBank12345',
        ]);
        
        $addressData = [
            'addressable_id' => $userAccount->user_id,
            'addressable_type' => 'App\Models\User',
            'gstin' => $userAccount?->gstin,
            'pan' => $userAccount?->pan,
            'name' => $userAccount->name,
            'contacts' => $userAccount->contact,
            'line1' => $userAccount->address,
            'line2' => $userAccount->city . ', ' . $userAccount->state . ', India',
            'pincode' => $userAccount->pincode,
        ];
    
        $addressModel = new Address($addressData);
        $userAccount->user->addresses()->save($addressModel);

        $defaultCart = Cart::create([
            'name' => $newUser->name . ' Default Cart',
            'user_id' => $newUser->id,
        ]);

        $buynowCart = Cart::create([
            'name' => $newUser->name . ' Buynow Cart',
            'user_id' => $newUser->id,
        ]);

        $userCheckout = Checkout::create([
            'user_id' => $newUser->id,
            'delivery_type' => 'dropoff',
            'secret' => Str::random(6),
            'billing_shipping_same' => true,
            'billing_address_id' => $userAccount->user->addresses[0]->id,
            'shipping_address_id' => $userAccount->user->addresses[0]->id,
        ]);

        $newUser->profile->update([
            'cart_default' => $defaultCart->id,
            'cart_buynow' => $buynowCart->id,
            'checkout' => $userCheckout->id,
            'billing' => $userAccount->user->addresses[0]->id,
            'shipping' => $userAccount->user->addresses[0]->id,
        ]);
    

        $categories = [
            // Top level categories
            [
                'name' => 'Mens',
                'display' => false,
                'children' => [
                    [
                        'name' => 'T-Shirts',
                        'display' => true,
                        'image' => asset('storage/assets/categories/m-tshirt.png'),
                    ],
                    [
                        'name' => 'Jeans',
                        'display' => true,
                        'image' => asset('storage/assets/categories/m-jeans.png'),
                    ],
                ],
            ],
            [
                'name' => 'Womens',
                'display' => false,
                'children' => [
                    [
                        'name' => 'T-Shirts',
                        'display' => true,
                        'image' => asset('storage/assets/categories/w-tshirt.png'),
                    ],
                    [
                        'name' => 'Bottoms',
                        'display' => true,
                        'image' => asset('storage/assets/categories/bottoms.png'),
                    ],
                    [
                        'name' => 'Co-ords',
                        'display' => true,
                        'image' => asset('storage/assets/categories/coords.png'),
                    ],
                    [
                        'name' => 'Bodysuits',
                        'display' => true,
                        'image' => asset('storage/assets/categories/bodysuit.png'),
                    ],
                    [
                        'name' => 'Dresses',
                        'display' => true,
                        'image' => asset('storage/assets/categories/dresses.png'),
                    ],
                    [
                        'name' => 'Jumpsuits',
                        'display' => true,
                        'image' => asset('storage/assets/categories/jumpsuits.png'),
                    ],
                    [
                        'name' => 'Tops',
                        'display' => true,
                        'image' => asset('storage/assets/categories/tops.png'),
                    ],
                    [
                        'name' => 'Shirts',
                        'display' => true,
                        'image' => asset('storage/assets/categories/shirts.png'),
                    ]
                ],
            ],
            [
                'name' => 'Kids',
                'display' => false,
                'children' => [
                    [
                        'name' => 'T-Shirts',
                        'display' => true,
                        'image' => asset('storage/assets/categories/k-tshirt.png'),
                    ],
                ],
            ],
            [
                'name' => 'Accessories',
                'display' => false,
                'children' => [
                    [
                        'name' => 'Belts',
                        'display' => false,
                        'image' => asset('storage/assets/categories/default.png'),
                    ],
                ],
            ],
        ];

        foreach ($categories as $category) {
            $newCategory = Category::create([
                'name' => $category['name'],
                'display' => $category['display'],
            ]);

            if (isset($category['children'])) {
                foreach ($category['children'] as $child) {
                    $newChild = Category::create([
                        'name' => $child['name'],
                        'display' => $child['display'],
                        'parent_id' => $newCategory->id,
                    ]);
    
                    $imagePath = public_path('storage/assets/categories/') . basename($child['image']);
                    if (file_exists($imagePath)) {
                        $newChild->addMedia($imagePath)->preservingOriginal()->toMediaCollection(Category::MEDIA_COLLECTION_NAME);
                    } else {
                        Log::warning("Image not found for category: " . $child['name']);
                    }
                }
            }

        }


        // Ranged collections

        $rangedCollections = [
            [
                'name' => 'Under ₹ 99',
                'shade' => '#f7e0d9', // slightly more orange
                'image' => asset('storage/assets/collections/99.png')
            ],
            [
                'name' => 'Under ₹ 199',
                'shade' => '#f7d5e0', // slightly more purple
                'image' => asset('storage/assets/collections/199.png')
            ],
            [
                'name' => 'Under ₹ 299',
                'shade' => '#f7d5cd', // slightly more peach
                'image' => asset('storage/assets/collections/299.png')
            ],
            [
                'name' => 'Under ₹ 399',
                'shade' => '#f7e8d5', // slightly more beige
                'image' => asset('storage/assets/collections/399.png')
            ],
            [
                'name' => 'Under ₹ 499',
                'shade' => '#f2d5f7', // a soft purple
                'image' => asset('storage/assets/collections/499.png')
            ],
            [
                'name' => 'Under ₹ 599',
                'shade' => '#d5f7e2', // a soft green
                'image' => asset('storage/assets/collections/599.png')
            ],
            [
                'name' => 'Under ₹ 699',
                'shade' => '#d5e7f7', // a soft blue
                'image' => asset('storage/assets/collections/699.png')
            ],
            [
                'name' => 'Under ₹ 799',
                'shade' => '#f7d5d2', // a slightly different pink
                'image' => asset('storage/assets/collections/799.png')
            ],
            [
                'name' => 'Under ₹ 899',
                'shade' => '#f7f0d5', // a soft yellow
                'image' => asset('storage/assets/collections/899.png')
            ],
            [
                'name' => 'Under ₹ 999',
                'shade' => '#e8d5f7', // a very soft lavender
                'image' => asset('storage/assets/collections/999.png')
            ],
        ];
        

        foreach ($rangedCollections as $index => $rangedCollection) {

            $newCollection = Collection::create([
                'name' => $rangedCollection['name'],
                'info' => $rangedCollection['name'],
                'order' => $index + 1,
                'type' => 'ranged',
                'shade' => $rangedCollection['shade'],
            ]);

            $newCollection
                ->addMediaFromUrl($rangedCollection['image'])
                ->preservingOriginal()
                ->toMediaCollection(Collection::MEDIA_COLLECTION_NAME);
        }

        
        
        // Featured collections

        $featuredCollections = [
            [
                'name' => 'New Arrivals',
                'image' => asset('storage/assets/collections/featured1.jpeg'),
            ],
            [
                'name' => 'Buy One Get One',
                'image' => asset('storage/assets/collections/featured2.jpeg'),
            ],
            [
                'name' => 'Stock Clearance',
                'image' => asset('storage/assets/collections/featured3.jpeg'),
            ],
        ];

        foreach ($featuredCollections as $index => $featuredCollection) {

            $newCollection = Collection::create([
                'name' => $featuredCollection['name'],
                'info' => $featuredCollection['name'],
                'order' => $index + 1,
                'type' => 'featured',
            ]);

            $newCollection
                ->addMediaFromUrl($featuredCollection['image'])
                ->preservingOriginal()
                ->toMediaCollection(Collection::MEDIA_COLLECTION_NAME);
        }

        // Recommended collection
    
        $recommended = Collection::create([
            'name' => 'Recommended',
            'info' => 'You may also like',
            'order' => 1,
            'type' => 'recommended',
        ]);

        $recommended
            ->addMediaFromUrl('https://picsum.photos/200/300')
            ->preservingOriginal()
            ->toMediaCollection(Collection::MEDIA_COLLECTION_NAME);

    }
}
