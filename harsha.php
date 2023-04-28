<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "IncludeFiles/links1.php"?>
    <title>Document</title>
</head>
<body>
    <style>
        #video{
            border-radius: 15px;
        }
    </style>
<video id="video" autoplay hight="300" width="200"></video>
<button id='pass' onclick='pass()'>Pass</button>
<button onclick='play()'>play</button>
<script>
    var video = document.getElementById("video");
    navigator.mediaDevices.getUserMedia({ video: true})
    .then(stream =>{
        video.srcObject = stream;
    })
    .catch(error =>{
        console.log("An error in video", error);
    })
      function pass(){
    video.pause();
  }
  function play(){
    video.play();
  }
  //     if (navigator.connection) {
//   var connection = navigator.connection;
//   var type = connection.type;
//   var effectiveType = connection.effectiveType;
//   var rtt = connection.rtt;
//   var downlink = connection.downlink;

//   console.log("Network type: " + type);
//   console.log("Effective network type: " + effectiveType);
//   console.log("Round-trip time: " + rtt + " ms");
//   console.log("Downlink speed: " + downlink + " Mbps");
// } else {
//   console.log("The navigator.connection API is not supported on this browser.");
// }
// console.log(downlink.typeof);
// if (navigator.connection) {
//   const { downlink } = navigator.connection;
//   if (downlink) {
//     const speedMbps = downlink.toFixed(2);
//     console.log(`Current network speed: ${speedMbps} Mbps`);
//   }
// // }
// function getNetworkSpeed() {
//   const connection = navigator.connection || navigator.mozConnection || navigator.webkitConnection;
//   if (connection) {
//     const { downlink, effectiveType, rtt } = connection;
//     const speed = downlink; // convert to bits per second
//     console.log(`Network speed: ${rtt} mbps, Connection type: ${effectiveType}`);
//     return rtt;
//   } else {
//     console.log('Network information not available.');
//     return null;
//   }
// }

// setInterval(getNetworkSpeed, 1000); // Call getNetworkSpeed every second

// if(navigator.onLine){
//     console.log("online");
// }else{
//     console.log("offline");
// }
</script>
</body>
</html>