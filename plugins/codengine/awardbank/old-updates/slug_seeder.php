<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Tag;
use Codengine\Awardbank\Models\Category;
use Codengine\Awardbank\Models\Award;
use Codengine\Awardbank\Models\Product;
use Codengine\Awardbank\Models\Supplier;
use Codengine\Awardbank\Models\Post;
use Seeder;
use Storage;

class SlugSeeder extends Seeder
{
    public function run()
    {

        $tags = Tag::all();
        foreach ($tags as $tag) {
            $tag->slug = null;
            $tag->slugAttributes();
            $tag->save();
        }
        
        /**
        $cats = Category::all();
        foreach ($cats as $cat) {
            $cat->slug = null;
            $cat->slugAttributes();
            $cat->save();
        }     
        **/

        $awards = Award::all();
        foreach ($awards as $award) {
            $award->slug = null;
            $award->slugAttributes();
            $award->save();
        }  

        /**
        $products = Product::all();
        foreach ($products as $product) {
            $product->slug = null;
            $product->slugAttributes();
            $product->save();
        }

        $suppliers = Supplier::all();
        foreach ($suppliers as $supplier) {
            $supplier->slug = null;
            $supplier->slugAttributes();
            $supplier->save();
        }    
        **/
        
        $posts = Post::where('post_type', '!=', 'message')->get();
        foreach ($posts as $post) {
            $post->slug = null;
            $post->slugAttributes();
            $post->save();
        }                     
    }
}