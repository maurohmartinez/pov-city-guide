crud.field('template').onChange(function (field) {
    crud.field('description_top').show(field.value === 'with_videos');
    crud.field('description_bottom').show(field.value === 'with_videos');
    crud.field('subcategories').show(field.value === 'with_subcategories');
    crud.field('hint_html').show(field.value === 'with_subcategories');
}).change();
