@if($categoriesMenuItems->isNotEmpty())
    <section class="bg-dark py-3 stripe-menu">
        <div class="container d-flex justify-content-between align-items-center">
            @include('inc.menu-item', ['categories' => $categoriesMenuItems, 'depth' => null])
        </div>
    </section>
@endif
