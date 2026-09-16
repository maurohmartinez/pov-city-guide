{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Pages" icon="la la-file-o" :link="route('page.index')" />
<x-backpack::menu-item title="Menu Items" icon="la la-file-o" :link="route('menu-item.index')" />
<x-backpack::menu-item title="Articles" icon="la la-file-o" :link="route('article.index')" />
<x-backpack::menu-item title="Categories" icon="la la-file-o" :link="route('category.index')" />
<x-backpack::menu-item title="Tags" icon="la la-file-o" :link="route('tag.index')" />
<x-backpack::menu-item title="Settings" icon="la la-cog" :link="route('setting.index')" />
