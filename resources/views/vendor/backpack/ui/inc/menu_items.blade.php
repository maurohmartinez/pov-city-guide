{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title='Pages' icon='la la-file-o' :link="backpack_url('page')" />
<x-backpack::menu-item title='Settings' icon='la la-cog' :link="backpack_url('setting')" />