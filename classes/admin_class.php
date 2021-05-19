<?php

require_once("../assets/db.php");

class Admin
{
    /* proprietà della classe */

    /* idAdmin dell'admin loggato (o NULL se non loggato) */
    private $id;

    /* mail dell'admin loggato (o NULL se nessuno loggato) */
    private $mail;

    /* nome dell'admin loggato */
    private $name;

    /* cognome dell'admin loggato */
    private $surname;

    /* scadenza password dell'admin loggato */
    private $expiration;

    /* ACL catalogo dell'admin loggato */
    private $ACLcatalogue;

    /* ACL prestiti loggato */
    private $ACLloan;

    /* ACL gestione utenti loggato */
    private $ACLuser;

    /* ACL gestione admin loggato */
    private $ACLadmin;

    /* TRUE se autenticato, FALSE altrimenti */
    private $authenticated;


    /* costruttore */
    public function __construct()
    {
        /* inizializza tutti i parametri a NULL */
        $this->id = NULL;
        $this->mail = NULL;
        $this->name = NULL;
        $this->surname = NULL;
        $this->expiration = NULL;

        /* inizializzo di default le acl a false */
        $this->ACLcatalogue = false;
        $this->ACLloan = false;
        $this->ACLuser = false;
        $this->ACLadmin = false;

        /* inizializzo a non autenticato */
        $this->authenticated = FALSE;
    }

    /* costruttore da id*/
    public function popolaDaId(int $id)
    {;
        /* Global pdo */
        global $pdo;

        //query per popolazione
        $query = "SELECT admin.idAdmin, name, surname, mail, admin.expiration AS ex, ACLcatalogue, ACLloan, ACLuser, ACLadmin, admin_session.idSession
                FROM admin, admin_session 
                WHERE admin.idAdmin = :idAdmin";

        //array di valori
        $values = array(':idAdmin' => $id);


        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di eccezione ritorno l'eccezione
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetch(PDO::FETCH_ASSOC);

        //se esiste il risultato restituisco il login
        if (is_array($res)) {
            //autenticazione avvenuta con successo, popolo l'oggetto e ritorno true
            $this->id = intval($res['idAdmin'], 10);
            $this->name = $res['name'];
            $this->surname = $res['surname'];
            $this->mail = $res['mail'];
            $this->expiration = $res['ex'];

            //ACL
            $this->ACLcatalogue = $res['ACLcatalogue'];
            $this->ACLloan = $res['ACLloan'];
            $this->ACLuser = $res['ACLuser'];
            $this->ACLadmin = $res['ACLadmin'];

            return TRUE;
        } else {

            //se non esiste utente
            return FALSE;
        }
    }

    /* Distruttore */
    public function __destruct()
    {
    }

    /********************************************************** */

    //getters 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function getACLcatalogue(): ?string
    {
        return $this->ACLcatalogue;
    }

    public function getACLloan(): ?string
    {
        return $this->ACLloan;
    }

    public function getACLuser(): ?string
    {
        return $this->ACLuser;
    }

    public function getACLadmin(): ?string
    {
        return $this->ACLadmin;
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }



    /********************************************************** */

    /* Funzioni di controllo dati */

    //controlli back-end sui dati inseriti

    /* Controlla nome o cognome */
    public function isNameValid(string $name): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //controllo lunghezza
        $len = mb_strlen($name);

