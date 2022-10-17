<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CMS') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="{{asset('css/image-picker.css')}}">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">


        <style type="text/css">
            #insert{
                position: absolute;
                bottom: 1rem;
                width: 13%;
                right: 1rem;
            }

            .search{
                width: 10%;
                position: absolute;
                right: 16rem;
                top: 1.4rem;
            }

            ul {
                list-style: none;
            }

            img {
                background: url('{{asset('img/loading.gif')}}') center no-repeat;
            }

            /* Responsive image gallery rules begin*/

            .thumbnails {
                position: absolute;
                top: 5rem;
                left: 2.5rem;
                height: 76%;
                width: 96%;
            }

            /*.image-gallery > li {*/
            /*    flex-basis: 350px; !*width: 350px;*!*/
            /*    position: relative;*/
            /*    cursor: pointer;*/
            /*}*/

            /*.image-gallery::after {*/
            /*    content: "";*/
            /*    flex-basis: 350px;*/
            /*}*/

            /*.selected {*/
            /*    background: #08C;*/
            /*}*/

            div.thumbnail {
                padding: 6px;
                height: 170px;
                width: 250px;
                object-fit: cover;
            }

            /*.overlay {*/
            /*    position: absolute;*/
            /*    width: 100%;*/
            /*    height: 100%;*/
            /*    background: rgba(57, 57, 57, 0.502);*/
            /*    top: 0;*/
            /*    left: 0;*/
            /*    transform: scale(0);*/
            /*    transition: all 0.2s 0.1s ease-in-out;*/
            /*    color: #fff;*/
            /*    border-radius: 5px;*/
            /*    !* center overlay text *!*/
            /*    display: flex;*/
            /*    align-items: center;*/
            /*    justify-content: center;*/
            /*}*/

            /* hover */
            /*.image-gallery li:hover .overlay {*/
            /*    transform: scale(1);*/
            /*}*/

            .image_picker_image{
                height: 100%;
                width: 100%;
                object-fit: cover;
            }
            #search{
                width: 28%;
                position: absolute;
                top: 1.3rem;
                right: 24rem;
            }

            #cancel{
                position: absolute;
                bottom: 1rem;
                width: 13%;
                left: 1rem;
            }
            .editor-landing__demo-inner, .editor-landing__demo .codex-editor__redactor {
                min-height: 450px;
            }
            .editor-landing__demo-inner {
                background: #fff;
                border-radius: 8px;
                -webkit-box-shadow: 0 24px 24px -18px rgb(69 104 129 / 33%), 0 9px 45px 0 rgb(114 119 160 / 12%);
                box-shadow: 0 24px 24px -18px rgb(69 104 129 / 33%), 0 9px 45px 0 rgb(114 119 160 / 12%);
                padding: 70px 50px;
                font-size: 16px;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
            }

            @media (max-width: 1550px) {
                .editor-landing__demo {
                    max-width: 850px;
                }

                .editor-landing__demo {
                    /*background: #eef5fa;*/
                    border-radius: 100px;
                    max-width: 950px;
                    margin: 0 auto;
                    padding: 70px 60px;
                }
            }

            .form-popup {
                display: none;
                position: fixed;
                bottom: 3rem;
                right: 12.5rem;
                border: 3px solid #f1f1f1;
                z-index: 9;
                background: #dfdfdf;
                width: 70rem;
                height: 40rem;
            }

            /* Add styles to the form container */
            .form-container {
                /*max-width: 300px;*/
                padding: 10px;
                background-color: white;
            }

            .simple-image {
                padding: 20px 0;
            }

            .simple-image input {
                width: 100%;
                padding: 10px;
                border: 1px solid #e4e4e4;
                border-radius: 3px;
                outline: none;
                font-size: 14px;
            }

            /* Full-width input fields */
            .form-container input[type=text], .form-container input[type=password] {
                width: 100%;
                padding: 15px;
                margin: 5px 0 22px 0;
                border: none;
                background: #f1f1f1;
            }

            /* When the inputs get focus, do something */
            .form-container input[type=text]:focus, .form-container input[type=password]:focus {
                background-color: #ddd;
                outline: none;
            }

            /* Set a style for the submit/login button */
            .form-container .btn {
            background: rgb(55 60 55);
                color: white;
                padding: 16px 20px;
                border: none;
                cursor: pointer;
                width: 100%;
                margin-bottom:10px;
                opacity: 0.8;
            }

            /* Add a red background color to the cancel button */
            .form-container .cancel {
                background-color: red;
            }

            /* Add some hover effects to buttons */
            .form-container .btn:hover, .open-button:hover {
                opacity: 1;
            }
        </style>

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>
        <script src="{{asset('js/image-picker.min.js')}}"></script>
        @if (isset($js))
           {{ $js }}
        @endif
    </body>
</html>
