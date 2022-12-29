<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>OUT</title>
</head>

<body>

    <body style="background-color: #8080803d">
        <nav class="navbar navbar-light " style="background-color:#837d7d">
            <form class="container-fluid justify-content-center">
                <a class="btn btn-warning mt-3 m-3 w-25" href="search.php">البحث </a>
                <a class="btn btn-danger fw-bolder mt-3  m-3 w-25" href="out.php">المصنع</a>
                <a class="btn btn-success mt-3 m-3 w-25" href="done.php">تم التسليم </a>
                <a class="btn btn-info mt-3 m-3 w-100" href="in.php">الفرع</a>
            </form>
        </nav>
        <main class="container mt-3 " style=" max-width:80%; margin:outo;">
            <!--  SEND BOX  -->
            <nav class="navbar navbar-light bg-light">
                <form class="container-fluid">
                    <div class="input-group">
                        <input class=" form-control " aria-describedby="basic-addon1" style="text-align: center;"
                            autofocus placeholder="استلام من المصنع" required type="number" name="nmuber">
                        <button class="input-group-text btn btn-outline-success " id="basic-addon1" type="submit"
                            name="send"> استلام </button>

                    </div>
                </form>
            </nav>

            <!-- search Box  -->
            <nav class="navbar navbar-light bg-light">
                <form class="container-fluid">
                    <div class="input-group">
                        <input class=" form-control " aria-describedby="basic-addon1" style="text-align: center;"
                            type="number" name="searchv" placeholder="البحث عن العناصر بالفرع ">
                        <button class="input-group-text btn btn-outline-success " id="basic-addon1" type="submit"
                            name="search">البحث</button>
                    </div>
                </form>
            </nav>


            <?php
require_once '../datapass.php';
header( "Refresh:30;url=http://new-worled.eb2a.com/out/in.php" ); 

  $done = $databass->prepare('SELECT * FROM `shop` WHERE  `STATA`="IN SHOP"');
$done->execute();
$count = $done->rowCount();
echo   ' <table class="table table-bordered table-striped table-responsive mt-3" id="example">';
 echo'  <tr class="table-success"> 
        <th  >No</th>
        <th >بالفرع  </th>
        <th  >'.  $count .'</th>
     
      </tr>
    </table>';

//  ubdate stata 
if(isset($_GET['send'])){
  $addcheak=$databass->prepare("SELECT * FROM shop WHERE NUMBER=:NUMBR" );
  $addcheak->bindParam("NUMBR",$_GET['nmuber']);
  $addcheak->execute();
  if($addcheak->rowCount()>0){
    $addcheak=$addcheak->fetchObject();
    if($addcheak->STATA =="IN SHOP"){
      echo' <div class="alert alert-success" role="alert">
      تم الاستلام بتاريخ'.
      $addcheak->DONE_DATE .'
      
      </div>';
    }else if ($addcheak->STATA =="IN LAP"){
      $UBDATE = $databass->prepare("  UPDATE `shop` SET `STATA`='IN SHOP', RECIVE_DATE=CURRENT_TIMESTAMP WHERE NUMBER = :id");
      $UBDATE->bindParam('id',$_GET['nmuber']);
       $UBDATE->execute();

       echo' <div class="alert alert-success" role="alert">
       تم الاستلام من المعمل    </div>';   
       
       
     
    }else if($addcheak->STATA =='DONE'){
      echo' <div class="alert alert-success" role="alert">
      تم الاستلام من قبل العميل   </div>';  
    }

  }else{
      echo' <div class="alert alert-success" role="alert">
    لم يتم ارسال هذا الايصال من قبل 
      </div>';
  }
  // header("location:done.php") ;
}



