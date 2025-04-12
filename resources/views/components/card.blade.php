<div>
    <div class="text-2xl w-full font-bold p-2 bg-secondar-900 text-black rounded-t-lg">
        <span class="p-2">
            {{$title}}
        </span>
    </div>
    <div class="p-5">
        <div class="text-md mt-4 text-justify">
            {{$content}}
        </div>
        <div class="flex flex-col-reverse md:flex-row-reverse py-5 px-4 gap-5 w-full items-center">
            {{$actions}}
        </div>
    </div>
</div>