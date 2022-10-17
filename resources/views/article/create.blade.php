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
        {{ Form::hidden('start', 0) }}{{ Form::hidden('totalrecords', 12) }}
        <ul class="thumbnails image_picker_selector"></ul>
        <div class="row ">
            <button  class="btn btn-dark" id="insert" disabled>Insert</button>
            <button class="btn btn-light" id="cancel" onclick="closeForm()">Cancel</button>
        </div>
    </div>
    <x-slot name="js">
        <script src="https://cdn.jsdelivr.net/npm/@editorjs/editorjs@latest"></script>
        <script>
            $(document).on('click','.search',function (){
                $("ul li").each(function() {
                    $(this).remove();
                });
                let term=$('input[name=search]').val();
                let limit=12;
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
                            $('.thumbnails.image_picker_selector').append('<li><div class="thumbnail"><img id="'+obj.id+'" class="image_picker_image" src="'+obj.images.fixed_height.url+'" alt=""/><div class="overlay"><span></span></div></li>');
                        }
                    }
                });
            })

            $(document).on('click','.image_picker_image',function (){
                setTimeout(function() {
                    if($('.thumbnails.image_picker_selector').find('.image_picker_image.selected').length == 0)
                    {
                        $('#insert').prop('disabled', true);
                    }
                    else
                    {
                        $('#insert').prop('disabled', false);
                    }
                }, 100);

                if($(this).hasClass('selected'))
                {
                    $(this).removeClass('selected');
                    $(this).parent().css('background','none');
                }
                else
                {
                    $(this).addClass('selected');
                    $(this).parent().css('background','#08C');
                }
            });

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
                    $("ul li").each(function() {
                        $(this).remove();
                    });
                    $('input[name=search]').val('');
                    $('#insert').prop('disabled', true);
                    $('.form-popup').show();
                    this.wrapper = document.createElement('div');
                    const insert = document.getElementById('insert');
                    insert.addEventListener('click', (event) => {
                        this._createImage($("ul").find('.selected'));
                        $('.form-popup').hide();
                    },{once : true});
                    return this.wrapper;
                }

                _createImage(selected){
                    for (let i = 0; i <selected.length ; i++) {
                        const image = document.createElement('img');
                        image.id=$(selected[i]).attr('id');
                        image.src= $(selected[i]).attr('src');
                        this.wrapper.appendChild(image);
                    }
                }
                    save(blockContent){
                    return {
                        url: blockContent.value
                    }
                }
            }

            const editor = new EditorJS({
                    holder: 'content',
                    tools:{
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

            // Fetch records
            function fetchData(){
                let records=12;
                let newstart = Number($('input[name=start]').val())+records;
                let totalrecords = Number($('input[name=totalrecords]').val());
                if(newstart <= totalrecords){
                    $('input[name=start]').val(newstart);
                    let term=$('input[name=search]').val();
                    $.ajax({
                        url:"http://localhost:5000/gifs",
                        type: 'get',
                        data: {term:term,limit: records,offset:newstart},
                        success: function(response){
                            let body = JSON.stringify(response);
                            body=JSON.parse(body);
                            for (let i = 0; i < body.data.length; i++) {
                                let obj = body.data[i];
                                $('.thumbnails.image_picker_selector').append('<li><div class="thumbnail"><img id="'+obj.id+'" class="image_picker_image" src="'+obj.images.fixed_height.url+'" alt=""/><div class="overlay"><span></span></div></li>');
                            }
                            $('input[name=totalrecords]').val(newstart+records);
                            // $(".image_picker_image:last").after(response).show().fadeIn("slow");
                        }
                    });
                }
            }

            // $(document).on('touchmove', onScroll); // for mobile
            //
            // function onScroll(){
            //
            //     if( $('.form-popup').scrollTop() > $(document).height() -  $('.form-popup').height()-100) {
            //         fetchData();
            //     }
            // }
            $(document).ready(function (){
                document.addEventListener('scroll', function (event) {
                    if($(event.target).attr('class')=='thumbnails image_picker_selector')
                    {
                        let position =  $('.thumbnails.image_picker_selector').scrollTop();
                        let height =  $('.thumbnails.image_picker_selector')[0].scrollHeight-505;
                        // console.log(position,height);
                        if( position >= height ){
                            fetchData();
                        }
                    }
                }, true);
            })

        </script>
    </x-slot>
</x-app-layout>
