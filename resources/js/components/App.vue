<template>
  <div id="app" >
      <h1>Infobox:Multipull (Click on pushpin)
      </h1>
      <div style="display: flex; width:90%;height:400px;">
          <div >
              <p style="color:white;"> 住所を入力</p><input id="keyword" value="" type="text" size="35" > 
              <input type="button" id="btn" value="送信" v-on:click="Mapcreate(); getLocation(); "  ><br>      
          </div>
          <div id="myMap" ></div>     
      </div>
      <table id="table" style="color:white;">
          <tr>
              <th>市区町村</th>
              <th>地名</th>
              <th>経度</th>
              <th>緯度</th>
          </tr>
      </table>
  </div>
</template>


  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>       
  <script src='https://www.bing.com/api/maps/mapcontrol?callback=GetMap' async defer></script>

<script>
import axios from 'axios'


export default {
  
  methods: {

    Mapcreate:function(){
      let map = new Microsoft.Maps.Map('#myMap', {
            center: new Microsoft.Maps.Location(36.6149, 138.1941), //Location(latitude: number, longitude: number)
            zoom: 10,
            credentials:'AgTSO8Gn-Cn4qus5G-JbxEiWiVb-evCtMuIQODEdNxMkMypdnhnpCfd0fDF0ZQ2V'
        });
        let center = map.getCenter();
       //********************************************************************
        //infobox
        //********************************************************************
        let infobox = new Microsoft.Maps.Infobox(center, {
            visible: false
        });
        infobox.setMap(map);//Add infobox to Map        
    },
      
      
      pushpinClicked:function(e){

        //Pushpin click event
          if(e.target.metadata) {
            let map = new Microsoft.Maps.Map('#myMap', {
                center: new Microsoft.Maps.Location(36.6149, 138.1941), //Location(latitude: number, longitude: number)
                zoom: 15,
                credentials:'AgTSO8Gn-Cn4qus5G-JbxEiWiVb-evCtMuIQODEdNxMkMypdnhnpCfd0fDF0ZQ2V'
            });
            let center = map.getCenter();
            
            let infobox = new Microsoft.Maps.Infobox(center, {
                visible: false
            });

            // Set information of clicked Pushpin
            infobox.setOptions({
              location: e.target.getLocation(),
              title: e.target.metadata.title,
              description: e.target.metadata.description,
              visible: true
            });
             infobox.setMap(map);//Add infobox to Map 
        
          } 

    },
     
      // pushpinClicked:function(e){
      //   //Pushpin click event
      //     if(e.target.metadata) {
      //       //Set information of clicked Pushpin
      //       infobox.setOptions({
      //         location: e.target.getLocation(),
      //         title: e.target.metadata.title,
      //         description: e.target.metadata.description,
      //         visible: true
      //       });
      //     }
      // },

      window:onload = function GetMap() {
        //Map init
        let map = new Microsoft.Maps.Map('#myMap', {
            center: new Microsoft.Maps.Location(36.6149, 138.1941), //Location(latitude: number, longitude: number)
            zoom: 15,
            credentials:'AgTSO8Gn-Cn4qus5G-JbxEiWiVb-evCtMuIQODEdNxMkMypdnhnpCfd0fDF0ZQ2V'
        });
        let center = map.getCenter();
       //********************************************************************
        //infobox
        //********************************************************************
        let infobox = new Microsoft.Maps.Infobox(center, {
            visible: false
        });
        infobox.setMap(map);//Add infobox to Map        
    },
      
     

//*****************************************************
        // HeartRails GeoAPIへデータRequest → APIサーバーからresponseデータ取得
        //http://geoapi.heartrails.com/api.html#suggest
        //*****************************************************
      getLocation:function(){
          let vm = this;
          //送信データをObject変数で用意
          const data={
            keyword: $("#keyword").val(),
            matching: "like"
          };
          //Ajax（非同期通信）
          axios.get('http://geoapi.heartrails.com/api/json?method=suggest',{
            params: data
          })
            .then(function(response) {
              //データ受信成功！！showData関数にデータを渡す
              vm.pinLocations(response.data);
              vm.showData(response.data);

            }).catch(function(error) {
              console.log(error); //通信Error
            }).then(function() {
              console.log("Last"); //通信OK/Error後に処理を必ずさせたい場合
            });
            
      },

      pinLocations:function(data) {

          let map = new Microsoft.Maps.Map('#myMap', {
              center: new Microsoft.Maps.Location(36.6149, 138.1941), //Location(latitude: number, longitude: number)
              zoom: 15,
              credentials:'AgTSO8Gn-Cn4qus5G-JbxEiWiVb-evCtMuIQODEdNxMkMypdnhnpCfd0fDF0ZQ2V'
          });
      
          //Iterative processing
          let len=data.response.location.length;
          console.log(data.response.location.length);

          for(let i=0;i<len;i++) {
            let a=({ latitude: data.response.location[i].y,longitude: data.response.location[i].x });
            let pin=new Microsoft.Maps.Pushpin(a);

            //PushuPin:Create display character for each
            pin.metadata={
              title: '掲示スペース'+i,
              description: data.response.location[i].prefecture+data.response.location[i].city+data.response.location[i].town
            };

            
            //Location(latitude: number, longitude: number)
            Microsoft.Maps.Events.addHandler(pin,'click',this.pushpinClicked);
            map.entities.push(pin); // Add Pushpin to Map
          }
      },
 

        //*****************************************************
        //データ表示処理
        //*****************************************************
        
        showData:function(data){
    
          //データ確認用
          // console.log(data);
          //データを繰り返し処理で取得
          const len=data.response.location.length; //データ数を取得 data.length
          for(let i=0;i<len;i++) {
            $("#table").append(
              '<tr><td>'
              +data.response.location[i].city+'</td><td>'+data.response.location[i].town+'</td><td>'+data.response.location[i].x+'</td><td>'+data.response.location[i].y+
              '</td></tr>');
          };


       }
  }
}

</script>

<style>
html,body{height:100%;}body{padding:0;margin:0;background:#333;}h1{padding:0;margin:0;font-size:50%;color:white;}
</style>


   



 



