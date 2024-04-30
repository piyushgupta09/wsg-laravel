<?php

namespace Fpaipl\Prody\Models;

use App\Models\User;
use Fpaipl\Prody\Models\Tax;
use Fpaipl\Shopy\Models\Cart;
use Fpaipl\Panel\Traits\Authx;
use Fpaipl\Prody\Models\Brand;
use Fpaipl\Prody\Models\Category;
use Fpaipl\Panel\Traits\NamedSlug;
use Spatie\Activitylog\LogOptions;
use Fpaipl\Prody\Models\Collection;
use Illuminate\Support\Facades\Log;
use Fpaipl\Prody\Models\ProductUser;
use Fpaipl\Shopy\Models\CartProduct;
use Illuminate\Support\Facades\Auth;
use Fpaipl\Prody\Models\ProductRange;
use Fpaipl\Prody\Models\ProductOption;
use Illuminate\Database\Eloquent\Model;
use Fpaipl\Prody\Models\ProductAttribute;
use Fpaipl\Prody\Models\CollectionProduct;
use Fpaipl\Prody\Models\ProductMeasurement;
use Spatie\Activitylog\Traits\LogsActivity;
use Fpaipl\Shopy\Http\Resources\CartItemResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Represents a Product model in the application.
 *
 * The Product model implements the HasMedia contract which guarantees that the model can handle media. 
 * It also uses several traits including those for handling media, logging activity, managing slugs, 
 * managing media, soft deleting records, managing model, managing tags, cascading soft deletes, and 
 * restoring soft deletes. 
 *
 * It also defines relationships that should be deleted or restored along with this model, and those 
 * that restrict soft deletes when they exist. It also provides methods to determine if there are 
 * dependent relationships and to get these relationships.
 */
class Product extends Model
{
    use
        Authx,
        LogsActivity,
        NamedSlug;

    const STATUS = ['draft', 'live'];
    
    protected $fillable = [
        'sid',
        'uuid',
        'name',
        'slug',
        'code',
        'details',
        'mrp',
        'rate',
        'moq',
        'active',
        'tags',
        'brand_id',
        'category_id',
        'tax_id',
        'in_stock',
        'stocks',
    ];