// searh 
if(isset($_GET['search'])){
  $search=$databass->prepare("SELECT * FROM  shop WHERE  NUMBER LIKE :VALUE1 ");
  $searchValue="%".$_GET['searchv'] ."%";
  $search->bindParam('VALUE1',$searchValue);
  $search->execute();
  if($search->rowCount()>0){
   
    echo '<table class="table mt-3">';
    echo   ' <tr class="table-primary">';
    echo    "<th> NO</th>";
    echo    "<th> SERIAL</th>";
    //  echo    "<th> SEND DATE</th>";
    echo    "<th> RECIVE DATE</th>";
    // echo    "<th> DONE DATE</th>";
    echo    "<th> STAT</th>";
    echo    "<th>DEALET</th>";
    echo    "<th> NOTE</th>";
    echo   "</tr>";  
    foreach($search AS $show){
      if($show["STATA"]==="IN SHOP"){
        echo   " <tr class='table-light'>";
        echo   "  <td >".$show["ID"]. "</td>";
        echo    "<td >".$show["NUMBER"]."</td>";
        // echo    " <td >".$show["SEND_DATA"]."</td>";
        echo    " <td >".$show["RECIVE_DATE"]."</td>";
        // echo    " <td >".$show["DONE_DATE"]."</td>";
        echo    " <td class='table-info' >".$show["STATA"]."</td>";
        echo '<td>  <form>  <button name="delet" class="btn btn-danger border-info" value="'.$show["ID"].'"> Delete</button>   </form> </td>';
         if($show["NOTTS"]==!null){


            echo    '<td >
            <nav class="navbar navbar-light bg-light">
            <form class="container-fluid"> 
            <div class="input-group"> 
            '.$show["NOTTS"].'
            <button name="add" class=" border-danger input-group-text btn btn-outlinewarning " value="'.$show["ID"].'"> Edit</button> 

            </div>  </form> </nav></td>';

         }else{echo    '<td > 
            <nav class="navbar navbar-light bg-light">
            <form class="container-fluid"> 
            <div class="input-group">
            <input class="form-control "  type="text" name="note" >
            <button class=" border-danger input-group-text btn btn-outlinewarning  " name="add" value="'.$show["ID"].'">Note</button> 
            </div> </form> </nav></td>';

         } echo   " <tr>";
        }
    }echo  ' </table>';
  
}else{
        echo' <div class="alert alert-warning" role="alert">
        غير موجود </div>';
}
// header("Refresh:30; url=in.php");
}



    
$view=$databass->prepare("SELECT * FROM shop WHERE STATA ='IN SHOP'" );
if($view->execute()){
    if($view->rowCount()>0){
        echo '<table class="table mt-3">';
        echo   " <tr  class='table-primary'>";
        echo    "<th> NO</th>";
        echo    "<th> SERIAL</th>";
        // echo    "<th> SEND DATE</th>";
        echo    "<th> RECIVE DATE</th>";
        // echo    "<th> DONE DATE</th>";
        echo    "<th> STAT</th>";
        echo    "<th>DEALET</th>";
        echo    "<th> NOTE</th>";
        echo   "</tr>";
        foreach($view AS $show){
            echo   " <tr class='table-light'>";
            echo   "  <td >".$show["ID"]. "</td>";
            echo    "<td >".$show["NUMBER"]."</td>";
            // echo    " <td >".$show["SEND_DATA"]."</td>";
            echo    " <td >".$show["RECIVE_DATE"]."</td>";
            // echo    " <td >".$show["DONE_DATE"]."</td>";
            echo    " <td class='table-success' >".$show["STATA"]."</td>";
            echo '<td>  <form>  <button name="delet" class="btn btn-danger border-info" value="'.$show["ID"].'"> Delete</button>   </form> </td>';
             if($show["NOTTS"]==!null){


                echo    '<td >
                <nav class="navbar navbar-light bg-light">
                <form class="container-fluid"> 
                <div class="input-group"> 
                '.$show["NOTTS"].'
                <button name="add" class=" border-danger input-group-text btn btn-outlinewarning " value="'.$show["ID"].'"> Edit</button> 

                </div>  </form> </nav></td>';

             }else{echo    '<td > 
                <nav class="navbar navbar-light bg-light">
                <form class="container-fluid"> 
                <div class="input-group">
                <input class="form-control "  type="text" name="note" >
                <button class=" border-danger input-group-text btn btn-outlinewarning  " name="add" value="'.$show["ID"].'">Note</button> 
                </div> </form> </nav></td>';

             } echo   " <tr>";

             }   echo  ' </table>';
    
    }
}
    


//  add note buto
if(isset($_GET['add'])){

    $updatt=$databass->prepare("UPDATE `shop` SET `NOTTS`=:NOTE WHERE ID=:ID" );
    $updatt->bindParam("ID",$_GET['add']);
    $updatt->bindParam("NOTE",$_GET['note']);
    if($updatt->execute()){
        
        // header("location:done.php");

      }}
     //  deale  iteam  buto
     if(isset($_GET['delet'])){
        $delet=$databass->prepare("DELETE FROM `shop` WHERE ID=:ID");
        $delet->bindParam("ID",$_GET['delet']);
      if($delet->execute()){
        
        // header("location:done.php");

      }}


?>
        </main>
    </body>

</html>