<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 12px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }
            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head>
    <body>
        <style>
            #container {
                min-width:300px;
                min-height:200px;
                border:3px dashed #000;
            }
        </style>
        <div id='container'></div>
        <script>
            function addDNDListener(obj){
                obj.addEventListener('dragover',function(e){
                    e.preventDefault();
                    e.stopPropagation();
                },false);
                obj.addEventListener('dragenter',function(e){
                    e.preventDefault();
                    e.stopPropagation();
                },false);

                obj.addEventListener('drop',function(e){
                    e.preventDefault();
                    e.stopPropagation()
                    var ul = document.createElement("ul");
                    var fileList = e.dataTransfer.files;
                    for(let i=0;i<fileList.length;i++){
                        var li = document.createElement('li');
                        li.innerHTML = '<label id="'+fileList[i].name+'">'+fileList[i].name+':</label><progress value="0"max="100"></progress>';
                        ul.appendChild(li);
                    }
                    document.getElementById('container').appendChild(ul);
                    for(let j=0;j<fileList.length;j++){
                        uploadFile(fileList[j],10);
                    }
                },false);
            }
            function uploadFile(file,gap){
                var chunk = 8*1024*1024;
                var progress = document.getElementById(file.name).nextSibling;
                var reader = new FileReader();
                reader.readAsArrayBuffer(file);
                reader.onloadstart = function (e) {

                };
                reader.onprogress = function(e){
                    progress.value = (e.loaded/e.total) * 100;
                };
                reader.onload = function(e){
                    var buf = new Int8Array(e.target.result);
                    const query = {
                        fileSize: e.total,
                        dataSize: chunk,
                        fileName: new Date().getTime(),
                        numSize: Math.ceil(e.total/chunk),
                        extend: file.name.substr(file.name.lastIndexOf('.'))
                    };
                    var xhr = new XMLHttpRequest();
                    xhr.responseType = 'json';
                    let [i,start,end,k,n] = [0,0,0,gap,gap];
                    test(n,buf,query,end,i,start,k)
                };
            }
            function test(n,buf,query,end,i,start,k) {
                var xhr = new XMLHttpRequest();
                var queryStr = Object.getOwnPropertyNames(query).map( key => {
                    return key + "=" + query[key];
                }).join("&");
                xhr.responseType = 'json';
                xhr.onreadystatechange = function(){
                    if (xhr.readyState === 4 && xhr.status === 200 ){
                        console.log('4444444===>'+xhr.response.message)
                        if (xhr.response.status === 2){
                            console.log(xhr.response.data);
                            xhr.response.data.forEach(function(value,key){
                                console.log('value => '+value+" key=> "+key);
                            });
                        }
                    }else if (xhr.readyState === 2 && xhr.status === 200) {
                        console.log('22222222222===>'+xhr.readyState)
                        n =k + n;
                        if ( i < query['numSize'] ){
                            if (i < n){
                                test(n,buf,query,end,i,start,k);
                            }
                        }
                    }else if (xhr.readyState === 3 && xhr.status === 200){
                        // console.log('33333333333===>'+xhr.readyState)
                    }else {
                        // console.log('1111111111===>'+xhr.readyState)
                    }
                };
                while(i < n){
                    if (i === query['numSize'] ){
                        return;
                    }
                    end = start + query['dataSize'];
                    xhr.open("POST", "/test/upLoad?="+queryStr+"&num="+i);
                    xhr.setRequestHeader("X-CSRF-TOKEN","{{csrf_token()}}");
                    xhr.send(buf.slice(start,end));
                    start = end;
                    ++i;
                    console.log("i等于 "+n+" =>"+i);
                }
            }
            window.onload = function(){
                addDNDListener(document.getElementById('container'));
            };
        </script>
        {{--<div class="flex-center position-ref full-height">--}}
            {{--@if (Route::has('login'))--}}
                {{--<div class="top-right links">--}}
                    {{--@auth--}}
                        {{--<a href="{{ url('/home') }}">Home</a>--}}
                    {{--@else--}}
                        {{--<a href="{{ route('login') }}">Login</a>--}}
                        {{--<a href="{{ route('register') }}">Register</a>--}}
                    {{--@endauth--}}
                {{--</div>--}}
            {{--@endif--}}

            {{--<div class="content">--}}
                {{--<div class="title m-b-md">--}}
                    {{--Laravel--}}
                {{--</div>--}}

                {{--<div class="links">--}}
                    {{--<a href="https://laravel.com/docs">Documentation</a>--}}
                    {{--<a href="https://laracasts.com">Laracasts</a>--}}
                    {{--<a href="https://laravel-news.com">News</a>--}}
                    {{--<a href="https://forge.laravel.com">Forge</a>--}}
                    {{--<a href="https://github.com/laravel/laravel">GitHub</a>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </body>
</html>
