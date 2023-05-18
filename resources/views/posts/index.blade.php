<x-layout>
    @include('posts._header')

    <main class="text-center mt-6 lg:mt-20 space-y-6">
        @if($posts->count())
            <x-posts-grid :posts="$posts"/>

            {{ $posts->links() }}
        @else
            <p>No posts yet. Please check back later.</p>
        @endif
    </main>
</x-layout>

