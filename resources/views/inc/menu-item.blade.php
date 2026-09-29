@foreach($categories as $category)
    @if($depth === null)
        <a class="text-white text-decoration-none @if(!$loop->first) px-4 @else pe-4 @endif mx-md-0" href="{{ route('category', $category) }}">{{ $category->name }}</a>
    @else
        @if($category->children->isEmpty())
            <li class="@if($depth === 0) nav-item @endif">
                <a
                    class="{{ $depth === 0 ? 'nav-link' : 'dropdown-item' }} @if(request()->url() === route('category', $category)) active @endif"
                    href="{{ route('category', $category) }}"
                >{{ $category->name }}</a>
            </li>
        @else
            <li class="@if($depth === 0) nav-item @endif dropdown">
                <a
                    class="@if($depth === 0) nav-link dropdown-toggle @else dropdown-item dropdown-toggle @endif @if(request()->url() === route('category', $category)) active @endif"
                    href="#"
                    role="button"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                >
                    {{ $category->name }}
                </a>
                <ul class="dropdown-menu">
                    @include('inc.menu-item', ['categories' => $category->children, 'depth' => $depth + 1])
                </ul>
            </li>
        @endif
    @endif

@endforeach
