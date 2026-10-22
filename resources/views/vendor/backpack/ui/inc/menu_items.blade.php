{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item">
    <a class="nav-link" href="{{ backpack_url('dashboard') }}">
        <i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}
    </a>
</li>

@can(\App\Services\RoleAndPermissionService::PERMISSION_ARTICLE_READ)
<x-backpack::menu-item title="Articles" icon="la la-file-o" :link="route('article.index')" />
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_CATEGORY_READ)
<x-backpack::menu-item title="Categories" icon="la la-file-o" :link="route('category.index')" />
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_TAG_READ)
<x-backpack::menu-item title="Tags" icon="la la-file-o" :link="route('tag.index')" />
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_AUTHOR_READ)
<x-backpack::menu-item title="Authors" icon="la la-users" :link="route('author.index')" />
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_CUSTOMER_READ)
<x-backpack::menu-item title="Customers" icon="la la-users" :link="route('user.index')" />
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_ADMIN_READ)
<x-backpack::menu-dropdown title="Admins" icon="la la-users">
    <x-backpack::menu-dropdown-item title="Admins" icon="la la-user" :link="route('admin.index')" />
    <x-backpack::menu-dropdown-item title="Roles" icon="la la-group" :link="backpack_url('role')" />
    <x-backpack::menu-dropdown-item title="Permissions" icon="la la-key" :link="backpack_url('permission')" />
</x-backpack::menu-dropdown>
@endcan

@can(\App\Services\RoleAndPermissionService::PERMISSION_SETTING_READ)
<x-backpack::menu-item title="Settings" icon="la la-cog" :link="route('setting.index')" />
@endcan
