<div class="links-menu row">
    @foreach(\App\Helper\Helper::getCategoryList() as $key => $category)
    <div class="col-sm-3">
        <a href="{{ route('website.product.byCategory', [ 'id' => $category->id, 'slug' => \App\Helper\Helper::slugify($category->name) ]) }}" class="not-underlined">
            <div class="py-3 mb-4 border rounded text-center link mb-1-xs py-1-xs text-left-xs">
                <div class="col-sm-12">
                    <strong>{{ $category->name }}</strong>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>
