<div id="header" class="container-fluid">
<!-- userheader main head -->
<?php 
    $name = $_SESSION["username"];
    include "IncludeFiles/function.php";
    include "connection/ServerConnection.php";
    $stm = "SELECT * FROM newusers where username = '$name'";
    $res = mysqli_query($server_connection, $stm);
    $userrow= mysqli_fetch_assoc($res);
    $short_cut = Array(3=>"OH",4=>"TH", 5=>"TTH", 6=>"L",7=> "TL",8=>"CR",9=>"CRT",10=>"M",11=>"TM");
    $cutted_length = Array(4=>1, 5=>2, 6=>1, 7=>2, 8=>1, 9=>3, 10=>1, 11=>3);
    $numbervalue = $userrow['daily_streck'];
    $totalstrecks =0;
    if($numbervalue>2){
        $valuelength = strlen($numbervalue);
        @$cutnumber = substr($numbervalue,0,$cutted_length[$valuelength]);
        @$shortcut = $short_cut[$valuelength];
        $totalstrecks =$cutnumber.$shortcut;
    }else{
       $totalstrecks = $userrow['daily_streck'];
    }
?>
    <h2 id='showuser'>Hiii...<?php echo $name ?>  <b id="hiiimage" style="float: left;"><?php echo $totalstrecks;?></b></h2>     
</div>
<div id="navbar">
    <ul>
        <?php 
        // nav bar
            $stm = "SELECT * FROM navbar";
            $res = mysqli_query($server_connection, $stm);
            while ($row = mysqli_fetch_assoc($res)) {
                echo "
                    <li><a href = $row[navlink]>$row[navname]</a></li> 
                ";
            }
        ?>
    </ul>
    <script>
        const streckNumber = <?php echo $userrow['daily_streck']?>;
        const streckinnertext = document.querySelector("#hiiimage");
        // console.log(streckNumber);
        if(streckNumber <= 1){
            streckinnertext.innerHTML += "&#128545;";
            streckinnertext.style = "color:red";
        }
        if(streckNumber > 1){
            streckinnertext.innerHTML += " "+"&#128512;";
            streckinnertext.style = "color:yellowgreen";
        }
        if(streckNumber >=10){
            streckinnertext.innerHTML += " "+"&#128131;	&#128378;";
            streckinnertext.style = "color:coral";
        }
        setInterval(() => {
            location.reload();
        }, 15000);
    </script>
</div>