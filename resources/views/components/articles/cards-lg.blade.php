<div class="position-relative mt-4">
    <div class="row cards-lg-row">
        @foreach($articles->take(4) as $article)
            <div class="col-6 col-md-3 mb-4">
                @include('components.articles.hero', ['article' => $article, 'words' => 5])
            </div>
        @endforeach
    </div>
</div>