    public static function validationRules() {
        return [
            'brand_id' => ['required', 'exists:brands,id'],
            'tax_id' => ['required', 'exists:taxes,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'details' => ['nullable', 'string', 'max:5000'],
            'moq' =>['required', 'integer', 'min:1', 'max:100'],
        ];
    } 

    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Adds the product to a collection based on its ranged price.
     * Also attaches the first product option to the collection.
     *
     * @return bool Returns true if the operation is successful, false otherwise
     */
    public function addToRangedCollection()
    {
        // Retrieve the first product range
        $firstRange = $this->productRanges()->first();
        
        // If there's no first product range, exit the function
        if (!$firstRange) {
            return false;
        }

        // Also update product price
        $this->mrp = $firstRange->mrp;
        $this->rate = $firstRange->rate;
        $this->saveQuietly();

        // Calculate the ranged price based on the first product range
        $rangePrice = $firstRange->rate;
        $rangedPrice = ceil($rangePrice / 100) * 100 - 1;
        $rangedPrice = (int) $rangedPrice;

        // Generate a slug for the collection based on the ranged price
        $rangedCollectionSlug = 'under-rs-' . $rangedPrice;

        // Look for an existing collection with that slug
        $collection = Collection::where('slug', $rangedCollectionSlug)->first();

        // Retrieve the ID of the first product option if it exists
        $firstProductOptionId = optional($this->productOptions->reverse()->first())->id;

        // If there's no first product option, exit the function
        if (!$firstProductOptionId) {
            return false;
        }

        // If the collection already exists
        if ($collection) {
            // Check if the product is already in the collection
            if (!$collection->products->contains($this->id)) {
                // Attach the product and its first product option to the collection
                $collection->products()->attach($this->id, ['product_option_id' => $firstProductOptionId]);
            }
            return true;
        }
    }

    /**
     * Removes the product from a collection based on its ranged price.
     *
     * @return bool Returns true if the operation is successful, false otherwise
     */
    public function removeFromRangedCollection()
    {
        $productCollection = $this->collections()->where('type', 'ranged')->get();
        foreach ($productCollection as $collection) {
            $collection->products()->detach($this->id);
        }
        return true;
    }


    public function addToCollection($collectionId)
    {
        // Retrieve all product options IDs
        $productOptionsIds = $this->productOptions()->pluck('id')->toArray();

        // Check if there are any product options available
        if (empty($productOptionsIds)) {
            return false;
        }

        // Select a random ID from the product options IDs array
        $randomProductOptionId = $productOptionsIds[array_rand($productOptionsIds)];

        // Retrieve the collection with the given slug
        $collection = Collection::where('slug', $collectionId)->first();

        // If the collection exists
        if ($collection) {
            // Check if the product is already in the collection
            if (!$collection->products->contains($this->id)) {
                // Attach the product and its random product option to the collection
                $collection->products()->attach($this->id, ['product_option_id' => $randomProductOptionId]);
            }
        }
    }

    public function removeFromCollection($collectionId)
    {
        // Look for an existing collection with that slug
        $collection = Collection::where('slug', $collectionId)->first();

        // If the collection already exists
        if ($collection) {
            // Check if the product is already in the collection
            if ($collection->products->contains($this->id)) {
                // Detach the product and its first product option to the collection
                $collection->products()->detach($this->id);
            }
            return true;
        }
    }
    
    /*------------------- RELATIONSHIPS -----------------*/

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function tax(): BelongsTo
    {
        return $this->belongsTo(Tax::class);
    }

    public function productOptions(): HasMany
    {
        return $this->hasMany(ProductOption::class);
    }

    public function productRanges(): HasMany
    {
        return $this->hasMany(ProductRange::class);
    }

    public function collections(): BelongsToMany
    {
        return $this->belongsToMany(Collection::class, 'collection_product');
    }

    public function productCollections(): HasMany
    {
        return $this->hasMany(CollectionProduct::class);
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany('Fpaipl\Shopy\Models\Cart', 'cart_products')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    public function isInAnyCart(): bool
    {
        return $this->carts()->exists();
    }

    public function isInUserCart()
    {
        $userId = Auth::id(); // Get the currently logged-in user's ID

        $userCart = $this->carts()
                    ->whereHas('user', function ($query) use ($userId) {
                        $query->where('id', $userId);
                    })
                    ->first();

        return $userCart ? true : false;
    }

    public function getUserCartProductOrderType()
    {
        $user = Auth::id(); // Get the currently logged-in user's ID
        $userCart = Cart::where('user_id', $user)->first();

        if (!$userCart) {
            return null; // Return null if the user does not have a cart
        }

        $userCartProduct = $userCart->cartProducts()
                                    ->where('product_id', $this->id)
                                    ->with('cartItems')
                                    ->first();

        if (isset($userCartProduct)) {
            return $userCartProduct->order_type;
        } else {
            return null;
        }
    }

    public function getUserCartProducts()
    {
        $user = Auth::id(); // Get the currently logged-in user's ID
        $userCart = Cart::where('user_id', $user)->first();

        if (!$userCart) {
            return null; // Return null if the user does not have a cart
        }

        $userCartProduct = $userCart->cartProducts()
                                    ->where('product_id', $this->id)
                                    ->with('cartItems')
                                    ->first();

        if (isset($userCartProduct)) {
            return CartItemResource::collection($userCartProduct->cartItems);
        } else {
            return null;
        }
                                    

        // $userId = Auth::id(); // Get the currently logged-in user's ID
        
        // $userCart = $this->carts()
        //             ->whereHas('user', function ($query) use ($userId) {
        //                 $query->where('id', $userId);
        //             })
        //             ->with('cartProducts.cartItems')
        //             ->first();

        // return $userCart?->cartProducts;
    }

    public function getImage($position = 'first') 
    {
        // Fetch the product option based on the specified position
        $productOption = $position === 'first' ? $this->productOptions->sortBy('id')->first() : $this->productOptions->reverse()->first();

        // Check if the product option exists and has an image
        if ($productOption && method_exists($productOption, 'getImage') && $image = $productOption->getImage()) {
            return $image;
        }
    
        // Return a default image or null if no product option is found or if the image is not available
        return config('panel.default_product_image') ?? null;
    }    

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Category

    public function getParentSlug($key)
    {
        $parentSlug = '';
        if (!empty($this->$key) && $this->categoryWithTrashed) {
            $parentSlug = $this->categoryWithTrashed->slug;
        }
        return $parentSlug;
    }

    public function getParentName($key)
    {
        $parentName = '';
        if (!empty($this->$key) && $this->categoryWithTrashed) {
            $parentName = $this->categoryWithTrashed->slug;
        }
        return $parentName;
    }

    public function hasCategory()
    {
        return $this->category()->count();
    }

    public function scopeUncollectioned($query)
    {
        return $query->whereDoesntHave('collections');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, ProductUser::class);
    }

    public function productAttributes()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function productMeasurements()
    {
        return $this->hasMany(ProductMeasurement::class);
    }

    public function inCart(){
        $colorSizes = [];
        $cartProducts = [];
        $count=0;
        foreach($this->colors as $color){
            foreach($color->colorSizes as $colorSize){
                array_push($colorSizes, $colorSize->id);
            }
        }
        foreach(CartProduct::all() as $cartProduct){
            if(in_array($cartProduct->color_size_id, $colorSizes)){
                array_push($cartProducts, $cartProduct->id);
                $count++;
            }
        }
     
        return [
            'modelName' => 'CartProduct',
            'cartProducts' => $cartProducts,
            'total' => $count,
        ];
    }

    // Logging

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->useLogName('model_log');
    }

    public function productSelectData()
    {
        return [
            'id' => $this->id,
            'color' => '',
            'tags' => $this->tags,
            'image' => $this->getImage(),
            'title' => $this->name,
            'detail' => $this->brand->name . ' | ' . $this->category->name . ' | ' . $this->mrp . ' | ' . $this->rate,
            'subtext' => $this->details,

        ];
    }
}
