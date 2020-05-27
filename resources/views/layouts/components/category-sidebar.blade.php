<div class="side-menu list-group">
    @foreach([1,2,3,4,5,6,7,8] as $item)
    <a href="{{ route('website.home') }}" class="list-group-item @yield('categoryX')">Categoria X</a>
    @endforeach
</div>