        //lunghezza minima del nome/cognome 1 carattere
        if ($len < 1 || $len > 45) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }

    /* Controlla mail */
    public function isMailValid(string $mail): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //elimino spazi e caratteri superflui
        $mail = trim($mail);

        //controllo lunghezza
        $len = mb_strlen($mail);

        //lunghezza minima della mail 5 caratteri (a@b.c) e max 255
        if ($len < 5 || $len > 255) {
            $res = FALSE;
        }

        //contiente una sola @
        //separa la stringa dove è posta la @, controlla che si creino 2 stringhe
        if (count(explode('@', $mail)) != 2) {
            $res = FALSE;
        }

        //contiene almeno un .
        //separa la stringa dove è posto un punto, controlla che si creino almeno 2 stringhe
        if (count(explode('.', $mail)) < 2) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }


    /* Controlla password */
    public function isPasswordValid(string $pass): bool
    {
        //inizializza valore di ritorno
        $res = TRUE;

        //controllo lunghezza
        $len = mb_strlen($pass);

        //lunghezza minima della password 6 caratteri, massima 15
        if ($len < 6 || $len > 15) {
            $res = FALSE;
        }

        //ritorno risultato del check
        return $res;
    }


    /********************************************************** */

    /* Funzioni di get vari */
    //get dal db da id o mail


    /* ritorna idAdmin dall'email, NULL in caso non ci sia nessuna corrispondenza */
    public function getIdFromMail(string $mail): ?int
    {
        /* Global pdo */
        global $pdo;

        // Inizializzo valore di ritorno con NULL (Nessun utente)
        $id = NULL;

        //query di ricerca
        $query = 'SELECT idAdmin FROM admin WHERE mail = :mail';

        //array di valori da passare
        $values = array(':mail' => $mail);

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di errore restituisco eccezione
            throw new Exception('Database query error');
        }

        //fetch del valore di ritorno
        $row = $res->fetch(PDO::FETCH_ASSOC);

        //se è un array (c'è un valore) ritorno id
        if (is_array($row)) {
            $id = $row['idAdmin'];
        }

        return $id;
    }


    /********************************************************** */

    /* Funzioni statiche */

    /* aggiunge account e ritorna id */
    public function addAccount(string $mail, string $password, string $name, string $surname, $ACL): int
    {
        /* Global pdo */
        global $pdo;

        // Controlla se mail è valida o genera eccezione
        if (!$this->isMailValid($mail)) {
            throw new Exception('Mail invalida');
        }

        // Controlla se password è valida o genera eccezione
        if (!$this->isPasswordValid($password)) {
            throw new Exception('Password non valida');
        }

        // Controlla se nome è valido o genera eccezione
        if (!$this->isNameValid($name)) {
            throw new Exception('Nome invalido');
        }

        // Controlla se cognome è valido o genera eccezione
        if (!$this->isNameValid($surname)) {
            throw new Exception('Cognome invalido');
        }

        // Controlla se la mail è già registrata
        if (!is_null($this->getIdFromMail($mail))) {
            throw new Exception('Mail già registrata');
        }

        // Gestisco array di ACL
        if (!isset($ACL['catalogue'])) {
            $ACL['catalogue'] = false;
        }

        if (!isset($ACL['loan'])) {
            $ACL['loan'] = false;
        }

        if (!isset($ACL['user'])) {
            $ACL['user'] = false;
        }

        if (!isset($ACL['admin'])) {
            $ACL['admin'] = false;
        }



        /* aggiunta dell'account nel db */

        // query di registrazione
        // obbligherò l'admin a resettare la password al primo accesso
        $query = 'INSERT INTO admin (name, surname, mail, password, ACLcatalogue, ACLloan, ACLuser, ACLadmin, expiration) 
                VALUES (:name, :surname, :mail, :password, :ACLcatalogue, :ACLloan, :ACLuser, :ACLadmin, NOW())';

        //hash password
        $hash = password_hash($password, PASSWORD_DEFAULT);

        //array di valori 
        $values = array(
            ':name' => $name,
            ':surname' => $surname,
            ':mail' => $mail,
            ':password' => $hash,
            ':ACLcatalogue' => $ACL['catalogue'],
            ':ACLloan' => $ACL['loan'],
            ':ACLuser' => $ACL['user'],
            ':ACLadmin' => $ACL['admin']
        );

        try {

            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //ritorno ultimo admin aggiunto
        return $pdo->lastInsertId();
    }

    /* edita account da id */
    public function editAccount(int $idAdmin, string $name, string $surname, string $mail, $ACL)
    {
        /* Global pdo */
        global $pdo;

        //controllo validità nome
        if (!$this->isNameValid($name)) {
            throw new Exception('Nome invalido');
        }

        //controllo validità cognome
        if (!$this->isNameValid($surname)) {
            throw new Exception('Cognome invalido');
        }

        //controllo validità mail
        if (!$this->isMailValid($mail)) {
            throw new Exception('E-Mail invalida');
        }


        //controlla se esiste admin già registrato con la stessa mail
        $idFromMail = $this->getIdFromMail($mail);

        if (!is_null($idFromMail) && ($idFromMail != $idAdmin)) {
            throw new Exception('E-Mail già utilizzata');
        }

        // Gestisco array di ACL
        if (!isset($ACL['catalogue'])) {
            $ACL['catalogue'] = false;
        }

        if (!isset($ACL['loan'])) {
            $ACL['loan'] = false;
        }

        if (!isset($ACL['user'])) {
            $ACL['user'] = false;
        }

        if (!isset($ACL['admin'])) {
            $ACL['admin'] = false;
        }

        //edito account

        //query base
        $query = 'UPDATE admin 
                SET name = :name, 
                    surname = :surname, 
                    mail = :mail,
                    ACLcatalogue = :ACLcatalogue, 
                    ACLloan = :ACLloan, 
                    ACLuser = :ACLuser, 
                    ACLadmin = :ACLadmin
                WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(
            ':idAdmin' => $idAdmin,
            ':name' => $name,
            ':surname' => $surname,
            ':mail' => $mail,
            ':ACLcatalogue' => $ACL['catalogue'],
            ':ACLloan' => $ACL['loan'],
            ':ACLuser' => $ACL['user'],
            ':ACLadmin' => $ACL['admin']
        );

        try {

            //preparo query
            $res = $pdo->prepare($query);

            //esecuzione con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }

    /* Elimina un account dato l'id */
    public function deleteAccount(int $id)
    {
        /* Global pdo */
        global $pdo;

        //query per eliminazione dato id
        $query = 'DELETE FROM admin WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(':idAdmin' => $id);

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //l'eliminazione delle sessioni e degli altri collegamenti avviene grazie ai cascade
    }

    /* cancella mediante una query tutte le sessioni scadute dal db con una semplice query */
    //la funzione viene invocata ogni volta prima di lavorare con le sessioni (registrazione e login)
    //il controllo viene comunque effettuato per ridondanza durante il sessionLogin, qui viene eseguita la pulizia dal DB per completezza
    public function expireSession()
    {

        /* Global pdo */
        global $pdo;

        $query = "DELETE FROM admin_session WHERE NOW() >= expiration";

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo query
            $res->execute();
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }


    /********************************************************** */

    /* Funzioni sull'admin loggato */


    /* Login con mail e password, ritorna true o false e imposta l'oggetto */
    public function login(string $mail, string $password): bool
    {
        /* Global pdo */
        global $pdo;


        //query per prendere dati dalla mail se account è abilitato, solo id per registrazione della sessione
        $query = 'SELECT idAdmin, password
            FROM admin
            WHERE mail = :mail';

        //array di valori
        $values = array(':mail' => $mail);


        try {
            //preparo la query
            $res = $pdo->prepare($query);

            //eseguo la query
            $res->execute($values);
        } catch (PDOException $e) {
            //in caso di eccezione ritorno l'eccezione 
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetch(PDO::FETCH_ASSOC);

        //se c'è un risultato procedo verificando la password con password_verify
        if (is_array($res)) {
            if (password_verify($password, $res['password'])) {
                //se l'autenticazione è andata a buon fine popolo gli altri campi utili alla registazione della sessione
                $this->id = intval($res['idAdmin'], 10);
                $this->mail = $mail;
                $this->authenticated = TRUE;

                //registra la sessione
                $this->registerLoginSession();

                //ritorna autenticazione effettuata con successo
                return TRUE;
            }
        }

        //se non è avvenuta correttamente, si ritorna insuccesso
        return FALSE;
    }


    /* Registrazione della sessione */
    //associa all'id dell'admin il codice di sessione
    private function registerLoginSession()
    {
        //eliminazione sessioni vecchie
        $this->expireSession();

        /* Global pdo */
        global $pdo;

        //controllo se una sessione è già startata
        if (session_status() == PHP_SESSION_ACTIVE) {
            //query per aggiungere la nuova sessione se non esiste, altrimenti faccio replace sul session id già esistente
            //il DBMS con la funzione di default imposta automaticamente l'expiration dopo 24 ore
            $query = 'REPLACE INTO admin_session (idSession, idAdmin) VALUES (:idSession, :idAdmin)';

            //array di valori
            $values = array(':idSession' => session_id(), ':idAdmin' => $this->id);


            try {
                //prepara la query
                $res = $pdo->prepare($query);

                //esegue la query
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione 
                throw new Exception('Database query error');
            }
        }
    }

    /* Login con sessione, ritorna True se andato a buon fine o false se fallito */
    public function sessionLogin(): bool
    {
        //eliminazione sessioni vecchie
        $this->expireSession();

        /* Global pdo */
        global $pdo;

        //controlla se sessione è già startata
        if (session_status() == PHP_SESSION_ACTIVE) {
            //query per controllo della sessione nel DB, se non scaduta
            $query = "SELECT admin.idAdmin, name, surname, mail, admin.expiration AS ex, ACLcatalogue, ACLloan, ACLuser, ACLadmin, admin_session.idSession
                FROM admin, admin_session 
                WHERE admin.idAdmin = admin_session.idAdmin
                    AND idSession = :idSession 
                    AND admin_session.expiration > NOW()";

            //array di valori
            $values = array(':idSession' => session_id());


            try {
                //preparo query
                $res = $pdo->prepare($query);

                //eseguo query
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione
                throw new Exception('Database query error');
            }

            //fetch del risultato
            $res = $res->fetch(PDO::FETCH_ASSOC);

            //se esiste il risultato restituisco il login
            if (is_array($res)) {
                //autenticazione avvenuta con successo, popolo l'oggetto e ritorno true
                $this->id = intval($res['idAdmin'], 10);
                $this->name = $res['name'];
                $this->surname = $res['surname'];
                $this->mail = $res['mail'];
                $this->expiration = $res['ex'];

                $this->ACLcatalogue = $res['ACLcatalogue'];
                $this->ACLloan = $res['ACLloan'];
                $this->ACLuser = $res['ACLuser'];
                $this->ACLadmin = $res['ACLadmin'];

                $this->authenticated = TRUE;

                return TRUE;
            }
        }

        //se non è avvenuta correttamente, si ritorna insuccesso
        return FALSE;
    }

    /* Cambia password dell'account loggato data la vecchia password e la nuova */
    public function changePassword(string $oldPassword, string $newPassword1, string $newPassword2)
    {
        //controllo back-end che le 2 nuove password coincidano
        if ($newPassword1 != $newPassword2) {
            throw new Exception("Le 2 password non corrispondono!");
        }

        //controllo dei requisiti della nuova password
        if (!$this->isPasswordValid($newPassword1)) {
            throw new Exception("La nuova password non &egrave; abbastanza sicura!");
        }

        /* Global pdo */
        global $pdo;

        //query per get della vecchia password (cifrata)
        $query = 'SELECT password FROM admin WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(':idAdmin' => $this->getId());

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //fetch del risultato
        $res = $res->fetchColumn();

        //controllo coincidenza della vecchia password
        if (!password_verify($oldPassword, $res)) {
            throw new Exception("La vecchia password non &egrave; corretta!");
        }

        //hash della nuova password
        $hash = password_hash($newPassword1, PASSWORD_DEFAULT);


        //inserimento della nuova password
        //query per inserimento nuova password (cifrata) e impostazione nuova data di scadenza
        $query = 'UPDATE admin SET password = :password, expiration = DEFAULT WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(':password' => $hash, ':idAdmin' => $this->getId());

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //sloggo da tutte le sessioni
        $this->closeAllSessions();

        return true;
    }

    /* Resetta la password dell'account */
    //la password andrà reimpostata al primo login
    public function resetPassword()
    {
        //genero nuova password
        $newPass = "";

        //for che genera nuova password di 8 caratteri
        for ($i = 0; $i < 8; $i++) {
            // Se i è in posto pari scrivo lettera minuscola
            if ($i % 2) {
                $newPass = $newPass . chr(rand(97, 122));

                // Se i è in posto dispari scrivo numero
            } else {
                $newPass = $newPass . rand(0, 9);
            }
        }

        /* Global pdo */
        global $pdo;

        //hash della nuova password
        $hash = password_hash($newPass, PASSWORD_DEFAULT);

        //inserimento della nuova password
        //query per inserimento nuova password (cifrata) e scadenza password NOW per farla reimpostare al primo accesso
        $query = 'UPDATE admin SET password = :password, expiration = NOW() WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(':password' => $hash, ':idAdmin' => $this->getId());

        try {
            //preparo query
            $res = $pdo->prepare($query);

            //eseguo con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }

        //sloggo da tutte le sessioni
        $this->closeAllSessions();

        return $newPass;
    }

    /* Logout dell'admin corrente */
    public function logout()
    {
        /* Global pdo */
        global $pdo;

        //controllo se c'è admin loggato, altrimenti nulla
        if (!$this->authenticated) {
            return;
        }

        //reset di tutti gli attributi
        $this->id = NULL;
        $this->mail = NULL;
        $this->name = NULL;
        $this->surname = NULL;
        $this->expiration = NULL;

        $this->ACLcatalogue = false;
        $this->ACLloan = false;
        $this->ACLuser = false;
        $this->ACLadmin = false;

        $this->authenticated = FALSE;

        //se ci sono sessioni attive le elimina dal DB
        if (session_status() == PHP_SESSION_ACTIVE) {

            //query per eliminazione della sessione
            $query = 'DELETE FROM admin_session WHERE (idSession = :sid)';

            //array di valori 
            $values = array(':sid' => session_id());


            try {
                //preparo query
                $res = $pdo->prepare($query);

                //esecuzione query con passaggio di valori
                $res->execute($values);
            } catch (PDOException $e) {
                //in caso di eccezione ritorno l'eccezione
                throw new Exception('Database query error');
            }
        }
    }

    /* Chiude tutte le sessioni dell'admin corrente */
    public function closeAllSessions()
    {
        /* Global pdo */
        global $pdo;

        //query per eliminazione di tutte le sessioni
        $query = 'DELETE FROM admin_session WHERE idAdmin = :idAdmin';

        //array di valori
        $values = array(':idAdmin' => $this->getId());


        try {
            //preparo la query
            $res = $pdo->prepare($query);

            //eseguo la query con passaggio di valori
            $res->execute($values);
        } catch (PDOException $e) {
            throw new Exception('Database query error');
        }
    }
}
