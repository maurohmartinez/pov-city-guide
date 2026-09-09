@foreach($items as $item)
    @if($item->children->isEmpty())
        <li class="@if($depth === 0) nav-item @endif">
            <a
                class="{{ $depth === 0 ? 'nav-link' : 'dropdown-item' }} @if(request()->url() === $item->url) active @endif"
                href="{{ $item->url }}"
            >{{ $item->name }}</a>
        </li>
    @else
        <li class="@if($depth === 0) nav-item @endif dropdown">
            <a
                class="@if($depth === 0) nav-link dropdown-toggle @else dropdown-item dropdown-toggle @endif @if(request()->url() === $item->url) active @endif"
                href="#"
                role="button"
                data-bs-toggle="dropdown"
                data-bs-auto-close="outside"
                aria-expanded="false"
            >
                {{ $item->name }}
            </a>
            <ul class="dropdown-menu">
                @include('inc.menu-item', ['items' => $item->children, 'depth' => $depth + 1])
            </ul>
        </li>
    @endif
@endforeach
