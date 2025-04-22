<?php 

namespace App\ReadModel;

use Illuminate\Database\Eloquent\Collection;
use App\Models\Category;



class CatalogList extends Collection 
{ 

    public string $category;

    public array $dishes;

    public function __construct($category, $dishes) { 
        $this->category = $category;
        $this->dishes = $dishes;
    }

    public static function fromModel(Category $category)
    { 
        return new self( 
            $category->name, 
            $category->dishes->toArray(),
        );
    }

}
