@if (isset($categoriesList) && count($categoriesList) > 0)
<div class="flex flex-wrap items-center gap-4 text-sm font-medium text-gray-800">
    @foreach ($categoriesList as $catalog)
    <a href="#category-{{ Str::slug($catalog->category) }}" class="hover:text-yellow-600 transition-colors duration-200">{{ $catalog->category }}</a>
    @endforeach
</div>
@endif