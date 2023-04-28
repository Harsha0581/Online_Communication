<script>
    var connection=0;
    const body = document.querySelector("body");
    var time = setInterval(()=>{
        if(!navigator.onLine){
            connection = 1;
            alert("Fail to connect internet");
            body.style ="display:none";
        }else{
            body.style ="display:block";
        }
    },1);  
</script>