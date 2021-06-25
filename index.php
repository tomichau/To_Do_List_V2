<!DOCTYPE html>
<html>
    <?php
    include('head.php');
    require 'admin/database.php';
    $db = Database::connect();
    if(isset($_POST['creat'])){
        if(empty($_POST['title']) || empty($_POST['description'])){
            echo "<script>alert('Veulliez remplir toutes les cases !')</script>";
        }
        else{
            $id = null;
            $creat = date('d/m/Y');
            $title = $_POST['title'];
            $todo = $_POST['description'];
            $status = 1;
            $statment = $db->prepare('INSERT INTO to_do (id,Creat,Title,Description,Status) values (?,?,?,?,?)');
            $statment->execute(array($id,$creat,$title,$todo,$status));
        }
    }
    $statmentModifyDelete = $db->query('SELECT id FROM to_do');
    while($row = $statmentModifyDelete->fetch()){
        if(isset($_POST['modify'.$row['id']])){
            if(empty($_POST['titles'.$row['id']]) || empty($_POST['todo'.$row['id']])){
                echo "<script>alert('Veulliez remplir toutes les cases !')</script>";
            }
            else{
                $id = $row['id'];
                $title = $_POST['titles'.$row['id']];
                $todo = $_POST['todo'.$row['id']];
                $status = (int)$_POST['status'.$row['id']];
                $statment = $db->prepare('UPDATE to_do set Title = ?, Description = ?, Status = ? WHERE id = ?');
                $statment->execute(array($title,$todo,$status,$id));
            }
        }
        if(isset($_POST['delete'.$row['id']])){
            $statment = $db->prepare('DELETE FROM to_do WHERE id = ?');
            $statment->execute(array($row['id']));
        }
    }
    ?>
    
    <body>
    <link rel="stylesheet" href="css/style.css"/>
        <header>
            <h1>To Do</h1>
            <div class="u"></div>
        </header>
    <section class="container-fuild">
        <div class="row">
            <div class="col-2 creats">
                <form class="creat" method="POST" action="index.php">
                    <div class="row">
                        <div class="center">
                            <div class="col-sm-12">
                                <h3><span class="fas fa-tools"></span> Créer</h3>
                            </div>
                            <div class="col-sm-12 title">
                                <input type="text" class="creatTitle" id="title" name="title" placeholder="Le Titre" value="">
                            </div>
                            <div class="col-sm-12">
                                <textarea id="description" name="description" placeholder="Ce que vous devez faire"></textarea>
                            </div>
                            <div class="col-sm-12">
                                <input type="submit" class="creatButton"id="creat" name="creat" value="+ Créer">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col trucs">
            <div class="row">
                <?php
                    $knownStatus = array(
                        0 => 'Pause',
                        1 => 'En cours',
                        2 => 'Terminer',
                    );

                    foreach ($knownStatus as $status => $name) {
                        if($name == "Pause"){
                            $classStatus = "fas fa-pause";
                            $nameStatus = "pause";
                        }
                        elseif($name == "En cours"){
                            $classStatus = "fas fa-list-ul";
                            $nameStatus = "prog";
                        }
                        elseif($name == "Terminer"){
                            $classStatus = "fas fa-tasks";
                            $nameStatus = "finish";
                        }
                        echo "<div class='col-sm panels'>" ;
                        echo "<div class='col-sm'>";
                        echo "<h4 class='".$nameStatus."'><span class='".$classStatus."'></span> ". $name ."</h4>";
                        echo "</div>";
                        $query = $db->prepare('SELECT * FROM to_do WHERE Status = ?');
                        $query->execute(array($status));                                
                        while($row = $query->fetch()){
                            echo "<div class='col-sm-12 panel'>";
                                echo "<form action='index.php' method='POST'>";
                                echo "<div class='row'>";
                                echo "<input  type='submit' class='delete' id='delete".$row['id']."' name ='delete".$row['id']."' value='X'>";
                                echo "<p class='date'>Créé le : ".$row['Creat'] . '</p> </div>';
                                echo "<input class='titles' id='titles".$row['id']."' name='titles".$row['id']."' type='text' value= '".$row['Title']."'>";
                                echo "<textarea  id='todo".$row['id']."' name='todo".$row['id']."'>".$row['Description']."</textarea>";
                                echo "<select name='status".$row['id']."' id='status".$row['id']."' class='status'>";
                                    echo "<option value='0'>En pause</option>";
                                    echo "<option value='1'>En cours</option>";
                                    echo "<option value='2'>Terminer</option>";
                                echo "</select>";
                                echo "<input type='submit' value='modifier' id='modify".$row['id']."' name='modify".$row['id']."' class='modify'>";                                      
                                    echo "</form>";
                                echo "</div>";
                        }
                        echo "</div>";
                    }
                    
                ?>
            </div>
        </div>
    </section>
    <footer>
        <p>Creat by kéin</p>
    </footer>
    <?php
    include('footer.php');
    Database::disconnect();
    ?>
</html>