@auth
    <hr>
    <!-- Create the editor container -->
    <form action="{{ url("/discussions/{$current_discussion->id}/post-message") }}" method="post" class="container">
        <div class="row">
            <div class="col-12">
                <p><i class="fas fa-pencil-alt"></i> Vous pouvez répondre via l'éditeur de texte ci-dessous.</p>
            </div>

            <div class="col-12 text-center">
                <textarea name="content" id="content" rows="10" cols="80">
                    Let your dreams dream...
                </textarea>
                {!! csrf_field() !!}
                <button class="btn btn-default"><i class="fab fa-telegram-plane"></i> Poster une réponse</button>
            </div>
        </div>
    </form>

    @section('more_css')
        <script src="{{ asset('plugins/ckeditor/ckeditor.js') }}"></script>
    @endsection

    @section('more_js')
        <script>
            CKEDITOR.replace('content');
        </script>
    @endsection
@endauth


