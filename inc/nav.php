<nav class="navbar navbar-default navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-collapse collapse" id="mainNavbar">
            <ul class="nav navbar-nav">
                <?php 
               // if ($_SESSION['type']=='Admin'){
                    echo '<li class="active"><a href="users.php"><b>Users</b></a></li> ';

                //}
                ?>
                <li class="active"><a href="appartement.php"><b>Appartement</b></a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li> <a href=""><?php // echo $_SESSION['login'].' '.$_SESSION['type'] ; ?> </a>   </li>
                <li><a href="deconnexion.php">Déconnexion</a></li>
            </ul>
        </div>
    </div>
</nav>