<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Budget Mapper</title>
        <link rel="stylesheet" href="{{asset('css/app.css')}}">
    </head>
    <body>
        <main>
            <header>
                <h1>Budget Mapper</h1>
                <p>A simple CSV tool to map BNP Paribas Fortis banking transactions to Spendee online budget &amp; money manager, including the following features:</p>
                <ul>
                    <li>Column mapping/renaming, only required columns are exported.</li>
                    <li>Uncluttered notes, by stripping unneeded information.</li>
                    <li>Automatic categorisation, based upon a pre-defined set of terms.</li>
                </ul>
            </header>

            <section class="mappings">
                {{-- <details open class="columns">
                    <summary>Columns</summary>
                </details> --}}

                <details open class="categories">
                    <summary>Categories</summary>

                    @if ($categories)
                        <ul class="category-list">
                        @foreach ($categories as $category)
                            <li>
                                <form action="{{ route('category.edit', $category->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <input type="text" name="name" value="{{ $category->name }}" />
                                    <input type="checkbox" name="income" value="1" {{ $category->income ? 'checked="checked': '' }}/>
                                    <input type="checkbox" name="expense" value="1" {{ $category->expense ? 'checked="checked': '' }}/>
                                    <input type="text" name="keywords" value="{{ $category->keywords->pluck('label')->implode(', ') }}" />
                                    <button type="submit" class="action action--update">Update category</button>
                                </form>

                                <form action="{{ route('category.delete', $category->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="action action--delete">Delete category</button>
                                </form>
                            </li>
                        @endforeach
                        </ul>
                    @endif

                    <form action="{{ route('category.add') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="form-input">
                            <label for="new-category-name">Category name</label>
                            <input type="text" name="name" id="new-category-name">
                        </div>

                        <button class="btn btn-primary">Add category</button>
                    </form>
                </details>
            </section>

            <section class="file">
                <form action="{{ route('file.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-input">
                        <input class="custom-file-input" type="file" name="file" id="file">
                        <label class="custom-file-label btn btn-primary" for="file">Choose file</label>
                    </div>

                    <button class="btn btn-primary">Import</button>
                    <a class="btn btn-secondary" href="{{ route('file.export') }}">Export</a>
                </form>
            </section>
        </main>
    </body>
</html>
