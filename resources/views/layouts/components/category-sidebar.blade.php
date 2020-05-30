<div class="side-menu list-group">
    @foreach(\App\Helper\Helper::getCategoryList() as $key => $category)
    <a href="{{ route('website.product.byCategory', [ 'id' => $category->id, 'slug' => \App\Helper\Helper::slugify($category->name) ]) }}" class="list-group-item">
        {{ $category->name }}
    </a>
    @endforeach
</div>
