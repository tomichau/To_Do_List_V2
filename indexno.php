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
            <div class="col-sm-6">
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
            <div class="col-sm-6">
                <div class="container">
                    <div class="row">
                        <!--Pause-->
                        <div class="col-sm panels ">   
                            <div class="col-sm">
                                <h4 class="pause"><span class="fas fa-pause"></span> En pause</h4>
                            </div>
                            <?php
                            $statmentpause = $db->query('SELECT * FROM to_do WHERE Status = 0');
                            while($row = $statmentpause->fetch()){
                                echo "<div class='col-sm-12 panel'>";
                                    echo "<form action='index.php' method='POST' class=''>";
                                        echo "<div class='card-header'>";
                                            echo "<input type='submit' class='delete col-1' id='delete".$row['id']."' name ='delete".$row['id']."' value='X'>";
                                            echo "<p class='date col-11'>Créé le : ".$row['Creat'];
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<input class='titles' id='titles".$row['id']."' name='titles".$row['id']."' type='text' value= '".$row['Title']."'>";
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<textarea  id='todo".$row['id']."' name='todo".$row['id']."'>".$row['Description']."</textarea>";
                                            echo "</div>";
                                            echo "<div class='card-footer'>";
                                                echo "<div class='col-sm-12'>";
                                                    echo "<select name='status".$row['id']."' id='status".$row['id']."' class='status'>";
                                                        echo "<option value='0'>En pause</option>";
                                                        echo "<option value='1'>En cours</option>";
                                                        echo "<option value='2'>Terminer</option>";
                                                    echo "</select>";
                                                    echo "<input type='submit' value='modifier' id='modify".$row['id']."' name='modify".$row['id']."' class='modify'>";
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</form>";
                                    echo "</div>";
                            }
                            ?>
                            
                            
                        </div>
                        <!--Progress-->
                        <div class="col-sm panels">   
                            <div class="col-sm">
                                <h4 class="prog"><span class="fas fa-list-ul"></span> En cours</h4>
                            </div>
                            <?php
                            $statmentProgress = $db->query('SELECT * FROM to_do WHERE Status = 1');
                            while($row = $statmentProgress->fetch()){
                                echo "<div class='col-sm-12 panel'>";
                                    echo "<form action='index.php' method='POST'>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<input type='submit' class='delete' id='delete".$row['id']."' name ='delete".$row['id']."' value='X'>";
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<p class='date'>Créé le : ".$row['Creat'];
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<input classe='titles'id='titles".$row['id']."' name='titles".$row['id']."' type='text' value= '".$row['Title']."'>";
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<textarea  id='todo".$row['id']."' name='todo".$row['id']."'>".$row['Description']."</textarea>";
                                            echo "</div>";
                                            echo "<div class='col-sm-12'>";
                                                echo "<select name='status".$row['id']."' id='status".$row['id']."' class='status'>";
                                                    echo "<option value='0'>En pause</option>";
                                                    echo "<option value='1'>En cours</option>";
                                                    echo "<option value='2'>Terminer</option>";
                                                echo "</select>";
                                                echo "<input type='submit' value='modifier' id='modify".$row['id']."' name='modify".$row['id']."' class='modify'>";
                                            echo "</div>";
                                        echo "</form>";
                                    echo "</div>";
                            }
                            ?>
                            
                        </div>
                        <!--Finish-->
                        <div class="col-sm panels panels-finish">   
                            <div class="col-sm">
                                <h4 class="finish"><span class="fas fa-tasks"></span> Terminer</h4>
                            </div>
                            <?php
                            $statmentFinish = $db->query('SELECT * FROM to_do WHERE Status = 2');
                            while($row = $statmentFinish->fetch()){
                                echo "<div class='col-sm-12 panel'>";
                                    echo "<form action='index.php' method='POST'>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<input type='submit' class='delete' id='delete".$row['id']."' name ='delete".$row['id']."' value='X'>";
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<p class='date'>Créé le : ".$row['Creat'];
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<input classe='titles'id='titles".$row['id']."' name='titles".$row['id']."' type='text' value= '".$row['Title']."'>";
                                        echo "</div>";
                                        echo "<div class='col-sm-12'>";
                                            echo "<textarea  id='todo".$row['id']."' name='todo".$row['id']."'>".$row['Description']."</textarea>";
                                            echo "</div>";
                                            echo "<div class='col-sm-12'>";
                                                echo "<select name='satus".$row['id']."' id='status".$row['id']."' class='status'>";
                                                    echo "<option value='0'>En pause</option>";
                                                    echo "<option value='1'>En cours</option>";
                                                    echo "<option value='2'>Terminer</option>";
                                                echo "</select>";
                                                echo "<input type='submit' value='modifier' id='modify".$row['id']."' name='modify".$row['id']."' class='modify'>";
                                            echo "</div>";
                                        echo "</form>";
                                    echo "</div>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
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