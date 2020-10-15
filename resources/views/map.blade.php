<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>KOKABE</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->

        <style>
            
            html, body {
                height:100%;
                padding:0;margin:0;
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 94vh;
                margin: 0;
            }

            .full-height {
                height: 94vh;
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
                font-size: 50px;
                margin: 0 auto;
                position: absolute;
                left: 40%;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
                
            }

            .table{
                margin-top: 5px;
            }

        </style>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>       
        <script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap&key=[]' async defer></script>
    
    </head>
    <body>
        <h1 class="title">MAP</h1>
       
        <div class="flex-center position-ref full-height">

            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            
            <div style="display: flex; width:90%;height:50%;">
                <div id="app" >
                    <p style="color:black;">住所を入力</p><input id="keyword" value="" type="text" size="35">
                    <input type="button" id="btn" value="送信"><br>      
                </div>
                <div id="myMap"></div>
            </div>

        </div>
        <div>
            <table id="table" class="table">
                <tr>
                    <th>市区町村</th>
                    <th>地名</th>
                    <th>経度</th>
                    <th>緯度</th>
                </tr>
            </table>
        </div>

        <!-- Scripts -->
        <script>
                function GetMap() {
                //Map init
                map = new Microsoft.Maps.Map('#myMap', {
                    center: new Microsoft.Maps.Location(36.6149, 138.1941), //Location(latitude: number, longitude: number)
                    zoom: 15,
                    credentials:'AgTSO8Gn-Cn4qus5G-JbxEiWiVb-evCtMuIQODEdNxMkMypdnhnpCfd0fDF0ZQ2V'
                });
                let center = map.getCenter();
                //********************************************************************
                //infobox
                //********************************************************************
                infobox = new Microsoft.Maps.Infobox(center, {
                    visible: false
                });
                infobox.setMap(map);//Add infobox to Map        
            };
            
            function pinLocations(data) { 
                //Iterative processing
                    let len = data.response.location.length;
                    console.log(data.response.location.length);

                    for (let i=0; i<len; i++) {      
                        let a = ({latitude:data.response.location[i].y, longitude:data.response.location[i].x});
                        let pin = new Microsoft.Maps.Pushpin(a);
                        
                        //PushuPin:Create display character for each
                        pin.metadata = {
                            title: '掲示スペース' + i,
                            description: data.response.location[i].prefecture + data.response.location[i].city + data.response.location[i].town
                        };

                        //Location(latitude: number, longitude: number)
                        Microsoft.Maps.Events.addHandler(pin, 'click', pushpinClicked);
                        map.entities.push(pin); // Add Pushpin to Map
                        }
                };
            
            //Pushpin click event
            function pushpinClicked(e) {
                if (e.target.metadata) {
                    //Set information of clicked Pushpin
                    infobox.setOptions({
                        location: e.target.getLocation(),
                        title: e.target.metadata.title,
                        description: e.target.metadata.description,
                        visible: true
                    });
                }
            }
            //*****************************************************
            // HeartRails GeoAPIへデータRequest → APIサーバーからresponseデータ取得
            //http://geoapi.heartrails.com/api.html#suggest
            //*****************************************************
            $("#btn").on("click", function() {
                //送信データをObject変数で用意
                const data = {
                    keyword:$("#keyword").val(),
                    matching:"like"     
                };
                
                //Ajax（非同期通信）
                axios.get('http://geoapi.heartrails.com/api/json?method=suggest', {
                    params:data
                })
                .then(function (response) {
                    //データ受信成功！！showData関数にデータを渡す
                    pinLocations(response.data);
                    showData(response.data);
                }).catch(function (error) {
                    console.log(error);//通信Error
                }).then(function () {
                    console.log("Last");//通信OK/Error後に処理を必ずさせたい場合
                });                   
            });         
                        
            //*****************************************************
            //データ表示処理
            //*****************************************************
            function showData(data){
                //データ確認用
                // console.log(data);
                
                //データを繰り返し処理で取得
                const len  = data.response.location.length; //データ数を取得 data.length
                for( let i=0; i<len; i++){
                    $("#table").append(
                        '<tr><td>' 
                        + data.response.location[i].city + '</td><td>' + data.response.location[i].town + '</td><td>' + data.response.location[i].x + '</td><td>' +  data.response.location[i].y +
                        '</td></tr>');
                };                   
            }
            
    </script>
    
    </body>
</html>

 
