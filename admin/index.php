<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/base.css">
    <title>Area Bibliotecario</title>
</head>

<body>
    <div class="content">
        <?php
        require_once("../assets/admin_header.php");
        ?>

        <br>

        <h4>Bibliotecario <?php echo $adminAccount->getName() . ' ' .
                                $adminAccount->getSurname(); ?> </h4>

        <br>

        Mail di accesso: <?php echo $adminAccount->getMail(); ?>

        <br><br>

        <b>Permessi</b>
        <br>

        Prestiti: <?php
                    //se consentito
                    if ($adminAccount->getACLloan()) {
                        echo '<span class="badge badge-success">Consentito</span>';
                        //se non consentito 
                    } else {
                        echo '<span class="badge badge-danger">NON consentito</span>';
                    }
                    ?>
        <br>
        Catalogo: <?php
                    //se consentito
                    if ($adminAccount->getACLcatalogue()) {
                        echo '<span class="badge badge-success">Consentito</span>';
                        //se non consentito 
                    } else {
                        echo '<span class="badge badge-danger">NON consentito</span>';
                    }
                    ?>
        <br>
        Utenti: <?php
                //se consentito
                if ($adminAccount->getACLuser()) {
                    echo '<span class="badge badge-success">Consentito</span>';
                    //se non consentito 
                } else {
                    echo '<span class="badge badge-danger">NON consentito</span>';
                }
                ?>
        <br>
        Bibliotecari: <?php
                        //se consentito
                        if ($adminAccount->getACLadmin()) {
                            echo '<span class="badge badge-success">Consentito</span>';
                            //se non consentito 
                        } else {
                            echo '<span class="badge badge-danger">NON consentito</span>';
                        }
                        ?>
    </div>

</body>

</html>