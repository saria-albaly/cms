<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create article') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="editor-landing__demo" data-module="editorLanding">
                        {!! Form::open(['route' => 'admin.articles.store','id' => 'article']) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title') !!}
                                {!! Form::text('title', null, ['placeholder' =>'title','required'=>'','data-validation-required-message'=>'required','class' => 'form-control','maxlength' => 500,'maxlength' => 500]) !!}
                        </div>
                        {{ Form::hidden('content', '') }}
                        <br>
                        <div class="form-group">
                        {!! Form::label('content','Content' ) !!}
                        <textarea name="module-settings" hidden="">            {
                                "output_id" : "output",
                                "blocks" : []
                            }
                        </textarea>
                        </div>
                        <div class="editor-landing__demo-inner">
                            <div id="content"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5"></div>
                        <div class="col-2">
                            <div id="save" class="btn btn-success">Save</div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

    <div class="form-popup" id="myForm">
        {!! Form::text('search', null, ['id'=>'search','placeholder' =>'Search GIF ..','class' => 'form-control','maxlength' => 100,'maxlength' => 100]) !!}
        <button class="btn btn-primary search">Search</button>
        <select class="image-picker show-html" id="select">
                <option value=""></option>
{{--                <option data-img-src="http://s3.amazonaws.com/resumator/customer_20190729133655_O33WBDSRAGZJWHDI/logos/20220221112033_New_Logo_small.png" value="1" selected>Cute Kitten 1</option>--}}
            </select>
        <div class="row ">
            <button  class="btn btn-dark" id="insert">Insert</button>
            <button class="btn btn-light" id="cancel" onclick="closeForm()">Cancel</button>
        </div>

    </div>
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>

        <script>

            $(document).on('click','.search',function (){
                $("select option").each(function() {
                    $(this).remove();
                });
                let term=$('input[name=search]').val();
                let limit=10;
                let offset=0;
                $.ajax({
                    url:"http://localhost:5000/gifs",
                    type: 'get',
                    data: {term:term,limit: limit,offset:offset},
                    success: function(response){
                        let body = JSON.stringify(response);
                        body=JSON.parse(body);
                        for (let i = 0; i < body.data.length; i++) {
                            let obj = body.data[i];
                            $('#select').append('<option value="'+obj.id+'" data-img-src="'+obj.images.fixed_height.url+'"></option');
                        }
                        $("select").imagepicker();
                    }
                });
            })
            $("select").imagepicker()

            function closeForm() {
                document.getElementById("myForm").style.display = "none";
            }
            class GIF {
                static get toolbox() {
                    return {
                        icon: '<svg width="17" height="15" viewBox="0 0 336 276" xmlns="http://www.w3.org/2000/svg"><path d="M291 150V79c0-19-15-34-34-34H79c-19 0-34 15-34 34v42l67-44 81 72 56-29 42 30zm0 52l-43-30-56 30-81-67-66 39v23c0 19 15 34 34 34h178c17 0 31-13 34-29zM79 0h178c44 0 79 35 79 79v118c0 44-35 79-79 79H79c-44 0-79-35-79-79V79C0 35 35 0 79 0z"/></svg>',
                        title: "GIF"
                    }
                }

                render(){
                    $("#select option").each(function() {
                        $(this).remove();
                    });
                    $("select").imagepicker();
                    $('input[name=search]').val('');
                    $('.form-popup').show();
                    this.wrapper = document.createElement('div');
                    const insert = document.getElementById('insert');
                    insert.addEventListener('click', (event) => {
                        this._createImage($("#select").find(':selected').attr('data-img-src'),$("#select").find(':selected').val());
                        $('.form-popup').hide();
                    });

                    return this.wrapper;
                }

                _createImage(url, id){
                    const image = document.createElement('img');
                    image.id=id;
                    this.wrapper.id=id+'simple-image';

                    this.wrapper.appendChild(image);
                    document.getElementById(id).src = url;
                }

                    save(blockContent){
                    return {
                        url: blockContent.value
                    }
                }
            }

            const editor = new EditorJS({
                    holder: 'content',
                    /**
                     * Available Tools list.
                     * Pass Tool's class or Settings object for each Tool you want to use
                     */
                    tools:{
                        // header:Header,
                        // delimiter: Delimiter,
                        gif: GIF,
                    }
                }
            );

            $(document).on('click','#save',function (){
                editor.save().then((output) => {
                    output=JSON.stringify(output);
                    $('input[name=content]').val(output);
                    $('#article').submit();
                }).catch((error) => {
                    console.log('Saving failed: ', error)
                });

            })

            checkWindowSize();

            // Check if the page has enough content or not. If not then fetch records
            function checkWindowSize(){
                if($(window).height() >= $(document).height()){
                    // Fetch records
                    fetchData();
                }
            }

            // Fetch records
            function fetchData(){
                let start = Number($('#start').val());
                let allcount = Number($('#totalrecords').val());
                let rowperpage = Number($('#rowperpage').val());
                start = start + rowperpage;

                if(start <= allcount){
                    $('#start').val(start);
                    let term=$('#search').val();
                    let limit=10;
                    let offset=0;
                    $.ajax({
                        url:"http://localhost:5000/gifs",
                        type: 'get',
                        data: {term:term,limit: limit,offset:offset},
                        success: function(response){
                            // Add
                            $(".post:last").after(response).show().fadeIn("slow");

                            // Check if the page has enough content or not. If not then fetch records
                            checkWindowSize();
                        }
                    });
                }
            }

            $(document).on('touchmove', onScroll); // for mobile

            function onScroll(){

                if( $('.form-popup').scrollTop() > $(document).height() -  $('.form-popup').height()-100) {
                    fetchData();
                }
            }

            $('.form-popup').scroll(function(){

                var position =  $('.form-popup').scrollTop();
                var bottom = $(document).height() -  $('.form-popup').height();

                if( position == bottom ){
                    fetchData();
                }

            });

        </script>
    </x-slot>
</x-app-layout>
